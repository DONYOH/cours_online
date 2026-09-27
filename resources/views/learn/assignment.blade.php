<x-layouts.learn :course="$course" :outline="$outline" :done="$done" :current="$assignment" :title="$assignment->title" :enrollment="$enrollment">
    <div class="card p-6 sm:p-10">
        <p class="text-sm font-medium text-amber-600">Devoir · noté sur {{ $assignment->max_points }}</p>
        <h1 class="mt-1 text-3xl font-extrabold tracking-tight">{{ $assignment->title }}</h1>
        @if($assignment->due_at)
            <p class="mt-2 text-sm {{ $assignment->isOverdue() ? 'text-rose-600' : 'text-slate-500' }}">
                📅 Date limite : {{ $assignment->due_at->translatedFormat('l j F Y à H:i') }} ({{ $assignment->due_at->diffForHumans() }})
            </p>
        @endif

        @if($assignment->instructions)<x-markdown :text="$assignment->instructions" class="mt-6" />@endif

        @if($canManage)
            <div class="mt-8 flex gap-2">
                <a href="{{ route('submissions.index', [$course, $assignment]) }}" class="btn-primary">Voir les rendus</a>
                <a href="{{ route('assignments.edit', [$course, $assignment]) }}" class="btn-secondary">Modifier</a>
            </div>
        @endif

        @if($enrollment)
            <div class="mt-10 rounded-xl border border-slate-200 p-6">
                @if($submission)
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <p class="font-semibold text-emerald-700">✓ Rendu le {{ $submission->submitted_at->translatedFormat('j F à H:i') }}</p>
                        @if($submission->is_late)<span class="badge bg-rose-50 text-rose-700">En retard</span>@endif
                    </div>
                    @if($submission->file_path)
                        <a href="{{ route('submissions.download', [$course, $assignment, $submission]) }}" class="mt-2 inline-block text-sm link">📎 {{ $submission->file_name }}</a>
                    @endif
                    @if($submission->isGraded())
                        <div class="mt-4 rounded-xl bg-brand-50 p-5">
                            <p class="text-sm text-brand-700">Note</p>
                            <p class="text-3xl font-extrabold text-brand-800">{{ rtrim(rtrim(number_format($submission->grade, 2, ',', ''), '0'), ',') }} / {{ $assignment->max_points }}</p>
                            @if($submission->feedback)<x-markdown :text="$submission->feedback" class="prose-sm mt-3" />@endif
                        </div>
                    @endif
                @endif

                @if(! $submission?->isGraded() && $assignment->acceptsSubmissions())
                    <form method="POST" action="{{ route('submissions.store', [$course, $assignment]) }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                        @csrf
                        <h2 class="font-bold">{{ $submission ? 'Mettre à jour mon rendu' : 'Rendre mon travail' }}</h2>
                        <div>
                            <label class="label" for="content">Réponse en ligne <span class="font-normal text-slate-400">(Markdown)</span></label>
                            <textarea class="input" id="content" name="content" rows="8">{{ old('content', $submission?->content) }}</textarea>
                        </div>
                        <div>
                            <label class="label" for="file">Fichier joint (20 Mo max)</label>
                            <input class="input" type="file" id="file" name="file">
                        </div>
                        <button class="btn-primary">{{ $submission ? 'Mettre à jour' : 'Envoyer mon devoir' }}</button>
                        @if($assignment->isOverdue())<p class="text-xs text-rose-600">La date limite est passée : votre rendu sera marqué en retard.</p>@endif
                    </form>
                @elseif(! $submission)
                    <p class="text-sm text-rose-600">La date limite est dépassée et ce devoir n'accepte plus de rendu.</p>
                @endif
            </div>
        @endif

        @include('learn._nav')
    </div>
</x-layouts.learn>
