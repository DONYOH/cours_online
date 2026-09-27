@props(['kind', 'type' => null])
@php
    $map = [
        'lesson' => ['bg-sky-100 text-sky-700', match ($type?->value ?? $type) { 'video' => '▶', 'file' => '📎', default => '📖' }],
        'quiz' => ['bg-violet-100 text-violet-700', '?'],
        'assignment' => ['bg-amber-100 text-amber-700', '✎'],
    ];
    [$cls, $sym] = $map[$kind];
@endphp
<span {{ $attributes->class(["grid h-7 w-7 shrink-0 place-items-center rounded-lg text-xs font-bold $cls"]) }}>{{ $sym }}</span>
