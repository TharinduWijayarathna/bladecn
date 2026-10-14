@props([
    'variant' => 'outline',
    'size' => 'icon-sm',
    'class' => '',
])

@aware(['orientation' => 'horizontal', 'loop' => false])

<x-ui.button type="button" :variant="$variant" :size="$size" data-slot="carousel-previous"
    :class="cn(
        'absolute rounded-full!',
        $orientation === 'vertical' ? '-top-12 left-1/2 -translate-x-1/2 rotate-90' : 'top-1/2 -left-12 -translate-y-1/2',
        $class,
    )"
    :disabled="! $loop" x-bind:disabled="! canPrev" x-on:click="prev()" x-bind:aria-controls="$id('carousel-viewport')" {{ $attributes }}>
    <x-icons.chevron-left />
    <span class="sr-only">Previous slide</span>
</x-ui.button>
