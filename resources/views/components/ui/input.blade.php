@php
    // Laravel form binding: a no-op unless the tag has a `name` (see BladeCN\BladeCN\Support\FormField).
    [$attributes, $fieldError, $fieldErrorId] = \BladeCN\BladeCN\Support\FormField::bind($attributes, $model ?? null, $bind ?? null, $errorBag ?? 'default', type: $type);
@endphp
<input type="{{ $type }}" {{ $attributes->merge(['class' => $inputClasses()]) }} data-slot="input" />
@if ($fieldError)
    <x-ui.field-error :id="$fieldErrorId" class="mt-2">{{ $fieldError }}</x-ui.field-error>
@endif
