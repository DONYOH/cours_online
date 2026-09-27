<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Services\ActivityLogger;
use App\Services\ProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function store(Request $request, Course $course, ActivityLogger $logger, ProgressService $progress): RedirectResponse
    {
        $user = $request->user();

        if ($user->isEnrolledIn($course)) {
            return redirect()->route('courses.learn', $course);
        }

        Gate::authorize('enroll', $course);

        if (filled($course->enrollment_key) && ! hash_equals((string) $course->enrollment_key, (string) $request->input('enrollment_key'))) {
            return back()->withErrors(['enrollment_key' => 'Clé d\'inscription incorrecte.']);
        }

        $course->enrollments()->create(['user_id' => $user->id, 'last_activity_at' => now()]);
        $logger->log($user, 'enrolled', $course, $course);
        $progress->refresh($course, $user);

        return redirect()->route('courses.learn', $course)->with('success', 'Inscription confirmée. Bon apprentissage !');
    }

    public function destroy(Request $request, Course $course): RedirectResponse
    {
        $course->enrollments()->where('user_id', $request->user()->id)->delete();

        return redirect()->route('dashboard')->with('success', 'Vous vous êtes désinscrit du cours.');
    }

    public function index(Request $request, Course $course): View
    {
        Gate::authorize('manage', $course);

        $enrollments = $course->enrollments()->with('user')
            ->when($request->string('q')->toString(), fn ($q, $term) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%")))
            ->orderByDesc('last_activity_at')
            ->paginate(30)->withQueryString();

        return view('courses.participants', compact('course', 'enrollments'));
    }

    public function remove(Course $course, Enrollment $enrollment): RedirectResponse
    {
        Gate::authorize('manage', $course);
        $enrollment->delete();

        return back()->with('success', 'Participant retiré du cours.');
    }
}
