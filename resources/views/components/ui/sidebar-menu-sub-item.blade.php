@props(['class' => ''])

<li data-slot="sidebar-menu-sub-item" {{ $attributes->merge(['class' => cn('group/menu-sub-item relative', $class)]) }}>
    {{ $slot }}
</li>
