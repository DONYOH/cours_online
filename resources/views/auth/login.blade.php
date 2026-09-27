<x-layouts.app title="Connexion">
    <div class="mx-auto max-w-md">
        <div class="card p-8">
            <h1 class="text-2xl font-extrabold">Bon retour 👋</h1>
            <p class="mt-1 text-sm text-slate-500">Connectez-vous pour reprendre votre apprentissage.</p>
            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label class="label" for="email">Adresse e-mail</label>
                    <input class="input" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                </div>
                <div>
                    <label class="label" for="password">Mot de passe</label>
                    <input class="input" id="password" type="password" name="password" required autocomplete="current-password">
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600"> Se souvenir de moi
                </label>
                <button class="btn-primary w-full">Se connecter</button>
            </form>
            <p class="mt-6 text-center text-sm text-slate-500">Pas encore de compte ? <a class="link" href="{{ route('register') }}">Inscrivez-vous</a></p>
        </div>
        @if(app()->environment('local'))
            <div class="mt-4 rounded-xl bg-amber-50 p-4 text-xs text-amber-800 ring-1 ring-amber-200">
                <p class="font-semibold">Comptes de démonstration (mot de passe : <code>password</code>)</p>
                <p class="mt-1">admin@edusphere.test · prof@edusphere.test · etudiant@edusphere.test</p>
            </div>
        @endif
    </div>
</x-layouts.app>
