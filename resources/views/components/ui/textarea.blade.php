@php
    // Laravel form binding: a no-op unless the tag has a `name` (see BladeCN\BladeCN\Support\FormField).
    [$attributes, $fieldError, $fieldErrorId] = \BladeCN\BladeCN\Support\FormField::bind($attributes, $model ?? null, $bind ?? null, $errorBag ?? 'default', fillValue: false);
    $fieldValue = $attributes->has('name') && ! \BladeCN\BladeCN\Support\FormField::isModelBound($attributes)
        ? \BladeCN\BladeCN\Support\FormField::value($attributes->get('name'), $model ?? null)
        : null;
@endphp
<textarea {{ $attributes->merge(['class' => $textareaClasses()]) }}>@if ($fieldValue !== null){{ \BladeCN\BladeCN\Support\FormField::toString($fieldValue) }}@else{{ $slot }}@endif</textarea>
@if ($fieldError)
    <x-ui.field-error :id="$fieldErrorId" class="mt-2">{{ $fieldError }}</x-ui.field-error>
@endif
