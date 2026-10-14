@props(['class' => ''])

<ul data-slot="sidebar-menu" {{ $attributes->merge(['class' => cn('flex w-full min-w-0 flex-col gap-1', $class)]) }}>
    {{ $slot }}
</ul>
