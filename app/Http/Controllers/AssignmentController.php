<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Section;
use App\Services\CourseOutline;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function show(Request $request, Course $course, Assignment $assignment, CourseOutline $outline): View
    {
        Gate::authorize('learn', $course);
        $canManage = Gate::allows('manage', $course);
        abort_unless($assignment->is_published || $canManage, 404);
        $user = $request->user();

        return view('learn.assignment', [
            'course' => $course,
            'assignment' => $assignment,
            'outline' => $outline->items($course, $canManage),
            'done' => $outline->doneMap($course, $user),
            'nav' => $outline->neighbours($course, $assignment, $canManage),
            'submission' => $assignment->submissions()->where('user_id', $user->id)->first(),
            'canManage' => $canManage,
            'enrollment' => $course->enrollmentFor($user),
        ]);
    }

    public function create(Course $course, Section $section): View
    {
        Gate::authorize('manage', $course);

        return view('assignments.form', [
            'course' => $course,
            'section' => $section,
            'assignment' => new Assignment(['max_points' => 20, 'allow_late' => true, 'due_at' => now()->addWeeks(2)->setTime(23, 59)]),
        ]);
    }

    public function store(Request $request, Course $course, Section $section): RedirectResponse
    {
        Gate::authorize('manage', $course);

        $assignment = new Assignment($this->validated($request));
        $assignment->course_id = $course->id;
        $assignment->section_id = $section->id;
        $assignment->position = $section->items()->max('position') + 1;
        $assignment->save();

        return redirect()->route('courses.builder', $course)->with('success', 'Devoir créé.');
    }

    public function edit(Course $course, Assignment $assignment): View
    {
        Gate::authorize('manage', $course);

        return view('assignments.form', ['course' => $course, 'section' => $assignment->section, 'assignment' => $assignment]);
    }

    public function update(Request $request, Course $course, Assignment $assignment): RedirectResponse
    {
        Gate::authorize('manage', $course);
        $assignment->update($this->validated($request));

        return redirect()->route('courses.builder', $course)->with('success', 'Devoir mis à jour.');
    }

    public function destroy(Course $course, Assignment $assignment): RedirectResponse
    {
        Gate::authorize('manage', $course);
        $assignment->delete();

        return back()->with('success', 'Devoir supprimé.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'instructions' => ['nullable', 'string', 'max:50000'],
            'due_at' => ['nullable', 'date'],
            'max_points' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);
        $data['allow_late'] = $request->boolean('allow_late');
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }
}
