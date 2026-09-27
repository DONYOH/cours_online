<x-layouts.app :title="$discussion->title">
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('discussions.index', $course) }}" class="link text-sm">← Forum · {{ $course->title }}</a>

        <div class="card mt-4 p-6">
            <div class="flex items-start justify-between gap-4">
                <h1 class="text-2xl font-extrabold">{{ $discussion->title }}</h1>
                @if($canModerate)
                    <div class="flex shrink-0 gap-1">
                        <form method="POST" action="{{ route('discussions.moderate', [$course, $discussion]) }}">@csrf @method('PATCH')<input type="hidden" name="is_pinned" value="{{ $discussion->is_pinned ? 0 : 1 }}"><button class="btn-ghost btn-sm">{{ $discussion->is_pinned ? 'Désépingler' : '📌 Épingler' }}</button></form>
                        <form method="POST" action="{{ route('discussions.moderate', [$course, $discussion]) }}">@csrf @method('PATCH')<input type="hidden" name="is_locked" value="{{ $discussion->is_locked ? 0 : 1 }}"><button class="btn-ghost btn-sm">{{ $discussion->is_locked ? 'Déverrouiller' : '🔒 Verrouiller' }}</button></form>
                    </div>
                @endif
            </div>
            <div class="mt-3 flex items-center gap-2 text-sm text-slate-500"><x-avatar :user="$discussion->author" /> {{ $discussion->author->name }} · {{ $discussion->created_at->diffForHumans() }}</div>
            <x-markdown :text="$discussion->body" class="mt-4" />
        </div>

        <h2 class="mb-3 mt-8 font-bold">{{ $discussion->replies->count() }} réponse(s)</h2>
        <div class="space-y-3">
            @foreach($discussion->replies as $reply)
                <div id="reponse-{{ $reply->id }}" class="card p-5 {{ $reply->is_solution ? 'ring-2 ring-emerald-400' : '' }}">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-2 text-sm">
                            <x-avatar :user="$reply->author" size="h-7 w-7 text-[10px]" />
                            <span class="font-semibold">{{ $reply->author->name }}</span>
                            @if($reply->author->id === $course->teacher_id)<span class="badge bg-brand-50 text-brand-700">Enseignant</span>@endif
                            <span class="text-slate-400">· {{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        @if($reply->is_solution)
                            <span class="badge bg-emerald-50 text-emerald-700">✓ Solution</span>
                        @elseif($discussion->user_id === auth()->id() || $canModerate)
                            <form method="POST" action="{{ route('discussions.solution', [$course, $discussion, $reply]) }}">@csrf<button class="text-xs text-slate-400 hover:text-emerald-600">Marquer comme solution</button></form>
                        @endif
                    </div>
                    <x-markdown :text="$reply->body" class="prose-sm mt-3" />
                </div>
            @endforeach
        </div>

        @if(! $discussion->is_locked || $canModerate)
            <form method="POST" action="{{ route('discussions.reply', [$course, $discussion]) }}" class="card mt-6 space-y-3 p-5">
                @csrf
                <textarea class="input" name="body" rows="4" placeholder="Votre réponse…" required></textarea>
                <button class="btn-primary">Répondre</button>
            </form>
        @else
            <p class="mt-6 text-center text-sm text-slate-500">🔒 Cette discussion est verrouillée.</p>
        @endif
    </div>
</x-layouts.app>
