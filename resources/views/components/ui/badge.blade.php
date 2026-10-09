@props([
    'variant' => 'default',
    'class' => '',
])

@php
    $baseClasses = 'inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2';
    $variantClasses = [
        'default' => 'border-transparent bg-primary text-primary-foreground hover:bg-primary/80',
        'secondary' => 'border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80',
        'destructive' => 'border-transparent bg-destructive text-white hover:bg-destructive/90 dark:bg-destructive/60',
        'outline' => 'text-foreground',
    ];
    $classes = cn($baseClasses, $variantClasses[$variant] ?? $variantClasses['default'], $class);
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>

