<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Création d'un compte administrateur (installation en production sans données de démo)
Artisan::command('edusphere:admin {email} {--name=Administrateur}', function (string $email) {
    $password = $this->secret('Mot de passe (8 caractères min.)');
    if (strlen((string) $password) < 8) {
        $this->error('Mot de passe trop court.');

        return 1;
    }

    $user = \App\Models\User::updateOrCreate(
        ['email' => $email],
        ['name' => $this->option('name'), 'password' => $password, 'role' => 'admin'],
    );

    $this->info("Administrateur prêt : {$user->email}");
})->purpose('Créer ou promouvoir un compte administrateur');
