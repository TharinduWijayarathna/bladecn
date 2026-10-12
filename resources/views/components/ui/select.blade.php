@props([
    'class' => '',
])
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

<div class="relative inline-block">
    <select {{ $attributes->merge(['class' => cn('flex h-10 w-full items-center justify-between rounded-lg border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 appearance-none pr-8', $class)]) }}>
        {{ $slot }}
    </select>
    <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 opacity-50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
</div>

@if ($fieldError)
    <x-ui.field-error :id="$fieldErrorId" class="mt-2">{{ $fieldError }}</x-ui.field-error>
@endif
