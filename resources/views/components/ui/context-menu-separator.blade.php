@props([
    'class' => '',
])

<div role="separator" data-slot="context-menu-separator" {{ $attributes->merge(['class' => cn('-mx-1 my-1 h-px bg-border', $class)]) }}></div>
