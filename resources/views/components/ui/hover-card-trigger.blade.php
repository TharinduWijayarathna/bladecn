@props([
    'href' => null,
    'class' => '',
])

{{-- A link by default (hover cards preview a link target); without `href` it renders a focusable <span>. --}}
@if ($href !== null)
    <a href="{{ $href }}" data-slot="hover-card-trigger" :aria-describedby="open ? $id('hover-card-content') : null"
        @mouseenter="show()" @focus="show()"
        {{ $attributes->merge(['class' => cn('underline-offset-4 hover:underline', $class)]) }}>{{ $slot }}</a>
@else
    <span tabindex="0" data-slot="hover-card-trigger" :aria-describedby="open ? $id('hover-card-content') : null"
        @mouseenter="show()" @focus="show()" {{ $attributes->merge(['class' => $class ?: null]) }}>{{ $slot }}</span>
@endif
