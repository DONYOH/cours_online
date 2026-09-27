<x-layouts.app :title="$quiz->title">
    <div class="mx-auto max-w-3xl">
        <div class="sticky top-16 z-20 -mx-4 mb-6 flex items-center justify-between border-b border-slate-200 bg-slate-50/95 px-4 py-3 backdrop-blur">
            <div>
                <p class="text-xs text-slate-500">{{ $course->title }}</p>
                <p class="font-bold">{{ $quiz->title }}</p>
            </div>
            @if($attempt->deadline())
                <div x-data="quizTimer(@js($attempt->deadline()->toIso8601String()))" class="rounded-lg px-3 py-1.5 font-mono text-lg font-bold" :class="remaining !== null && remaining < 60 ? 'bg-rose-100 text-rose-700' : 'bg-white ring-1 ring-slate-200'">
                    ⏱ <span x-text="display"></span>
                </div>
            @endif
        </div>

        <form id="attempt-form" method="POST" action="{{ route('attempts.submit', [$course, $quiz, $attempt]) }}" class="space-y-6"
              x-data="{ answered: 0 }" @change="answered = new Set([...$el.querySelectorAll('input:checked, input[type=text]')].filter(i => i.type !== 'text' || i.value.trim()).map(i => i.name.replace('[]',''))).size">
            @csrf
            @foreach($questions as $question)
                <fieldset class="card p-6">
                    <legend class="sr-only">Question {{ $loop->iteration }}</legend>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Question {{ $loop->iteration }} / {{ $questions->count() }} · {{ $question->points }} pt{{ $question->points > 1 ? 's' : '' }}</p>
                    <p class="mt-2 text-lg font-medium">{{ $question->prompt }}</p>
                    @if($question->type === \App\Enums\QuestionType::Multiple)<p class="mt-1 text-xs text-slate-500">Plusieurs réponses possibles.</p>@endif

                    <div class="mt-4 space-y-2">
                        @switch($question->type)
                            @case(\App\Enums\QuestionType::Short)
                                <input class="input" type="text" name="answers[{{ $question->id }}]" autocomplete="off" placeholder="Votre réponse">
                                @break
                            @case(\App\Enums\QuestionType::Multiple)
                                @foreach($question->displayOptions() as $i => $opt)
                                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 hover:bg-slate-50 has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50">
                                        <input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $i }}" class="rounded text-brand-600"> <span>{{ $opt }}</span>
                                    </label>
                                @endforeach
                                @break
                            @default
                                @foreach($question->displayOptions() as $i => $opt)
                                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 hover:bg-slate-50 has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $i }}" class="text-brand-600"> <span>{{ $opt }}</span>
                                    </label>
                                @endforeach
                        @endswitch
                    </div>
                </fieldset>
            @endforeach

            <div class="card flex items-center justify-between p-4">
                <span class="text-sm text-slate-500"><span x-text="answered"></span> / {{ $questions->count() }} répondue(s)</span>
                <button class="btn-primary px-6" onclick="return confirm('Soumettre vos réponses ? Vous ne pourrez plus les modifier.')">Soumettre le quiz</button>
            </div>
        </form>
    </div>
</x-layouts.app>
