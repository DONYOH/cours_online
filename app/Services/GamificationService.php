<?php

namespace App\Services;

use App\Models\User;

class GamificationService
{
    public const XP = [
        'lesson_completed' => 10,
        'quiz_passed' => 30,
        'assignment_submitted' => 25,
        'discussion_created' => 5,
        'reply_posted' => 3,
        'solution_accepted' => 15,
        'course_completed' => 100,
    ];

    public function award(User $user, string $event, int $multiplier = 1): int
    {
        $points = (self::XP[$event] ?? 0) * $multiplier;
        if ($points > 0) {
            $user->increment('xp', $points);
        }

        return $points;
    }
}
