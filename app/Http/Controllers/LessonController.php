<?php

namespace App\Http\Controllers;

use App\Enums\LessonType;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Section;
use App\Services\ActivityLogger;
use App\Services\CourseOutline;
use App\Services\GamificationService;
use App\Services\ProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LessonController extends Controller
{
    public function __construct(private CourseOutline $outline) {}

    /** Reprend là où l'apprenant s'est arrêté. */
    public function resume(Request $request, Course $course): RedirectResponse
    {
        Gate::authorize('learn', $course);

        $done = $this->outline->doneMap($course, $request->user());
        $items = $this->outline->items($course);

        $next = $items->first(fn ($i) => ! in_array($i->id, $done[$i->kind()], true)) ?? $items->first();

        return $next
            ? redirect($this->outline->url($course, $next))
            : redirect()->route('courses.show', $course)->with('info', 'Ce cours n\'a pas encore de contenu publié.');
    }

    public function show(Request $request, Course $course, Lesson $lesson, ActivityLogger $logger): View
    {
        Gate::authorize('learn', $course);
        $canManage = Gate::allows('manage', $course);
        abort_unless($lesson->is_published || $canManage, 404);

        $user = $request->user();
        $logger->log($user, 'lesson_viewed', $course, $lesson);

        return view('learn.lesson', [
            'course' => $course,
            'lesson' => $lesson,
            'outline' => $this->outline->items($course, $canManage),
            'done' => $this->outline->doneMap($course, $user),
            'nav' => $this->outline->neighbours($course, $lesson, $canManage),
            'isDone' => $user->completedLessons()->whereKey($lesson->id)->exists(),
            'enrollment' => $course->enrollmentFor($user),
        ]);
    }

    public function complete(Request $request, Course $course, Lesson $lesson, ProgressService $progress, GamificationService $xp, ActivityLogger $logger): RedirectResponse
    {
        Gate::authorize('learn', $course);
        $user = $request->user();

        $changes = $user->completedLessons()->syncWithoutDetaching([$lesson->id]);
        if ($changes['attached'] !== []) {
            $xp->award($user, 'lesson_completed');
            $logger->log($user, 'lesson_completed', $course, $lesson);
        }
        $progress->refresh($course, $user);

        $next = $this->outline->neighbours($course, $lesson)['next'];

        return $next
            ? redirect($this->outline->url($course, $next))->with('success', 'Leçon terminée · +'.GamificationService::XP['lesson_completed'].' XP')
            : redirect()->route('courses.show', $course)->with('success', 'Vous avez atteint la fin du parcours !');
    }

    public function download(Course $course, Lesson $lesson): StreamedResponse
    {
        Gate::authorize('learn', $course);
        abort_unless($lesson->attachment_path && Storage::disk('local')->exists($lesson->attachment_path), 404);

        return Storage::disk('local')->download($lesson->attachment_path, $lesson->attachment_name);
    }

    public function create(Course $course, Section $section): View
    {
        Gate::authorize('manage', $course);

        return view('lessons.form', ['course' => $course, 'section' => $section, 'lesson' => new Lesson(['type' => LessonType::Text, 'duration_minutes' => 10, 'is_published' => true])]);
    }

    public function store(Request $request, Course $course, Section $section): RedirectResponse
    {
        Gate::authorize('manage', $course);

        $lesson = new Lesson($this->validated($request));
        $lesson->course_id = $course->id;
        $lesson->section_id = $section->id;
        $lesson->position = $section->items()->max('position') + 1;
        $this->handleUpload($request, $lesson);
        $lesson->save();

        return redirect()->route('courses.builder', $course)->with('success', 'Leçon ajoutée.');
    }

    public function edit(Course $course, Lesson $lesson): View
    {
        Gate::authorize('manage', $course);

        return view('lessons.form', ['course' => $course, 'section' => $lesson->section, 'lesson' => $lesson]);
    }

    public function update(Request $request, Course $course, Lesson $lesson): RedirectResponse
    {
        Gate::authorize('manage', $course);

        $lesson->fill($this->validated($request));
        $this->handleUpload($request, $lesson);
        $lesson->save();

        return redirect()->route('courses.builder', $course)->with('success', 'Leçon mise à jour.');
    }

    public function destroy(Course $course, Lesson $lesson): RedirectResponse
    {
        Gate::authorize('manage', $course);

        if ($lesson->attachment_path) {
            Storage::disk('local')->delete($lesson->attachment_path);
        }
        $lesson->delete();

        return back()->with('success', 'Leçon supprimée.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'type' => ['required', Rule::enum(LessonType::class)],
            'content' => ['nullable', 'string', 'max:100000'],
            'video_url' => ['nullable', 'url', 'max:500', 'required_if:type,video'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:600'],
            'attachment' => ['nullable', 'file', 'max:51200', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,odt,odp,zip,txt,csv,png,jpg,jpeg'],
        ]);
        unset($data['attachment']);
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }

    private function handleUpload(Request $request, Lesson $lesson): void
    {
        if (! $request->hasFile('attachment')) {
            return;
        }

        if ($lesson->attachment_path) {
            Storage::disk('local')->delete($lesson->attachment_path);
        }

        $file = $request->file('attachment');
        $lesson->attachment_path = $file->store('lessons', 'local');
        $lesson->attachment_name = $file->getClientOriginalName();
    }
}
