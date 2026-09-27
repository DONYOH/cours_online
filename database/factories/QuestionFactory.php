<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Question> */
class QuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'quiz_id' => Quiz::factory(),
            'type' => 'single',
            'prompt' => fake()->sentence().' ?',
            'options' => ['A', 'B', 'C', 'D'],
            'correct' => [1],
            'points' => 1,
            'position' => 1,
        ];
    }
}
