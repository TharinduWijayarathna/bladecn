@props([
    'class' => '',
])

<p data-slot="drawer-description" :id="$id('drawer-description')" {{ $attributes->merge(['class' => cn('text-sm text-muted-foreground', $class)]) }}>
    {{ $slot }}
</p>
