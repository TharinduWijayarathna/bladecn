@props([
    'value',
    'title' => null,
    'class' => '',
])

@php
    $count = substr_count((string) $slot, 'data-slot="kanban-card"');
@endphp

{{-- A column of the board. `value` keys the column in the posted data and the `sorted` event; an `actions` slot sits in the header. --}}
<section data-slot="kanban-column" data-column="{{ $value }}"
    {{ $attributes->merge(['class' => cn('flex min-w-60 flex-1 shrink-0 basis-60 flex-col rounded-lg border bg-muted/40 dark:bg-muted/20', $class)]) }}>
    <header class="flex items-center gap-2 px-3 pt-3 pb-2">
        <h3 class="text-sm font-medium text-foreground">{{ $title ?? $value }}</h3>
        <span data-slot="kanban-column-count" class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-background px-1.5 text-xs tabular-nums text-muted-foreground"
            x-text="(columns[@js((string) $value)] || []).length">{{ $count }}</span>
        @isset($actions)
            <div class="ml-auto flex items-center gap-1">{{ $actions }}</div>
        @endisset
    </header>
    <ul role="list" data-slot="kanban-column-list" data-column="{{ $value }}" aria-label="{{ $title ?? $value }}" :aria-describedby="$id('kanban-help')"
        class="flex min-h-24 flex-1 flex-col gap-2 px-3 pb-3">
        {{ $slot }}
    </ul>
</section>
