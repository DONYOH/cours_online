<x-layouts.app title="Classement">
    <x-page-header title="Classement" subtitle="Gagnez de l'XP en terminant des leçons, en réussissant des quiz, en rendant vos devoirs et en aidant sur le forum." />
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="card divide-y divide-slate-100 lg:col-span-2">
            @foreach($leaders as $i => $u)
                <div @class(['flex items-center gap-4 px-5 py-3', 'bg-brand-50' => $u->id === $me->id])>
                    <span class="w-8 text-center text-lg font-extrabold {{ $i < 3 ? '' : 'text-slate-400' }}">{{ ['🥇', '🥈', '🥉'][$i] ?? $i + 1 }}</span>
                    <x-avatar :user="$u" />
                    <span class="flex-1 font-medium">{{ $u->name }}</span>
                    <span class="badge bg-amber-50 text-amber-700">Niv. {{ $u->level() }}</span>
                    <span class="w-20 text-right font-bold">{{ $u->xp }} XP</span>
                </div>
            @endforeach
        </div>
        <div class="space-y-4">
            <div class="card p-6 text-center">
                <p class="text-sm text-slate-500">Votre rang</p>
                <p class="text-5xl font-extrabold text-brand-700">#{{ $myRank }}</p>
                <p class="mt-2 text-sm">{{ $me->xp }} XP · niveau {{ $me->level() }}</p>
                <x-progress :value="$me->levelProgress()" class="mt-3" />
            </div>
            <div class="card p-6 text-sm">
                <p class="mb-3 font-bold">Barème d'XP</p>
                @foreach(['lesson_completed' => 'Leçon terminée', 'quiz_passed' => 'Quiz réussi', 'assignment_submitted' => 'Devoir rendu', 'discussion_created' => 'Discussion lancée', 'reply_posted' => 'Réponse au forum', 'solution_accepted' => 'Réponse acceptée comme solution', 'course_completed' => 'Cours terminé'] as $key => $label)
                    <div class="flex justify-between py-1"><span class="text-slate-600">{{ $label }}</span><strong>+{{ \App\Services\GamificationService::XP[$key] }}</strong></div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>
