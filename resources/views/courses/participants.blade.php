<x-layouts.app :title="'Participants · '.$course->title">
    <x-page-header :title="$course->title" :subtitle="$enrollments->total().' participant(s)'" />
    @include('courses._tabs')

    <form class="mb-4 flex max-w-md gap-2"><input class="input" type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher un apprenant"><button class="btn-secondary">Rechercher</button></form>

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead><tr><th>Apprenant</th><th>Progression</th><th>Dernière activité</th><th>Inscrit le</th><th></th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($enrollments as $e)
                    <tr>
                        <td><div class="flex items-center gap-3"><x-avatar :user="$e->user" /><div><p class="font-medium">{{ $e->user->name }}</p><p class="text-xs text-slate-500">{{ $e->user->email }}</p></div></div></td>
                        <td class="w-48"><div class="flex items-center gap-2"><x-progress :value="$e->progress" /><span class="text-xs">{{ $e->progress }}%</span></div></td>
                        <td class="text-xs text-slate-500">{{ $e->last_activity_at?->diffForHumans() ?? '—' }}</td>
                        <td class="text-xs text-slate-500">{{ $e->created_at->translatedFormat('j M Y') }}</td>
                        <td class="text-right">
                            <form method="POST" action="{{ route('enrollments.remove', [$course, $e]) }}" onsubmit="return confirm('Retirer cet apprenant ?')">@csrf @method('DELETE')<button class="btn-ghost btn-sm text-rose-600">Retirer</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-slate-500">Aucun participant.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $enrollments->links() }}</div>
</x-layouts.app>
