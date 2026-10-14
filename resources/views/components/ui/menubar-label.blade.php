@props([
    'inset' => false,
    'class' => '',
])

<div data-slot="menubar-label" {{ $attributes->merge(['class' => cn('px-2 py-1.5 text-sm font-medium', $inset ? 'pl-8' : null, $class)]) }}>
    {{ $slot }}
</div>
