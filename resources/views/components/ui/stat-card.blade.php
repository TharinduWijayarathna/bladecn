@props([
    'title' => '',
    'value' => '',
    'change' => null,
    'trend' => null,
    'description' => null,
    'class' => '',
])

@php
    $trend = in_array($trend, ['up', 'down'], true) ? $trend : null;
@endphp

{{--
    A KPI card: a label, a big value, an optional change with a trend arrow and a supporting line.
    Pass an `icon` slot for a muted icon in the corner.
--}}
<x-ui.card data-slot="stat-card" {{ $attributes->merge(['class' => cn('gap-3 py-5', $class)]) }}>
    <x-ui.card-header class="px-5">
        <x-ui.card-description class="text-sm font-medium">{{ $title }}</x-ui.card-description>
        @isset($icon)
            <x-ui.card-action>
                <span class="flex size-8 items-center justify-center rounded-md bg-muted text-muted-foreground [&_svg]:size-4" aria-hidden="true">{{ $icon }}</span>
            </x-ui.card-action>
        @endisset
    </x-ui.card-header>
    <x-ui.card-content class="flex flex-col gap-1 px-5">
        <span data-slot="stat-card-value" class="text-2xl font-semibold tracking-tight tabular-nums break-words">{{ $value }}</span>
        @if (filled($change) || filled($description))
            <p class="flex flex-wrap items-center gap-x-1.5 text-xs text-muted-foreground">
                @if (filled($change))
                    <span data-slot="stat-card-change" data-trend="{{ $trend ?? 'neutral' }}" class="inline-flex items-center gap-1 font-medium text-foreground">
                        @if ($trend === 'up')
                            <x-icons.trending-up class="size-3.5" aria-hidden="true" /><span class="sr-only">Up</span>
                        @elseif ($trend === 'down')
                            <x-icons.trending-down class="size-3.5" aria-hidden="true" /><span class="sr-only">Down</span>
                        @endif
                        {{ $change }}
                    </span>
                @endif
                @if (filled($description))
                    <span>{{ $description }}</span>
                @endif
            </p>
        @endif
    </x-ui.card-content>
</x-ui.card>
