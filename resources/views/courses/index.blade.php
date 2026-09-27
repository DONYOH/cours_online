<x-layouts.app title="Mes cours">
    <x-page-header title="Espace enseignant" subtitle="Créez et pilotez vos cours.">
        <x-slot:actions><a href="{{ route('courses.create') }}" class="btn-primary">+ Nouveau cours</a></x-slot:actions>
    </x-page-header>

    @if($courses->isEmpty())
        <x-empty title="Aucun cours pour l'instant" icon="🧑‍🏫">Lancez-vous : un cours se crée en moins d'une minute.</x-empty>
    @else
        <div class="card overflow-x-auto">
            <table class="table-base">
                <thead><tr><th>Cours</th><th>Statut</th><th>Contenu</th><th>Apprenants</th><th class="text-right">Actions</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($courses as $course)
                        <tr>
                            <td>
                                <a href="{{ route('courses.show', $course) }}" class="font-semibold text-slate-900 hover:text-brand-600">{{ $course->title }}</a>
                                <p class="text-xs text-slate-500">{{ $course->category?->name ?? 'Sans catégorie' }} · {{ $course->level->label() }}</p>
                            </td>
                            <td>@if($course->is_published)<span class="badge bg-emerald-50 text-emerald-700">Publié</span>@else<span class="badge bg-amber-50 text-amber-700">Brouillon</span>@endif</td>
                            <td class="text-xs text-slate-500">{{ $course->lessons_count }} leçons · {{ $course->quizzes_count }} quiz · {{ $course->assignments_count }} devoirs</td>
                            <td>{{ $course->students_count }}</td>
                            <td class="whitespace-nowrap text-right">
                                <a class="btn-ghost btn-sm" href="{{ route('courses.builder', $course) }}">Contenu</a>
                                <a class="btn-ghost btn-sm" href="{{ route('gradebook.show', $course) }}">Notes</a>
                                <a class="btn-ghost btn-sm" href="{{ route('courses.analytics', $course) }}">Analytique</a>
                                <a class="btn-ghost btn-sm" href="{{ route('courses.edit', $course) }}">Paramètres</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layouts.app>
