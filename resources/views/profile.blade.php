<x-layouts.app title="Mon profil">
    <x-page-header title="Mon profil" />
    <div class="grid max-w-4xl gap-6 lg:grid-cols-2">
        <form method="POST" action="{{ route('profile.update') }}" class="card space-y-4 p-6">
            @csrf @method('PUT')
            <h2 class="font-bold">Informations</h2>
            <div><label class="label">Nom</label><input class="input" name="name" value="{{ old('name', $user->name) }}" required></div>
            <div><label class="label">E-mail</label><input class="input" type="email" name="email" value="{{ old('email', $user->email) }}" required></div>
            <div><label class="label">Bio</label><textarea class="input" name="bio" rows="3">{{ old('bio', $user->bio) }}</textarea></div>
            <button class="btn-primary">Enregistrer</button>
        </form>
        <form method="POST" action="{{ route('profile.password') }}" class="card space-y-4 p-6">
            @csrf @method('PUT')
            <h2 class="font-bold">Mot de passe</h2>
            <div><label class="label">Mot de passe actuel</label><input class="input" type="password" name="current_password" required></div>
            <div><label class="label">Nouveau mot de passe</label><input class="input" type="password" name="password" required></div>
            <div><label class="label">Confirmation</label><input class="input" type="password" name="password_confirmation" required></div>
            <button class="btn-primary">Changer le mot de passe</button>
        </form>
    </div>
</x-layouts.app>
