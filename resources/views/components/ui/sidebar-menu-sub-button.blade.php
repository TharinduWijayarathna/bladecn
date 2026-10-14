@props([
    'href' => null,
    'isActive' => false,
    'size' => 'md',
    'class' => '',
])

@php
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }} data-slot="sidebar-menu-sub-button" data-size="{{ $size }}" data-active="{{ $isActive ? 'true' : 'false' }}"
    @if ($href) href="{{ $href }}" @if ($isActive) aria-current="page" @endif @else type="button" @endif
    {{ $attributes->merge(['class' => cn(
        'flex h-7 w-full min-w-0 -translate-x-px items-center gap-2 overflow-hidden rounded-md px-2 text-left text-sidebar-foreground outline-hidden ring-sidebar-ring hover:bg-sidebar-accent hover:text-sidebar-accent-foreground focus-visible:ring-2 active:bg-sidebar-accent active:text-sidebar-accent-foreground disabled:pointer-events-none disabled:opacity-50 aria-disabled:pointer-events-none aria-disabled:opacity-50 data-[active=true]:bg-sidebar-accent data-[active=true]:text-sidebar-accent-foreground group-data-[collapsible=icon]:hidden [&>span:last-child]:truncate [&>svg]:size-4 [&>svg]:shrink-0',
        $size === 'sm' ? 'text-xs' : 'text-sm',
        $class,
    )]) }}>
    {{ $slot }}
</{{ $tag }}>
