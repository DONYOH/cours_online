<x-layouts.app :title="'Rendus · '.$assignment->title">
    <x-page-header :title="$assignment->title" :subtitle="$submissions->count().' rendu(s) · '.$submissions->whereNull('grade')->count().' à corriger · '.$missing->count().' manquant(s)'">
        <x-slot:breadcrumb><a class="link" href="{{ route('courses.builder', $course) }}">← {{ $course->title }}</a></x-slot:breadcrumb>
    </x-page-header>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-4 lg:col-span-2">
            @forelse($submissions as $sub)
                <div class="card p-5" x-data="{ open: {{ $sub->isGraded() ? 'false' : 'true' }} }">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <x-avatar :user="$sub->user" />
                            <div>
                                <p class="font-semibold">{{ $sub->user->name }}</p>
                                <p class="text-xs text-slate-500">Rendu {{ $sub->submitted_at->translatedFormat('j M Y à H:i') }} @if($sub->is_late)<span class="badge bg-rose-50 text-rose-700">En retard</span>@endif</p>
                            </div>
                        </div>
                        @if($sub->isGraded())
                            <button @click="open = !open" class="badge bg-emerald-50 text-emerald-700">{{ rtrim(rtrim(number_format($sub->grade, 2, ',', ''), '0'), ',') }}/{{ $assignment->max_points }} ✎</button>
                        @else
                            <span class="badge bg-amber-50 text-amber-700">À corriger</span>
                        @endif
                    </div>
                    @if($sub->content)
                        <div class="mt-4 rounded-lg bg-slate-50 p-4"><x-markdown :text="$sub->content" class="prose-sm" /></div>
                    @endif
                    @if($sub->file_path)
                        <a href="{{ route('submissions.download', [$course, $assignment, $sub]) }}" class="mt-3 inline-flex items-center gap-2 text-sm link">📎 {{ $sub->file_name }}</a>
                    @endif
                    <form x-show="open" x-cloak method="POST" action="{{ route('submissions.grade', [$course, $assignment, $sub]) }}" class="mt-4 grid gap-3 border-t border-slate-100 pt-4 sm:grid-cols-4">
                        @csrf @method('PUT')
                        <div>
                            <label class="label">Note /{{ $assignment->max_points }}</label>
                            <input class="input" type="number" step="0.25" min="0" max="{{ $assignment->max_points }}" name="grade" value="{{ $sub->grade }}" required>
                        </div>
                        <div class="sm:col-span-3">
                            <label class="label">Commentaire pour l'apprenant</label>
                            <textarea class="input" name="feedback" rows="2">{{ $sub->feedback }}</textarea>
                        </div>
                        <div class="sm:col-span-4"><button class="btn-primary btn-sm">Enregistrer et notifier</button></div>
                    </form>
                </div>
            @empty
                <x-empty title="Aucun rendu pour le moment" icon="📭" />
            @endforelse
        </div>
        <div class="card h-fit p-5">
            <h2 class="font-bold">Pas encore rendu</h2>
            <ul class="mt-3 space-y-2 text-sm">
                @forelse($missing as $student)
                    <li class="flex items-center gap-2"><x-avatar :user="$student" size="h-6 w-6 text-[10px]" /> {{ $student->name }}</li>
                @empty
                    <li class="text-slate-500">Tout le monde a rendu 🎉</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-layouts.app>
