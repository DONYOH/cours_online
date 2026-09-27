<x-layouts.app :title="'Notes · '.$course->title">
    <x-page-header :title="$course->title" subtitle="Carnet de notes — toutes les notes sont ramenées sur 20 (quiz : meilleure tentative).">
        <x-slot:actions><a href="{{ route('gradebook.export', $course) }}" class="btn-secondary">⬇ Export CSV (Excel)</a></x-slot:actions>
    </x-page-header>
    @include('courses._tabs')

    <div class="card overflow-x-auto">
        <table class="table-base">
            <thead>
                <tr>
                    <th class="sticky left-0 z-10">Apprenant</th>
                    <th>Progression</th>
                    @foreach($columns as $col)
                        <th class="whitespace-nowrap" title="{{ $col['title'] }}">{{ $col['type'] === 'quiz' ? '❓' : '✎' }} {{ \Illuminate\Support\Str::limit($col['title'], 18) }}</th>
                    @endforeach
                    <th>Moyenne</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($rows as $row)
                    <tr>
                        <td class="sticky left-0 whitespace-nowrap bg-white font-medium">{{ $row['student']->name }}</td>
                        <td class="text-xs">{{ $row['progress'] }} %</td>
                        @foreach($columns as $col)
                            @php($g = $row['grades'][$col['key']])
                            <td class="{{ $g === null ? 'text-slate-300' : ($g < 10 ? 'text-rose-600' : 'text-slate-800') }}">{{ $g === null ? '—' : str_replace('.', ',', $g) }}</td>
                        @endforeach
                        <td class="font-bold {{ $row['average'] !== null && $row['average'] < 10 ? 'text-rose-600' : '' }}">{{ $row['average'] === null ? '—' : str_replace('.', ',', $row['average']) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="{{ $columns->count() + 3 }}" class="py-8 text-center text-slate-500">Aucun apprenant inscrit.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
