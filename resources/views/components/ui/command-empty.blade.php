@props([
    'class' => '',
])

<div data-slot="command-empty" role="presentation" hidden {{ $attributes->merge(['class' => cn('py-6 text-center text-sm', $class)]) }}>
    {{ $slot }}
</div>
