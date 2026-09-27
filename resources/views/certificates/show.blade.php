<x-layouts.app :title="'Certificat · '.$certificate->course->title">
    <div class="mx-auto max-w-4xl">
        <div class="mb-4 flex justify-between print:hidden">
            <span class="badge bg-emerald-50 text-emerald-700">✓ Certificat authentique · code {{ $certificate->code }}</span>
            <button onclick="window.print()" class="btn-secondary btn-sm">🖨 Imprimer / PDF</button>
        </div>
        <div class="relative overflow-hidden rounded-2xl bg-white p-12 text-center shadow-xl ring-1 ring-slate-200 print:shadow-none">
            <div class="absolute inset-3 rounded-xl border-4 border-double border-brand-200"></div>
            <div class="relative">
                <p class="text-sm font-bold uppercase tracking-[0.3em] text-brand-600">{{ config('app.name') }}</p>
                <h1 class="mt-6 font-serif text-5xl text-slate-900">Certificat de réussite</h1>
                <p class="mt-8 text-slate-500">Ce certificat atteste que</p>
                <p class="mt-2 font-serif text-4xl font-bold text-slate-900">{{ $certificate->user->name }}</p>
                <p class="mt-6 text-slate-500">a suivi et validé l'intégralité du cours</p>
                <p class="mt-2 text-2xl font-bold text-brand-700">{{ $certificate->course->title }}</p>
                @if($certificate->final_grade !== null)
                    <p class="mt-4 text-slate-600">avec une moyenne de <strong>{{ str_replace('.', ',', $certificate->final_grade) }}/20</strong></p>
                @endif
                <div class="mt-12 grid grid-cols-2 gap-8 text-sm">
                    <div><p class="border-t border-slate-300 pt-2 font-semibold">{{ $certificate->course->teacher->name }}</p><p class="text-slate-500">Enseignant</p></div>
                    <div><p class="border-t border-slate-300 pt-2 font-semibold">{{ $certificate->issued_at->translatedFormat('j F Y') }}</p><p class="text-slate-500">Date de délivrance</p></div>
                </div>
                <p class="mt-10 text-xs text-slate-400">Vérification : {{ route('certificates.show', $certificate) }}</p>
            </div>
        </div>
    </div>
</x-layouts.app>
