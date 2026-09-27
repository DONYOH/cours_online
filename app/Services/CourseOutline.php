<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/** Plan du cours à plat : navigation précédent / suivant et état d'avancement. */
class CourseOutline
{
    public function items(Course $course, bool $includeDrafts = false): Collection
    {
        $course->loadMissing(['sections.lessons', 'sections.quizzes', 'sections.assignments']);

        return $course->sections->flatMap(fn ($section) => $section->items(! $includeDrafts));
    }

    public function url(Course $course, Model $item): string
    {
        return match ($item->kind()) {
            'lesson' => route('lessons.show', [$course, $item]),
            'quiz' => route('quizzes.show', [$course, $item]),
            'assignment' => route('assignments.show', [$course, $item]),
        };
    }

    /** @return array{prev: ?Model, next: ?Model} */
    public function neighbours(Course $course, Model $current, bool $includeDrafts = false): array
    {
        $items = $this->items($course, $includeDrafts)->values();
        $index = $items->search(fn ($i) => $i->kind() === $current->kind() && $i->id === $current->id);

        if ($index === false) {
            return ['prev' => null, 'next' => null];
        }

        return ['prev' => $items->get($index - 1), 'next' => $items->get($index + 1)];
    }

    /** Identifiants des éléments terminés par l'apprenant. */
    public function doneMap(Course $course, ?User $user): array
    {
        if (! $user) {
            return ['lesson' => [], 'quiz' => [], 'assignment' => []];
        }

        return [
            'lesson' => $user->completedLessons()->where('lessons.course_id', $course->id)->pluck('lessons.id')->all(),
            'quiz' => $user->quizAttempts()->where('passed', true)->whereHas('quiz', fn ($q) => $q->where('course_id', $course->id))->pluck('quiz_id')->unique()->values()->all(),
            'assignment' => $user->submissions()->whereHas('assignment', fn ($q) => $q->where('course_id', $course->id))->pluck('assignment_id')->all(),
        ];
    }
}
