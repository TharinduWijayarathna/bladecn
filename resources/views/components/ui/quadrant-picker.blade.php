@props([
    'labels' => [],
    'x' => 0,
    'y' => 0,
    'name' => null,
    'step' => 0.1,
    'label' => 'Position',
    'disabled' => false,
    'class' => '',
])

@php
    $clamp = fn ($v) => round(min(1, max(-1, (float) $v)), 2);
    $x = $clamp($x);
    $y = $clamp($y);
    $step = max(0.01, min(1, (float) $step));
    $disabled = filter_var($disabled, FILTER_VALIDATE_BOOLEAN);
    $labels = array_values(array_pad(array_slice((array) $labels, 0, 4), 4, null));
    $corners = [
        ['top-3 left-3', 'text-left'],
        ['top-3 right-3', 'text-right'],
        ['bottom-3 left-3', 'text-left'],
        ['bottom-3 right-3', 'text-right'],
    ];
@endphp

{{--
    A two-axis picker (an "XY pad"): click, drag or use the arrow keys to place the handle. Values run from -1
    to 1 on each axis (y is positive at the top). Optional `labels` name the four corners, in reading order:
    top-left, top-right, bottom-left, bottom-right. Submits `{name}[x]` and `{name}[y]`, works with `x-model`
    (`{ x, y }`) and dispatches a bubbling `change` event with `{ x, y }`.
--}}
<div data-slot="quadrant-picker" x-modelable="value"
    x-data="{
        value: { x: @js($x), y: @js($y) },
        step: @js($step),
        dragging: false,
        disabled: @js($disabled),
        clamp(v) { return Math.round(Math.min(1, Math.max(-1, v)) * 100) / 100 },
        set(x, y, emit = true) {
            if (this.disabled) return;
            const next = { x: this.clamp(x), y: this.clamp(y) };
            if (next.x === this.value.x && next.y === this.value.y) return;
            this.value = next;
            if (emit) this.$nextTick(() => this.$root.dispatchEvent(new CustomEvent('change', { bubbles: true, detail: { ...next } })));
        },
        fromPointer(event) {
            const rect = this.$refs.pad.getBoundingClientRect();
            this.set(((event.clientX - rect.left) / rect.width) * 2 - 1, 1 - ((event.clientY - rect.top) / rect.height) * 2);
        },
        down(event) {
            if (this.disabled) return;
            this.dragging = true;
            this.$refs.pad.setPointerCapture?.(event.pointerId);
            this.$refs.pad.focus();
            this.fromPointer(event);
        },
        key(event) {
            const big = event.shiftKey ? 5 : 1;
            const moves = { ArrowRight: [1, 0], ArrowLeft: [-1, 0], ArrowUp: [0, 1], ArrowDown: [0, -1] };
            if (event.key in moves) {
                event.preventDefault();
                const [dx, dy] = moves[event.key];
                this.set(this.value.x + dx * this.step * big, this.value.y + dy * this.step * big);
            } else if (event.key === 'Home' || event.key === 'Escape') {
                event.preventDefault();
                this.set(0, 0);
            }
        },
        get left() { return ((this.value.x + 1) / 2) * 100 },
        get top() { return ((1 - this.value.y) / 2) * 100 },
        get text() { return `x ${this.value.x}, y ${this.value.y}` },
    }"
    {{ $attributes->merge(['class' => cn('relative w-full max-w-sm', $class)]) }}>
    <div x-ref="pad" role="slider" tabindex="{{ $disabled ? '-1' : '0' }}" aria-label="{{ $label }}"
        aria-valuemin="-1" aria-valuemax="1" aria-valuenow="{{ $x }}" :aria-valuenow="value.x"
        aria-valuetext="x {{ $x }}, y {{ $y }}" :aria-valuetext="text"
        @if ($disabled) aria-disabled="true" @endif
        x-on:pointerdown="down($event)" x-on:pointermove="dragging && fromPointer($event)"
        x-on:pointerup="dragging = false" x-on:pointercancel="dragging = false" x-on:keydown="key($event)"
        class="relative aspect-square w-full touch-none select-none overflow-hidden rounded-xl border bg-card shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 {{ $disabled ? 'cursor-not-allowed opacity-50' : 'cursor-crosshair' }}">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle,var(--color-border)_1px,transparent_1px)] [background-size:16px_16px] opacity-60" aria-hidden="true"></div>
        <div class="pointer-events-none absolute inset-x-0 top-1/2 h-px bg-border" aria-hidden="true"></div>
        <div class="pointer-events-none absolute inset-y-0 left-1/2 w-px bg-border" aria-hidden="true"></div>
        @foreach ($labels as $i => $text)
            @if (filled($text))
                <span data-slot="quadrant-picker-label" class="pointer-events-none absolute text-xs font-medium text-muted-foreground {{ $corners[$i][0] }} {{ $corners[$i][1] }}">{{ $text }}</span>
            @endif
        @endforeach
        <span data-slot="quadrant-picker-handle" aria-hidden="true"
            class="pointer-events-none absolute size-5 -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-background bg-primary shadow-md ring-1 ring-primary/30"
            :class="dragging ? 'scale-110' : 'transition-[left,top] duration-150'"
            style="left: {{ ($x + 1) / 2 * 100 }}%; top: {{ (1 - $y) / 2 * 100 }}%;"
            :style="`left: ${left}%; top: ${top}%`"></span>
    </div>
    @if ($name)
        <input type="hidden" name="{{ $name }}[x]" value="{{ $x }}" :value="value.x" />
        <input type="hidden" name="{{ $name }}[y]" value="{{ $y }}" :value="value.y" />
    @endif
</div>
