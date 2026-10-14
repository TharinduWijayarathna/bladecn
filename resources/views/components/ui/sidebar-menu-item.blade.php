@props(['class' => ''])

<li data-slot="sidebar-menu-item" {{ $attributes->merge(['class' => cn('group/menu-item relative', $class)]) }}>
    {{ $slot }}
</li>
