@props([
    'value',
    'disabled' => false,
    'class' => '',
])

<div data-slot="accordion-item" x-data="{ itemValue: @js((string) $value), itemDisabled: @js((bool) $disabled) }"
    x-id="['accordion-trigger', 'accordion-content']" :data-state="isOpen(itemValue) ? 'open' : 'closed'"
    {{ $attributes->merge(['class' => cn('border-b last:border-b-0', $class)]) }}>
    {{ $slot }}
</div>
