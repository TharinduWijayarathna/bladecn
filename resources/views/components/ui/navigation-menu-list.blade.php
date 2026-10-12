@props([
    'class' => '',
])

<ul data-slot="navigation-menu-list"
    {{ $attributes->merge(['class' => cn('group flex flex-1 list-none items-center justify-center gap-1', $class)]) }}>
    {{ $slot }}
</ul>
