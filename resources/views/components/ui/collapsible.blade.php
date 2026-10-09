@props([
    'open' => false,
    'disabled' => false,
    'class' => '',
])

<div data-slot="collapsible" x-data="{ open: @js((bool) $open), disabled: @js((bool) $disabled) }" x-modelable="open"
    x-id="['collapsible-content']" :data-state="open ? 'open' : 'closed'" data-state="{{ $open ? 'open' : 'closed' }}"
    {{ $attributes->merge(['class' => $class ?: null]) }}>
    {{ $slot }}
</div>
