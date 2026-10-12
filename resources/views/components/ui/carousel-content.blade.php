@props([
    'class' => '',
])

@aware(['orientation' => 'horizontal'])

@php $vertical = $orientation === 'vertical'; @endphp

{{--
    The viewport scrolls with scroll-snap (scrollbar hidden). It is pulled out by the slides' 1rem gutter
    (`-ml-4` / `-mt-4`, matched by `pl-4` / `pt-4` on each item), so snapping lands exactly on each slide.
--}}
<div data-slot="carousel-content" :id="$id('carousel-viewport')" tabindex="0"
    @scroll.debounce.60ms="update()"
    {{ $attributes->merge(['class' => cn(
        'outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden',
        $vertical ? '-mt-4 snap-y snap-mandatory overflow-y-auto overflow-x-hidden' : '-ml-4 snap-x snap-mandatory overflow-x-auto overflow-y-hidden',
        $class,
    )]) }}>
    <div class="{{ $vertical ? 'flex h-full flex-col' : 'flex' }}">
        {{ $slot }}
    </div>
</div>
