@props([
    'href' => null,
    'variant' => 'default',
    'inset' => false,
    'disabled' => false,
    'class' => '',
])

@php
    $classes = cn(
        'relative flex w-full cursor-default select-none items-center gap-2 rounded-sm px-2 py-1.5 text-left text-sm outline-hidden',
        'focus:bg-accent focus:text-accent-foreground aria-disabled:pointer-events-none aria-disabled:opacity-50',
        $variant === 'destructive' ? 'text-destructive focus:bg-destructive/10 focus:text-destructive dark:focus:bg-destructive/20 [&_svg]:!text-destructive' : null,
        $inset ? 'pl-8' : null,
        "[&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4 [&_svg:not([class*='text-'])]:text-muted-foreground",
        $class,
    );
@endphp

{{-- Activating an item closes the menu; add your own `x-on:click` / `@click` for the action. --}}
@if ($href && ! $disabled)
    <a href="{{ $href }}" role="menuitem" tabindex="-1" data-slot="context-menu-item" data-variant="{{ $variant }}"
        @mousemove="$el.focus()" @click="close()" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="button" role="menuitem" tabindex="-1" data-slot="context-menu-item" data-variant="{{ $variant }}"
        @if ($disabled) aria-disabled="true" @endif
        @mousemove="$el.focus()" @click="close()" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
