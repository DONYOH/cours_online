@props(['title', 'icon' => '📚'])
<div class="card flex flex-col items-center px-6 py-12 text-center">
    <div class="text-4xl">{{ $icon }}</div>
    <p class="mt-3 font-semibold text-slate-800">{{ $title }}</p>
    <div class="mt-1 text-sm text-slate-500">{{ $slot }}</div>
</div>
