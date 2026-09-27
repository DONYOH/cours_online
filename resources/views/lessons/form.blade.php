<x-layouts.app :title="$lesson->exists ? 'Modifier la leçon' : 'Nouvelle leçon'">
    <x-page-header :title="$lesson->exists ? 'Modifier la leçon' : 'Nouvelle leçon'" :subtitle="'Section : '.$section->title">
        <x-slot:breadcrumb><a class="link" href="{{ route('courses.builder', $course) }}">← {{ $course->title }}</a></x-slot:breadcrumb>
    </x-page-header>

    <form method="POST" enctype="multipart/form-data"
          action="{{ $lesson->exists ? route('lessons.update', [$course, $lesson]) : route('lessons.store', [$course, $section]) }}"
          class="grid gap-6 lg:grid-cols-3" x-data="{ type: @js(old('type', $lesson->type->value)) }">
        @csrf
        @if($lesson->exists) @method('PUT') @endif
        <div class="card space-y-5 p-6 lg:col-span-2">
            <div>
                <label class="label" for="title">Titre</label>
                <input class="input" id="title" name="title" value="{{ old('title', $lesson->title) }}" required>
            </div>
            <div x-show="type === 'video'" x-cloak>
                <label class="label" for="video_url">URL de la vidéo (YouTube, Vimeo ou fichier MP4)</label>
                <input class="input" id="video_url" name="video_url" type="url" value="{{ old('video_url', $lesson->video_url) }}" placeholder="https://www.youtube.com/watch?v=…">
            </div>
            <div x-show="type === 'file'" x-cloak>
                <label class="label" for="attachment">Document (PDF, Office, ZIP… 50 Mo max)</label>
                <input class="input" id="attachment" name="attachment" type="file">
                @if($lesson->attachment_name)<p class="mt-1 text-xs text-slate-500">Fichier actuel : {{ $lesson->attachment_name }}</p>@endif
            </div>
            <div>
                <label class="label" for="content">Contenu <span class="font-normal text-slate-400">(Markdown : titres, listes, code, liens… — sert aussi de source pour générer les quiz)</span></label>
                <textarea class="input font-mono" id="content" name="content" rows="18">{{ old('content', $lesson->content) }}</textarea>
            </div>
        </div>
        <div class="space-y-6">
            <div class="card space-y-4 p-6">
                <div>
                    <label class="label">Type</label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach(\App\Enums\LessonType::cases() as $t)
                            <label class="cursor-pointer rounded-lg border px-2 py-2 text-center text-sm" :class="type === '{{ $t->value }}' ? 'border-brand-500 bg-brand-50 text-brand-700' : 'border-slate-200'">
                                <input type="radio" name="type" value="{{ $t->value }}" x-model="type" class="sr-only"> {{ $t->label() }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="label" for="duration_minutes">Durée estimée (min)</label>
                    <input class="input" type="number" min="1" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $lesson->duration_minutes) }}" required>
                </div>
                <label class="flex items-center gap-2 text-sm font-medium">
                    <input type="checkbox" name="is_published" value="1" class="rounded border-slate-300 text-brand-600" @checked(old('is_published', $lesson->is_published))> Visible par les apprenants
                </label>
            </div>
            <button class="btn-primary w-full">Enregistrer</button>
            @if($lesson->exists)<a href="{{ route('lessons.show', [$course, $lesson]) }}" class="btn-secondary w-full">Voir la leçon</a>@endif
        </div>
    </form>
</x-layouts.app>
