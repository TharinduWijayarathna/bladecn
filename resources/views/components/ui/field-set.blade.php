@props([
    'class' => '',
])

<fieldset data-slot="field-set" {{ $attributes->merge(['class' => cn('flex flex-col gap-6 has-[>[data-slot=checkbox-group]]:gap-3 has-[>[data-slot=radio-group]]:gap-3', $class)]) }}>
    {{ $slot }}
</fieldset>
