@props([
    'variant' => 'outline',
    'size' => 'icon-sm',
    'class' => '',
])

@aware(['orientation' => 'horizontal'])

<x-ui.button type="button" :variant="$variant" :size="$size" data-slot="carousel-next"
    :class="cn(
        'absolute rounded-full!',
        $orientation === 'vertical' ? '-bottom-12 left-1/2 -translate-x-1/2 rotate-90' : 'top-1/2 -right-12 -translate-y-1/2',
        $class,
    )"
    x-bind:disabled="! canNext" x-on:click="next()" x-bind:aria-controls="$id('carousel-viewport')" {{ $attributes }}>
    <x-icons.chevron-right />
    <span class="sr-only">Next slide</span>
</x-ui.button>
