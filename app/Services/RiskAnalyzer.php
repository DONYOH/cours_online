<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Collection;

/**
 * Détection précoce du décrochage : score de risque 0-100 par apprenant,
 * expliqué par des signaux lisibles (inactivité, retard, résultats, devoirs manqués).
 */
class RiskAnalyzer
{
    /** Durée de référence (semaines) quand le cours n'a pas de dates. */
    private const DEFAULT_COURSE_WEEKS = 8;

    /** @return Collection<int, array{enrollment: Enrollment, score:int, level:string, reasons: array<int,string>, expected:int}> */
    public function analyze(Course $course): Collection
    {
        $enrollments = $course->enrollments()->with('user')->get();

        $quizIds = $course->quizzes()->where('is_published', true)->pluck('id');
        $overdueAssignments = $course->assignments()
            ->where('is_published', true)
            ->whereNotNull('due_at')
            ->where('due_at', '<', now())
            ->pluck('id');

        return $enrollments->map(function (Enrollment $enrollment) use ($course, $quizIds, $overdueAssignments) {
            $user = $enrollment->user;
            $score = 0;
            $reasons = [];

            if ($enrollment->completed_at) {
                return ['enrollment' => $enrollment, 'score' => 0, 'level' => 'low', 'reasons' => ['Cours terminé'], 'expected' => 100];
            }

            // 1. Inactivité
            $lastSeen = $enrollment->last_activity_at ?? $enrollment->created_at;
            $idleDays = (int) $lastSeen->diffInDays(now());
            if ($idleDays > 14) {
                $score += 35;
                $reasons[] = "Inactif depuis {$idleDays} jours";
            } elseif ($idleDays > 7) {
                $score += 20;
                $reasons[] = "Inactif depuis {$idleDays} jours";
            } elseif ($idleDays > 3) {
                $score += 8;
            }

            // 2. Retard sur la progression attendue
            $expected = $this->expectedProgress($course, $enrollment);
            $lag = $expected - $enrollment->progress;
            if ($lag > 40) {
                $score += 30;
                $reasons[] = "Retard important ({$enrollment->progress} % vs {$expected} % attendu)";
            } elseif ($lag > 20) {
                $score += 18;
                $reasons[] = "En retard ({$enrollment->progress} % vs {$expected} % attendu)";
            } elseif ($lag > 10) {
                $score += 8;
            }

            // 3. Résultats aux quiz
            $avg = $user->quizAttempts()
                ->whereIn('quiz_id', $quizIds)
                ->whereNotNull('submitted_at')
                ->selectRaw('quiz_id, MAX(percent) as best')
                ->groupBy('quiz_id')
                ->get()
                ->avg('best');
            if ($avg !== null && $avg < 50) {
                $score += 20;
                $reasons[] = 'Moyenne aux quiz faible ('.round($avg).' %)';
            } elseif ($avg !== null && $avg < 65) {
                $score += 10;
                $reasons[] = 'Moyenne aux quiz fragile ('.round($avg).' %)';
            }

            // 4. Devoirs en retard non rendus
            if ($overdueAssignments->isNotEmpty()) {
                $missing = $overdueAssignments->count()
                    - $user->submissions()->whereIn('assignment_id', $overdueAssignments)->count();
                if ($missing > 0) {
                    $score += min(25, $missing * 12);
                    $reasons[] = $missing.' devoir(s) non rendu(s) après échéance';
                }
            }

            $score = min(100, $score);

            return [
                'enrollment' => $enrollment,
                'score' => $score,
                'level' => $score >= 60 ? 'high' : ($score >= 30 ? 'medium' : 'low'),
                'reasons' => $reasons ?: ['Aucun signal préoccupant'],
                'expected' => $expected,
            ];
        })->sortByDesc('score')->values();
    }

    public function expectedProgress(Course $course, Enrollment $enrollment): int
    {
        if ($course->starts_at && $course->ends_at && $course->ends_at->greaterThan($course->starts_at)) {
            $total = $course->starts_at->diffInDays($course->ends_at);
            $elapsed = $course->starts_at->diffInDays(now(), false);

            return (int) max(0, min(100, round($elapsed / $total * 100)));
        }

        $weeks = $enrollment->created_at->diffInDays(now()) / 7;

        return (int) min(100, round($weeks / self::DEFAULT_COURSE_WEEKS * 100));
    }
}
