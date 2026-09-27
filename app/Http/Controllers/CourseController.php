<?php

namespace App\Http\Controllers;

use App\Enums\CourseLevel;
use App\Models\Category;
use App\Models\Course;
use App\Services\ProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $courses = ($user->isAdmin() ? Course::query() : $user->taughtCourses())
            ->with('category')->withCount(['students', 'lessons', 'quizzes', 'assignments'])
            ->latest()->get();

        return view('courses.index', compact('courses'));
    }

    public function create(): View
    {
        return view('courses.form', [
            'course' => new Course(['level' => CourseLevel::Beginner, 'language' => 'fr']),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Course::class);

        $course = new Course($this->validated($request));
        $course->teacher_id = $request->user()->id;
        $course->save();

        $course->sections()->create(['title' => 'Introduction', 'position' => 1]);

        return redirect()->route('courses.builder', $course)->with('success', 'Cours créé. Ajoutez maintenant votre contenu.');
    }

    public function show(Request $request, Course $course, ProgressService $progress): View
    {
        Gate::authorize('view', $course);
        $user = $request->user();

        $course->load(['teacher', 'category', 'sections.lessons', 'sections.quizzes', 'sections.assignments', 'announcements.author'])
            ->loadCount('students');

        $enrollment = $course->enrollmentFor($user);
        $completedLessonIds = $user ? $user->completedLessons()->where('lessons.course_id', $course->id)->pluck('lessons.id')->all() : [];
        $passedQuizIds = $user ? $user->quizAttempts()->where('passed', true)->pluck('quiz_id')->unique()->all() : [];
        $submittedIds = $user ? $user->submissions()->pluck('assignment_id')->all() : [];

        return view('courses.show', [
            'course' => $course,
            'enrollment' => $enrollment,
            'canManage' => $user && Gate::allows('manage', $course),
            'breakdown' => $enrollment ? $progress->breakdown($course, $user) : null,
            'done' => ['lesson' => $completedLessonIds, 'quiz' => $passedQuizIds, 'assignment' => $submittedIds],
            'certificate' => $user ? $user->certificates()->where('course_id', $course->id)->first() : null,
        ]);
    }

    public function edit(Course $course): View
    {
        Gate::authorize('manage', $course);

        return view('courses.form', [
            'course' => $course,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        Gate::authorize('manage', $course);
        $course->update($this->validated($request, $course));

        return redirect()->route('courses.builder', $course)->with('success', 'Paramètres du cours enregistrés.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        Gate::authorize('manage', $course);
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Cours archivé.');
    }

    private function validated(Request $request, ?Course $course = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'summary' => ['nullable', 'string', 'max:300'],
            'description' => ['nullable', 'string', 'max:20000'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'level' => ['required', Rule::enum(CourseLevel::class)],
            'language' => ['required', 'string', 'size:2'],
            'enrollment_key' => ['nullable', 'string', 'max:60'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }
}
