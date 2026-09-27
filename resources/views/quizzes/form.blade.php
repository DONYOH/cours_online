<x-layouts.app title="Nouveau quiz">
    <x-page-header title="Nouveau quiz" :subtitle="'Section : '.$section->title">
        <x-slot:breadcrumb><a class="link" href="{{ route('courses.builder', $course) }}">← {{ $course->title }}</a></x-slot:breadcrumb>
    </x-page-header>
    <form method="POST" action="{{ route('quizzes.store', [$course, $section]) }}" class="card max-w-2xl p-6">
        @csrf
        @include('quizzes._settings')
        <button class="btn-primary mt-6">Créer et ajouter des questions</button>
    </form>
</x-layouts.app>
