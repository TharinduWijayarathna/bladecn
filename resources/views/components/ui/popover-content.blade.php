@props([
    'side' => 'bottom',
    'align' => 'center',
    'sideOffset' => 4,
    'class' => '',
])

@php
    $sides = [
        'top' => ['bottom-full', 'margin-bottom', 'origin-bottom'],
        'bottom' => ['top-full', 'margin-top', 'origin-top'],
        'left' => ['right-full', 'margin-right', 'origin-right'],
        'right' => ['left-full', 'margin-left', 'origin-left'],
    ];
    [$sidePosition, $marginProperty, $origin] = $sides[$side] ?? $sides['bottom'];
    $vertical = in_array($side, ['top', 'bottom'], true) || ! isset($sides[$side]);
    $alignPosition = $vertical
        ? ['start' => 'left-0', 'center' => 'left-1/2 -translate-x-1/2', 'end' => 'right-0'][$align] ?? 'left-1/2 -translate-x-1/2'
        : ['start' => 'top-0', 'center' => 'top-1/2 -translate-y-1/2', 'end' => 'bottom-0'][$align] ?? 'top-1/2 -translate-y-1/2';
@endphp

<div role="dialog" data-slot="popover-content" data-side="{{ $side }}" data-align="{{ $align }}" tabindex="-1"
    x-ref="popoverContent" :id="$id('popover-content')" x-show="open" x-cloak
    x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
    style="{{ $marginProperty }}: {{ (int) $sideOffset }}px;"
    {{ $attributes->merge(['class' => cn(
        'absolute z-50 w-72 rounded-md border bg-popover p-4 text-popover-foreground shadow-md outline-hidden',
        $sidePosition, $alignPosition, $origin,
        $class,
    )]) }}>
    {{ $slot }}
</div>
