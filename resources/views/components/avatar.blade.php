@props(['user', 'size' => 'h-8 w-8 text-xs'])
@php($hues = ['bg-indigo-500', 'bg-emerald-500', 'bg-amber-500', 'bg-rose-500', 'bg-sky-500', 'bg-violet-500', 'bg-teal-500'])
<span {{ $attributes->class(["inline-grid shrink-0 place-items-center rounded-full font-bold text-white $size", $hues[$user->id % count($hues)]]) }}>{{ $user->initials() }}</span>
