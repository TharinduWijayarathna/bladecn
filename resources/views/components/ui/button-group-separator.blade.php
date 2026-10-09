@props([
    'orientation' => 'vertical',
    'class' => '',
])

<div role="separator" aria-orientation="{{ $orientation }}" data-slot="button-group-separator"
    {{ $attributes->merge(['class' => cn(
        'relative !m-0 shrink-0 self-stretch bg-input',
        $orientation === 'vertical' ? 'h-auto w-px' : 'h-px w-auto',
        $class,
    )]) }}></div>
