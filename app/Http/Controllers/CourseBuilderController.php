<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CourseBuilderController extends Controller
{
    public function show(Course $course): View
    {
        Gate::authorize('manage', $course);

        $course->load(['sections.lessons', 'sections.quizzes.questions', 'sections.assignments'])
            ->loadCount('students');

        return view('courses.builder', compact('course'));
    }

    /**
     * Réordonnancement par glisser-déposer.
     * Payload : { sections: [{id, items: [{kind, id}]}] }
     */
    public function reorder(Request $request, Course $course): JsonResponse
    {
        Gate::authorize('manage', $course);

        $data = $request->validate([
            'sections' => ['required', 'array'],
            'sections.*.id' => ['required', 'integer'],
            'sections.*.items' => ['array'],
            'sections.*.items.*.kind' => ['required', 'in:lesson,quiz,assignment'],
            'sections.*.items.*.id' => ['required', 'integer'],
        ]);

        $sectionIds = $course->sections()->pluck('id')->all();
        $models = ['lesson' => Lesson::class, 'quiz' => Quiz::class, 'assignment' => Assignment::class];

        DB::transaction(function () use ($data, $course, $sectionIds, $models) {
            foreach ($data['sections'] as $sPos => $section) {
                if (! in_array($section['id'], $sectionIds, true)) {
                    continue;
                }
                $course->sections()->whereKey($section['id'])->update(['position' => $sPos + 1]);

                foreach ($section['items'] ?? [] as $iPos => $item) {
                    $models[$item['kind']]::where('course_id', $course->id)
                        ->whereKey($item['id'])
                        ->update(['section_id' => $section['id'], 'position' => $iPos + 1]);
                }
            }
        });

        return response()->json(['ok' => true]);
    }
}
