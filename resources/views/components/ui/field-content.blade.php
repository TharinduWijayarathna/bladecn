@props([
    'class' => '',
])

<div data-slot="field-content" {{ $attributes->merge(['class' => cn('group/field-content flex flex-1 flex-col gap-1.5 leading-snug', $class)]) }}>
    {{ $slot }}
</div>
