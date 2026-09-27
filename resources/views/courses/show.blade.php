<x-layouts.app :title="$course->title" :wide="true">
    <section class="bg-gradient-to-br {{ $course->gradient() }}">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center gap-2">
                <span class="badge bg-white/20 text-white">{{ $course->level->label() }}</span>
                @if($course->category)<span class="badge bg-white/20 text-white">{{ $course->category->name }}</span>@endif
                @unless($course->is_published)<span class="badge bg-amber-300 text-amber-900">Brouillon</span>@endunless
            </div>
            <h1 class="mt-4 max-w-3xl text-3xl font-extrabold tracking-tight text-white sm:text-4xl">{{ $course->title }}</h1>
            <p class="mt-3 max-w-2xl text-lg text-white/85">{{ $course->summary }}</p>
            <div class="mt-6 flex flex-wrap items-center gap-6 text-sm text-white/90">
                <span class="flex items-center gap-2"><x-avatar :user="$course->teacher" /> {{ $course->teacher->name }}</span>
                <span>👥 {{ $course->students_count }} inscrit(s)</span>
                <span>⏱ {{ intdiv($course->durationMinutes(), 60) }} h {{ $course->durationMinutes() % 60 }} min de contenu</span>
                @if($course->starts_at)<span>📅 {{ $course->starts_at->translatedFormat('j M Y') }}@if($course->ends_at) → {{ $course->ends_at->translatedFormat('j M Y') }}@endif</span>@endif
            </div>
        </div>
    </section>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if($canManage)
            @include('courses._tabs')
        @endif

        <div class="grid gap-8 lg:grid-cols-3">
            <div class="space-y-8 lg:col-span-2">
                @if($course->announcements->isNotEmpty() || $canManage)
                    <div>
                        <h2 class="mb-3 text-lg font-bold">📣 Annonces</h2>
                        @if($canManage)
                            <form method="POST" action="{{ route('announcements.store', $course) }}" class="card mb-4 space-y-3 p-4" x-data="{ open: false }">
                                @csrf
                                <input class="input" name="title" placeholder="Publier une annonce aux apprenants…" @focus="open = true" required>
                                <div x-show="open" x-cloak class="space-y-3">
                                    <textarea class="input" name="body" rows="3" placeholder="Message (Markdown)" required></textarea>
                                    <button class="btn-primary btn-sm">Publier et notifier</button>
                                </div>
                            </form>
                        @endif
                        <div class="space-y-3">
                            @foreach($course->announcements->take(3) as $a)
                                <div class="card p-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <p class="font-semibold">{{ $a->title }}</p>
                                        <span class="shrink-0 text-xs text-slate-400">{{ $a->created_at->diffForHumans() }}</span>
                                    </div>
                                    <x-markdown :text="$a->body" class="prose-sm mt-1" />
                                    @if($canManage)
                                        <form method="POST" action="{{ route('announcements.destroy', [$course, $a]) }}" class="mt-2">@csrf @method('DELETE')<button class="text-xs text-rose-600 hover:underline">Supprimer</button></form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($course->description)
                    <div class="card p-6">
                        <h2 class="mb-3 text-lg font-bold">À propos de ce cours</h2>
                        <x-markdown :text="$course->description" />
                    </div>
                @endif

                <div>
                    <h2 class="mb-3 text-lg font-bold">Programme</h2>
                    <div class="space-y-3">
                        @foreach($course->sections as $section)
                            @php($items = $section->items(! $canManage))
                            <div class="card overflow-hidden" x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }">
                                <button @click="open = !open" class="flex w-full items-center justify-between px-5 py-4 text-left hover:bg-slate-50">
                                    <span class="font-semibold">{{ $loop->iteration }}. {{ $section->title }}</span>
                                    <span class="flex items-center gap-3 text-xs text-slate-500">{{ $items->count() }} élément(s) <span :class="open && 'rotate-180'" class="transition">▾</span></span>
                                </button>
                                <ul x-show="open" class="divide-y divide-slate-100 border-t border-slate-100">
                                    @forelse($items as $item)
                                        @php($isDone = in_array($item->id, $done[$item->kind()]))
                                        <li class="flex items-center gap-3 px-5 py-3 text-sm">
                                            <x-item-icon :kind="$item->kind()" :type="$item->type ?? null" />
                                            @if($enrollment || $canManage)
                                                <a class="flex-1 hover:text-brand-600" href="{{ route(['lesson' => 'lessons.show', 'quiz' => 'quizzes.show', 'assignment' => 'assignments.show'][$item->kind()], [$course, $item]) }}">{{ $item->title }}</a>
                                            @else
                                                <span class="flex-1 text-slate-600">{{ $item->title }}</span>
                                            @endif
                                            @unless($item->is_published)<span class="badge bg-amber-50 text-amber-700">Brouillon</span>@endunless
                                            @if($item->kind() === 'lesson')<span class="text-xs text-slate-400">{{ $item->duration_minutes }} min</span>@endif
                                            @if($item->kind() !== 'lesson' && $item->due_at)<span class="text-xs text-slate-400">échéance {{ $item->due_at->translatedFormat('j M') }}</span>@endif
                                            @if($isDone)<span class="text-emerald-600" title="Terminé">✓</span>@elseif(! $enrollment && ! $canManage)<span class="text-slate-300">🔒</span>@endif
                                        </li>
                                    @empty
                                        <li class="px-5 py-3 text-sm text-slate-400">Section vide.</li>
                                    @endforelse
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="card p-6 lg:sticky lg:top-24">
                    @if($enrollment)
                        <p class="text-sm font-medium text-slate-500">Votre progression</p>
                        <p class="mt-1 text-4xl font-extrabold">{{ $enrollment->progress }}%</p>
                        <x-progress :value="$enrollment->progress" class="mt-3" size="h-3" />
                        <p class="mt-2 text-xs text-slate-500">{{ $breakdown['done'] }} / {{ $breakdown['total'] }} éléments terminés</p>
                        <a href="{{ route('courses.learn', $course) }}" class="btn-primary mt-5 w-full">{{ $enrollment->progress ? 'Reprendre le cours' : 'Commencer le cours' }}</a>
                        @if($certificate)
                            <a href="{{ route('certificates.show', $certificate) }}" class="btn-secondary mt-2 w-full">🎓 Voir mon certificat</a>
                        @endif
                        <div class="mt-5 grid grid-cols-2 gap-2 text-center text-sm">
                            <a href="{{ route('discussions.index', $course) }}" class="rounded-lg bg-slate-50 py-2 hover:bg-slate-100">💬 Forum</a>
                            <a href="{{ route('gradebook.show', $course) }}" class="rounded-lg bg-slate-50 py-2 hover:bg-slate-100">📊 Mes notes</a>
                        </div>
                        <form method="POST" action="{{ route('enrollments.destroy', $course) }}" class="mt-4 text-center" onsubmit="return confirm('Se désinscrire ? Votre progression sera conservée.')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-slate-400 hover:text-rose-600">Se désinscrire</button>
                        </form>
                    @elseif($canManage)
                        <p class="font-semibold">Vous gérez ce cours</p>
                        <p class="mt-1 text-sm text-slate-500">Utilisez les onglets pour éditer le contenu et suivre vos apprenants.</p>
                        <a href="{{ route('courses.builder', $course) }}" class="btn-primary mt-4 w-full">Éditer le contenu</a>
                    @elseif(auth()->check())
                        <p class="text-lg font-bold">Rejoindre ce cours</p>
                        <p class="mt-1 text-sm text-slate-500">Accès immédiat à toutes les ressources.</p>
                        <form method="POST" action="{{ route('enrollments.store', $course) }}" class="mt-4 space-y-3">
                            @csrf
                            @if(filled($course->getRawOriginal('enrollment_key')))
                                <input class="input" name="enrollment_key" placeholder="Clé d'inscription" required>
                            @endif
                            <button class="btn-primary w-full">S'inscrire gratuitement</button>
                        </form>
                    @else
                        <p class="text-lg font-bold">Envie de suivre ce cours ?</p>
                        <a href="{{ route('login') }}" class="btn-primary mt-4 w-full">Se connecter pour s'inscrire</a>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</x-layouts.app>
