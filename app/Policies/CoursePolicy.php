<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

/** Les administrateurs passent toutes les vérifications via Gate::before. */
class CoursePolicy
{
    public function view(?User $user, Course $course): bool
    {
        return $course->is_published || ($user && $this->manage($user, $course));
    }

    public function create(User $user): bool
    {
        return $user->canTeach();
    }

    public function manage(User $user, Course $course): bool
    {
        return $course->isOwnedBy($user);
    }

    public function update(User $user, Course $course): bool
    {
        return $this->manage($user, $course);
    }

    public function delete(User $user, Course $course): bool
    {
        return $this->manage($user, $course);
    }

    /** Accès au contenu : enseignant du cours ou apprenant inscrit. */
    public function learn(User $user, Course $course): bool
    {
        return $this->manage($user, $course) || $user->isEnrolledIn($course);
    }

    public function enroll(User $user, Course $course): bool
    {
        return $course->is_published && ! $course->isOwnedBy($user) && ! $user->isEnrolledIn($course);
    }
}
