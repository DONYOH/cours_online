<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Assignment> */
class AssignmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'section_id' => Section::factory(),
            'course_id' => fn (array $attrs) => Section::find($attrs['section_id'])->course_id,
            'title' => 'Devoir '.fake()->word(),
            'instructions' => fake()->paragraph(),
            'due_at' => now()->addWeek(),
            'max_points' => 20,
            'allow_late' => true,
            'position' => 3,
            'is_published' => true,
        ];
    }
}
