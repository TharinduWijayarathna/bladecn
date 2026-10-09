@props([
    'class' => '',
])

@aware(['open' => false])

<div data-slot="collapsible-content" :id="$id('collapsible-content')" x-show="open"
    x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1"
    x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    @unless ($open) style="display: none;" @endunless
    {{ $attributes->merge(['class' => $class ?: null]) }}>
    {{ $slot }}
</div>
