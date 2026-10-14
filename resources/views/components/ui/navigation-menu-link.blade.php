@props([
    'href' => '#',
    'active' => false,
    'variant' => 'default',
    'class' => '',
])

{{-- `variant="trigger"` styles a top-level link like a trigger; the default suits links inside a content panel. --}}
<a href="{{ $href }}" data-slot="navigation-menu-link" data-active="{{ $active ? 'true' : 'false' }}"
    @if ($active) aria-current="page" @endif
    @if ($variant === 'trigger') @keydown="topKey($event)" @focus="close()" @endif
    {{ $attributes->merge(['class' => cn(
        $variant === 'trigger'
            ? 'inline-flex h-9 w-max items-center justify-center rounded-md bg-background px-4 py-2 text-sm font-medium transition-[color,box-shadow] outline-none hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground focus-visible:ring-[3px] focus-visible:ring-ring/50 focus-visible:outline-1 data-[active=true]:bg-accent/50 dark:bg-transparent dark:hover:bg-accent/50'
            : "flex flex-col gap-1 rounded-sm p-2 text-sm transition-all outline-none hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground focus-visible:ring-[3px] focus-visible:ring-ring/50 focus-visible:outline-1 data-[active=true]:bg-accent/50 [&_svg:not([class*='size-'])]:size-4 [&_svg:not([class*='text-'])]:text-muted-foreground",
        $class,
    )]) }}>
    {{ $slot }}
</a>
