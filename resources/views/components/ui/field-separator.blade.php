@props([
    'class' => '',
])

<div data-slot="field-separator" @if ($slot->isNotEmpty()) data-content="true" @endif
    {{ $attributes->merge(['class' => cn('relative -my-2 h-5 text-sm group-data-[variant=outline]/field-group:-mb-2', $class)]) }}>
    <div role="separator" aria-hidden="true" class="absolute inset-0 top-1/2 h-px w-full bg-border"></div>
    @if ($slot->isNotEmpty())
        <span class="relative mx-auto block w-fit bg-background px-2 text-muted-foreground" data-slot="field-separator-content">{{ $slot }}</span>
    @endif
</div>
