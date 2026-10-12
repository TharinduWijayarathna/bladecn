@props([
    'value' => null,
    'class' => '',
])

<div role="group" data-slot="menubar-radio-group" x-data="{ selected: @js($value) }" x-modelable="selected"
    {{ $attributes->merge(['class' => $class ?: null]) }}>
    {{ $slot }}
</div>
