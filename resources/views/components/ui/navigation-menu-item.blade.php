@props([
    'value' => null,
    'class' => '',
])

@php $value ??= 'item-'.\Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(8)); @endphp

<li data-slot="navigation-menu-item" x-data="{ value: @js((string) $value) }"
    @mouseenter="$el.querySelector('[data-slot=navigation-menu-trigger]') ? open(value, delay) : close(closeDelay)"
    {{ $attributes->merge(['class' => cn('relative', $class)]) }}>
    {{ $slot }}
</li>
