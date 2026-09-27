<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Discussion;
use App\Models\Reply;
use App\Notifications\NewReply;
use App\Services\ActivityLogger;
use App\Services\GamificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DiscussionController extends Controller
{
    public function index(Request $request, Course $course): View
    {
        Gate::authorize('learn', $course);

        $discussions = $course->discussions()
            ->with('author')
            ->withCount('replies')
            ->withExists(['replies as resolved' => fn ($q) => $q->where('is_solution', true)])
            ->when($request->string('q')->toString(), fn ($q, $term) => $q->where('title', 'like', "%{$term}%"))
            ->orderByDesc('is_pinned')
            ->orderByRaw('COALESCE(last_reply_at, created_at) DESC')
            ->paginate(20)->withQueryString();

        return view('discussions.index', compact('course', 'discussions'));
    }

    public function store(Request $request, Course $course, ActivityLogger $logger, GamificationService $xp): RedirectResponse
    {
        Gate::authorize('learn', $course);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'body' => ['required', 'string', 'max:10000'],
        ]);

        $discussion = $course->discussions()->create($data + ['user_id' => $request->user()->id]);
        $xp->award($request->user(), 'discussion_created');
        $logger->log($request->user(), 'discussion_created', $course, $discussion);

        return redirect()->route('discussions.show', [$course, $discussion]);
    }

    public function show(Course $course, Discussion $discussion): View
    {
        Gate::authorize('learn', $course);
        $discussion->load(['author', 'replies.author']);

        return view('discussions.show', [
            'course' => $course,
            'discussion' => $discussion,
            'canModerate' => Gate::allows('manage', $course),
        ]);
    }

    public function reply(Request $request, Course $course, Discussion $discussion, ActivityLogger $logger, GamificationService $xp): RedirectResponse
    {
        Gate::authorize('learn', $course);

        if ($discussion->is_locked && ! Gate::allows('manage', $course)) {
            return back()->with('error', 'Cette discussion est verrouillée.');
        }

        $data = $request->validate(['body' => ['required', 'string', 'max:10000']]);
        $reply = $discussion->replies()->create($data + ['user_id' => $request->user()->id]);
        $discussion->update(['last_reply_at' => now()]);

        $xp->award($request->user(), 'reply_posted');
        $logger->log($request->user(), 'reply_posted', $course, $discussion);

        if ($discussion->user_id !== $request->user()->id) {
            $discussion->author->notify(new NewReply($reply->setRelation('discussion', $discussion->setRelation('course', $course))));
        }

        return redirect()->to(route('discussions.show', [$course, $discussion]).'#reponse-'.$reply->id);
    }

    public function markSolution(Request $request, Course $course, Discussion $discussion, Reply $reply, GamificationService $xp): RedirectResponse
    {
        abort_unless($discussion->user_id === $request->user()->id || Gate::allows('manage', $course), 403);

        $discussion->replies()->update(['is_solution' => false]);
        $reply->update(['is_solution' => true]);

        if ($reply->user_id !== $request->user()->id) {
            $xp->award($reply->author, 'solution_accepted');
        }

        return back()->with('success', 'Réponse marquée comme solution.');
    }

    public function moderate(Request $request, Course $course, Discussion $discussion): RedirectResponse
    {
        Gate::authorize('manage', $course);

        $discussion->update($request->validate([
            'is_pinned' => ['sometimes', 'boolean'],
            'is_locked' => ['sometimes', 'boolean'],
        ]));

        return back()->with('success', 'Discussion mise à jour.');
    }
}
