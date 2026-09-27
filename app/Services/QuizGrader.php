<?php

namespace App\Services;

use App\Enums\QuestionType;
use App\Models\Question;
use App\Models\QuizAttempt;
use Illuminate\Support\Str;

/**
 * Correction automatique des tentatives de quiz.
 * - Choix multiples : crédit partiel (bonnes cochées - mauvaises cochées), plancher à 0.
 * - Réponse courte : comparaison normalisée (casse, accents, espaces) + tolérance aux fautes de frappe.
 */
class QuizGrader
{
    public function grade(QuizAttempt $attempt, array $answers): QuizAttempt
    {
        $quiz = $attempt->quiz->loadMissing('questions');
        $score = 0.0;
        $max = 0.0;
        $details = [];

        foreach ($quiz->questions as $question) {
            $given = $answers[$question->id] ?? null;
            $ratio = $this->scoreQuestion($question, $given);
            $earned = round($ratio * $question->points, 2);

            $score += $earned;
            $max += $question->points;
            $details[$question->id] = [
                'given' => $given,
                'earned' => $earned,
                'correct' => $ratio >= 0.999,
            ];
        }

        $percent = $max > 0 ? round($score / $max * 100, 2) : 0;

        $attempt->fill([
            'answers' => $details,
            'score' => $score,
            'max_score' => $max,
            'percent' => $percent,
            'passed' => $percent >= $quiz->pass_score,
            'submitted_at' => now(),
        ])->save();

        return $attempt;
    }

    /** Retourne un ratio entre 0 et 1. */
    public function scoreQuestion(Question $question, mixed $given): float
    {
        $correct = $question->correct ?? [];

        return match ($question->type) {
            QuestionType::Single, QuestionType::TrueFalse => $given !== null && in_array((int) $given, array_map('intval', $correct), true) ? 1.0 : 0.0,
            QuestionType::Multiple => $this->scoreMultiple(array_map('intval', (array) $given), array_map('intval', $correct), count($question->options ?? [])),
            QuestionType::Short => $this->scoreShort((string) $given, $correct),
        };
    }

    private function scoreMultiple(array $given, array $correct, int $optionCount): float
    {
        if ($correct === []) {
            return $given === [] ? 1.0 : 0.0;
        }

        $good = count(array_intersect($given, $correct));
        $bad = count(array_diff($given, $correct));
        $wrongOptions = max(1, $optionCount - count($correct));

        $ratio = $good / count($correct) - $bad / $wrongOptions;

        return max(0.0, min(1.0, $ratio));
    }

    private function scoreShort(string $given, array $accepted): float
    {
        $normalizedGiven = $this->normalize($given);
        if ($normalizedGiven === '') {
            return 0.0;
        }

        foreach ($accepted as $answer) {
            $normalized = $this->normalize((string) $answer);
            if ($normalized === $normalizedGiven) {
                return 1.0;
            }
            // Tolère une faute de frappe par tranche de 6 caractères
            $tolerance = intdiv(mb_strlen($normalized), 6);
            if ($tolerance > 0 && levenshtein($normalized, $normalizedGiven) <= $tolerance) {
                return 1.0;
            }
        }

        return 0.0;
    }

    private function normalize(string $value): string
    {
        return (string) Str::of($value)->ascii()->lower()->replaceMatches('/[^a-z0-9]+/', ' ')->squish();
    }
}
