@props([
    'class' => '',
])

<ul data-slot="pagination-content" {{ $attributes->merge(['class' => cn('flex flex-row items-center gap-1', $class)]) }}>
    {{ $slot }}
</ul>
