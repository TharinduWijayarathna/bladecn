@props([
    'class' => '',
])

<p data-slot="alert-dialog-description" :id="$id('alert-dialog-description')" {{ $attributes->merge(['class' => cn('text-sm text-muted-foreground', $class)]) }}>
    {{ $slot }}
</p>
