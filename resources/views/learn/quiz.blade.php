<x-layouts.learn :course="$course" :outline="$outline" :done="$done" :current="$quiz" :title="$quiz->title" :enrollment="$enrollment">
    <div class="card p-6 sm:p-10">
        <p class="text-sm font-medium text-violet-600">Quiz</p>
        <h1 class="mt-1 text-3xl font-extrabold tracking-tight">{{ $quiz->title }}</h1>
        @if($quiz->description)<x-markdown :text="$quiz->description" class="mt-4" />@endif

        <dl class="mt-6 grid gap-4 sm:grid-cols-4">
            <div class="rounded-xl bg-slate-50 p-4"><dt class="text-xs text-slate-500">Questions</dt><dd class="text-xl font-bold">{{ $quiz->questions_count }}</dd></div>
            <div class="rounded-xl bg-slate-50 p-4"><dt class="text-xs text-slate-500">Réussite</dt><dd class="text-xl font-bold">≥ {{ $quiz->pass_score }} %</dd></div>
            <div class="rounded-xl bg-slate-50 p-4"><dt class="text-xs text-slate-500">Durée</dt><dd class="text-xl font-bold">{{ $quiz->time_limit_minutes ? $quiz->time_limit_minutes.' min' : 'Libre' }}</dd></div>
            <div class="rounded-xl bg-slate-50 p-4"><dt class="text-xs text-slate-500">Tentatives restantes</dt><dd class="text-xl font-bold">{{ $attemptsLeft ?? '∞' }}</dd></div>
        </dl>
        @if($quiz->due_at)<p class="mt-3 text-sm text-slate-500">📅 À faire avant le {{ $quiz->due_at->translatedFormat('l j F à H:i') }}</p>@endif

        @if($best)
            <div class="mt-6 flex items-center gap-4 rounded-xl p-4 {{ $best->passed ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800' }}">
                <span class="text-3xl">{{ $best->passed ? '🏅' : '💪' }}</span>
                <p>Meilleur score : <strong>{{ rtrim(rtrim(number_format($best->percent, 1, ',', ''), '0'), ',') }} %</strong> — {{ $best->passed ? 'quiz réussi !' : 'encore un effort.' }}</p>
            </div>
        @endif

        <div class="mt-8 flex flex-wrap gap-3">
            @if($attemptsLeft !== 0 && $quiz->questions_count > 0)
                <form method="POST" action="{{ route('attempts.store', [$course, $quiz]) }}">
                    @csrf
                    <button class="btn-primary px-6 py-3">{{ $attempts->whereNull('submitted_at')->isNotEmpty() ? 'Reprendre la tentative' : ($attempts->isEmpty() ? 'Commencer le quiz' : 'Nouvelle tentative') }}</button>
                </form>
            @elseif($quiz->questions_count === 0)
                <p class="text-sm text-slate-500">Ce quiz ne contient pas encore de question.</p>
            @endif
            @if($canManage)
                <a href="{{ route('quizzes.edit', [$course, $quiz]) }}" class="btn-secondary">Éditer les questions</a>
                <a href="{{ route('quizzes.results', [$course, $quiz]) }}" class="btn-secondary">Résultats</a>
            @endif
        </div>

        @if($attempts->whereNotNull('submitted_at')->isNotEmpty())
            <h2 class="mt-10 font-bold">Historique</h2>
            <ul class="mt-3 divide-y divide-slate-100 rounded-xl ring-1 ring-slate-200">
                @foreach($attempts->whereNotNull('submitted_at') as $a)
                    <li class="flex items-center justify-between px-4 py-3 text-sm">
                        <span>{{ $a->submitted_at->translatedFormat('j M Y à H:i') }}</span>
                        <span class="flex items-center gap-3">
                            <strong>{{ rtrim(rtrim(number_format($a->percent, 1, ',', ''), '0'), ',') }} %</strong>
                            <a class="link" href="{{ route('attempts.show', [$course, $quiz, $a]) }}">Détail</a>
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif

        @include('learn._nav')
    </div>
</x-layouts.learn>
