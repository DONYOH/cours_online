<x-layouts.app title="Créer un compte">
    <div class="mx-auto max-w-md">
        <div class="card p-8">
            <h1 class="text-2xl font-extrabold">Créer votre compte</h1>
            <p class="mt-1 text-sm text-slate-500">Accédez gratuitement au catalogue de cours.</p>
            <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label class="label" for="name">Nom complet</label>
                    <input class="input" id="name" name="name" value="{{ old('name') }}" required autofocus>
                </div>
                <div>
                    <label class="label" for="email">Adresse e-mail</label>
                    <input class="input" id="email" type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div>
                    <label class="label" for="password">Mot de passe</label>
                    <input class="input" id="password" type="password" name="password" required autocomplete="new-password">
                    <p class="mt-1 text-xs text-slate-400">8 caractères minimum, avec lettres et chiffres.</p>
                </div>
                <div>
                    <label class="label" for="password_confirmation">Confirmer le mot de passe</label>
                    <input class="input" id="password_confirmation" type="password" name="password_confirmation" required>
                </div>
                <button class="btn-primary w-full">Créer mon compte</button>
            </form>
            <p class="mt-6 text-center text-sm text-slate-500">Déjà inscrit ? <a class="link" href="{{ route('login') }}">Connexion</a></p>
        </div>
    </div>
</x-layouts.app>
