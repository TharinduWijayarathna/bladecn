@props([
    'class' => '',
])

<kbd data-slot="kbd-group" {{ $attributes->merge(['class' => cn('inline-flex items-center gap-1', $class)]) }}>{{ $slot }}</kbd>
