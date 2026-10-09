@props([
    'class' => '',
])

<div data-slot="alert-title" {{ $attributes->merge(['class' => cn('col-start-2 line-clamp-1 min-h-4 font-medium tracking-tight', $class)]) }}>
    {{ $slot }}
</div>
