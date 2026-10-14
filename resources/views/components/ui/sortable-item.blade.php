@aware([
    'name' => null,
    'handle' => false,
])

@props([
    'value',
    'label' => null,
    'class' => '',
])

@php
    $text = $label ?? trim(strip_tags((string) $slot));
@endphp

{{-- One reorderable row. `value` identifies it in the posted order and the `sorted` event. --}}
<li data-slot="sortable-item" data-value="{{ $value }}" @if ($label) data-label="{{ $label }}" @endif draggable="{{ $handle ? 'false' : 'true' }}"
    {{ $attributes->merge(['class' => cn(
        'flex items-center gap-2 rounded-md border bg-card px-3 py-2 text-sm text-card-foreground shadow-xs transition-[opacity,box-shadow] data-[dragging=true]:opacity-50 data-[dragging=true]:ring-2 data-[dragging=true]:ring-ring/50',
        $handle ? '' : 'cursor-grab active:cursor-grabbing',
        $class,
    )]) }}>
    <button type="button" data-sortable-handle aria-label="Reorder {{ $text }}" @keydown="key($event)" @pointerdown="arm($event)" @pointerup="disarm($event)"
        class="-ml-1 inline-flex size-6 shrink-0 cursor-grab items-center justify-center rounded-sm text-muted-foreground hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 active:cursor-grabbing">
        <x-icons.grip-vertical class="size-4" aria-hidden="true" />
    </button>
    <div class="min-w-0 flex-1">{{ $slot }}</div>
    @if ($name)
        <input type="hidden" name="{{ $name }}[]" value="{{ $value }}">
    @endif
</li>
