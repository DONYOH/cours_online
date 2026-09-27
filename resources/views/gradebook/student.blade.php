<x-layouts.app :title="'Mes notes · '.$course->title">
    <x-page-header title="Mes notes" :subtitle="$course->title">
        <x-slot:breadcrumb><a class="link" href="{{ route('courses.show', $course) }}">← Retour au cours</a></x-slot:breadcrumb>
    </x-page-header>
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="card divide-y divide-slate-100 lg:col-span-2">
            @forelse($columns as $col)
                @php($g = $grades[$col['key']])
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-3">
                        <x-item-icon :kind="$col['type']" />
                        <a class="font-medium hover:text-brand-600" href="{{ $col['type'] === 'quiz' ? route('quizzes.show', [$course, $col['id']]) : route('assignments.show', [$course, $col['id']]) }}">{{ $col['title'] }}</a>
                    </div>
                    <span class="text-lg font-bold {{ $g === null ? 'text-slate-300' : ($g < 10 ? 'text-rose-600' : 'text-emerald-600') }}">{{ $g === null ? '—' : str_replace('.', ',', $g).' /20' }}</span>
                </div>
            @empty
                <p class="p-8 text-center text-slate-500">Aucune évaluation dans ce cours.</p>
            @endforelse
        </div>
        <div class="card h-fit p-6 text-center">
            <p class="text-sm text-slate-500">Moyenne actuelle</p>
            <p class="mt-2 text-5xl font-extrabold {{ $final !== null && $final < 10 ? 'text-rose-600' : 'text-brand-700' }}">{{ $final === null ? '—' : str_replace('.', ',', $final) }}</p>
            <p class="mt-1 text-sm text-slate-400">sur 20</p>
        </div>
    </div>
</x-layouts.app>
