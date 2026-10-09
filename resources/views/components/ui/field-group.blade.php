@props([
    'class' => '',
])

<div data-slot="field-group" {{ $attributes->merge(['class' => cn('group/field-group @container/field-group flex w-full flex-col gap-7 data-[slot=checkbox-group]:gap-3 [&>[data-slot=field-group]]:gap-4', $class)]) }}>
    {{ $slot }}
</div>
