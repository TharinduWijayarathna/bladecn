@props([
    'value' => null,
    'class' => '',
])

@php $value ??= 'menu-'.\Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(8)); @endphp

<div data-slot="menubar-menu" data-value="{{ $value }}" x-data="{ value: @js((string) $value) }"
    {{ $attributes->merge(['class' => cn('relative', $class)]) }}>
    {{ $slot }}
</div>
