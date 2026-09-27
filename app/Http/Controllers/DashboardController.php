<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Submission;
use App\Models\User;
use App\Services\RiskAnalyzer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, RiskAnalyzer $risk): View
    {
        $user = $request->user();

        $data = ['user' => $user];

        // --- Apprenant ---
        $enrollments = $user->enrollments()->with('course.teacher')->latest('last_activity_at')->get()
            ->filter(fn ($e) => $e->course !== null);
        $courseIds = $enrollments->pluck('course_id');

        $data['enrollments'] = $enrollments;
        $data['upcoming'] = $this->upcomingDeadlines($user, $courseIds);
        $data['recentGrades'] = $user->submissions()->with('assignment.course')->whereNotNull('graded_at')
            ->latest('graded_at')->take(5)->get();
        $data['certificates'] = $user->certificates()->with('course')->latest('issued_at')->get();

        // --- Enseignant ---
        if ($user->canTeach()) {
            $courses = ($user->isAdmin() ? Course::query() : $user->taughtCourses())
                ->withCount('students')->latest()->get();

            $data['teaching'] = $courses;
            $data['toGrade'] = Submission::whereNull('grade')
                ->whereHas('assignment', fn ($q) => $q->whereIn('course_id', $courses->pluck('id')))
                ->with(['assignment.course', 'user'])->latest('submitted_at')->take(8)->get();
            $data['atRisk'] = $courses->take(10)->flatMap(fn (Course $c) => $risk->analyze($c)
                ->where('level', 'high')->map(fn ($r) => $r + ['course' => $c]))
                ->sortByDesc('score')->take(6)->values();
        }

        // --- Administrateur ---
        if ($user->isAdmin()) {
            $data['stats'] = [
                'users' => User::count(),
                'courses' => Course::count(),
                'published' => Course::published()->count(),
                'active7' => User::where('last_seen_at', '>=', now()->subDays(7))->count(),
            ];
        }

        return view('dashboard', $data);
    }

    private function upcomingDeadlines(User $user, $courseIds)
    {
        $assignments = Assignment::with('course')->whereIn('course_id', $courseIds)->where('is_published', true)
            ->whereBetween('due_at', [now(), now()->addDays(21)])
            ->whereDoesntHave('submissions', fn ($q) => $q->where('user_id', $user->id))
            ->get()->map(fn ($a) => ['type' => 'Devoir', 'item' => $a, 'due' => $a->due_at, 'url' => route('assignments.show', [$a->course, $a])]);

        $quizzes = Quiz::with('course')->whereIn('course_id', $courseIds)->where('is_published', true)
            ->whereBetween('due_at', [now(), now()->addDays(21)])
            ->whereDoesntHave('attempts', fn ($q) => $q->where('user_id', $user->id)->where('passed', true))
            ->get()->map(fn ($q) => ['type' => 'Quiz', 'item' => $q, 'due' => $q->due_at, 'url' => route('quizzes.show', [$q->course, $q])]);

        return $assignments->concat($quizzes)->sortBy('due')->values();
    }
}
