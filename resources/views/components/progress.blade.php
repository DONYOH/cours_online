@props(['value' => 0, 'size' => 'h-2'])
@php($v = max(0, min(100, (int) $value)))
<div {{ $attributes->class(["w-full overflow-hidden rounded-full bg-slate-200 $size"]) }} role="progressbar" aria-valuenow="{{ $v }}" aria-valuemin="0" aria-valuemax="100">
    <div class="h-full rounded-full {{ $v >= 100 ? 'bg-emerald-500' : 'bg-brand-500' }} transition-all" style="width: {{ $v }}%"></div>
</div>
