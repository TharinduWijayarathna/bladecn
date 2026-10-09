@props([
    'class' => '',
])

<div role="separator" data-slot="command-separator" {{ $attributes->merge(['class' => cn('-mx-1 h-px bg-border', $class)]) }}></div>
