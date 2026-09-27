<x-layouts.app title="Catalogue">
    <x-page-header title="Catalogue des cours" subtitle="Trouvez la formation qui vous correspond." />

    <form method="GET" class="card mb-8 grid gap-3 p-4 sm:grid-cols-2 lg:grid-cols-5">
        <input class="input lg:col-span-2" type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher un cours…">
        <select class="input" name="categorie">
            <option value="">Toutes les catégories</option>
            @foreach($categories as $cat)<option value="{{ $cat->slug }}" @selected(request('categorie') === $cat->slug)>{{ $cat->name }}</option>@endforeach
        </select>
        <select class="input" name="niveau">
            <option value="">Tous niveaux</option>
            @foreach($levels as $level)<option value="{{ $level->value }}" @selected(request('niveau') === $level->value)>{{ $level->label() }}</option>@endforeach
        </select>
        <div class="flex gap-2">
            <select class="input" name="tri">
                <option value="">Récents</option>
                <option value="populaires" @selected(request('tri') === 'populaires')>Populaires</option>
            </select>
            <button class="btn-primary">Filtrer</button>
        </div>
    </form>

    @if($courses->isEmpty())
        <x-empty title="Aucun cours trouvé" icon="🔍">Essayez d'autres filtres.</x-empty>
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($courses as $course)
                <x-course-card :course="$course" :enrolled="in_array($course->id, $enrolledIds)" />
            @endforeach
        </div>
        <div class="mt-8">{{ $courses->links() }}</div>
    @endif
</x-layouts.app>
