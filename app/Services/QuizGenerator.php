<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Génère des questions de quiz à partir d'un support de cours.
 * - Si une clé ANTHROPIC_API_KEY est configurée : génération par IA (Claude).
 * - Sinon : générateur local hors-ligne (questions à trous), utile sans connexion.
 */
class QuizGenerator
{
    private const STOPWORDS = [
        'dans', 'avec', 'pour', 'sont', 'mais', 'plus', 'cette', 'comme', 'aussi', 'leurs', 'entre',
        'être', 'avoir', 'fait', 'faire', 'peut', 'donc', 'alors', 'ainsi', 'chaque', 'toutes', 'tous',
        'nous', 'vous', 'elles', 'ils', 'dont', 'lorsque', 'quand', 'selon', 'depuis', 'permet',
    ];

    public function aiEnabled(): bool
    {
        return filled(config('services.anthropic.key'));
    }

    /**
     * @return array<int, array{type:string, prompt:string, options:array, correct:array, explanation:?string, points:int}>
     */
    public function generate(string $source, int $count = 5): array
    {
        $source = trim(strip_tags($source));
        if ($source === '') {
            return [];
        }

        if ($this->aiEnabled()) {
            try {
                $questions = $this->generateWithAi($source, $count);
                if ($questions !== []) {
                    return $questions;
                }
            } catch (\Throwable $e) {
                Log::warning('Génération IA indisponible, repli local : '.$e->getMessage());
            }
        }

        return $this->generateLocally($source, $count);
    }

    private function generateWithAi(string $source, int $count): array
    {
        $prompt = <<<PROMPT
Tu es un ingénieur pédagogique. À partir du support de cours ci-dessous, rédige {$count} questions d'évaluation en français,
variées (choix unique, choix multiples, vrai/faux), qui testent la compréhension et pas seulement la mémorisation.
Réponds UNIQUEMENT avec un tableau JSON, sans texte autour, au format :
[{"type":"single|multiple|true_false","prompt":"...","options":["..."],"correct":[index...],"explanation":"..."}]
Pour true_false : options = [] et correct = [0] pour Vrai ou [1] pour Faux. Les index commencent à 0.

<support>
{$source}
</support>
PROMPT;

        $response = Http::withHeaders([
            'x-api-key' => config('services.anthropic.key'),
            'anthropic-version' => '2023-06-01',
        ])->timeout(60)->post('https://api.anthropic.com/v1/messages', [
            'model' => config('services.anthropic.model'),
            'max_tokens' => 4000,
            'messages' => [['role' => 'user', 'content' => Str::limit($prompt, 60000)]],
        ])->throw();

        $text = collect($response->json('content', []))->where('type', 'text')->pluck('text')->implode('');
        if (! preg_match('/\[.*\]/s', $text, $m)) {
            return [];
        }

        $items = json_decode($m[0], true);
        if (! is_array($items)) {
            return [];
        }

        return collect($items)
            ->filter(fn ($q) => isset($q['type'], $q['prompt'], $q['correct']) && in_array($q['type'], ['single', 'multiple', 'true_false'], true))
            ->map(fn ($q) => [
                'type' => $q['type'],
                'prompt' => (string) $q['prompt'],
                'options' => $q['type'] === 'true_false' ? [] : array_values(array_map('strval', $q['options'] ?? [])),
                'correct' => array_values(array_map('intval', (array) $q['correct'])),
                'explanation' => $q['explanation'] ?? null,
                'points' => 1,
            ])
            ->take($count)
            ->values()
            ->all();
    }

    /** Générateur hors-ligne : phrases clés transformées en questions à trous. */
    public function generateLocally(string $source, int $count): array
    {
        $plain = preg_replace('/[#*_`>\[\]()-]+/', ' ', $source);
        $sentences = collect(preg_split('/(?<=[.!?])\s+/u', (string) $plain))
            ->map(fn ($s) => Str::squish($s))
            ->filter(fn ($s) => mb_strlen($s) >= 40 && mb_strlen($s) <= 260)
            ->unique()
            ->values();

        $questions = [];
        foreach ($sentences as $sentence) {
            $keyword = collect(preg_split('/[^\p{L}\p{N}]+/u', $sentence))
                ->filter(fn ($w) => mb_strlen($w) >= 6 && ! in_array(mb_strtolower($w), self::STOPWORDS, true))
                ->sortByDesc(fn ($w) => mb_strlen($w))
                ->first();

            if (! $keyword) {
                continue;
            }

            $questions[] = [
                'type' => 'short',
                'prompt' => 'Complétez : « '.preg_replace('/\b'.preg_quote($keyword, '/').'\b/u', '_____', $sentence, 1).' »',
                'options' => [],
                'correct' => [$keyword],
                'explanation' => 'Phrase d\'origine : '.$sentence,
                'points' => 1,
            ];

            if (count($questions) >= $count) {
                break;
            }
        }

        return $questions;
    }
}
