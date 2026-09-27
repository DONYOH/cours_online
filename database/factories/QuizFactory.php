<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Quiz> */
class QuizFactory extends Factory
{
    public function definition(): array
    {
        return [
            'section_id' => Section::factory(),
            'course_id' => fn (array $attrs) => Section::find($attrs['section_id'])->course_id,
            'title' => 'Quiz '.fake()->word(),
            'pass_score' => 50,
            'show_answers' => true,
            'position' => 2,
            'is_published' => true,
        ];
    }
}
