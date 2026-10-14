@props([
    'class' => '',
])

<div data-slot="drawer-footer" {{ $attributes->merge(['class' => cn('mt-auto flex flex-col gap-2 p-4', $class)]) }}>
    {{ $slot }}
</div>
