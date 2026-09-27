<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Carnet de notes : toutes les notes ramenées sur 20.
 * Quiz = meilleure tentative ; devoirs = note de l'enseignant.
 */
class GradebookService
{
    /** Colonnes du carnet de notes. */
    public function columns(Course $course): Collection
    {
        $quizzes = $course->quizzes()->where('is_published', true)->orderBy('position')->get()
            ->map(fn ($q) => ['key' => 'quiz-'.$q->id, 'type' => 'quiz', 'id' => $q->id, 'title' => $q->title]);

        $assignments = $course->assignments()->where('is_published', true)->orderBy('position')->get()
            ->map(fn ($a) => ['key' => 'assignment-'.$a->id, 'type' => 'assignment', 'id' => $a->id, 'title' => $a->title, 'max' => $a->max_points]);

        return $quizzes->concat($assignments)->values();
    }

    /**
     * Notes /20 d'un apprenant pour chaque colonne (null = pas encore noté).
     *
     * @return array<string, float|null>
     */
    public function gradesFor(Course $course, User $user, ?Collection $columns = null): array
    {
        $columns ??= $this->columns($course);
        $grades = [];

        $bestQuiz = $user->quizAttempts()
            ->whereNotNull('submitted_at')
            ->whereIn('quiz_id', $columns->where('type', 'quiz')->pluck('id'))
            ->selectRaw('quiz_id, MAX(percent) as best')
            ->groupBy('quiz_id')
            ->pluck('best', 'quiz_id');

        $submissions = $user->submissions()
            ->whereIn('assignment_id', $columns->where('type', 'assignment')->pluck('id'))
            ->get()->keyBy('assignment_id');

        foreach ($columns as $col) {
            if ($col['type'] === 'quiz') {
                $grades[$col['key']] = isset($bestQuiz[$col['id']]) ? round($bestQuiz[$col['id']] / 5, 2) : null;
            } else {
                $sub = $submissions->get($col['id']);
                $grades[$col['key']] = $sub?->grade !== null ? round($sub->grade / max(1, $col['max']) * 20, 2) : null;
            }
        }

        return $grades;
    }

    public function finalGrade(Course $course, User $user): ?float
    {
        $values = array_filter($this->gradesFor($course, $user), fn ($g) => $g !== null);

        return $values ? round(array_sum($values) / count($values), 2) : null;
    }

    /** Matrice complète pour l'enseignant. */
    public function matrix(Course $course): array
    {
        $columns = $this->columns($course);
        $rows = $course->students()->orderBy('name')->get()->map(function (User $student) use ($course, $columns) {
            $grades = $this->gradesFor($course, $student, $columns);
            $values = array_filter($grades, fn ($g) => $g !== null);

            return [
                'student' => $student,
                'progress' => $student->pivot->progress,
                'grades' => $grades,
                'average' => $values ? round(array_sum($values) / count($values), 2) : null,
            ];
        });

        return ['columns' => $columns, 'rows' => $rows];
    }
}
