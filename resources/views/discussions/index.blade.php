<x-layouts.app :title="'Forum · '.$course->title">
    <x-page-header title="Forum" :subtitle="$course->title">
        <x-slot:breadcrumb><a class="link" href="{{ route('courses.show', $course) }}">← Retour au cours</a></x-slot:breadcrumb>
    </x-page-header>
    @can('manage', $course) @include('courses._tabs') @endcan

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <form class="mb-4 flex gap-2"><input class="input" type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher une discussion…"><button class="btn-secondary">Rechercher</button></form>
            <div class="card divide-y divide-slate-100">
                @forelse($discussions as $d)
                    <a href="{{ route('discussions.show', [$course, $d]) }}" class="flex items-start gap-4 p-4 hover:bg-slate-50">
                        <x-avatar :user="$d->author" />
                        <div class="min-w-0 flex-1">
                            <p class="flex flex-wrap items-center gap-2 font-semibold">
                                @if($d->is_pinned)<span title="Épinglé">📌</span>@endif
                                @if($d->is_locked)<span title="Verrouillé">🔒</span>@endif
                                {{ $d->title }}
                                @if($d->resolved)<span class="badge bg-emerald-50 text-emerald-700">Résolu</span>@endif
                            </p>
                            <p class="text-xs text-slate-500">{{ $d->author->name }} · {{ $d->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="shrink-0 text-sm text-slate-500">💬 {{ $d->replies_count }}</span>
                    </a>
                @empty
                    <p class="p-8 text-center text-slate-500">Aucune discussion. Lancez la première !</p>
                @endforelse
            </div>
            <div class="mt-4">{{ $discussions->links() }}</div>
        </div>
        <form method="POST" action="{{ route('discussions.store', $course) }}" class="card h-fit space-y-3 p-5">
            @csrf
            <h2 class="font-bold">Nouvelle discussion</h2>
            <input class="input" name="title" placeholder="Sujet" required value="{{ old('title') }}">
            <textarea class="input" name="body" rows="6" placeholder="Décrivez votre question (Markdown accepté)" required>{{ old('body') }}</textarea>
            <button class="btn-primary w-full">Publier</button>
        </form>
    </div>
</x-layouts.app>
