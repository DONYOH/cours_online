@foreach (['success' => 'bg-emerald-50 text-emerald-800 ring-emerald-200', 'error' => 'bg-rose-50 text-rose-800 ring-rose-200', 'info' => 'bg-sky-50 text-sky-800 ring-sky-200'] as $type => $classes)
    @if (session($type))
        <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 flex items-start justify-between gap-4 rounded-xl px-4 py-3 text-sm ring-1 {{ $classes }}">
            <span>{{ session($type) }}</span>
            <button @click="show = false" class="opacity-60 hover:opacity-100" aria-label="Fermer">✕</button>
        </div>
    @endif
@endforeach
@if ($errors->any())
    <div class="mb-6 rounded-xl bg-rose-50 px-4 py-3 text-sm text-rose-800 ring-1 ring-rose-200">
        <p class="font-semibold">Veuillez corriger les erreurs suivantes :</p>
        <ul class="mt-1 list-inside list-disc">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif
