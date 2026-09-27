@props(['title' => null, 'wide' => false])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title.' · ' : '' }}{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-slate-800 antialiased">
<div class="min-h-full">
    <nav class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur" x-data="{ open: false }">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-8">
                <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="flex items-center gap-2 text-lg font-extrabold tracking-tight text-slate-900">
                    <span class="grid h-8 w-8 place-items-center rounded-lg bg-gradient-to-br from-brand-500 to-violet-600 text-white">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m-6-9.5v5.5c0 1.5 2.7 3 6 3s6-1.5 6-3v-5.5"/></svg>
                    </span>
                    {{ config('app.name') }}
                </a>
                <div class="hidden items-center gap-1 md:flex">
                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Tableau de bord</x-nav-link>
                    @endauth
                    <x-nav-link :href="route('catalog')" :active="request()->routeIs('catalog')">Catalogue</x-nav-link>
                    @auth
                        @can('teach')
                            <x-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.index', 'courses.create')">Enseigner</x-nav-link>
                        @endcan
                        <x-nav-link :href="route('leaderboard')" :active="request()->routeIs('leaderboard')">Classement</x-nav-link>
                        @can('administrate')
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.*')">Administration</x-nav-link>
                        @endcan
                    @endauth
                </div>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    @php($unread = auth()->user()->unreadNotifications()->count())
                    <a href="{{ route('notifications.index') }}" class="relative rounded-full p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700" title="Notifications">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4-5.7V5a2 2 0 10-4 0v.3A6 6 0 006 11v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @if($unread)
                            <span class="absolute -right-0.5 -top-0.5 grid h-4 min-w-4 place-items-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white">{{ $unread }}</span>
                        @endif
                    </a>
                    <div class="relative" x-data="{ menu: false }" @click.outside="menu = false">
                        <button @click="menu = !menu" class="flex items-center gap-2 rounded-full p-1 pr-3 hover:bg-slate-100">
                            <x-avatar :user="auth()->user()" />
                            <span class="hidden text-left sm:block">
                                <span class="block text-sm font-semibold leading-4">{{ auth()->user()->name }}</span>
                                <span class="block text-xs text-slate-500">Niv. {{ auth()->user()->level() }} · {{ auth()->user()->xp }} XP</span>
                            </span>
                        </button>
                        <div x-show="menu" x-cloak x-transition class="absolute right-0 mt-2 w-52 overflow-hidden rounded-xl bg-white py-1 shadow-lg ring-1 ring-slate-200">
                            <p class="px-4 py-2 text-xs text-slate-500">{{ auth()->user()->role->label() }}</p>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-slate-50">Mon profil</a>
                            <form method="POST" action="{{ route('logout') }}">@csrf
                                <button class="block w-full px-4 py-2 text-left text-sm text-rose-600 hover:bg-slate-50">Se déconnecter</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-primary">Créer un compte</a>
                @endauth
                <button class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 md:hidden" @click="open = !open" aria-label="Menu">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
        <div x-show="open" x-cloak class="space-y-1 border-t border-slate-200 px-4 py-3 md:hidden">
            @auth<a class="block rounded-lg px-3 py-2 text-sm hover:bg-slate-100" href="{{ route('dashboard') }}">Tableau de bord</a>@endauth
            <a class="block rounded-lg px-3 py-2 text-sm hover:bg-slate-100" href="{{ route('catalog') }}">Catalogue</a>
            @auth
                @can('teach')<a class="block rounded-lg px-3 py-2 text-sm hover:bg-slate-100" href="{{ route('courses.index') }}">Enseigner</a>@endcan
                <a class="block rounded-lg px-3 py-2 text-sm hover:bg-slate-100" href="{{ route('leaderboard') }}">Classement</a>
                @can('administrate')<a class="block rounded-lg px-3 py-2 text-sm hover:bg-slate-100" href="{{ route('admin.users.index') }}">Administration</a>@endcan
            @endauth
        </div>
    </nav>

    <main class="{{ $wide ? '' : 'mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8' }}">
        <div class="{{ $wide ? 'mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8' : '' }}">
            <x-flash />
        </div>
        {{ $slot }}
    </main>

    <footer class="mt-16 border-t border-slate-200 py-8 text-center text-xs text-slate-500">
        {{ config('app.name') }} · Plateforme d'apprentissage open source · Laravel {{ app()->version() }} · PHP {{ PHP_MAJOR_VERSION }}.{{ PHP_MINOR_VERSION }}
    </footer>
</div>
@stack('scripts')
</body>
</html>
