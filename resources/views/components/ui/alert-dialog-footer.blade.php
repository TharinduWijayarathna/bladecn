@props([
    'class' => '',
])

<div data-slot="alert-dialog-footer" {{ $attributes->merge(['class' => cn('flex flex-col-reverse gap-2 sm:flex-row sm:justify-end', $class)]) }}>
    {{ $slot }}
</div>
