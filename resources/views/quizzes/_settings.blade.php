<div class="space-y-4">
    <div>
        <label class="label" for="title">Titre</label>
        <input class="input" id="title" name="title" value="{{ old('title', $quiz->title) }}" required>
    </div>
    <div>
        <label class="label" for="description">Consignes</label>
        <textarea class="input" id="description" name="description" rows="3">{{ old('description', $quiz->description) }}</textarea>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div><label class="label" for="pass_score">Seuil de réussite (%)</label><input class="input" type="number" min="0" max="100" id="pass_score" name="pass_score" value="{{ old('pass_score', $quiz->pass_score) }}" required></div>
        <div><label class="label" for="max_attempts">Tentatives max.</label><input class="input" type="number" min="1" id="max_attempts" name="max_attempts" value="{{ old('max_attempts', $quiz->max_attempts) }}" placeholder="Illimité"></div>
        <div><label class="label" for="time_limit_minutes">Durée limite (min)</label><input class="input" type="number" min="1" id="time_limit_minutes" name="time_limit_minutes" value="{{ old('time_limit_minutes', $quiz->time_limit_minutes) }}" placeholder="Aucune"></div>
        <div><label class="label" for="due_at">Échéance</label><input class="input" type="datetime-local" id="due_at" name="due_at" value="{{ old('due_at', $quiz->due_at?->format('Y-m-d\TH:i')) }}"></div>
    </div>
    <div class="space-y-2 text-sm">
        <label class="flex items-center gap-2"><input type="checkbox" name="shuffle_questions" value="1" class="rounded border-slate-300 text-brand-600" @checked(old('shuffle_questions', $quiz->shuffle_questions))> Mélanger l'ordre des questions</label>
        <label class="flex items-center gap-2"><input type="checkbox" name="show_answers" value="1" class="rounded border-slate-300 text-brand-600" @checked(old('show_answers', $quiz->show_answers))> Montrer la correction détaillée après soumission</label>
        <label class="flex items-center gap-2 font-medium"><input type="checkbox" name="is_published" value="1" class="rounded border-slate-300 text-brand-600" @checked(old('is_published', $quiz->is_published))> Publier le quiz</label>
    </div>
</div>
