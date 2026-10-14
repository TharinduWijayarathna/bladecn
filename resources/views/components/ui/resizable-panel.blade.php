@props([
    'defaultSize' => null,
    'minSize' => 0,
    'maxSize' => 100,
    'class' => '',
])

{{-- Sizes are percentages of the group. The server renders `defaultSize` so the layout is right before Alpine starts. --}}
<div data-slot="resizable-panel" @if ($defaultSize !== null) data-default-size="{{ $defaultSize }}" @endif
    data-min-size="{{ $minSize }}" data-max-size="{{ $maxSize }}"
    style="flex: {{ $defaultSize ?? 1 }} 1 0px;"
    {{ $attributes->merge(['class' => cn('min-h-0 min-w-0 overflow-hidden', $class)]) }}>
    {{ $slot }}
</div>
