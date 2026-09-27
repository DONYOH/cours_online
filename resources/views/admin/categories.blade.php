<x-layouts.app title="Catégories">
    <x-page-header title="Catégories de cours">
        <x-slot:breadcrumb><a class="link" href="{{ route('admin.users.index') }}">← Administration</a></x-slot:breadcrumb>
    </x-page-header>
    <div class="grid max-w-4xl gap-6 lg:grid-cols-3">
        <div class="card divide-y divide-slate-100 lg:col-span-2">
            @forelse($categories as $cat)
                <div class="flex items-center justify-between p-4">
                    <span class="font-medium">{{ $cat->name }} <span class="text-xs text-slate-400">· {{ $cat->courses_count }} cours</span></span>
                    <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Supprimer cette catégorie ?')">@csrf @method('DELETE')<button class="btn-ghost btn-sm text-rose-600">Supprimer</button></form>
                </div>
            @empty
                <p class="p-6 text-slate-500">Aucune catégorie.</p>
            @endforelse
        </div>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="card h-fit space-y-3 p-5">
            @csrf
            <input class="input" name="name" placeholder="Nom de la catégorie" required>
            <button class="btn-primary w-full">Ajouter</button>
        </form>
    </div>
</x-layouts.app>
