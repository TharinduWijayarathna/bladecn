@props([
    'class' => '',
])

<div data-slot="alert-description" {{ $attributes->merge(['class' => cn('col-start-2 grid justify-items-start gap-1 text-sm text-muted-foreground [&_p]:leading-relaxed', $class)]) }}>
    {{ $slot }}
</div>
