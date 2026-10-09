@props([
    'orientation' => 'vertical',
    'invalid' => false,
    'class' => '',
])

@php
    $orientations = [
        'vertical' => 'flex-col [&>*]:w-full [&>.sr-only]:w-auto',
        'horizontal' => 'flex-row items-center [&>[data-slot=field-label]]:flex-auto has-[>[data-slot=field-content]]:items-start has-[>[data-slot=field-content]]:[&>[role=checkbox],[role=radio],[role=switch],input[type=checkbox]]:mt-px',
        'responsive' => 'flex-col [&>*]:w-full [&>.sr-only]:w-auto @md/field-group:flex-row @md/field-group:items-center @md/field-group:[&>*]:w-auto @md/field-group:[&>[data-slot=field-label]]:flex-auto',
    ];
@endphp

<div role="group" data-slot="field" data-orientation="{{ $orientation }}" @if ($invalid) data-invalid="true" @endif
    {{ $attributes->merge(['class' => cn(
        'group/field flex w-full gap-3 data-[invalid=true]:text-destructive',
        $orientations[$orientation] ?? $orientations['vertical'],
        $class,
    )]) }}>
    {{ $slot }}
</div>
