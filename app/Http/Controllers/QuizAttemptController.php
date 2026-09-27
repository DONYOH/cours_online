<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Services\ActivityLogger;
use App\Services\GamificationService;
use App\Services\ProgressService;
use App\Services\QuizGrader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class QuizAttemptController extends Controller
{
    public function store(Request $request, Course $course, Quiz $quiz, ActivityLogger $logger): RedirectResponse
    {
        Gate::authorize('learn', $course);
        abort_unless($quiz->is_published || Gate::allows('manage', $course), 404);
        $user = $request->user();

        $open = $quiz->attempts()->where('user_id', $user->id)->whereNull('submitted_at')->latest('started_at')->first();
        if ($open && ! $open->isExpired()) {
            return redirect()->route('attempts.show', [$course, $quiz, $open]);
        }

        // Une tentative abandonnée après expiration du chrono est clôturée (et comptée).
        if ($open) {
            app(QuizGrader::class)->grade($open, []);
        }

        if ($quiz->attemptsLeftFor($user) === 0) {
            return back()->with('error', 'Vous avez utilisé toutes vos tentatives pour ce quiz.');
        }

        if ($quiz->questions()->count() === 0) {
            return back()->with('error', 'Ce quiz ne contient encore aucune question.');
        }

        $order = $quiz->questions()->pluck('id');
        if ($quiz->shuffle_questions) {
            $order = $order->shuffle();
        }

        $attempt = $quiz->attempts()->create([
            'user_id' => $user->id,
            'question_order' => $order->values()->all(),
            'started_at' => now(),
        ]);
        $logger->log($user, 'quiz_started', $course, $quiz);

        return redirect()->route('attempts.show', [$course, $quiz, $attempt]);
    }

    public function show(Request $request, Course $course, Quiz $quiz, QuizAttempt $attempt): View
    {
        $this->authorizeAttempt($request, $course, $attempt);

        $questions = $quiz->questions()->get()->keyBy('id');
        $ordered = collect($attempt->question_order ?? $questions->keys())
            ->map(fn ($id) => $questions->get($id))
            ->filter()
            ->values();

        return view($attempt->isSubmitted() ? 'learn.attempt-result' : 'learn.attempt', [
            'course' => $course,
            'quiz' => $quiz,
            'attempt' => $attempt,
            'questions' => $ordered,
        ]);
    }

    public function submit(Request $request, Course $course, Quiz $quiz, QuizAttempt $attempt, QuizGrader $grader, ProgressService $progress, GamificationService $xp, ActivityLogger $logger): RedirectResponse
    {
        $this->authorizeAttempt($request, $course, $attempt);

        if ($attempt->isSubmitted()) {
            return redirect()->route('attempts.show', [$course, $quiz, $attempt]);
        }

        $user = $request->user();
        $alreadyPassed = $quiz->attempts()->where('user_id', $user->id)->where('passed', true)->exists();

        $grader->grade($attempt, (array) $request->input('answers', []));
        $logger->log($user, 'quiz_submitted', $course, $quiz, ['percent' => $attempt->percent, 'late' => $attempt->isExpired()]);

        $message = 'Quiz corrigé : '.rtrim(rtrim(number_format($attempt->percent, 1, ',', ' '), '0'), ',').' %';
        if ($attempt->passed && ! $alreadyPassed) {
            $xp->award($user, 'quiz_passed');
            $message .= ' · +'.GamificationService::XP['quiz_passed'].' XP';
        }
        $progress->refresh($course, $user);

        return redirect()->route('attempts.show', [$course, $quiz, $attempt])->with($attempt->passed ? 'success' : 'info', $message);
    }

    private function authorizeAttempt(Request $request, Course $course, QuizAttempt $attempt): void
    {
        Gate::authorize('learn', $course);
        abort_unless($attempt->user_id === $request->user()->id || Gate::allows('manage', $course), 403);
    }
}
