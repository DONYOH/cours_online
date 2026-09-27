<x-layouts.app :title="'Analytique · '.$course->title">
    <x-page-header :title="$course->title" subtitle="Learning analytics : engagement, progression et détection du décrochage." />
    @include('courses._tabs')

    <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <x-stat label="Apprenants" :value="$kpis['learners']" />
        <x-stat label="Progression moyenne" :value="$kpis['avgProgress'].' %'" tone="brand" />
        <x-stat label="Taux d'achèvement" :value="$kpis['completion'].' %'" tone="emerald" />
        <x-stat label="Actifs sur 7 jours" :value="$kpis['active7']" />
        <x-stat label="Risque élevé" :value="$kpis['atRisk']" tone="rose" />
    </div>

    <div class="mb-8 grid gap-6 lg:grid-cols-3">
        <div class="card p-6 lg:col-span-2">
            <h2 class="font-bold">Activité sur 30 jours</h2>
            <div class="mt-4 h-64"><canvas id="activityChart"></canvas></div>
        </div>
        <div class="card p-6">
            <h2 class="font-bold">Répartition de la progression</h2>
            <div class="mt-4 h-64"><canvas id="progressChart"></canvas></div>
        </div>
    </div>

    <div class="card overflow-x-auto">
        <div class="flex items-center justify-between p-6 pb-2">
            <div>
                <h2 class="font-bold">Suivi individuel & risque de décrochage</h2>
                <p class="text-sm text-slate-500">Score 0–100 combinant inactivité, retard sur la progression attendue, résultats aux quiz et devoirs non rendus.</p>
            </div>
        </div>
        <table class="table-base">
            <thead><tr><th>Apprenant</th><th>Risque</th><th>Progression</th><th>Attendue</th><th>Signaux</th><th>Dernière activité</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($risks as $r)
                    <tr>
                        <td><div class="flex items-center gap-2"><x-avatar :user="$r['enrollment']->user" /><span class="font-medium">{{ $r['enrollment']->user->name }}</span></div></td>
                        <td><x-risk-badge :level="$r['level']" :score="$r['score']" /></td>
                        <td class="w-40"><div class="flex items-center gap-2"><x-progress :value="$r['enrollment']->progress" /><span class="text-xs">{{ $r['enrollment']->progress }}%</span></div></td>
                        <td class="text-xs text-slate-500">{{ $r['expected'] }} %</td>
                        <td class="text-xs text-slate-600">{{ implode(' · ', $r['reasons']) }}</td>
                        <td class="text-xs text-slate-500">{{ $r['enrollment']->last_activity_at?->diffForHumans() ?? 'jamais' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-8 text-center text-slate-500">Aucun apprenant inscrit.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @push('scripts')
        <script type="module">
            const activity = @js($activity);
            new Chart(document.getElementById('activityChart'), {
                type: 'bar',
                data: {
                    labels: activity.map(d => d.label),
                    datasets: [
                        { type: 'line', label: 'Apprenants actifs', data: activity.map(d => d.learners), borderColor: '#10b981', backgroundColor: '#10b981', tension: .3, yAxisID: 'y1' },
                        { label: 'Actions', data: activity.map(d => d.events), backgroundColor: '#c7d2fe', borderRadius: 4 },
                    ],
                },
                options: { maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true }, y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false } } } },
            });
            const buckets = @js($buckets);
            new Chart(document.getElementById('progressChart'), {
                type: 'doughnut',
                data: { labels: Object.keys(buckets), datasets: [{ data: Object.values(buckets), backgroundColor: ['#fecaca', '#fde68a', '#c7d2fe', '#a5b4fc', '#34d399'] }] },
                options: { maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } },
            });
        </script>
    @endpush
</x-layouts.app>
