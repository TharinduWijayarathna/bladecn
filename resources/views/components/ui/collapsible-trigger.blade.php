@props([
    'variant' => null,
    'size' => 'default',
    'class' => '',
])

{{-- Renders an <x-ui.button> when `variant` is set, otherwise an unstyled <button>. --}}
@if ($variant)
    <x-ui.button type="button" :variant="$variant" :size="$size" :class="$class" data-slot="collapsible-trigger"
        x-bind:aria-controls="$id('collapsible-content')" x-bind:aria-expanded="open.toString()"
        x-bind:data-state="open ? 'open' : 'closed'" x-bind:disabled="disabled" x-on:click="open = ! open" {{ $attributes }}>
        {{ $slot }}
    </x-ui.button>
@else
    <button type="button" data-slot="collapsible-trigger" :aria-controls="$id('collapsible-content')"
        :aria-expanded="open.toString()" :data-state="open ? 'open' : 'closed'" :disabled="disabled" @click="open = ! open"
        {{ $attributes->merge(['class' => $class ?: null]) }}>
        {{ $slot }}
    </button>
@endif
