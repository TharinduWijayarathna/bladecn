@props([
    'variant' => 'default',
    'class' => '',
])

@php
    $variants = [
        'default' => 'bg-card text-card-foreground',
        'destructive' => 'text-destructive bg-card [&>svg]:text-current *:data-[slot=alert-description]:text-destructive/90',
    ];
@endphp

<div role="alert" data-slot="alert" data-variant="{{ $variant }}"
    {{ $attributes->merge(['class' => cn(
        'relative grid w-full grid-cols-[0_1fr] items-start gap-y-0.5 rounded-lg border px-4 py-3 text-sm has-[>svg]:grid-cols-[calc(var(--spacing)*4)_1fr] has-[>svg]:gap-x-3 [&>svg]:size-4 [&>svg]:translate-y-0.5 [&>svg]:text-current',
        $variants[$variant] ?? $variants['default'],
        $class,
    )]) }}>
    {{ $slot }}
</div>
