@props([
    'href' => null,
    'isActive' => false,
    'disabled' => false,
    'size' => 'icon',
    'class' => '',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4';
    $variant = $isActive
        ? 'border bg-background shadow-xs hover:bg-accent hover:text-accent-foreground dark:border-input dark:bg-input/30 dark:hover:bg-input/50'
        : 'hover:bg-accent hover:text-accent-foreground dark:hover:bg-accent/50';
    $sizes = [
        'default' => 'h-9 px-4 py-2 has-[>svg]:px-3',
        'sm' => 'h-8 gap-1.5 px-3',
        'lg' => 'h-10 px-6',
        'icon' => 'size-9',
    ];
    $classes = cn($base, $variant, $sizes[$size] ?? $sizes['icon'], $disabled ? 'pointer-events-none opacity-50' : null, $class);
@endphp

@if ($disabled || $href === null)
    <span role="link" aria-disabled="true" data-slot="pagination-link" data-active="{{ $isActive ? 'true' : 'false' }}"
        @if ($isActive) aria-current="page" @endif
        {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</span>
@else
    <a href="{{ $href }}" data-slot="pagination-link" data-active="{{ $isActive ? 'true' : 'false' }}"
        @if ($isActive) aria-current="page" @endif
        {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@endif
