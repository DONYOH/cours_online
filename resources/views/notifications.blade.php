<x-layouts.app title="Notifications">
    <x-page-header title="Notifications">
        <x-slot:actions>
            <form method="POST" action="{{ route('notifications.read') }}">@csrf<button class="btn-secondary btn-sm">Tout marquer comme lu</button></form>
        </x-slot:actions>
    </x-page-header>
    <div class="card mx-auto max-w-3xl divide-y divide-slate-100">
        @forelse($notifications as $n)
            <a href="{{ $n->data['url'] ?? '#' }}" @class(['flex gap-4 p-4 hover:bg-slate-50', 'bg-brand-50/50' => ! $n->read_at])>
                <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full {{ $n->read_at ? 'bg-transparent' : 'bg-brand-500' }}"></span>
                <span class="min-w-0 flex-1">
                    <span class="block font-semibold">{{ $n->data['title'] ?? 'Notification' }}</span>
                    <span class="block text-sm text-slate-600">{{ $n->data['message'] ?? '' }}</span>
                </span>
                <span class="shrink-0 text-xs text-slate-400">{{ $n->created_at->diffForHumans() }}</span>
            </a>
        @empty
            <p class="p-8 text-center text-slate-500">Aucune notification.</p>
        @endforelse
    </div>
    <div class="mx-auto mt-4 max-w-3xl">{{ $notifications->links() }}</div>
</x-layouts.app>
