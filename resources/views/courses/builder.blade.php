<x-layouts.app :title="'Contenu · '.$course->title">
    <x-page-header :title="$course->title" subtitle="Glissez-déposez sections et éléments pour réorganiser le parcours.">
        <x-slot:actions>
            <a href="{{ route('courses.show', $course) }}" class="btn-secondary">Aperçu apprenant</a>
        </x-slot:actions>
    </x-page-header>
    @include('courses._tabs')

    @unless($course->is_published)
        <div class="mb-6 rounded-xl bg-amber-50 px-4 py-3 text-sm text-amber-800 ring-1 ring-amber-200">
            Ce cours est un <strong>brouillon</strong> : il n'apparaît pas dans le catalogue. <a class="font-semibold underline" href="{{ route('courses.edit', $course) }}">Le publier</a>
        </div>
    @endunless

    <div x-data="courseBuilder(@js(route('courses.reorder', $course)))">
        <div class="mb-3 h-5 text-right text-xs">
            <span x-show="saving" x-cloak class="text-slate-400">Enregistrement…</span>
            <span x-show="saved" x-cloak class="text-emerald-600">✓ Ordre enregistré</span>
        </div>

        <div x-ref="sections" class="space-y-4">
            @foreach($course->sections as $section)
                <div class="card" data-section="{{ $section->id }}" x-data="{ editing: false }">
                    <div class="flex items-center gap-3 border-b border-slate-100 px-4 py-3">
                        <span data-section-handle class="cursor-grab select-none text-slate-400" title="Déplacer la section">⠿</span>
                        <div class="flex-1">
                            <p x-show="!editing" class="font-bold">{{ $section->title }}</p>
                            <form x-show="editing" x-cloak method="POST" action="{{ route('sections.update', [$course, $section]) }}" class="flex gap-2">
                                @csrf @method('PUT')
                                <input class="input" name="title" value="{{ $section->title }}" required>
                                <button class="btn-primary btn-sm">OK</button>
                            </form>
                        </div>
                        <button @click="editing = !editing" class="btn-ghost btn-sm">Renommer</button>
                        <form method="POST" action="{{ route('sections.destroy', [$course, $section]) }}" onsubmit="return confirm('Supprimer la section et tout son contenu ?')">
                            @csrf @method('DELETE')
                            <button class="btn-ghost btn-sm text-rose-600">Supprimer</button>
                        </form>
                    </div>

                    <ul data-items class="min-h-12 divide-y divide-slate-100">
                        @foreach($section->items() as $item)
                            <li data-item="{{ $item->id }}" data-kind="{{ $item->kind() }}" class="flex items-center gap-3 bg-white px-4 py-2.5">
                                <span data-handle class="cursor-grab select-none text-slate-300" title="Déplacer">⠿</span>
                                <x-item-icon :kind="$item->kind()" :type="$item->type ?? null" />
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium">{{ $item->title }}</p>
                                    <p class="text-xs text-slate-400">
                                        @switch($item->kind())
                                            @case('lesson') Leçon · {{ $item->type->label() }} · {{ $item->duration_minutes }} min @break
                                            @case('quiz') Quiz · {{ $item->questions->count() }} question(s) · réussite ≥ {{ $item->pass_score }} % @break
                                            @case('assignment') Devoir · /{{ $item->max_points }}@if($item->due_at) · échéance {{ $item->due_at->translatedFormat('j M H:i') }}@endif @break
                                        @endswitch
                                    </p>
                                </div>
                                @unless($item->is_published)<span class="badge bg-amber-50 text-amber-700">Brouillon</span>@endunless
                                <div class="flex shrink-0 items-center gap-1">
                                    @switch($item->kind())
                                        @case('lesson')
                                            <a class="btn-ghost btn-sm" href="{{ route('lessons.edit', [$course, $item]) }}">Modifier</a>
                                            <form method="POST" action="{{ route('lessons.destroy', [$course, $item]) }}" onsubmit="return confirm('Supprimer cette leçon ?')">@csrf @method('DELETE')<button class="btn-ghost btn-sm text-rose-600">✕</button></form>
                                            @break
                                        @case('quiz')
                                            <a class="btn-ghost btn-sm" href="{{ route('quizzes.edit', [$course, $item]) }}">Questions</a>
                                            <a class="btn-ghost btn-sm" href="{{ route('quizzes.results', [$course, $item]) }}">Résultats</a>
                                            @break
                                        @case('assignment')
                                            <a class="btn-ghost btn-sm" href="{{ route('assignments.edit', [$course, $item]) }}">Modifier</a>
                                            <a class="btn-ghost btn-sm" href="{{ route('submissions.index', [$course, $item]) }}">Rendus</a>
                                            @break
                                    @endswitch
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="flex flex-wrap gap-2 border-t border-slate-100 bg-slate-50/60 px-4 py-3">
                        <a href="{{ route('lessons.create', [$course, $section]) }}" class="btn-secondary btn-sm">+ Leçon</a>
                        <a href="{{ route('quizzes.create', [$course, $section]) }}" class="btn-secondary btn-sm">+ Quiz</a>
                        <a href="{{ route('assignments.create', [$course, $section]) }}" class="btn-secondary btn-sm">+ Devoir</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <form method="POST" action="{{ route('sections.store', $course) }}" class="mt-6 flex gap-2">
        @csrf
        <input class="input max-w-md" name="title" placeholder="Titre de la nouvelle section" required>
        <button class="btn-primary">+ Ajouter une section</button>
    </form>
</x-layouts.app>
