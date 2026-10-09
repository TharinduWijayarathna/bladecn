@props([
    'class' => '',
])

<div data-slot="field-title" {{ $attributes->merge(['class' => cn('flex w-fit items-center gap-2 text-sm font-medium leading-snug group-data-[disabled=true]/field:opacity-50', $class)]) }}>
    {{ $slot }}
</div>
