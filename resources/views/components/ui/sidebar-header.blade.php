@props(['class' => ''])

<div data-slot="sidebar-header" {{ $attributes->merge(['class' => cn('flex flex-col gap-2 p-2', $class)]) }}>
    {{ $slot }}
</div>
