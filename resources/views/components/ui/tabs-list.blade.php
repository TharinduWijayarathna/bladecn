@props([
    'class' => '',
])

@aware(['orientation' => 'horizontal'])

<div role="tablist" data-slot="tabs-list" aria-orientation="{{ $orientation }}"
    {{ $attributes->merge(['class' => cn(
        'inline-flex w-fit items-center justify-center rounded-lg bg-muted p-[3px] text-muted-foreground',
        $orientation === 'vertical' ? 'h-fit flex-col' : 'h-9',
        $class,
    )]) }}>
    {{ $slot }}
</div>
