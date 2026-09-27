<x-layouts.learn :course="$course" :outline="$outline" :done="$done" :current="$lesson" :title="$lesson->title" :enrollment="$enrollment">
    <article class="card p-6 sm:p-10">
        <p class="text-sm font-medium text-brand-600">{{ $lesson->section->title }} · {{ $lesson->duration_minutes }} min</p>
        <h1 class="mt-1 text-3xl font-extrabold tracking-tight">{{ $lesson->title }}</h1>
        @unless($lesson->is_published)<p class="mt-2"><span class="badge bg-amber-50 text-amber-700">Brouillon — invisible pour les apprenants</span></p>@endunless

        @if($lesson->type === \App\Enums\LessonType::Video && $lesson->embedUrl())
            <div class="mt-6 aspect-video overflow-hidden rounded-xl bg-black">
                @if(\Illuminate\Support\Str::endsWith(strtolower($lesson->video_url), ['.mp4', '.webm']))
                    <video src="{{ $lesson->video_url }}" controls class="h-full w-full"></video>
                @else
                    <iframe src="{{ $lesson->embedUrl() }}" class="h-full w-full" allowfullscreen allow="accelerometer; encrypted-media; picture-in-picture"></iframe>
                @endif
            </div>
        @endif

        @if($lesson->attachment_path)
            <a href="{{ route('lessons.download', [$course, $lesson]) }}" class="mt-6 flex items-center gap-3 rounded-xl border border-slate-200 p-4 hover:bg-slate-50">
                <span class="text-2xl">📎</span>
                <span><span class="block font-semibold">{{ $lesson->attachment_name }}</span><span class="text-xs text-slate-500">Télécharger le document</span></span>
            </a>
        @endif

        @if($lesson->content)
            <x-markdown :text="$lesson->content" class="mt-8" />
        @endif

        @if($enrollment)
            <div class="mt-10 flex flex-wrap items-center justify-between gap-4 rounded-xl bg-slate-50 p-5">
                @if($isDone)
                    <p class="font-medium text-emerald-700">✓ Leçon terminée</p>
                @else
                    <p class="text-sm text-slate-600">Vous avez terminé cette leçon ?</p>
                @endif
                <form method="POST" action="{{ route('lessons.complete', [$course, $lesson]) }}">
                    @csrf
                    <button class="btn-primary">{{ $isDone ? 'Continuer →' : 'Marquer comme terminée et continuer' }}</button>
                </form>
            </div>
        @elseif(Gate::allows('manage', $course))
            <div class="mt-10"><a class="btn-secondary" href="{{ route('lessons.edit', [$course, $lesson]) }}">Modifier cette leçon</a></div>
        @endif

        @include('learn._nav')
    </article>
</x-layouts.learn>
