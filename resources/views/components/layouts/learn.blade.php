@props(['course', 'outline', 'done', 'current', 'title' => null, 'enrollment' => null])
<x-layouts.app :title="$title" :wide="true">
    <div class="mx-auto flex max-w-7xl gap-8 px-4 py-6 sm:px-6 lg:px-8" x-data="{ side: false }">
        <aside class="fixed inset-0 z-30 bg-slate-900/40 lg:static lg:z-auto lg:block lg:w-80 lg:shrink-0 lg:bg-transparent" :class="side ? 'block' : 'hidden'" @click.self="side = false">
            <div class="h-full w-80 overflow-y-auto bg-white p-4 lg:sticky lg:top-24 lg:h-[calc(100vh-7rem)] lg:rounded-2xl lg:ring-1 lg:ring-slate-200">
                <a href="{{ route('courses.show', $course) }}" class="block font-bold leading-tight hover:text-brand-600">{{ $course->title }}</a>
                @if($enrollment)
                    <div class="mt-3 flex items-center gap-2"><x-progress :value="$enrollment->progress" /><span class="text-xs font-semibold">{{ $enrollment->progress }}%</span></div>
                @endif
                <nav class="mt-5 space-y-5">
                    @foreach($course->sections as $section)
                        @php($items = $outline->where('section_id', $section->id))
                        @continue($items->isEmpty())
                        <div>
                            <p class="mb-2 text-xs font-bold uppercase tracking-wide text-slate-400">{{ $section->title }}</p>
                            <ul class="space-y-0.5">
                                @foreach($items as $item)
                                    @php($active = $item->kind() === $current->kind() && $item->id === $current->id)
                                    @php($isDone = in_array($item->id, $done[$item->kind()]))
                                    <li>
                                        <a href="{{ route(['lesson' => 'lessons.show', 'quiz' => 'quizzes.show', 'assignment' => 'assignments.show'][$item->kind()], [$course, $item]) }}"
                                           @class(['flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm', 'bg-brand-50 font-semibold text-brand-700' => $active, 'text-slate-600 hover:bg-slate-50' => ! $active])>
                                            <x-item-icon :kind="$item->kind()" :type="$item->type ?? null" class="h-6 w-6" />
                                            <span class="flex-1 truncate">{{ $item->title }}</span>
                                            @if($isDone)<span class="text-emerald-500">✓</span>@endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </nav>
                <div class="mt-6 grid grid-cols-2 gap-2 border-t border-slate-100 pt-4 text-center text-xs">
                    <a href="{{ route('discussions.index', $course) }}" class="rounded-lg bg-slate-50 py-2 hover:bg-slate-100">💬 Forum</a>
                    <a href="{{ route('gradebook.show', $course) }}" class="rounded-lg bg-slate-50 py-2 hover:bg-slate-100">📊 Notes</a>
                </div>
            </div>
        </aside>

        <div class="min-w-0 flex-1">
            <button class="btn-secondary btn-sm mb-4 lg:hidden" @click="side = true">☰ Plan du cours</button>
            {{ $slot }}
        </div>
    </div>
</x-layouts.app>
