@php($routeFor = ['lesson' => 'lessons.show', 'quiz' => 'quizzes.show', 'assignment' => 'assignments.show'])
<div class="mt-8 flex items-center justify-between gap-4 border-t border-slate-200 pt-6">
    @if($nav['prev'])
        <a href="{{ route($routeFor[$nav['prev']->kind()], [$course, $nav['prev']]) }}" class="group max-w-[45%] text-sm">
            <span class="block text-xs text-slate-400">← Précédent</span>
            <span class="block truncate font-medium group-hover:text-brand-600">{{ $nav['prev']->title }}</span>
        </a>
    @else <span></span> @endif
    @if($nav['next'])
        <a href="{{ route($routeFor[$nav['next']->kind()], [$course, $nav['next']]) }}" class="group max-w-[45%] text-right text-sm">
            <span class="block text-xs text-slate-400">Suivant →</span>
            <span class="block truncate font-medium group-hover:text-brand-600">{{ $nav['next']->title }}</span>
        </a>
    @endif
</div>
