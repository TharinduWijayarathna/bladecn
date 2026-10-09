@props([
    'class' => '',
])

<span data-slot="context-menu-shortcut" {{ $attributes->merge(['class' => cn('ml-auto text-xs tracking-widest text-muted-foreground', $class)]) }}>{{ $slot }}</span>
