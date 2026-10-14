@props(['class' => ''])

<div data-slot="sidebar-separator" role="separator" aria-orientation="horizontal"
    {{ $attributes->merge(['class' => cn('mx-2 h-px w-auto shrink-0 bg-sidebar-border', $class)]) }}></div>
