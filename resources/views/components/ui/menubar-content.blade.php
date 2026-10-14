@props([
    'align' => 'start',
    'class' => '',
])

<div role="menu" data-slot="menubar-content" tabindex="-1" aria-orientation="vertical" :id="value + '-content'"
    x-show="active === value" style="display: none;" @keydown="menuKey($event, value)"
    x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    {{ $attributes->merge(['class' => cn(
        'absolute top-full z-50 mt-2 min-w-[12rem] rounded-md border bg-popover p-1 text-popover-foreground shadow-md outline-none',
        $align === 'end' ? 'right-0 origin-top-right' : 'left-0 origin-top-left',
        $class,
    )]) }}>
    {{ $slot }}
</div>
