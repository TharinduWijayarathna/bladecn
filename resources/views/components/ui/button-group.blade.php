@props([
    'orientation' => 'horizontal',
    'class' => '',
])

@php
    $orientations = [
        'horizontal' => '[&>*:not(:first-child)]:rounded-l-none [&>*:not(:first-child)]:border-l-0 [&>*:not(:last-child)]:rounded-r-none',
        'vertical' => 'flex-col [&>*:not(:first-child)]:rounded-t-none [&>*:not(:first-child)]:border-t-0 [&>*:not(:last-child)]:rounded-b-none',
    ];
@endphp

<div role="group" data-slot="button-group" data-orientation="{{ $orientation }}"
    {{ $attributes->merge(['class' => cn(
        'flex w-fit items-stretch has-[>[data-slot=button-group]]:gap-2 [&>*]:focus-visible:relative [&>*]:focus-visible:z-10 [&>input]:flex-1',
        $orientations[$orientation] ?? $orientations['horizontal'],
        $class,
    )]) }}>
    {{ $slot }}
</div>
