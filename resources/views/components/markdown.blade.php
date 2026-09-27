@props(['text' => ''])
<div {{ $attributes->class('prose prose-slate max-w-none prose-headings:font-bold prose-a:text-brand-600 prose-pre:bg-slate-900 prose-code:before:content-none prose-code:after:content-none') }}>
    {!! \Illuminate\Support\Str::markdown((string) $text, ['html_input' => 'escape', 'allow_unsafe_links' => false]) !!}
</div>
