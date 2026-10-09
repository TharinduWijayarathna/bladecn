@props([
    'ratio' => 1,
    'class' => '',
])

@php
    // Accept a number (1.7778), "16/9" or "16:9".
    if (is_string($ratio) && preg_match('#^\s*([\d.]+)\s*[/:]\s*([\d.]+)\s*$#', $ratio, $m) && (float) $m[2] > 0) {
        $ratio = (float) $m[1] / (float) $m[2];
    }
    $ratio = (float) $ratio > 0 ? (float) $ratio : 1;
@endphp

<div data-slot="aspect-ratio" style="aspect-ratio: {{ $ratio }};"
    {{ $attributes->merge(['class' => cn('relative w-full overflow-hidden [&>img]:absolute [&>img]:inset-0 [&>img]:size-full [&>img]:object-cover', $class)]) }}>
    {{ $slot }}
</div>
