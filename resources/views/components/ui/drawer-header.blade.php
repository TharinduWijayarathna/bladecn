@props([
    'class' => '',
])

@aware(['direction' => 'bottom'])

{{-- On touch screens the header is a drag area too, so the drawer can be swiped closed from it. --}}
<div data-slot="drawer-header"
    @pointerdown="$event.pointerType !== 'mouse' && dragStart($event)" @pointermove="dragMove($event)" @pointerup="dragEnd()" @pointercancel="dragEnd()"
    {{ $attributes->merge(['class' => cn(
        'flex flex-col gap-0.5 p-4 md:gap-1.5',
        in_array($direction, ['top', 'bottom'], true) ? 'text-center md:text-left' : null,
        $class,
    )]) }}>
    {{ $slot }}
</div>
