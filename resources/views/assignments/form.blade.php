<x-layouts.app :title="$assignment->exists ? 'Modifier le devoir' : 'Nouveau devoir'">
    <x-page-header :title="$assignment->exists ? 'Modifier le devoir' : 'Nouveau devoir'" :subtitle="'Section : '.($section?->title ?? '—')">
        <x-slot:breadcrumb><a class="link" href="{{ route('courses.builder', $course) }}">← {{ $course->title }}</a></x-slot:breadcrumb>
    </x-page-header>

    <form method="POST" action="{{ $assignment->exists ? route('assignments.update', [$course, $assignment]) : route('assignments.store', [$course, $section]) }}" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if($assignment->exists) @method('PUT') @endif
        <div class="card space-y-5 p-6 lg:col-span-2">
            <div>
                <label class="label" for="title">Titre</label>
                <input class="input" id="title" name="title" value="{{ old('title', $assignment->title) }}" required>
            </div>
            <div>
                <label class="label" for="instructions">Consignes <span class="font-normal text-slate-400">(Markdown)</span></label>
                <textarea class="input font-mono" id="instructions" name="instructions" rows="14">{{ old('instructions', $assignment->instructions) }}</textarea>
            </div>
        </div>
        <div class="space-y-6">
            <div class="card space-y-4 p-6">
                <div>
                    <label class="label" for="due_at">Date limite</label>
                    <input class="input" type="datetime-local" id="due_at" name="due_at" value="{{ old('due_at', $assignment->due_at?->format('Y-m-d\TH:i')) }}">
                </div>
                <div>
                    <label class="label" for="max_points">Barème (points)</label>
                    <input class="input" type="number" min="1" id="max_points" name="max_points" value="{{ old('max_points', $assignment->max_points) }}" required>
                </div>
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="allow_late" value="1" class="rounded border-slate-300 text-brand-600" @checked(old('allow_late', $assignment->allow_late))> Accepter les rendus en retard (signalés)</label>
                <label class="flex items-center gap-2 text-sm font-medium"><input type="checkbox" name="is_published" value="1" class="rounded border-slate-300 text-brand-600" @checked(old('is_published', $assignment->is_published))> Publier le devoir</label>
            </div>
            <button class="btn-primary w-full">Enregistrer</button>
        </div>
    </form>

    @if($assignment->exists)
        <form method="POST" action="{{ route('assignments.destroy', [$course, $assignment]) }}" class="mt-8" onsubmit="return confirm('Supprimer ce devoir et tous les rendus ?')">
            @csrf @method('DELETE')
            <button class="text-sm text-rose-600 hover:underline">Supprimer le devoir</button>
        </form>
    @endif
</x-layouts.app>
