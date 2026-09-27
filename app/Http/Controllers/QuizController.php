<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\Section;
use App\Services\CourseOutline;
use App\Services\QuizGenerator;
use App\Services\QuizGrader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function show(Request $request, Course $course, Quiz $quiz, CourseOutline $outline): View
    {
        Gate::authorize('learn', $course);
        $canManage = Gate::allows('manage', $course);
        abort_unless($quiz->is_published || $canManage, 404);

        $user = $request->user();
        $quiz->loadCount('questions');

        return view('learn.quiz', [
            'course' => $course,
            'quiz' => $quiz,
            'outline' => $outline->items($course, $canManage),
            'done' => $outline->doneMap($course, $user),
            'nav' => $outline->neighbours($course, $quiz, $canManage),
            'attempts' => $quiz->attempts()->where('user_id', $user->id)->latest('started_at')->get(),
            'attemptsLeft' => $quiz->attemptsLeftFor($user),
            'best' => $quiz->bestAttemptFor($user),
            'canManage' => $canManage,
            'enrollment' => $course->enrollmentFor($user),
        ]);
    }

    public function create(Course $course, Section $section): View
    {
        Gate::authorize('manage', $course);

        return view('quizzes.form', ['course' => $course, 'section' => $section, 'quiz' => new Quiz(['pass_score' => 50, 'show_answers' => true])]);
    }

    public function store(Request $request, Course $course, Section $section): RedirectResponse
    {
        Gate::authorize('manage', $course);

        $quiz = new Quiz($this->validated($request));
        $quiz->course_id = $course->id;
        $quiz->section_id = $section->id;
        $quiz->position = $section->items()->max('position') + 1;
        $quiz->save();

        return redirect()->route('quizzes.edit', [$course, $quiz])->with('success', 'Quiz créé. Ajoutez vos questions (ou générez-les automatiquement).');
    }

    public function edit(Course $course, Quiz $quiz, QuizGenerator $generator): View
    {
        Gate::authorize('manage', $course);
        $quiz->load('questions', 'section.lessons');

        return view('quizzes.edit', [
            'course' => $course,
            'quiz' => $quiz,
            'aiEnabled' => $generator->aiEnabled(),
        ]);
    }

    public function update(Request $request, Course $course, Quiz $quiz): RedirectResponse
    {
        Gate::authorize('manage', $course);
        $quiz->update($this->validated($request));

        return back()->with('success', 'Paramètres du quiz enregistrés.');
    }

    public function destroy(Course $course, Quiz $quiz): RedirectResponse
    {
        Gate::authorize('manage', $course);
        $quiz->delete();

        return redirect()->route('courses.builder', $course)->with('success', 'Quiz supprimé.');
    }

    /** Résultats + analyse d'items (indice de réussite par question). */
    public function results(Course $course, Quiz $quiz, QuizGrader $grader): View
    {
        Gate::authorize('manage', $course);
        $quiz->load('questions');

        $attempts = $quiz->attempts()->with('user')->whereNotNull('submitted_at')->latest('submitted_at')->get();

        $itemAnalysis = $quiz->questions->map(function ($question) use ($attempts) {
            $answered = $attempts->filter(fn ($a) => isset($a->answers[$question->id]));
            $correct = $answered->filter(fn ($a) => $a->answers[$question->id]['correct'] ?? false)->count();
            $rate = $answered->count() ? round($correct / $answered->count() * 100) : null;

            return [
                'question' => $question,
                'answered' => $answered->count(),
                'rate' => $rate,
                'flag' => $rate === null ? null : ($rate < 30 ? 'Très difficile — vérifier l\'énoncé' : ($rate > 95 ? 'Trop facile' : null)),
            ];
        });

        return view('quizzes.results', [
            'course' => $course,
            'quiz' => $quiz,
            'attempts' => $attempts,
            'itemAnalysis' => $itemAnalysis,
            'stats' => [
                'count' => $attempts->count(),
                'learners' => $attempts->pluck('user_id')->unique()->count(),
                'avg' => $attempts->count() ? round($attempts->avg('percent'), 1) : null,
                'passRate' => $attempts->count() ? round($attempts->where('passed', true)->count() / $attempts->count() * 100) : null,
            ],
        ]);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:5000'],
            'time_limit_minutes' => ['nullable', 'integer', 'min:1', 'max:600'],
            'max_attempts' => ['nullable', 'integer', 'min:1', 'max:50'],
            'pass_score' => ['required', 'integer', 'min:0', 'max:100'],
            'due_at' => ['nullable', 'date'],
        ]);

        foreach (['shuffle_questions', 'show_answers', 'is_published'] as $flag) {
            $data[$flag] = $request->boolean($flag);
        }

        return $data;
    }
}
