<x-layouts.app :title="$course->exists ? 'Paramètres du cours' : 'Nouveau cours'">
    <x-page-header :title="$course->exists ? 'Paramètres du cours' : 'Nouveau cours'">
        @if($course->exists)
            <x-slot:breadcrumb><a class="link" href="{{ route('courses.builder', $course) }}">← {{ $course->title }}</a></x-slot:breadcrumb>
        @endif
    </x-page-header>

    <form method="POST" action="{{ $course->exists ? route('courses.update', $course) : route('courses.store') }}" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if($course->exists) @method('PUT') @endif
        <div class="card space-y-5 p-6 lg:col-span-2">
            <div>
                <label class="label" for="title">Titre</label>
                <input class="input" id="title" name="title" value="{{ old('title', $course->title) }}" required maxlength="160" placeholder="Ex. : Introduction à Python pour la data">
            </div>
            <div>
                <label class="label" for="summary">Résumé (affiché dans le catalogue)</label>
                <input class="input" id="summary" name="summary" value="{{ old('summary', $course->summary) }}" maxlength="300">
            </div>
            <div>
                <label class="label" for="description">Description détaillée <span class="font-normal text-slate-400">(Markdown)</span></label>
                <textarea class="input font-mono" id="description" name="description" rows="12" placeholder="## Objectifs&#10;- …">{{ old('description', $course->description) }}</textarea>
            </div>
        </div>
        <div class="space-y-6">
            <div class="card space-y-4 p-6">
                <div>
                    <label class="label" for="category_id">Catégorie</label>
                    <select class="input" id="category_id" name="category_id">
                        <option value="">—</option>
                        @foreach($categories as $cat)<option value="{{ $cat->id }}" @selected(old('category_id', $course->category_id) == $cat->id)>{{ $cat->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="level">Niveau</label>
                    <select class="input" id="level" name="level">
                        @foreach(\App\Enums\CourseLevel::cases() as $level)<option value="{{ $level->value }}" @selected(old('level', $course->level?->value) === $level->value)>{{ $level->label() }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="language">Langue</label>
                    <select class="input" id="language" name="language">
                        @foreach(['fr' => 'Français', 'en' => 'Anglais', 'es' => 'Espagnol', 'de' => 'Allemand'] as $code => $name)<option value="{{ $code }}" @selected(old('language', $course->language) === $code)>{{ $name }}</option>@endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="label" for="starts_at">Début</label><input class="input" type="date" id="starts_at" name="starts_at" value="{{ old('starts_at', $course->starts_at?->format('Y-m-d')) }}"></div>
                    <div><label class="label" for="ends_at">Fin</label><input class="input" type="date" id="ends_at" name="ends_at" value="{{ old('ends_at', $course->ends_at?->format('Y-m-d')) }}"></div>
                </div>
                <p class="text-xs text-slate-400">Les dates servent à calculer la progression attendue (détection du décrochage).</p>
                <div>
                    <label class="label" for="enrollment_key">Clé d'inscription <span class="font-normal text-slate-400">(optionnelle)</span></label>
                    <input class="input" id="enrollment_key" name="enrollment_key" value="{{ old('enrollment_key', $course->enrollment_key) }}" placeholder="Laisser vide = inscription libre">
                </div>
                <label class="flex items-center gap-2 text-sm font-medium">
                    <input type="checkbox" name="is_published" value="1" class="rounded border-slate-300 text-brand-600" @checked(old('is_published', $course->is_published))> Publier dans le catalogue
                </label>
            </div>
            <button class="btn-primary w-full">{{ $course->exists ? 'Enregistrer' : 'Créer le cours' }}</button>
        </div>
    </form>

    @if($course->exists)
        <form method="POST" action="{{ route('courses.destroy', $course) }}" class="mt-10" onsubmit="return confirm('Archiver ce cours ? Il ne sera plus accessible.')">
            @csrf @method('DELETE')
            <button class="btn-ghost text-rose-600">Archiver ce cours</button>
        </form>
    @endif
</x-layouts.app>
