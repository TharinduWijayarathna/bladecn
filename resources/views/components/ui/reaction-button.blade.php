@props([
    'pressed' => false,
    'count' => null,
    'icon' => 'heart',
    'label' => 'Like',
    'name' => null,
    'disabled' => false,
    'class' => '',
])

@php
    $pressed = filter_var($pressed, FILTER_VALIDATE_BOOLEAN);
    $disabled = filter_var($disabled, FILTER_VALIDATE_BOOLEAN);
    $count = is_numeric($count) ? (int) $count : null;
    $icons = ['heart' => 'icons.heart', 'star' => 'icons.star', 'thumbs-up' => 'icons.thumbs-up'];
    $iconComponent = $icons[$icon] ?? $icons['heart'];
@endphp

{{--
    A toggle for likes, favourites or upvotes, with an optional count. It is a real `aria-pressed` button:
    clicking flips the state and adjusts the count. Submits `1`/`0` through `name`, works with `x-model`
    (boolean) and dispatches a bubbling `change` event with `{ pressed, count }`.
--}}
<button type="button" data-slot="reaction-button" x-modelable="pressed"
    x-data="{
        pressed: @js($pressed),
        count: @js($count),
        toggle() {
            this.pressed = ! this.pressed;
            if (this.count !== null) this.count += this.pressed ? 1 : -1;
            this.$nextTick(() => this.$el.dispatchEvent(new CustomEvent('change', { bubbles: true, detail: { pressed: this.pressed, count: this.count } })));
        },
    }"
    x-on:click="toggle()" :aria-pressed="pressed.toString()" aria-pressed="{{ $pressed ? 'true' : 'false' }}"
    @disabled($disabled)
    {{ $attributes->merge(['class' => cn(
        'group/reaction inline-flex h-8 shrink-0 items-center justify-center gap-1.5 rounded-md border bg-background px-2.5 text-sm font-medium text-muted-foreground shadow-xs transition-colors outline-none hover:bg-accent hover:text-accent-foreground focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50 aria-pressed:border-primary/30 aria-pressed:bg-primary/5 aria-pressed:text-foreground [&_svg]:size-4 [&_svg]:shrink-0',
        $class,
    )]) }}>
    <x-dynamic-component :component="$iconComponent" class="transition-transform group-aria-pressed/reaction:scale-110 group-aria-pressed/reaction:fill-current group-aria-pressed/reaction:text-primary" aria-hidden="true" />
    <span class="{{ $count !== null ? 'sr-only' : '' }}">{{ trim($slot) !== '' ? $slot : $label }}</span>
    @if ($count !== null)
        <span data-slot="reaction-button-count" class="tabular-nums" x-text="count">{{ $count }}</span>
    @endif
    @if ($name)
        <input type="hidden" name="{{ $name }}" value="{{ $pressed ? 1 : 0 }}" :value="pressed ? 1 : 0" />
    @endif
</button>
