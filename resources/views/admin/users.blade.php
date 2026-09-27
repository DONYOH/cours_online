<x-layouts.app title="Utilisateurs">
    <x-page-header title="Utilisateurs" :subtitle="$users->total().' compte(s)'">
        <x-slot:actions><a href="{{ route('admin.categories.index') }}" class="btn-secondary">Catégories</a></x-slot:actions>
    </x-page-header>

    <div class="grid gap-6 lg:grid-cols-4">
        <div class="lg:col-span-3">
            <form class="mb-4 flex flex-wrap gap-2">
                <input class="input max-w-xs" type="search" name="q" value="{{ request('q') }}" placeholder="Nom ou e-mail">
                <select class="input w-44" name="role"><option value="">Tous les rôles</option>@foreach($roles as $r)<option value="{{ $r->value }}" @selected(request('role') === $r->value)>{{ $r->label() }}</option>@endforeach</select>
                <button class="btn-secondary">Filtrer</button>
            </form>
            <div class="card overflow-x-auto">
                <table class="table-base">
                    <thead><tr><th>Utilisateur</th><th>Rôle</th><th>Activité</th><th>Dernière visite</th><th></th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $u)
                            <tr>
                                <td><div class="flex items-center gap-3"><x-avatar :user="$u" /><div><p class="font-medium">{{ $u->name }}</p><p class="text-xs text-slate-500">{{ $u->email }}</p></div></div></td>
                                <td>
                                    <form method="POST" action="{{ route('admin.users.update', $u) }}">
                                        @csrf @method('PATCH')
                                        <select class="input py-1 text-xs" name="role" onchange="this.form.submit()">
                                            @foreach($roles as $r)<option value="{{ $r->value }}" @selected($u->role === $r)>{{ $r->label() }}</option>@endforeach
                                        </select>
                                    </form>
                                </td>
                                <td class="text-xs text-slate-500">{{ $u->enrollments_count }} inscription(s)@if($u->taught_courses_count) · {{ $u->taught_courses_count }} cours donné(s)@endif</td>
                                <td class="text-xs text-slate-500">{{ $u->last_seen_at?->diffForHumans() ?? 'jamais' }}</td>
                                <td class="text-right">
                                    @unless($u->is(auth()->user()))
                                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Supprimer définitivement ce compte ?')">@csrf @method('DELETE')<button class="btn-ghost btn-sm text-rose-600">Supprimer</button></form>
                                    @endunless
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $users->links() }}</div>
        </div>
        <form method="POST" action="{{ route('admin.users.store') }}" class="card h-fit space-y-3 p-5">
            @csrf
            <h2 class="font-bold">Créer un compte</h2>
            <input class="input" name="name" placeholder="Nom complet" required>
            <input class="input" type="email" name="email" placeholder="E-mail" required>
            <select class="input" name="role">@foreach($roles as $r)<option value="{{ $r->value }}">{{ $r->label() }}</option>@endforeach</select>
            <button class="btn-primary w-full">Créer</button>
            <p class="text-xs text-slate-400">Un mot de passe provisoire sera affiché une seule fois.</p>
        </form>
    </div>
</x-layouts.app>
