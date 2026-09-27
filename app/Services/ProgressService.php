<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * Calcule l'avancement d'un apprenant et délivre le certificat à 100 %.
 * Un élément est "terminé" quand : leçon marquée lue, quiz réussi, devoir rendu.
 */
class ProgressService
{
    public function __construct(private GamificationService $gamification) {}

    /** @return array{done:int,total:int,percent:int} */
    public function breakdown(Course $course, User $user): array
    {
        $lessonIds = $course->lessons()->where('is_published', true)->pluck('id');
        $quizIds = $course->quizzes()->where('is_published', true)->pluck('id');
        $assignmentIds = $course->assignments()->where('is_published', true)->pluck('id');

        $total = $lessonIds->count() + $quizIds->count() + $assignmentIds->count();

        $done = $user->completedLessons()->whereIn('lessons.id', $lessonIds)->count()
            + $user->quizAttempts()->whereIn('quiz_id', $quizIds)->where('passed', true)->distinct('quiz_id')->count('quiz_id')
            + $user->submissions()->whereIn('assignment_id', $assignmentIds)->count();

        return [
            'done' => $done,
            'total' => $total,
            'percent' => $total > 0 ? (int) floor($done / $total * 100) : 0,
        ];
    }

    public function refresh(Course $course, User $user): ?Enrollment
    {
        $enrollment = $course->enrollmentFor($user);
        if (! $enrollment) {
            return null;
        }

        $percent = $this->breakdown($course, $user)['percent'];
        $enrollment->progress = $percent;

        if ($percent >= 100 && $enrollment->completed_at === null) {
            $enrollment->completed_at = now();
            $this->issueCertificate($course, $user);
            $this->gamification->award($user, 'course_completed');
        }

        $enrollment->save();

        return $enrollment;
    }

    public function issueCertificate(Course $course, User $user): Certificate
    {
        return Certificate::firstOrCreate(
            ['course_id' => $course->id, 'user_id' => $user->id],
            [
                'code' => strtoupper(Str::random(4).'-'.Str::random(4).'-'.Str::random(4)),
                'final_grade' => app(GradebookService::class)->finalGrade($course, $user),
                'issued_at' => now(),
            ],
        );
    }
}
