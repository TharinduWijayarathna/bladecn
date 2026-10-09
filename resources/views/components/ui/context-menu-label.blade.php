@props([
    'inset' => false,
    'class' => '',
])

<div data-slot="context-menu-label" role="presentation" {{ $attributes->merge(['class' => cn('px-2 py-1.5 text-sm font-medium text-foreground', $inset ? 'pl-8' : null, $class)]) }}>
    {{ $slot }}
</div>
