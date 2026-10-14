@aware([
    'alternate' => false,
])

@props([
    'time' => null,
    'datetime' => null,
    'title' => null,
    'variant' => 'default',
    'class' => '',
])

@php
    $dots = [
        'default' => 'bg-primary ring-primary/20',
        'muted' => 'bg-muted-foreground/40 ring-muted',
        'success' => 'bg-emerald-500 ring-emerald-500/20',
        'warning' => 'bg-amber-500 ring-amber-500/20',
        'destructive' => 'bg-destructive ring-destructive/20',
    ];
    $icons = [
        'default' => 'border-primary/30 text-primary',
        'muted' => 'border-border text-muted-foreground',
        'success' => 'border-emerald-500/30 text-emerald-600 dark:text-emerald-400',
        'warning' => 'border-amber-500/30 text-amber-600 dark:text-amber-400',
        'destructive' => 'border-destructive/30 text-destructive',
    ];
    $hasIcon = isset($icon) && trim((string) $icon) !== '';
    $alternate = filter_var($alternate, FILTER_VALIDATE_BOOLEAN);
@endphp

{{-- One event. Put an icon in `<x-slot:icon>` to replace the dot; the default slot is the body. --}}
<li data-slot="timeline-item" data-variant="{{ $variant }}"
    {{ $attributes->merge(['class' => cn(
        'group relative flex gap-4 pb-8 last:pb-0',
        $alternate ? 'md:grid md:grid-cols-[1fr_auto_1fr] md:gap-x-6' : '',
        $class,
    )]) }}>
    <span aria-hidden="true" data-slot="timeline-connector"
        class="absolute left-3 top-7 -bottom-1 w-px -translate-x-1/2 bg-border group-last:hidden {{ $alternate ? 'md:left-1/2' : '' }}"></span>

    @if ($alternate && $time)
        <div class="hidden pt-0.5 text-sm text-muted-foreground md:col-start-1 md:row-start-1 md:block md:text-right md:group-even:col-start-3 md:group-even:text-left">
            <time @if ($datetime) datetime="{{ $datetime }}" @endif>{{ $time }}</time>
        </div>
    @endif

    <div data-slot="timeline-marker" class="relative z-10 flex size-6 shrink-0 items-center justify-center {{ $alternate ? 'md:col-start-2 md:row-start-1' : '' }}">
        @if ($hasIcon)
            <span class="flex size-6 items-center justify-center rounded-full border bg-background [&_svg]:size-3.5 {{ $icons[$variant] ?? $icons['default'] }}">{{ $icon }}</span>
        @else
            <span class="size-2.5 rounded-full ring-4 {{ $dots[$variant] ?? $dots['default'] }}"></span>
        @endif
    </div>

    <div data-slot="timeline-content" class="min-w-0 flex-1 {{ $alternate ? 'md:col-start-3 md:row-start-1 md:group-even:col-start-1 md:group-even:text-right' : '' }}">
        @if ($time)
            <time @if ($datetime) datetime="{{ $datetime }}" @endif class="block text-xs text-muted-foreground {{ $alternate ? 'md:hidden' : '' }}">{{ $time }}</time>
        @endif
        @if ($title)
            <h3 data-slot="timeline-title" class="text-sm font-medium leading-6 text-foreground">{{ $title }}</h3>
        @endif
        @if (trim((string) $slot) !== '')
            <div data-slot="timeline-description" class="mt-1 text-sm text-muted-foreground">{{ $slot }}</div>
        @endif
    </div>
</li>
