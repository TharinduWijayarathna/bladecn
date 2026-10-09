@props([
    'value' => null,
    'min' => 0,
    'max' => 100,
    'step' => 1,
    'name' => null,
    'disabled' => false,
    'orientation' => 'horizontal',
    'label' => null,
    'class' => '',
])

@php
    $min = (float) $min;
    $max = (float) $max;
    $initial = min($max, max($min, (float) ($value ?? $min)));
    $percent = $max > $min ? ($initial - $min) / ($max - $min) * 100 : 0;
    $vertical = $orientation === 'vertical';
@endphp

<div data-slot="slider" data-orientation="{{ $orientation }}" @if ($disabled) data-disabled @endif x-modelable="value"
    x-data="{
        value: @js($initial),
        min: @js($min),
        max: @js($max),
        step: @js((float) $step),
        vertical: @js($vertical),
        disabled: @js((bool) $disabled),
        dragging: false,
        get percent() { return this.max > this.min ? (this.value - this.min) / (this.max - this.min) * 100 : 0 },
        set(raw) {
            const steps = Math.round((raw - this.min) / this.step);
            const decimals = (String(this.step).split('.')[1] || '').length;
            const next = Math.min(this.max, Math.max(this.min, +(this.min + steps * this.step).toFixed(decimals)));
            if (next !== this.value) {
                this.value = next;
                this.$nextTick(() => this.$root.dispatchEvent(new CustomEvent('change', { bubbles: true, detail: { value: next } })));
            }
        },
        fromPointer(event) {
            const rect = this.$refs.track.getBoundingClientRect();
            const ratio = this.vertical ? (rect.bottom - event.clientY) / rect.height : (event.clientX - rect.left) / rect.width;
            this.set(this.min + Math.min(1, Math.max(0, ratio)) * (this.max - this.min));
        },
        start(event) {
            if (this.disabled) return;
            this.dragging = true;
            this.$refs.thumb.focus();
            this.$root.setPointerCapture?.(event.pointerId);
            this.fromPointer(event);
        },
        key(event) {
            const big = Math.max(this.step, (this.max - this.min) / 10);
            const delta = { ArrowRight: this.step, ArrowUp: this.step, ArrowLeft: -this.step, ArrowDown: -this.step, PageUp: big, PageDown: -big }[event.key];
            if (delta !== undefined) { event.preventDefault(); this.set(this.value + delta); }
            else if (event.key === 'Home') { event.preventDefault(); this.set(this.min); }
            else if (event.key === 'End') { event.preventDefault(); this.set(this.max); }
        },
    }"
    @pointerdown="start($event)" @pointermove="dragging && fromPointer($event)" @pointerup="dragging = false" @pointercancel="dragging = false"
    {{ $attributes->merge(['class' => cn(
        'relative flex touch-none select-none items-center data-[disabled]:opacity-50',
        $vertical ? 'h-full min-h-44 w-auto flex-col' : 'w-full',
        $class,
    )]) }}>
    <span data-slot="slider-track" x-ref="track"
        class="relative grow overflow-hidden rounded-full bg-muted {{ $vertical ? 'h-full w-1.5' : 'h-1.5 w-full' }}">
        <span data-slot="slider-range" class="absolute bg-primary {{ $vertical ? 'bottom-0 w-full' : 'h-full' }}"
            style="{{ $vertical ? 'height' : 'width' }}: {{ $percent }}%;" :style="{ {{ $vertical ? 'height' : 'width' }}: percent + '%' }"></span>
    </span>
    <span role="slider" data-slot="slider-thumb" x-ref="thumb" tabindex="{{ $disabled ? -1 : 0 }}"
        aria-valuemin="{{ $min }}" aria-valuemax="{{ $max }}" aria-valuenow="{{ $initial }}" :aria-valuenow="value"
        aria-orientation="{{ $orientation }}" @if ($label) aria-label="{{ $label }}" @endif @if ($disabled) aria-disabled="true" @endif
        @keydown="! disabled && key($event)"
        class="absolute block size-4 shrink-0 rounded-full border border-primary bg-background shadow-sm outline-none ring-ring/50 transition-[color,box-shadow] hover:ring-4 focus-visible:ring-4 {{ $vertical ? 'left-1/2 -translate-x-1/2 translate-y-1/2' : 'top-1/2 -translate-x-1/2 -translate-y-1/2' }}"
        style="{{ $vertical ? 'bottom' : 'left' }}: {{ $percent }}%;" :style="{ {{ $vertical ? 'bottom' : 'left' }}: percent + '%' }"></span>
    @if ($name)
        <input type="hidden" name="{{ $name }}" value="{{ $initial }}" :value="value" @disabled($disabled)>
    @endif
</div>
