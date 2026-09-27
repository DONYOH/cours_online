<x-layouts.app :title="'Quiz · '.$quiz->title">
    <x-page-header :title="$quiz->title" :subtitle="$quiz->questions->count().' question(s) · '.$quiz->totalPoints().' point(s)'">
        <x-slot:breadcrumb><a class="link" href="{{ route('courses.builder', $course) }}">← {{ $course->title }}</a></x-slot:breadcrumb>
        <x-slot:actions>
            <a href="{{ route('quizzes.show', [$course, $quiz]) }}" class="btn-secondary">Prévisualiser</a>
            <a href="{{ route('quizzes.results', [$course, $quiz]) }}" class="btn-secondary">Résultats</a>
        </x-slot:actions>
    </x-page-header>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-4 lg:col-span-2">
            {{-- Génération automatique --}}
            <div class="card overflow-hidden" x-data="{ open: false }">
                <button @click="open = !open" class="flex w-full items-center justify-between bg-gradient-to-r from-violet-50 to-indigo-50 px-5 py-4 text-left">
                    <span>
                        <span class="font-bold">✨ Générer des questions automatiquement</span>
                        <span class="block text-xs text-slate-500">{{ $aiEnabled ? 'Propulsé par Claude (IA) à partir de vos leçons ou d\'un texte.' : 'Mode local (questions à trous). Ajoutez ANTHROPIC_API_KEY dans .env pour activer l\'IA.' }}</span>
                    </span>
                    <span :class="open && 'rotate-180'" class="transition">▾</span>
                </button>
                <form x-show="open" x-cloak method="POST" action="{{ route('questions.generate', [$course, $quiz]) }}" class="space-y-3 p-5">
                    @csrf
                    <label class="label">Support source <span class="font-normal text-slate-400">(laisser vide pour utiliser les {{ $quiz->section?->lessons->count() ?? 0 }} leçon(s) de la section)</span></label>
                    <textarea class="input" name="source" rows="5" placeholder="Collez ici un extrait de cours…"></textarea>
                    <div class="flex items-center gap-3">
                        <label class="text-sm">Nombre :</label>
                        <input class="input w-24" type="number" name="count" min="1" max="20" value="5">
                        <button class="btn-primary">Générer</button>
                    </div>
                </form>
            </div>

            @foreach($quiz->questions as $question)
                <div class="card p-5" x-data="{ edit: false }">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold uppercase text-slate-400">Q{{ $loop->iteration }} · {{ $question->type->label() }} · {{ $question->points }} pt</p>
                            <p class="mt-1 font-medium">{{ $question->prompt }}</p>
                            <ul x-show="!edit" class="mt-2 space-y-1 text-sm">
                                @if($question->type === \App\Enums\QuestionType::Short)
                                    <li class="text-emerald-700">✓ {{ implode(' · ', $question->correct) }}</li>
                                @else
                                    @foreach($question->displayOptions() as $i => $opt)
                                        <li class="{{ in_array($i, $question->correct) ? 'font-medium text-emerald-700' : 'text-slate-500' }}">{{ in_array($i, $question->correct) ? '✓' : '○' }} {{ $opt }}</li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        <div class="flex shrink-0 gap-1">
                            <button @click="edit = !edit" class="btn-ghost btn-sm" x-text="edit ? 'Fermer' : 'Modifier'"></button>
                            <form method="POST" action="{{ route('questions.destroy', [$course, $quiz, $question]) }}" onsubmit="return confirm('Supprimer cette question ?')">@csrf @method('DELETE')<button class="btn-ghost btn-sm text-rose-600">✕</button></form>
                        </div>
                    </div>
                    <div x-show="edit" x-cloak class="mt-4 border-t border-slate-100 pt-4">
                        @include('quizzes._question-form', ['question' => $question, 'action' => route('questions.update', [$course, $quiz, $question])])
                    </div>
                </div>
            @endforeach

            <div class="card p-5">
                <h2 class="mb-4 font-bold">Nouvelle question</h2>
                @include('quizzes._question-form', ['question' => null, 'action' => route('questions.store', [$course, $quiz])])
            </div>
        </div>

        <div>
            <form method="POST" action="{{ route('quizzes.update', [$course, $quiz]) }}" class="card p-5 lg:sticky lg:top-24">
                @csrf @method('PUT')
                <h2 class="mb-4 font-bold">Paramètres</h2>
                @include('quizzes._settings')
                <button class="btn-primary mt-5 w-full">Enregistrer</button>
            </form>
            <form method="POST" action="{{ route('quizzes.destroy', [$course, $quiz]) }}" class="mt-4 text-center" onsubmit="return confirm('Supprimer ce quiz et toutes les tentatives ?')">
                @csrf @method('DELETE')
                <button class="text-sm text-rose-600 hover:underline">Supprimer le quiz</button>
            </form>
        </div>
    </div>
</x-layouts.app>
