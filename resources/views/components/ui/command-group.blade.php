@props([
    'heading' => null,
    'class' => '',
])

<div role="group" data-slot="command-group" x-id="['command-group-heading']" @if ($heading) :aria-labelledby="$id('command-group-heading')" @endif
    {{ $attributes->merge(['class' => cn('overflow-hidden p-1 text-foreground', $class)]) }}>
    @if ($heading)
        <div data-slot="command-group-heading" :id="$id('command-group-heading')" class="px-2 py-1.5 text-xs font-medium text-muted-foreground">{{ $heading }}</div>
    @endif
    {{ $slot }}
</div>
