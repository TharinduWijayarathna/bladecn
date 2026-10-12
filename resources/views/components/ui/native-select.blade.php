@php
    // Laravel form binding: a no-op unless the tag has a `name` (see BladeCN\BladeCN\Support\FormField).
    [$attributes, $fieldError, $fieldErrorId] = \BladeCN\BladeCN\Support\FormField::bind($attributes, $model ?? null, $bind ?? null, $errorBag ?? 'default', fillValue: false);
    $fieldValue = $attributes->has('name') && ! \BladeCN\BladeCN\Support\FormField::isModelBound($attributes)
        ? \BladeCN\BladeCN\Support\FormField::value($attributes->get('name'), $model ?? null, $attributes->get('value'))
        : null;
    if ($fieldValue !== null) {
        // A <select> has no value attribute: mark the matching <option>(s) as selected instead.
        $slot = new \Illuminate\Support\HtmlString(\BladeCN\BladeCN\Support\FormField::selectOptions((string) $slot, $fieldValue));
        $attributes = $attributes->except('value');
    }
@endphp
<div class="group/native-select relative w-fit has-[select:disabled]:opacity-50">
    <select {{ $attributes->merge(['class' => $nativeSelectClasses()]) }}>
        {{ $slot }}
    </select>
    <x-icons.chevron-down class="text-muted-foreground pointer-events-none absolute top-1/2 right-3.5 size-4 -translate-y-1/2 opacity-50 select-none" />
</div>
@if ($fieldError)
    <x-ui.field-error :id="$fieldErrorId" class="mt-2">{{ $fieldError }}</x-ui.field-error>
@endif
