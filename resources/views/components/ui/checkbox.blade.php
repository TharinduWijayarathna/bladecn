@php
    // Laravel form binding: a no-op unless the tag has a `name` (see BladeCN\BladeCN\Support\FormField).
    [$attributes, $fieldError, $fieldErrorId] = \BladeCN\BladeCN\Support\FormField::bind($attributes, $model ?? null, $bind ?? null, $errorBag ?? 'default', fillValue: false);
    if ($attributes->has('name') && ! \BladeCN\BladeCN\Support\FormField::isModelBound($attributes)) {
        $checked = \BladeCN\BladeCN\Support\FormField::checked($attributes->get('name'), $model ?? null, \BladeCN\BladeCN\Support\FormField::toString($attributes->get('value')), $checked, ($bind ?? null) === true);
    }
@endphp
<input type="checkbox" {{ $checked ? 'checked' : '' }}
    {{ $attributes->merge(['class' => 'size-4 shrink-0 rounded-[4px] border border-input accent-primary shadow-xs outline-none transition-shadow focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:cursor-not-allowed disabled:opacity-50 aria-invalid:ring-destructive/20']) }} />

@if ($fieldError)
    <x-ui.field-error :id="$fieldErrorId" class="mt-2">{{ $fieldError }}</x-ui.field-error>
@endif
