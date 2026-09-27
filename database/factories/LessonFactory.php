<?php

namespace Database\Factories;

use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Lesson> */
class LessonFactory extends Factory
{
    public function definition(): array
    {
        return [
            'section_id' => Section::factory(),
            'course_id' => fn (array $attrs) => Section::find($attrs['section_id'])->course_id,
            'title' => ucfirst(fake()->words(4, true)),
            'type' => 'text',
            'content' => fake()->paragraphs(3, true),
            'duration_minutes' => 10,
            'position' => 1,
            'is_published' => true,
        ];
    }
}
