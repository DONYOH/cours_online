@props(['label', 'value', 'hint' => null, 'tone' => 'slate'])
@php($color = ['slate' => 'text-slate-900', 'brand' => 'text-brand-700', 'emerald' => 'text-emerald-700', 'rose' => 'text-rose-700', 'amber' => 'text-amber-700'][$tone] ?? 'text-slate-900')
<div class="card p-5">
    <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
    <p class="mt-1 text-3xl font-extrabold tracking-tight {{ $color }}">{{ $value }}</p>
    @if($hint)<p class="mt-1 text-xs text-slate-400">{{ $hint }}</p>@endif
</div>
