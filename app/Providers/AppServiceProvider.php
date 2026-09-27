<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale(config('app.locale'));
        Paginator::useTailwind();

        // L'administrateur a tous les droits.
        Gate::before(fn (User $user) => $user->isAdmin() ? true : null);

        Gate::define('teach', fn (User $user) => $user->canTeach());
        Gate::define('administrate', fn (User $user) => $user->isAdmin());
    }
}
