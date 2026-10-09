@props([
    'value' => null,
    'keywords' => null,
    'href' => null,
    'disabled' => false,
    'class' => '',
])

<div role="option" data-slot="command-item" aria-selected="false" tabindex="-1"
    data-value="{{ $value ?? trim(preg_replace('/\s+/', ' ', strip_tags((string) $slot))) }}" data-keywords="{{ is_array($keywords) ? implode(' ', $keywords) : $keywords }}"
    @if ($href) data-href="{{ $href }}" @endif data-disabled="{{ $disabled ? 'true' : 'false' }}" @if ($disabled) aria-disabled="true" @endif
    @click="select($el)" @mousemove="! $el.hasAttribute('data-selected') && highlight($el, false)"
    {{ $attributes->merge(['class' => cn(
        'relative flex cursor-default select-none items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-hidden',
        'data-[selected]:bg-accent data-[selected]:text-accent-foreground data-[disabled=true]:pointer-events-none data-[disabled=true]:opacity-50',
        "[&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4 [&_svg:not([class*='text-'])]:text-muted-foreground",
        $class,
    )]) }}>
    {{ $slot }}
</div>
