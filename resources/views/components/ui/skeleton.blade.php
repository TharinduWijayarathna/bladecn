@props([
    'class' => '',
])

<div data-slot="skeleton" aria-hidden="true" {{ $attributes->merge(['class' => cn('animate-pulse rounded-md bg-accent', $class)]) }}></div>
