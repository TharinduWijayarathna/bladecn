@props([
    'class' => '',
])

<div data-slot="alert-dialog-header" {{ $attributes->merge(['class' => cn('flex flex-col gap-2 text-center sm:text-left', $class)]) }}>
    {{ $slot }}
</div>
