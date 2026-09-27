@props(['level', 'score' => null])
@php($cfg = ['high' => ['bg-rose-100 text-rose-700', 'Élevé'], 'medium' => ['bg-amber-100 text-amber-700', 'Modéré'], 'low' => ['bg-emerald-100 text-emerald-700', 'Faible']][$level])
<span class="badge whitespace-nowrap {{ $cfg[0] }}">{{ $cfg[1] }}@if($score !== null) · {{ $score }}@endif</span>
