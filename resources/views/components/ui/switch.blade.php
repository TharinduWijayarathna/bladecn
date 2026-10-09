@props([
    'checked' => false,
    'name' => null,
    'value' => 'on',
    'disabled' => false,
    'size' => 'default',
    'class' => '',
])

@php
    $sizes = [
        'default' => ['h-[1.15rem] w-8', 'size-4'],
        'sm' => ['h-3.5 w-6', 'size-3'],
    ];
    [$trackSize, $thumbSize] = $sizes[$size] ?? $sizes['default'];
@endphp

{{-- role="switch" button; a hidden checkbox carries the value in forms. Works with `x-model` on the tag. --}}
<span class="relative inline-flex shrink-0" data-slot="switch-root" x-data="{ checked: @js((bool) $checked) }" x-modelable="checked"
    {{ $attributes->only(['x-model', 'x-model.boolean', 'wire:model', 'wire:model.live'])->merge([]) }}>
    @if ($name)
        <input type="checkbox" name="{{ $name }}" value="{{ $value }}" class="sr-only" tabindex="-1" aria-hidden="true"
            :checked="checked" @checked($checked) @disabled($disabled)>
    @endif
    <button type="button" role="switch" data-slot="switch" @disabled($disabled)
        aria-checked="{{ $checked ? 'true' : 'false' }}" data-state="{{ $checked ? 'checked' : 'unchecked' }}"
        :aria-checked="checked.toString()" :data-state="checked ? 'checked' : 'unchecked'"
        @click="checked = ! checked; $nextTick(() => $el.dispatchEvent(new CustomEvent('change', { bubbles: true, detail: { checked } })))"
        {{ $attributes->except(['x-model', 'x-model.boolean', 'wire:model', 'wire:model.live'])->merge(['class' => cn(
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
