<x-layouts.app :title="'Résultat · '.$quiz->title">
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('quizzes.show', [$course, $quiz]) }}" class="link text-sm">← Retour au quiz</a>

        <div class="card mt-4 overflow-hidden">
            <div class="p-8 text-center {{ $attempt->passed ? 'bg-gradient-to-br from-emerald-500 to-teal-600' : 'bg-gradient-to-br from-amber-500 to-orange-600' }} text-white">
                <p class="text-5xl">{{ $attempt->passed ? '🎉' : '📚' }}</p>
                <p class="mt-3 text-5xl font-extrabold">{{ rtrim(rtrim(number_format($attempt->percent, 1, ',', ''), '0'), ',') }} %</p>
                <p class="mt-1 text-white/90">{{ rtrim(rtrim(number_format($attempt->score, 2, ',', ''), '0'), ',') }} / {{ rtrim(rtrim(number_format($attempt->max_score, 2, ',', ''), '0'), ',') }} points · seuil {{ $quiz->pass_score }} %</p>
                <p class="mt-3 font-semibold">{{ $attempt->passed ? 'Quiz réussi, bravo !' : 'Pas encore — revoyez les notions ci-dessous et retentez.' }}</p>
                @if($attempt->user_id !== auth()->id())<p class="mt-2 text-sm text-white/80">Tentative de {{ $attempt->user->name }}</p>@endif
            </div>
        </div>

        @if($quiz->show_answers || Gate::allows('manage', $course))
            <div class="mt-6 space-y-4">
                @foreach($questions as $question)
                    @php($detail = $attempt->answers[$question->id] ?? ['given' => null, 'earned' => 0, 'correct' => false])
                    @php($given = collect((array) $detail['given'])->map(fn ($g) => is_numeric($g) ? (int) $g : $g)->all())
                    <div class="card p-6 {{ $detail['correct'] ? 'ring-emerald-200' : 'ring-rose-200' }}">
                        <div class="flex items-start justify-between gap-4">
                            <p class="font-medium">{{ $loop->iteration }}. {{ $question->prompt }}</p>
                            <span class="badge shrink-0 {{ $detail['correct'] ? 'bg-emerald-50 text-emerald-700' : ($detail['earned'] > 0 ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">{{ rtrim(rtrim(number_format($detail['earned'], 2, ',', ''), '0'), ',') ?: '0' }} / {{ $question->points }}</span>
                        </div>
                        <div class="mt-3 space-y-1.5 text-sm">
                            @if($question->type === \App\Enums\QuestionType::Short)
                                <p>Votre réponse : <strong>{{ $detail['given'] ?: '—' }}</strong></p>
                                <p class="text-emerald-700">Réponse attendue : {{ implode(' / ', $question->correct) }}</p>
                            @else
                                @foreach($question->displayOptions() as $i => $opt)
                                    @php($isCorrect = in_array($i, $question->correct))
                                    @php($isGiven = in_array($i, $given, true))
                                    <p @class(['rounded-lg px-3 py-2', 'bg-emerald-50 text-emerald-800 font-medium' => $isCorrect, 'bg-rose-50 text-rose-800 line-through' => $isGiven && ! $isCorrect, 'text-slate-500' => ! $isCorrect && ! $isGiven])>
                                        {{ $isCorrect ? '✓' : ($isGiven ? '✗' : '○') }} {{ $opt }} @if($isGiven)<span class="text-xs">(votre choix)</span>@endif
                                    </p>
                                @endforeach
                            @endif
                        </div>
                        @if($question->explanation)
                            <p class="mt-3 rounded-lg bg-sky-50 px-3 py-2 text-sm text-sky-800">💡 {{ $question->explanation }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="card mt-6 p-6 text-center text-sm text-slate-500">La correction détaillée n'est pas affichée pour ce quiz.</p>
        @endif
    </div>
</x-layouts.app>
