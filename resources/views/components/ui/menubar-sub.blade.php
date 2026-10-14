@props([
    'class' => '',
])

<div data-slot="menubar-sub" x-data="{ subOpen: false }" @mouseleave="subOpen = false"
    {{ $attributes->merge(['class' => cn('relative', $class)]) }}>
    {{ $slot }}
</div>
