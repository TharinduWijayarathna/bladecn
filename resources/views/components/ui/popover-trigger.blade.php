@props([
    'variant' => 'outline',
    'size' => 'default',
    'class' => '',
])

{{-- Renders an <x-ui.button>; pass `variant=""` for an unstyled <button>. --}}
@if ($variant)
    <x-ui.button type="button" :variant="$variant" :size="$size" :class="$class" data-slot="popover-trigger" x-ref="popoverTrigger"
        aria-haspopup="dialog" x-bind:aria-controls="$id('popover-content')" x-bind:aria-expanded="open.toString()"
        x-bind:data-state="open ? 'open' : 'closed'" x-on:click="toggle()" {{ $attributes }}>
        {{ $slot }}
    </x-ui.button>
@else
    <button type="button" data-slot="popover-trigger" x-ref="popoverTrigger" aria-haspopup="dialog"
        :aria-controls="$id('popover-content')" :aria-expanded="open.toString()" :data-state="open ? 'open' : 'closed'"
        @click="toggle()" {{ $attributes->merge(['class' => $class ?: null]) }}>
        {{ $slot }}
    </button>
@endif
