<?php

namespace Tests\Unit;

use App\Models\Question;
use App\Services\QuizGrader;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class QuizGraderTest extends TestCase
{
    private function question(string $type, array $correct, array $options = ['A', 'B', 'C', 'D']): Question
    {
        return new Question(['type' => $type, 'prompt' => 'Q', 'options' => $options, 'correct' => $correct, 'points' => 1]);
    }

    public static function cases(): array
    {
        return [
            'unique juste' => ['single', [2], 2, 1.0],
            'unique fausse' => ['single', [2], 1, 0.0],
            'unique vide' => ['single', [2], null, 0.0],
            'vrai/faux' => ['true_false', [1], '1', 1.0],
            'multiple parfaite' => ['multiple', [0, 2], [0, 2], 1.0],
            'multiple partielle' => ['multiple', [0, 2], [0], 0.5],
            'multiple avec erreur' => ['multiple', [0, 2], [0, 2, 3], 0.5],
            'multiple tout cocher' => ['multiple', [0, 2], [0, 1, 2, 3], 0.0],
            'courte exacte' => ['short', ['Normalisation'], 'normalisation', 1.0],
            'courte accents' => ['short', ['Lomé'], 'LOME', 1.0],
            'courte faute de frappe' => ['short', ['normalisation'], 'normalisaton', 1.0],
            'courte fausse' => ['short', ['normalisation'], 'indexation', 0.0],
        ];
    }

    #[DataProvider('cases')]
    public function test_scoring(string $type, array $correct, mixed $given, float $expected): void
    {
        $this->assertEqualsWithDelta($expected, (new QuizGrader)->scoreQuestion($this->question($type, $correct), $given), 0.001);
    }
}
