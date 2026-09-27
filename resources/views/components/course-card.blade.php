@props(['course', 'progress' => null, 'enrolled' => false])
<a href="{{ route('courses.show', $course) }}" class="group card flex flex-col overflow-hidden transition hover:-translate-y-0.5 hover:shadow-md">
    <div class="relative h-32 bg-gradient-to-br {{ $course->gradient() }} p-4">
        <span class="badge bg-white/20 text-white backdrop-blur">{{ $course->level->label() }}</span>
        @if($course->category)
            <span class="badge absolute right-4 top-4 bg-black/20 text-white">{{ $course->category->name }}</span>
        @endif
        <h3 class="absolute bottom-3 left-4 right-4 line-clamp-2 text-lg font-bold leading-tight text-white drop-shadow">{{ $course->title }}</h3>
    </div>
    <div class="flex flex-1 flex-col p-4">
        <p class="line-clamp-2 flex-1 text-sm text-slate-600">{{ $course->summary }}</p>
        <div class="mt-4 flex items-center justify-between text-xs text-slate-500">
            <span class="flex items-center gap-2"><x-avatar :user="$course->teacher" size="h-6 w-6 text-[10px]" /> {{ $course->teacher->name }}</span>
            @isset($course->students_count)<span>{{ $course->students_count }} inscrit(s)</span>@endisset
        </div>
        @if($progress !== null)
            <div class="mt-3 flex items-center gap-2"><x-progress :value="$progress" /><span class="text-xs font-semibold text-slate-600">{{ $progress }}%</span></div>
        @elseif($enrolled)
            <span class="mt-3 badge w-fit bg-emerald-50 text-emerald-700">✓ Inscrit</span>
        @endif
    </div>
</a>
