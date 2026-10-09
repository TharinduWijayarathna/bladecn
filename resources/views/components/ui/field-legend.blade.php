@props([
    'variant' => 'legend',
    'class' => '',
])

<legend data-slot="field-legend" data-variant="{{ $variant }}"
    {{ $attributes->merge(['class' => cn('mb-3 font-medium', $variant === 'label' ? 'text-sm' : 'text-base', $class)]) }}>
    {{ $slot }}
</legend>
