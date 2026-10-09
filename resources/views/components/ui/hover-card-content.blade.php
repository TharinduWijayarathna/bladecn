@props([
    'side' => 'bottom',
    'align' => 'center',
    'sideOffset' => 4,
    'class' => '',
])

@php
    $sides = [
        'top' => ['bottom-full', 'padding-bottom'],
        'bottom' => ['top-full', 'padding-top'],
        'left' => ['right-full', 'padding-right'],
        'right' => ['left-full', 'padding-left'],
    ];
    [$sidePosition, $paddingProperty] = $sides[$side] ?? $sides['bottom'];
    $vertical = in_array($side, ['top', 'bottom'], true) || ! isset($sides[$side]);
    $alignPosition = $vertical
        ? ['start' => 'left-0', 'center' => 'left-1/2 -translate-x-1/2', 'end' => 'right-0'][$align] ?? 'left-1/2 -translate-x-1/2'
        : ['start' => 'top-0', 'center' => 'top-1/2 -translate-y-1/2', 'end' => 'bottom-0'][$align] ?? 'top-1/2 -translate-y-1/2';
@endphp

{{-- The offset is padding on a transparent wrapper, so the pointer can travel from trigger to card without closing it. --}}
<div class="absolute z-50 {{ $sidePosition }} {{ $alignPosition }}" style="{{ $paddingProperty }}: {{ (int) $sideOffset }}px;"
    x-show="open" x-cloak @mouseenter="show(0)"
    x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div data-slot="hover-card-content" data-side="{{ $side }}" :id="$id('hover-card-content')"
        {{ $attributes->merge(['class' => cn('w-64 rounded-md border bg-popover p-4 text-popover-foreground shadow-md outline-hidden', $class)]) }}>
        {{ $slot }}
    </div>
</div>
