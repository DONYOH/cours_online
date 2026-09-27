{{-- Formulaire de question réutilisé pour la création et l'édition --}}
@php
    $q = $question ?? null;
    $type = $q?->type->value ?? 'single';
    $opts = $q?->options ?: ['', '', '', ''];
    while (count($opts) < 4) { $opts[] = ''; }
    $correct = $q?->correct ?? [];
@endphp
<form method="POST" action="{{ $action }}" class="space-y-4"
      x-data="{ type: @js($type), options: @js(array_values($opts)) }">
    @csrf
    @if($q) @method('PUT') @endif
    <div class="grid gap-3 sm:grid-cols-4">
        <div class="sm:col-span-3">
            <label class="label">Type de question</label>
            <select class="input" name="type" x-model="type">
                @foreach(\App\Enums\QuestionType::cases() as $t)<option value="{{ $t->value }}">{{ $t->label() }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="label">Points</label>
            <input class="input" type="number" min="1" name="points" value="{{ $q?->points ?? 1 }}" required>
        </div>
    </div>
    <div>
        <label class="label">Énoncé</label>
        <textarea class="input" name="prompt" rows="2" required>{{ $q?->prompt }}</textarea>
    </div>

    <div x-show="type === 'single' || type === 'multiple'">
        <label class="label">Options — cochez la ou les bonnes réponses</label>
        <div class="space-y-2">
            <template x-for="(opt, i) in options" :key="i">
                <div class="flex items-center gap-2">
                    <template x-if="type === 'single'">
                        <input type="radio" name="correct_single" :value="i" class="text-brand-600" :checked="@js($type === 'single' ? $correct : []).includes(i)">
                    </template>
                    <template x-if="type === 'multiple'">
                        <input type="checkbox" name="correct_multiple[]" :value="i" class="rounded text-brand-600" :checked="@js($type === 'multiple' ? $correct : []).includes(i)">
                    </template>
                    <input class="input" :name="'options[' + i + ']'" x-model="options[i]" :placeholder="'Option ' + (i + 1)">
                </div>
            </template>
        </div>
        <button type="button" class="mt-2 text-xs font-medium text-brand-600" @click="options.push('')">+ Ajouter une option</button>
    </div>

    <div x-show="type === 'true_false'" x-cloak>
        <label class="label">Bonne réponse</label>
        <select class="input" name="correct_tf">
            <option value="0" @selected($type === 'true_false' && ($correct[0] ?? 0) == 0)>Vrai</option>
            <option value="1" @selected($type === 'true_false' && ($correct[0] ?? 0) == 1)>Faux</option>
        </select>
    </div>

    <div x-show="type === 'short'" x-cloak>
        <label class="label">Réponses acceptées <span class="font-normal text-slate-400">(une par ligne — casse, accents et petites fautes tolérés)</span></label>
        <textarea class="input" name="accepted" rows="2">{{ $type === 'short' ? implode("\n", $correct) : '' }}</textarea>
    </div>

    <div>
        <label class="label">Explication (affichée après correction)</label>
        <textarea class="input" name="explanation" rows="2">{{ $q?->explanation }}</textarea>
    </div>
    <button class="btn-primary btn-sm">{{ $q ? 'Enregistrer la question' : 'Ajouter la question' }}</button>
</form>
