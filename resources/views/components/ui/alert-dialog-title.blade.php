@props([
    'class' => '',
])

<h2 data-slot="alert-dialog-title" :id="$id('alert-dialog-title')" {{ $attributes->merge(['class' => cn('text-lg font-semibold', $class)]) }}>
    {{ $slot }}
</h2>
