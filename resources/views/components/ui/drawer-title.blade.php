@props([
    'class' => '',
])

<h2 data-slot="drawer-title" :id="$id('drawer-title')" {{ $attributes->merge(['class' => cn('font-semibold text-foreground', $class)]) }}>
    {{ $slot }}
</h2>
