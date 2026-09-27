<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SectionController extends Controller
{
    public function store(Request $request, Course $course): RedirectResponse
    {
        Gate::authorize('manage', $course);
        $data = $request->validate(['title' => ['required', 'string', 'max:160']]);

        $course->sections()->create($data + ['position' => $course->sections()->max('position') + 1]);

        return back()->with('success', 'Section ajoutée.');
    }

    public function update(Request $request, Course $course, Section $section): RedirectResponse
    {
        Gate::authorize('manage', $course);
        $section->update($request->validate([
            'title' => ['required', 'string', 'max:160'],
            'summary' => ['nullable', 'string', 'max:2000'],
        ]));

        return back()->with('success', 'Section mise à jour.');
    }

    public function destroy(Course $course, Section $section): RedirectResponse
    {
        Gate::authorize('manage', $course);

        if ($course->sections()->count() <= 1) {
            return back()->with('error', 'Un cours doit contenir au moins une section.');
        }

        $section->quizzes()->delete();
        $section->assignments()->delete();
        $section->delete(); // les leçons sont supprimées en cascade

        return back()->with('success', 'Section supprimée avec son contenu.');
    }
}
