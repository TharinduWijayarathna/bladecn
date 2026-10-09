@props([
    'class' => '',
])

<div role="menu" data-slot="context-menu-content" x-ref="menu" tabindex="-1" :id="$id('context-menu')" aria-orientation="vertical"
    x-show="open" x-cloak @keydown="key($event)" @click.outside="close(false)" @contextmenu.outside="close(false)"
    x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    {{ $attributes->merge(['class' => cn(
        'fixed z-50 min-w-[8rem] origin-top-left overflow-y-auto overflow-x-hidden rounded-md border bg-popover p-1 text-popover-foreground shadow-md outline-none',
        $class,
    )]) }}>
    {{ $slot }}
</div>
