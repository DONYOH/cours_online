<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Submission;
use App\Notifications\GradePosted;
use App\Services\ActivityLogger;
use App\Services\GamificationService;
use App\Services\ProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubmissionController extends Controller
{
    public function store(Request $request, Course $course, Assignment $assignment, ProgressService $progress, GamificationService $xp, ActivityLogger $logger): RedirectResponse
    {
        Gate::authorize('learn', $course);
        abort_unless($assignment->is_published, 404);
        $user = $request->user();

        if (! $assignment->acceptsSubmissions()) {
            return back()->with('error', 'La date limite est dépassée : les rendus ne sont plus acceptés.');
        }

        $data = $request->validate([
            'content' => ['nullable', 'string', 'max:50000', 'required_without:file'],
            'file' => ['nullable', 'file', 'max:20480', 'mimes:pdf,doc,docx,odt,zip,txt,py,php,sql,ipynb,png,jpg,jpeg,xlsx,csv,pptx'],
        ]);

        $submission = $assignment->submissions()->firstOrNew(['user_id' => $user->id]);

        if ($submission->exists && $submission->isGraded()) {
            return back()->with('error', 'Ce devoir a déjà été noté : il ne peut plus être modifié.');
        }

        $isNew = ! $submission->exists;
        $submission->content = $data['content'] ?? null;
        $submission->submitted_at = now();
        $submission->is_late = $assignment->isOverdue();

        if ($request->hasFile('file')) {
            if ($submission->file_path) {
                Storage::disk('local')->delete($submission->file_path);
            }
            $submission->file_path = $request->file('file')->store("submissions/{$assignment->id}", 'local');
            $submission->file_name = $request->file('file')->getClientOriginalName();
        }
        $submission->save();

        if ($isNew) {
            $xp->award($user, 'assignment_submitted');
        }
        $logger->log($user, 'assignment_submitted', $course, $assignment, ['late' => $submission->is_late]);
        $progress->refresh($course, $user);

        return back()->with('success', $isNew ? 'Devoir rendu · +'.GamificationService::XP['assignment_submitted'].' XP' : 'Rendu mis à jour.');
    }

    public function index(Course $course, Assignment $assignment): View
    {
        Gate::authorize('manage', $course);

        $submissions = $assignment->submissions()->with('user')->orderByRaw('grade is not null')->latest('submitted_at')->get();
        $missing = $course->students()->whereNotIn('users.id', $submissions->pluck('user_id'))->orderBy('name')->get();

        return view('assignments.submissions', compact('course', 'assignment', 'submissions', 'missing'));
    }

    public function grade(Request $request, Course $course, Assignment $assignment, Submission $submission): RedirectResponse
    {
        Gate::authorize('manage', $course);

        $data = $request->validate([
            'grade' => ['required', 'numeric', 'min:0', 'max:'.$assignment->max_points],
            'feedback' => ['nullable', 'string', 'max:10000'],
        ]);

        $submission->update($data + ['graded_by' => $request->user()->id, 'graded_at' => now()]);
        $submission->user->notify(new GradePosted($submission->setRelation('assignment', $assignment)));

        return back()->with('success', 'Note enregistrée pour '.$submission->user->name.'.');
    }

    public function download(Request $request, Course $course, Assignment $assignment, Submission $submission): StreamedResponse
    {
        abort_unless($submission->user_id === $request->user()->id || Gate::allows('manage', $course), 403);
        abort_unless($submission->file_path && Storage::disk('local')->exists($submission->file_path), 404);

        return Storage::disk('local')->download($submission->file_path, $submission->file_name);
    }
}
