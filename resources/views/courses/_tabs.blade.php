{{-- Onglets de gestion d'un cours (enseignant) --}}
@php($tabs = [
    'courses.show' => 'Aperçu',
    'courses.builder' => 'Contenu',
    'enrollments.index' => 'Participants',
    'gradebook.show' => 'Notes',
    'courses.analytics' => 'Analytique',
    'discussions.index' => 'Forum',
    'courses.edit' => 'Paramètres',
])
<div class="mb-8 flex gap-1 overflow-x-auto border-b border-slate-200">
    @foreach($tabs as $route => $label)
        <a href="{{ route($route, $course) }}" @class([
            '-mb-px whitespace-nowrap border-b-2 px-4 py-2 text-sm font-medium',
            'border-brand-600 text-brand-700' => request()->routeIs($route),
            'border-transparent text-slate-500 hover:text-slate-800' => ! request()->routeIs($route),
        ])>{{ $label }}</a>
    @endforeach
</div>
