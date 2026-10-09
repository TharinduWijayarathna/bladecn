@props([
    'openDelay' => 700,
    'closeDelay' => 300,
    'class' => '',
])

<div data-slot="hover-card" x-id="['hover-card-content']"
    x-data="{
        open: false,
        timer: null,
        show(delay = @js((int) $openDelay)) { clearTimeout(this.timer); this.timer = setTimeout(() => this.open = true, delay) },
        hide(delay = @js((int) $closeDelay)) { clearTimeout(this.timer); this.timer = setTimeout(() => this.open = false, delay) },
    }"
    @mouseleave="hide()" @focusout="! $root.contains($event.relatedTarget) && hide(0)" @keydown.escape="hide(0)"
    {{ $attributes->merge(['class' => cn('relative inline-block', $class)]) }}>
    {{ $slot }}
</div>
