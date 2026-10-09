@props([
    'class' => '',
])

@aware(['value' => null, 'defaultValue' => null])

@php
    // Server-render the initial state so default-open items don't animate in on load.
    $initiallyOpen = $value !== null && in_array((string) $value, array_map('strval', (array) $defaultValue), true);
@endphp

{{-- Animates height with the grid-rows 0fr → 1fr technique; closed content is `inert` so it can't be focused. --}}
<div role="region" data-slot="accordion-content" :id="$id('accordion-content')" :aria-labelledby="$id('accordion-trigger')"
    :data-state="isOpen(itemValue) ? 'open' : 'closed'" :inert="! isOpen(itemValue)"
    class="grid text-sm transition-[grid-template-rows] duration-200 ease-out data-[state=closed]:grid-rows-[0fr] data-[state=open]:grid-rows-[1fr]"
    data-state="{{ $initiallyOpen ? 'open' : 'closed' }}" @unless ($initiallyOpen) inert @endunless>
    <div class="overflow-hidden">
        <div {{ $attributes->merge(['class' => cn('pt-0 pb-4', $class)]) }}>
            {{ $slot }}
        </div>
    </div>
</div>
