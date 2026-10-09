@props([
    'class' => '',
])

<div data-slot="button-group-text" {{ $attributes->merge(['class' => cn("flex items-center gap-2 rounded-md border bg-muted px-4 text-sm font-medium shadow-xs [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4", $class)]) }}>
    {{ $slot }}
</div>
