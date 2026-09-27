<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActivityLogger
{
    public function log(User $user, string $type, ?Course $course = null, ?Model $subject = null, array $meta = []): void
    {
        Activity::create([
            'user_id' => $user->id,
            'course_id' => $course?->id,
            'type' => $type,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'meta' => $meta ?: null,
        ]);

        if ($course) {
            Enrollment::where('course_id', $course->id)
                ->where('user_id', $user->id)
                ->update(['last_activity_at' => now()]);
        }
    }
}
