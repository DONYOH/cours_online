<?php

namespace App\Http\Controllers;

use App\Enums\QuestionType;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use App\Services\QuizGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class QuestionController extends Controller
{
    public function store(Request $request, Course $course, Quiz $quiz): RedirectResponse
    {
        Gate::authorize('manage', $course);

        $quiz->questions()->create($this->validated($request) + ['position' => $quiz->questions()->max('position') + 1]);

        return back()->with('success', 'Question ajoutée.');
    }

    public function update(Request $request, Course $course, Quiz $quiz, Question $question): RedirectResponse
    {
        Gate::authorize('manage', $course);
        $question->update($this->validated($request));

        return back()->with('success', 'Question mise à jour.');
    }

    public function destroy(Course $course, Quiz $quiz, Question $question): RedirectResponse
    {
        Gate::authorize('manage', $course);
        $question->delete();

        return back()->with('success', 'Question supprimée.');
    }

    /** Génère des questions depuis les leçons de la section ou un texte collé. */
    public function generate(Request $request, Course $course, Quiz $quiz, QuizGenerator $generator): RedirectResponse
    {
        Gate::authorize('manage', $course);
        $data = $request->validate([
            'source' => ['nullable', 'string', 'max:60000'],
            'count' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $source = $data['source'] ?? '';
        if (blank($source) && $quiz->section) {
            $source = $quiz->section->lessons->pluck('content')->implode("\n\n");
        }

        $generated = $generator->generate($source, (int) $data['count']);
        if ($generated === []) {
            return back()->with('error', 'Impossible de générer des questions : le support est vide ou trop court.');
        }

        $position = (int) $quiz->questions()->max('position');
        foreach ($generated as $item) {
            $quiz->questions()->create($item + ['position' => ++$position]);
        }

        $mode = $generator->aiEnabled() ? 'par IA' : 'en mode local (questions à trous)';

        return back()->with('success', count($generated).' question(s) générée(s) '.$mode.'. Relisez-les avant de publier.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'type' => ['required', Rule::enum(QuestionType::class)],
            'prompt' => ['required', 'string', 'max:5000'],
            'points' => ['required', 'integer', 'min:1', 'max:100'],
            'explanation' => ['nullable', 'string', 'max:5000'],
            'options' => ['array'],
            'options.*' => ['nullable', 'string', 'max:500'],
            'correct_single' => ['nullable', 'integer'],
            'correct_multiple' => ['array'],
            'correct_multiple.*' => ['integer'],
            'correct_tf' => ['nullable', 'in:0,1'],
            'accepted' => ['nullable', 'string', 'max:2000'],
        ]);

        $type = QuestionType::from($data['type']);
        $options = [];
        $correct = [];

        switch ($type) {
            case QuestionType::Single:
            case QuestionType::Multiple:
                // On retire les options vides en conservant la correspondance des index
                $map = [];
                foreach ($data['options'] ?? [] as $i => $label) {
                    if (filled($label)) {
                        $map[(int) $i] = count($options);
                        $options[] = trim($label);
                    }
                }
                if (count($options) < 2) {
                    throw ValidationException::withMessages(['options' => 'Indiquez au moins deux options.']);
                }
                $chosen = $type === QuestionType::Single
                    ? (isset($data['correct_single']) ? [(int) $data['correct_single']] : [])
                    : array_map('intval', $data['correct_multiple'] ?? []);
                $correct = array_values(array_filter(array_map(fn ($i) => $map[$i] ?? null, $chosen), fn ($v) => $v !== null));
                if ($correct === []) {
                    throw ValidationException::withMessages(['options' => 'Cochez au moins une bonne réponse.']);
                }
                break;

            case QuestionType::TrueFalse:
                $correct = [(int) ($data['correct_tf'] ?? 0)];
                break;

            case QuestionType::Short:
                $correct = collect(preg_split('/\r?\n|\|/', (string) ($data['accepted'] ?? '')))
                    ->map(fn ($a) => trim($a))->filter()->values()->all();
                if ($correct === []) {
                    throw ValidationException::withMessages(['accepted' => 'Indiquez au moins une réponse acceptée.']);
                }
                break;
        }

        return [
            'type' => $type,
            'prompt' => $data['prompt'],
            'points' => $data['points'],
            'explanation' => $data['explanation'] ?? null,
            'options' => $options,
            'correct' => $correct,
        ];
    }
}
