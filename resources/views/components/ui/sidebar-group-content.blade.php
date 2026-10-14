@props(['class' => ''])

<div data-slot="sidebar-group-content" {{ $attributes->merge(['class' => cn('w-full text-sm', $class)]) }}>
    {{ $slot }}
</div>
