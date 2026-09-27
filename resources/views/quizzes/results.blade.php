<x-layouts.app :title="'Résultats · '.$quiz->title">
    <x-page-header :title="'Résultats — '.$quiz->title">
        <x-slot:breadcrumb><a class="link" href="{{ route('quizzes.edit', [$course, $quiz]) }}">← Retour au quiz</a></x-slot:breadcrumb>
    </x-page-header>

    <div class="mb-8 grid gap-4 sm:grid-cols-4">
        <x-stat label="Tentatives" :value="$stats['count']" />
        <x-stat label="Apprenants" :value="$stats['learners']" />
        <x-stat label="Score moyen" :value="$stats['avg'] !== null ? $stats['avg'].' %' : '—'" tone="brand" />
        <x-stat label="Taux de réussite" :value="$stats['passRate'] !== null ? $stats['passRate'].' %' : '—'" tone="emerald" />
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="card p-6">
            <h2 class="font-bold">Analyse des questions</h2>
            <p class="mb-4 text-sm text-slate-500">Indice de réussite par question pour repérer les énoncés ambigus ou les notions mal comprises.</p>
            <div class="space-y-4">
                @foreach($itemAnalysis as $row)
                    <div>
                        <div class="flex items-start justify-between gap-4 text-sm">
                            <span class="font-medium">Q{{ $loop->iteration }}. {{ \Illuminate\Support\Str::limit($row['question']->prompt, 80) }}</span>
                            <span class="shrink-0 font-semibold">{{ $row['rate'] !== null ? $row['rate'].' %' : '—' }}</span>
                        </div>
                        <x-progress :value="$row['rate'] ?? 0" class="mt-1" />
                        @if($row['flag'])<p class="mt-1 text-xs text-amber-600">⚠ {{ $row['flag'] }}</p>@endif
                    </div>
                @endforeach
            </div>
        </div>
        <div class="card overflow-x-auto">
            <table class="table-base">
                <thead><tr><th>Apprenant</th><th>Score</th><th>Statut</th><th>Date</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($attempts as $a)
                        <tr>
                            <td><a class="link" href="{{ route('attempts.show', [$course, $quiz, $a]) }}">{{ $a->user->name }}</a></td>
                            <td>{{ rtrim(rtrim(number_format($a->percent, 1, ',', ''), '0'), ',') }} %</td>
                            <td>@if($a->passed)<span class="badge bg-emerald-50 text-emerald-700">Réussi</span>@else<span class="badge bg-rose-50 text-rose-700">Échoué</span>@endif</td>
                            <td class="text-xs text-slate-500">{{ $a->submitted_at->translatedFormat('j M H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-8 text-center text-slate-500">Aucune tentative.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
