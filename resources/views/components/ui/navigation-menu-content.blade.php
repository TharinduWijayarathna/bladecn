@props([
    'align' => 'start',
    'class' => '',
])

<div data-slot="navigation-menu-content" :data-value="value" :id="value + '-content'"
    x-show="active === value" x-effect="active === value && $nextTick(() => fit($el))" style="display: none;" @keydown="panelKey($event, value)"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    {{ $attributes->merge(['class' => cn(
        'absolute top-full z-50 mt-1.5 w-max max-w-[calc(100vw-2rem)] origin-top overflow-hidden rounded-md border bg-popover p-2 text-popover-foreground shadow',
        $align === 'end' ? 'right-0' : ($align === 'center' ? 'left-1/2 -translate-x-1/2' : 'left-0'),
        $class,
    )]) }}>
    {{ $slot }}
</div>
