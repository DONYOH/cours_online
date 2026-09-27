<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Course> */
class CourseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'teacher_id' => User::factory()->teacher(),
            'title' => ucfirst(fake()->unique()->words(3, true)),
            'summary' => fake()->sentence(12),
            'description' => fake()->paragraphs(2, true),
            'level' => 'beginner',
            'language' => 'fr',
            'is_published' => true,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['is_published' => false]);
    }
}
