@props([
    'type' => 'single',
    'collapsible' => false,
    'defaultValue' => null,
    'class' => '',
])

@php
    $initial = array_values(array_filter((array) $defaultValue, fn ($value) => $value !== null && $value !== ''));
@endphp

<div data-slot="accordion"
    x-data="{
        type: @js($type),
        collapsible: @js((bool) $collapsible),
        openItems: @js($initial),
        isOpen(value) { return this.openItems.includes(value) },
        toggle(value) {
            if (this.isOpen(value)) {
                if (this.type === 'multiple' || this.collapsible) this.openItems = this.openItems.filter(v => v !== value);
            } else {
                this.openItems = this.type === 'multiple' ? [...this.openItems, value] : [value];
            }
        },
        focusTrigger(current, step) {
            const root = current.closest('[data-slot=accordion]');
            const triggers = [...root.querySelectorAll('[data-slot=accordion-trigger]:not([disabled])')]
                .filter(el => el.closest('[data-slot=accordion]') === root);
            const index = triggers.indexOf(current);
            const next = step === 'first' ? 0 : step === 'last' ? triggers.length - 1 : (index + step + triggers.length) % triggers.length;
            triggers[next]?.focus();
        },
    }"
    {{ $attributes->merge(['class' => cn('w-full', $class)]) }}>
    {{ $slot }}
</div>
