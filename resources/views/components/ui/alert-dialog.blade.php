@props([
    'open' => false,
    'class' => '',
])

<div data-slot="alert-dialog" x-data="{ open: @js((bool) $open) }" x-id="['alert-dialog-title', 'alert-dialog-description']"
    x-modelable="open" {{ $attributes->merge(['class' => $class ?: null]) }}>
    {{ $slot }}
</div>
