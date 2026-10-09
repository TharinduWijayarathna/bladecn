@props([
    'type' => 'single',
    'value' => null,
    'name' => null,
    'variant' => 'default',
    'size' => 'default',
    'orientation' => 'horizontal',
    'class' => '',
])

@php
    $initial = $type === 'multiple'
        ? array_values(array_map('strval', (array) $value))
        : ($value === null ? null : (string) $value);
@endphp

<div role="group" data-slot="toggle-group" data-variant="{{ $variant }}" data-size="{{ $size }}" data-orientation="{{ $orientation }}"
    x-modelable="value"
    x-data="{
        multiple: @js($type === 'multiple'),
        value: @js($initial),
        isOn(item) { return this.multiple ? this.value.includes(item) : this.value === item },
        toggle(item) {
            if (this.multiple) this.value = this.isOn(item) ? this.value.filter(v => v !== item) : [...this.value, item];
            else this.value = this.isOn(item) ? null : item;
        },
        move(event, step) {
            const items = [...this.$root.querySelectorAll('[data-slot=toggle-group-item]:not([disabled])')];
            const index = items.indexOf(event.currentTarget);
            const next = step === 'first' ? items[0] : step === 'last' ? items[items.length - 1] : items[(index + step + items.length) % items.length];
            next?.focus();
        },
        key(event) {
            const steps = { ArrowLeft: -1, ArrowUp: -1, ArrowRight: 1, ArrowDown: 1, Home: 'first', End: 'last' };
            if (event.key in steps) { event.preventDefault(); this.move(event, steps[event.key]); }
        },
    }"
    {{ $attributes->merge(['class' => cn(
        'group/toggle-group flex w-fit items-center rounded-md',
        $orientation === 'vertical' ? 'flex-col' : null,
        $variant === 'outline' ? 'shadow-xs' : null,
        $class,
    )]) }}>
    @if ($name)
        <template x-for="item in (multiple ? value : (value === null ? [] : [value]))" :key="item">
            <input type="hidden" name="{{ $name }}{{ $type === 'multiple' ? '[]' : '' }}" :value="item">
        </template>
    @endif
    {{ $slot }}
</div>
