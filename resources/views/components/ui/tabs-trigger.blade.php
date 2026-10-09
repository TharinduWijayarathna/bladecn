@props([
    'value',
    'disabled' => false,
    'class' => '',
])

@aware(['defaultValue' => null])

@php $selected = (string) $defaultValue === (string) $value; @endphp

<button type="button" role="tab" data-slot="tabs-trigger" data-value="{{ $value }}" @disabled($disabled)
    :id="$id('tabs') + '-trigger-' + @js((string) $value)"
    :aria-controls="$id('tabs') + '-content-' + @js((string) $value)"
    :aria-selected="(tab === @js((string) $value)).toString()" :tabindex="tab === @js((string) $value) ? 0 : -1"
    :data-state="tab === @js((string) $value) ? 'active' : 'inactive'"
    aria-selected="{{ $selected ? 'true' : 'false' }}" data-state="{{ $selected ? 'active' : 'inactive' }}"
    tabindex="{{ $selected ? 0 : -1 }}"
    @click="tab = @js((string) $value)" @focus="automatic && (tab = @js((string) $value))" @keydown="key($event)"
    {{ $attributes->merge(['class' => cn(
        'inline-flex h-[calc(100%-1px)] flex-1 items-center justify-center gap-1.5 whitespace-nowrap rounded-md border border-transparent px-2 py-1 text-sm font-medium text-foreground transition-[color,box-shadow] dark:text-muted-foreground',
        'focus-visible:border-ring focus-visible:outline-1 focus-visible:outline-ring focus-visible:ring-[3px] focus-visible:ring-ring/50',
        'disabled:pointer-events-none disabled:opacity-50',
        'data-[state=active]:bg-background data-[state=active]:shadow-sm dark:data-[state=active]:border-input dark:data-[state=active]:bg-input/30 dark:data-[state=active]:text-foreground',
        "[&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4",
        $class,
    )]) }}>
    {{ $slot }}
</button>
