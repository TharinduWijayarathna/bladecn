@props([
    'value',
    'disabled' => false,
    'class' => '',
])

@aware(['variant' => 'default', 'size' => 'default', 'orientation' => 'horizontal'])

@php
    $variants = [
        'default' => 'bg-transparent',
        'outline' => 'border border-input bg-transparent shadow-xs hover:bg-accent hover:text-accent-foreground',
    ];
    $sizes = [
        'default' => 'h-9 min-w-9 px-2',
        'sm' => 'h-8 min-w-8 px-1.5',
        'lg' => 'h-10 min-w-10 px-2.5',
    ];
    $item = (string) $value;
@endphp

<button type="button" data-slot="toggle-group-item" data-value="{{ $item }}" @disabled($disabled)
    aria-pressed="false" data-state="off"
    :aria-pressed="isOn(@js($item)).toString()" :data-state="isOn(@js($item)) ? 'on' : 'off'"
    @click="toggle(@js($item))" @keydown="key($event)"
    {{ $attributes->merge(['class' => cn(
        'inline-flex min-w-0 flex-1 shrink-0 items-center justify-center gap-2 whitespace-nowrap rounded-none text-sm font-medium shadow-none outline-none transition-[color,box-shadow]',
        'hover:bg-muted hover:text-muted-foreground disabled:pointer-events-none disabled:opacity-50 data-[state=on]:bg-accent data-[state=on]:text-accent-foreground',
        'focus:z-10 focus-visible:z-10 focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50',
        "[&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4",
        $orientation === 'vertical'
            ? 'w-full first:rounded-t-md last:rounded-b-md data-[variant=outline]:border-t-0 data-[variant=outline]:first:border-t'
            : 'first:rounded-l-md last:rounded-r-md data-[variant=outline]:border-l-0 data-[variant=outline]:first:border-l',
        $variants[$variant] ?? $variants['default'],
        $sizes[$size] ?? $sizes['default'],
        $class,
    )]) }} data-variant="{{ $variant }}">
    {{ $slot }}
</button>
