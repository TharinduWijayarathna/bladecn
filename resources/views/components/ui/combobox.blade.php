@props([
    'options' => [],
    'value' => null,
    'name' => null,
    'placeholder' => 'Select an option...',
    'searchPlaceholder' => 'Search...',
    'empty' => 'No results found.',
    'disabled' => false,
    'class' => '',
])

@php
    // Accept ['value' => 'Label'], a list of strings, or a list of ['value' => ..., 'label' => ...].
    $items = collect($options)->map(function ($option, $key) {
        if (is_array($option)) {
            return ['value' => (string) ($option['value'] ?? $key), 'label' => (string) ($option['label'] ?? $option['value'] ?? $key)];
        }

        return is_int($key) ? ['value' => (string) $option, 'label' => (string) $option] : ['value' => (string) $key, 'label' => (string) $option];
    })->values();
    $labels = $items->pluck('label', 'value')->all();
    $selectedLabel = $value !== null ? ($labels[(string) $value] ?? null) : null;
@endphp

{{-- A popover + command list. Works with `x-model`; with `name` the value is submitted through a hidden input. --}}
<div data-slot="combobox" class="inline-block" x-modelable="selected"
    x-data="{ selected: @js($value === null ? null : (string) $value), labels: @js($labels), placeholder: @js($placeholder) }"
    {{ $attributes->except('class') }}>
    @if ($name)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}" :value="selected ?? ''">
    @endif
    <x-ui.popover x-on:command-select="selected = selected === $event.detail.value ? null : $event.detail.value; close()">
        <x-ui.popover-trigger role="combobox" :disabled="$disabled" :class="cn('w-[200px] justify-between font-normal', $class)">
            <span class="truncate" :class="selected === null && 'text-muted-foreground'"
                x-text="selected !== null && labels[selected] !== undefined ? labels[selected] : placeholder">{{ $selectedLabel ?? $placeholder }}</span>
            <x-icons.chevrons-up-down class="ml-2 size-4 shrink-0 opacity-50" />
        </x-ui.popover-trigger>
        <x-ui.popover-content align="start" class="w-[200px] p-0">
            <x-ui.command>
                <x-ui.command-input :placeholder="$searchPlaceholder" class="h-9" />
                <x-ui.command-list>
                    <x-ui.command-empty>{{ $empty }}</x-ui.command-empty>
                    <x-ui.command-group>
                        @foreach ($items as $item)
                            <x-ui.command-item :value="$item['value']" :keywords="$item['label']">
                                {{ $item['label'] }}
                                <span class="ml-auto" :class="selected === @js($item['value']) ? 'opacity-100' : 'opacity-0'" aria-hidden="true">
                                    <x-icons.check class="size-4" />
                                </span>
                            </x-ui.command-item>
                        @endforeach
                    </x-ui.command-group>
                </x-ui.command-list>
            </x-ui.command>
        </x-ui.popover-content>
    </x-ui.popover>
</div>
