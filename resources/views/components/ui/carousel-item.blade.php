@props([
    'class' => '',
])

@aware(['orientation' => 'horizontal'])

<div role="group" aria-roledescription="slide" data-slot="carousel-item"
    {{ $attributes->merge(['class' => cn(
        'min-w-0 shrink-0 grow-0 basis-full snap-start',
        $orientation === 'vertical' ? 'pt-4' : 'pl-4',
        $class,
    )]) }}>
    {{ $slot }}
</div>
