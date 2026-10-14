@props([
    'value',
    'label' => null,
    'class' => '',
])

@php
    $text = $label ?? trim(preg_replace('/\s+/', ' ', strip_tags((string) $slot)));
@endphp

{{-- A draggable card. `value` identifies it in the posted data and the `sorted` event. --}}
<li data-slot="kanban-card" data-value="{{ $value }}" @if ($label) data-label="{{ $label }}" @endif draggable="true"
    {{ $attributes->merge(['class' => cn(
        'group/card flex cursor-grab items-start gap-2 rounded-md border bg-card p-3 text-sm text-card-foreground shadow-xs transition-[opacity,box-shadow] hover:shadow-sm active:cursor-grabbing data-[dragging=true]:opacity-50 data-[dragging=true]:ring-2 data-[dragging=true]:ring-ring/50',
        $class,
    )]) }}>
    <button type="button" data-kanban-handle aria-label="Move {{ $text }}" @keydown="key($event)"
        class="-ml-1 inline-flex size-6 shrink-0 items-center justify-center rounded-sm text-muted-foreground hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50">
        <x-icons.grip-vertical class="size-4" aria-hidden="true" />
    </button>
    <div class="min-w-0 flex-1">{{ $slot }}</div>
</li>
