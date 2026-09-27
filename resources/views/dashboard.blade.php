<x-layouts.app title="Tableau de bord">
    <div class="mb-8 flex flex-wrap items-center justify-between gap-6">
        <div>
            <p class="text-sm font-medium text-brand-600">{{ now()->translatedFormat('l j F Y') }}</p>
            <h1 class="mt-1 text-3xl font-extrabold tracking-tight">Bonjour {{ \Illuminate\Support\Str::of($user->name)->before(' ') }} 👋</h1>
        </div>
        <div class="card flex items-center gap-4 px-5 py-4">
            <div class="grid h-12 w-12 place-items-center rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-lg font-extrabold text-white">{{ $user->level() }}</div>
            <div class="w-44">
                <p class="text-sm font-semibold">Niveau {{ $user->level() }} · {{ $user->xp }} XP</p>
                <x-progress :value="$user->levelProgress()" class="mt-2" />
                <a href="{{ route('leaderboard') }}" class="mt-1 block text-xs text-slate-500 hover:text-brand-600">Voir le classement →</a>
            </div>
        </div>
    </div>

    @isset($stats)
        <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat label="Utilisateurs" :value="$stats['users']" />
            <x-stat label="Cours" :value="$stats['courses']" :hint="$stats['published'].' publiés'" />
            <x-stat label="Actifs (7 j)" :value="$stats['active7']" tone="emerald" />
            <div class="card flex flex-col justify-center gap-2 p-5">
                <a href="{{ route('admin.users.index') }}" class="link text-sm">Gérer les utilisateurs →</a>
                <a href="{{ route('admin.categories.index') }}" class="link text-sm">Gérer les catégories →</a>
            </div>
        </div>
    @endisset

    @isset($teaching)
        <div class="mb-10 grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="text-lg font-bold">Mes cours en tant qu'enseignant</h2>
                    <a href="{{ route('courses.create') }}" class="btn-primary btn-sm">+ Nouveau cours</a>
                </div>
                <div class="card divide-y divide-slate-100">
                    @forelse($teaching->take(6) as $course)
                        <div class="flex items-center justify-between gap-4 p-4">
                            <div class="flex min-w-0 items-center gap-3">
                                <div class="h-10 w-10 shrink-0 rounded-lg bg-gradient-to-br {{ $course->gradient() }}"></div>
                                <div class="min-w-0">
                                    <a href="{{ route('courses.show', $course) }}" class="block truncate font-semibold hover:text-brand-600">{{ $course->title }}</a>
                                    <p class="text-xs text-slate-500">{{ $course->students_count }} apprenant(s) · {!! $course->is_published ? '<span class="text-emerald-600">Publié</span>' : '<span class="text-amber-600">Brouillon</span>' !!}</p>
                                </div>
                            </div>
                            <div class="flex shrink-0 gap-1">
                                <a href="{{ route('courses.builder', $course) }}" class="btn-ghost btn-sm">Contenu</a>
                                <a href="{{ route('courses.analytics', $course) }}" class="btn-ghost btn-sm">Analytique</a>
                            </div>
                        </div>
                    @empty
                        <p class="p-6 text-sm text-slate-500">Vous n'avez pas encore créé de cours.</p>
                    @endforelse
                </div>
            </div>
            <div class="space-y-6">
                <div>
                    <h2 class="mb-3 text-lg font-bold">À corriger</h2>
                    <div class="card divide-y divide-slate-100">
                        @forelse($toGrade as $sub)
                            <a href="{{ route('submissions.index', [$sub->assignment->course, $sub->assignment]) }}" class="block p-3 hover:bg-slate-50">
                                <p class="text-sm font-medium">{{ $sub->user->name }}</p>
                                <p class="truncate text-xs text-slate-500">{{ $sub->assignment->title }} · {{ $sub->submitted_at->diffForHumans() }}</p>
                            </a>
                        @empty
                            <p class="p-4 text-sm text-slate-500">Rien à corriger 🎉</p>
                        @endforelse
                    </div>
                </div>
                <div>
                    <h2 class="mb-3 text-lg font-bold">Apprenants à risque</h2>
                    <div class="card divide-y divide-slate-100">
                        @forelse($atRisk as $r)
                            <a href="{{ route('courses.analytics', $r['course']) }}" class="block p-3 hover:bg-slate-50">
                                <div class="flex items-center justify-between"><span class="text-sm font-medium">{{ $r['enrollment']->user->name }}</span><x-risk-badge :level="$r['level']" :score="$r['score']" /></div>
                                <p class="truncate text-xs text-slate-500">{{ $r['course']->title }} — {{ $r['reasons'][0] }}</p>
                            </a>
                        @empty
                            <p class="p-4 text-sm text-slate-500">Aucun apprenant en risque élevé.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endisset

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <h2 class="mb-3 text-lg font-bold">Mes cours</h2>
            @if($enrollments->isEmpty())
                <x-empty title="Vous n'êtes inscrit à aucun cours">
                    <a href="{{ route('catalog') }}" class="link">Parcourir le catalogue</a>
                </x-empty>
            @else
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach($enrollments as $enrollment)
                        @php($course = $enrollment->course)
                        <div class="card overflow-hidden">
                            <div class="h-2 bg-gradient-to-r {{ $course->gradient() }}"></div>
                            <div class="p-5">
                                <a href="{{ route('courses.show', $course) }}" class="font-bold hover:text-brand-600">{{ $course->title }}</a>
                                <p class="text-xs text-slate-500">{{ $course->teacher->name }}</p>
                                <div class="mt-4 flex items-center gap-3"><x-progress :value="$enrollment->progress" /><span class="text-sm font-semibold">{{ $enrollment->progress }}%</span></div>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-xs text-slate-400">{{ $enrollment->last_activity_at ? 'Vu '.$enrollment->last_activity_at->diffForHumans() : 'Pas encore commencé' }}</span>
                                    <a href="{{ route('courses.learn', $course) }}" class="btn-primary btn-sm">{{ $enrollment->completed_at ? 'Revoir' : ($enrollment->progress ? 'Continuer' : 'Commencer') }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div>
                <h2 class="mb-3 text-lg font-bold">Échéances à venir</h2>
                <div class="card divide-y divide-slate-100">
                    @forelse($upcoming as $d)
                        <a href="{{ $d['url'] }}" class="flex items-center gap-3 p-3 hover:bg-slate-50">
                            <div class="w-12 shrink-0 rounded-lg bg-slate-100 py-1 text-center">
                                <p class="text-[10px] font-semibold uppercase text-slate-500">{{ $d['due']->translatedFormat('M') }}</p>
                                <p class="text-lg font-extrabold leading-5">{{ $d['due']->format('d') }}</p>
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium">{{ $d['item']->title }}</p>
                                <p class="text-xs text-slate-500">{{ $d['type'] }} · {{ $d['due']->diffForHumans() }}</p>
                            </div>
                        </a>
                    @empty
                        <p class="p-4 text-sm text-slate-500">Aucune échéance dans les 3 prochaines semaines.</p>
                    @endforelse
                </div>
            </div>
            <div>
                <h2 class="mb-3 text-lg font-bold">Dernières notes</h2>
                <div class="card divide-y divide-slate-100">
                    @forelse($recentGrades as $sub)
                        <a href="{{ route('assignments.show', [$sub->assignment->course, $sub->assignment]) }}" class="flex items-center justify-between p-3 hover:bg-slate-50">
                            <span class="truncate text-sm">{{ $sub->assignment->title }}</span>
                            <span class="badge bg-brand-50 text-brand-700">{{ rtrim(rtrim(number_format($sub->grade, 2, ',', ''), '0'), ',') }}/{{ $sub->assignment->max_points }}</span>
                        </a>
                    @empty
                        <p class="p-4 text-sm text-slate-500">Pas encore de note.</p>
                    @endforelse
                </div>
            </div>
            @if($certificates->isNotEmpty())
                <div>
                    <h2 class="mb-3 text-lg font-bold">Mes certificats</h2>
                    <div class="card divide-y divide-slate-100">
                        @foreach($certificates as $cert)
                            <a href="{{ route('certificates.show', $cert) }}" class="flex items-center gap-3 p-3 hover:bg-slate-50">
                                <span class="text-2xl">🎓</span>
                                <span class="min-w-0"><span class="block truncate text-sm font-medium">{{ $cert->course->title }}</span><span class="text-xs text-slate-500">{{ $cert->issued_at->translatedFormat('j F Y') }}</span></span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
