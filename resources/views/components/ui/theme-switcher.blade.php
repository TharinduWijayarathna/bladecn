@props([
    'value' => session('appearance', 'system'),
    'name' => null,
    'iconOnly' => false,
    'size' => 'default',
    'label' => 'Theme',
    'class' => '',
])

@php
    $value = in_array($value, ['light', 'dark', 'system'], true) ? $value : '';
    $iconOnly = filter_var($iconOnly, FILTER_VALIDATE_BOOLEAN);
    $modes = ['light' => ['Light', 'icons.sun'], 'dark' => ['Dark', 'icons.moon'], 'system' => ['System', 'icons.monitor']];
    $sizes = ['sm' => 'h-7 px-2 text-xs', 'default' => 'h-8 px-3 text-sm'];
    $itemSize = $sizes[$size] ?? $sizes['default'];
@endphp

{{--
    A light / dark / system switcher. Stores the choice as `appearance` in localStorage and a cookie (the keys
    the app layout reads), toggles `.dark` on <html>, follows the OS in `system` mode and keeps every switcher
    on the page in sync. `name` adds a hidden input for forms; dispatches a bubbling `change` with `{ value }`.
--}}
<div role="radiogroup" aria-label="{{ $label }}" data-slot="theme-switcher" x-modelable="mode"
    x-data="{
        mode: 'system',
        init() {
            const server = @js($value);
            let stored = null;
            try { stored = localStorage.getItem('appearance') } catch (e) {}
            this.mode = server || stored || 'system';
            if (server) this.store(server);
            this.apply();
            window.matchMedia?.('(prefers-color-scheme: dark)').addEventListener('change', () => this.mode === 'system' && this.apply());
        },
        store(mode) {
            try { localStorage.setItem('appearance', mode) } catch (e) {}
            document.cookie = `appearance=${mode};path=/;max-age=${365 * 24 * 60 * 60};SameSite=Lax`;
        },
        apply() {
            const dark = this.mode === 'dark' || (this.mode === 'system' && window.matchMedia?.('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', !! dark);
        },
        select(mode) {
            if (mode === this.mode) return;
            this.mode = mode;
            this.store(mode);
            this.apply();
            window.dispatchEvent(new CustomEvent('appearance-changed', { detail: { value: mode, source: this.$el } }));
            this.$nextTick(() => this.$el.dispatchEvent(new CustomEvent('change', { bubbles: true, detail: { value: mode } })));
        },
        move(event, step) {
            const modes = ['light', 'dark', 'system'];
            const next = modes[(modes.indexOf(this.mode) + step + modes.length) % modes.length];
            this.select(next);
            this.$nextTick(() => this.$el.querySelector(`[data-value=${next}]`)?.focus());
        },
    }"
    x-on:appearance-changed.window="$event.detail.source !== $el && (mode = $event.detail.value)"
    x-on:keydown.right.prevent="move($event, 1)" x-on:keydown.down.prevent="move($event, 1)"
    x-on:keydown.left.prevent="move($event, -1)" x-on:keydown.up.prevent="move($event, -1)"
    {{ $attributes->merge(['class' => cn('inline-flex items-center gap-0.5 rounded-lg bg-muted p-0.5 text-muted-foreground', $class)]) }}>
    @foreach ($modes as $mode => [$text, $icon])
        <button type="button" role="radio" data-value="{{ $mode }}"
            aria-checked="{{ $value === $mode ? 'true' : 'false' }}" :aria-checked="(mode === '{{ $mode }}').toString()"
            tabindex="{{ $value === $mode ? '0' : '-1' }}" :tabindex="mode === '{{ $mode }}' ? 0 : -1"
            x-on:click="select('{{ $mode }}')"
            @if ($iconOnly) aria-label="{{ $text }}" title="{{ $text }}" @endif
            class="{{ cn('inline-flex items-center justify-center gap-1.5 rounded-md font-medium transition-colors outline-none hover:text-foreground focus-visible:ring-[3px] focus-visible:ring-ring/50 aria-checked:bg-background aria-checked:text-foreground aria-checked:shadow-xs dark:aria-checked:bg-input/50 [&_svg]:size-4 [&_svg]:shrink-0', $itemSize, $iconOnly ? 'aspect-square px-0' : '') }}">
            <x-dynamic-component :component="$icon" aria-hidden="true" />
            @unless ($iconOnly)<span>{{ $text }}</span>@endunless
        </button>
    @endforeach
    @if ($name)
        <input type="hidden" name="{{ $name }}" value="{{ $value ?: 'system' }}" :value="mode" />
    @endif
</div>
