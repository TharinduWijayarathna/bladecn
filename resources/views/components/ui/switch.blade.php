@props([
    'checked' => false,
    'name' => null,
    'value' => 'on',
    'disabled' => false,
    'size' => 'default',
    'class' => '',
    'model' => null,
    'bind' => null,
    'errorBag' => 'default',
])

@php
    $sizes = [
        'default' => ['h-[1.15rem] w-8', 'size-4'],
        'sm' => ['h-3.5 w-6', 'size-3'],
    ];
    [$trackSize, $thumbSize] = $sizes[$size] ?? $sizes['default'];

    // Laravel form binding (see BladeCN\BladeCN\Support\FormField): old input, then `model`, then `checked`.
    $modelAttributes = $attributes->filter(fn ($value, $key) => str_starts_with($key, 'wire:model') || str_starts_with($key, 'x-model') || str_starts_with($key, 'wire:ignore'));
    if ($name && ! \BladeCN\BladeCN\Support\FormField::isModelBound($attributes)) {
        $checked = \BladeCN\BladeCN\Support\FormField::checked($name, $model, \BladeCN\BladeCN\Support\FormField::toString($value), (bool) $checked, $bind === true);
    }
    [, $fieldError, $fieldErrorId] = \BladeCN\BladeCN\Support\FormField::withError(new \Illuminate\View\ComponentAttributeBag, (string) \BladeCN\BladeCN\Support\FormField::key($name), $name && ($model !== null || $bind === true), $errorBag, $bind);
    $name = \BladeCN\BladeCN\Support\FormField::htmlName($name);
    // With wire:model: wire:ignore + input/change events on the root (see BladeCN\BladeCN\Support\Livewire). No-op otherwise.
    $rootAttributes = \BladeCN\BladeCN\Support\Livewire::attributes($modelAttributes, 'checked');
@endphp

{{-- role="switch" button; a hidden checkbox carries the value in forms. Works with `x-model` on the tag.
     Any `wire:model*` / `x-model*` (with modifiers) binds to the root through x-modelable. --}}
<span class="relative inline-flex shrink-0" data-slot="switch-root" x-data="{ checked: @js((bool) $checked) }" x-modelable="checked"
    {{ $rootAttributes->merge([]) }}>
    @if ($name)
        <input type="checkbox" name="{{ $name }}" value="{{ $value }}" class="sr-only" tabindex="-1" aria-hidden="true"
            :checked="checked" @checked($checked) @disabled($disabled)>
    @endif
    <button type="button" role="switch" data-slot="switch" @disabled($disabled)
        aria-checked="{{ $checked ? 'true' : 'false' }}" data-state="{{ $checked ? 'checked' : 'unchecked' }}"
        :aria-checked="checked.toString()" :data-state="checked ? 'checked' : 'unchecked'"
        @click="checked = ! checked; $nextTick(() => $el.dispatchEvent(new CustomEvent('change', { bubbles: true, detail: { checked } })))"
        @if ($fieldError)aria-invalid="true" aria-describedby="{{ $fieldErrorId }}" @endif{{ $attributes->filter(fn ($value, $key) => ! $modelAttributes->has($key))->merge(['class' => cn(
            'peer inline-flex shrink-0 items-center rounded-full border border-transparent shadow-xs outline-none transition-all',
            'focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:cursor-not-allowed disabled:opacity-50',
            'data-[state=checked]:bg-primary data-[state=unchecked]:bg-input dark:data-[state=unchecked]:bg-input/80',
            $trackSize,
            $class,
        )]) }}>
        <span data-slot="switch-thumb" data-state="{{ $checked ? 'checked' : 'unchecked' }}" :data-state="checked ? 'checked' : 'unchecked'"
            class="pointer-events-none block rounded-full bg-background ring-0 transition-transform data-[state=checked]:translate-x-[calc(100%-2px)] data-[state=unchecked]:translate-x-0 dark:data-[state=checked]:bg-primary-foreground dark:data-[state=unchecked]:bg-foreground {{ $thumbSize }}"></span>
    </button>
</span>
@if ($fieldError)
    <x-ui.field-error :id="$fieldErrorId" class="mt-2">{{ $fieldError }}</x-ui.field-error>
@endif
