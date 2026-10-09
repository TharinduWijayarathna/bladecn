@props([
    'class' => '',
])

<li data-slot="pagination-item" {{ $attributes->merge(['class' => $class ?: null]) }}>{{ $slot }}</li>
