@props([
    'href' => null,
    'tag' => null,
    'isActive' => false,
    'size' => 'default',
    'variant' => 'default',
    'tooltip' => null,
    'class' => '',
])

@php
    $sizes = [
        'default' => 'h-8 text-sm',
        'sm' => 'h-7 text-xs',
        'lg' => 'h-12 text-sm group-data-[collapsible=icon]:p-0!',
    ];
    $variants = [
        'default' => 'hover:bg-sidebar-accent hover:text-sidebar-accent-foreground',
        'outline' => 'bg-background shadow-[0_0_0_1px_var(--sidebar-border)] hover:bg-sidebar-accent hover:text-sidebar-accent-foreground hover:shadow-[0_0_0_1px_var(--sidebar-accent)]',
    ];
    $classes = cn(
        'peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-left outline-hidden ring-sidebar-ring transition-[width,height,padding] focus-visible:ring-2 active:bg-sidebar-accent active:text-sidebar-accent-foreground disabled:pointer-events-none disabled:opacity-50 aria-disabled:pointer-events-none aria-disabled:opacity-50 data-[active=true]:bg-sidebar-accent data-[active=true]:font-medium data-[active=true]:text-sidebar-accent-foreground group-data-[collapsible=icon]:size-8! group-data-[collapsible=icon]:p-2! [&>span:last-child]:truncate [&>svg]:size-4 [&>svg]:shrink-0',
        $variants[$variant] ?? $variants['default'],
        $sizes[$size] ?? $sizes['default'],
        $class,
    );
    // `tag` renders another element, e.g. `span` when the button sits inside a collapsible-trigger.
    $tag = $tag ?: ($href ? 'a' : 'button');
@endphp

<{{ $tag }} data-slot="sidebar-menu-button" data-size="{{ $size }}" data-active="{{ $isActive ? 'true' : 'false' }}"
    @if ($href) href="{{ $href }}" @if ($isActive) aria-current="page" @endif @elseif ($tag === 'button') type="button" @endif
    @if ($tooltip) x-bind:title="state === 'collapsed' && ! isMobile ? @js($tooltip) : null" @endif
    {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $tag }}>
