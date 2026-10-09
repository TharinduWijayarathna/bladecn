@props([
    'variant' => 'default',
    'size' => 'default',
    'pressed' => false,
    'disabled' => false,
    'class' => '',
])

@php
    $variants = [
        'default' => 'bg-transparent',
        'outline' => 'border border-input bg-transparent shadow-xs hover:bg-accent hover:text-accent-foreground',
    ];
    $sizes = [
        'default' => 'h-9 min-w-9 px-2',
        'sm' => 'h-8 min-w-8 px-1.5',
        'lg' => 'h-10 min-w-10 px-2.5',
    ];
@endphp

<button type="button" data-slot="toggle" @disabled($disabled) x-data="{ pressed: @js((bool) $pressed) }" x-modelable="pressed"
    aria-pressed="{{ $pressed ? 'true' : 'false' }}" data-state="{{ $pressed ? 'on' : 'off' }}"
    :aria-pressed="pressed.toString()" :data-state="pressed ? 'on' : 'off'" @click="pressed = ! pressed"
    {{ $attributes->merge(['class' => cn(
        'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium outline-none transition-[color,box-shadow]',
        'hover:bg-muted hover:text-muted-foreground disabled:pointer-events-none disabled:opacity-50 data-[state=on]:bg-accent data-[state=on]:text-accent-foreground',
        'focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 aria-invalid:border-destructive aria-invalid:ring-destructive/20',
        "[&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4",
        $variants[$variant] ?? $variants['default'],
        $sizes[$size] ?? $sizes['default'],
        $class,
    )]) }}>
    {{ $slot }}
</button>
