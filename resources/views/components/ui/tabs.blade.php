@props([
    'defaultValue' => null,
    'orientation' => 'horizontal',
    'activation' => 'automatic',
    'class' => '',
])

<div data-slot="tabs" data-orientation="{{ $orientation }}" x-id="['tabs']" x-modelable="tab"
    x-data="{
        tab: @js($defaultValue),
        orientation: @js($orientation),
        automatic: @js($activation !== 'manual'),
        triggers() {
            return [...this.$root.querySelectorAll('[data-slot=tabs-trigger]')]
                .filter(el => el.closest('[data-slot=tabs]') === this.$root && ! el.disabled);
        },
        init() {
            if (this.tab === null) this.tab = this.triggers()[0]?.dataset.value ?? null;
        },
        move(event, step) {
            const triggers = this.triggers();
            const index = triggers.indexOf(event.currentTarget);
            const next = step === 'first' ? triggers[0] : step === 'last' ? triggers[triggers.length - 1]
                : triggers[(index + step + triggers.length) % triggers.length];
            next?.focus();
            if (next && this.automatic) this.tab = next.dataset.value;
        },
        key(event) {
            const prev = this.orientation === 'vertical' ? 'ArrowUp' : 'ArrowLeft';
            const next = this.orientation === 'vertical' ? 'ArrowDown' : 'ArrowRight';
            const steps = { [prev]: -1, [next]: 1, Home: 'first', End: 'last' };
            if (event.key in steps) { event.preventDefault(); this.move(event, steps[event.key]); }
        },
    }"
    {{ $attributes->merge(['class' => cn('flex gap-2', $orientation === 'vertical' ? 'flex-row' : 'flex-col', $class)]) }}>
    {{ $slot }}
</div>
