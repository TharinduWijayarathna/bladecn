@props([
    'value' => 0,
    'max' => 5,
    'step' => 1,
    'name' => null,
    'readonly' => false,
    'disabled' => false,
    'size' => 'default',
    'label' => 'Rating',
    'class' => '',
])

@php
    $max = max(1, (int) $max);
    $step = (float) $step === 0.5 ? 0.5 : 1.0;
    $value = min($max, max(0, (float) $value));
    $readonly = filter_var($readonly, FILTER_VALIDATE_BOOLEAN);
    $disabled = filter_var($disabled, FILTER_VALIDATE_BOOLEAN);
    $sizes = ['sm' => 'size-4', 'default' => 'size-5', 'lg' => 'size-7'];
    $starSize = $sizes[$size] ?? $sizes['default'];
    $format = fn ($v) => rtrim(rtrim(number_format($v, 1, '.', ''), '0'), '.');
    $fill = fn (int $i) => (int) round(min(1, max(0, $value - ($i - 1))) * 100);
    $text = $format($value).' out of '.$max;
@endphp

{{--
    Star rating. Read-only ratings show fractional values (3.5 → three and a half stars).
    Interactive ratings are a `role="slider"`: click a star, or use the arrow keys, Home/End and number keys.
    `step="0.5"` allows half stars. Submits through `name`, works with `x-model`, dispatches `change` with `{ value }`.
--}}
<div data-slot="rating" x-modelable="value"
    x-data="{
        value: @js($value),
        hover: null,
        max: @js($max),
        step: @js($step),
        interactive: @js(! $readonly && ! $disabled),
        get shown() { return this.hover ?? this.value },
        fill(i) { return Math.round(Math.min(1, Math.max(0, this.shown - (i - 1))) * 100) },
        get text() { return `${+this.value.toFixed(1)} out of ${this.max}` },
        fromPointer(event, i) {
            if (this.step === 1) return i;
            const rect = event.currentTarget.getBoundingClientRect();
            const rtl = getComputedStyle(this.$root).direction === 'rtl';
            const half = rtl ? event.clientX > rect.left + rect.width / 2 : event.clientX < rect.left + rect.width / 2;
            return half ? i - 0.5 : i;
        },
        set(v) {
            if (! this.interactive) return;
            const next = Math.min(this.max, Math.max(0, Math.round(v / this.step) * this.step));
            if (next === this.value) return;
            this.value = next;
            this.$nextTick(() => this.$root.dispatchEvent(new CustomEvent('change', { bubbles: true, detail: { value: next } })));
        },
        key(event) {
            const delta = { ArrowRight: this.step, ArrowUp: this.step, ArrowLeft: -this.step, ArrowDown: -this.step }[event.key];
            if (delta !== undefined) this.set(this.value + delta);
            else if (event.key === 'Home') this.set(0);
            else if (event.key === 'End') this.set(this.max);
            else if (/^[0-9]$/.test(event.key) && +event.key <= this.max) this.set(+event.key);
            else return;
            event.preventDefault();
        },
    }"
    @if ($readonly)
        role="img" aria-label="{{ $label }}: {{ $text }}"
    @else
        role="slider" aria-label="{{ $label }}" aria-valuemin="0" aria-valuemax="{{ $max }}"
        aria-valuenow="{{ $format($value) }}" :aria-valuenow="value" aria-valuetext="{{ $text }}" :aria-valuetext="text"
        @if ($disabled) aria-disabled="true" data-disabled @else tabindex="0" @keydown="key($event)" @endif
    @endif
    @mouseleave="hover = null"
    {{ $attributes->merge(['class' => cn(
        'inline-flex items-center gap-0.5 rounded-md outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50',
        $disabled ? 'cursor-not-allowed opacity-50' : '',
        $class,
    )]) }}>
    @if ($name)
        <input type="hidden" name="{{ $name }}" value="{{ $format($value) }}" :value="value">
    @endif
    @for ($i = 1; $i <= $max; $i++)
        <span data-slot="rating-star" aria-hidden="true" class="relative inline-flex shrink-0 {{ $readonly || $disabled ? '' : 'cursor-pointer' }}"
            @unless ($readonly || $disabled)
                @mousemove="hover = fromPointer($event, {{ $i }})" @click="set(fromPointer($event, {{ $i }}) === value ? 0 : fromPointer($event, {{ $i }}))"
            @endunless>
            <x-icons.star class="{{ $starSize }} fill-current text-muted-foreground/25" stroke-width="1.5" />
            <span class="absolute inset-y-0 left-0 overflow-hidden rtl:left-auto rtl:right-0" style="width: {{ $fill($i) }}%" :style="`width: ${fill({{ $i }})}%`">
                <x-icons.star class="{{ $starSize }} max-w-none fill-current text-amber-400 dark:text-amber-300" stroke-width="1.5" />
            </span>
        </span>
    @endfor
</div>
