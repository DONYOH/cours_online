@props(['title', 'subtitle' => null])
<div class="mb-8 flex flex-wrap items-end justify-between gap-4">
    <div>
        @isset($breadcrumb)<div class="mb-2 text-sm text-slate-500">{{ $breadcrumb }}</div>@endisset
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">{{ $title }}</h1>
        @if($subtitle)<p class="mt-1 text-slate-500">{{ $subtitle }}</p>@endif
    </div>
    @isset($actions)<div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>@endisset
</div>
