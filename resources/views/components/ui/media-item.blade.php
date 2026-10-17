@props([
    'title' => '',
    'description' => null,
    'images' => [],
    'href' => null,
    'action' => 'View',
    'max' => 3,
    'class' => '',
])

@php
    $images = array_values(array_filter((array) $images));
    $shown = array_slice($images, 0, max(1, (int) $max));
    $extra = count($images) - count($shown);
@endphp

{{--
    A list row with overlapping thumbnails, a title, a supporting line and an action. Use it for review queues,
    uploads or galleries. Pass `href` for a default action button, or your own buttons in the `actions` slot.
--}}
<div data-slot="media-item" {{ $attributes->merge(['class' => cn('flex items-center gap-4 rounded-lg p-3 transition-colors hover:bg-muted/50', $class)]) }}>
    @if (count($shown))
        <div data-slot="media-item-thumbnails" class="flex shrink-0 -space-x-4">
            @foreach ($shown as $image)
                <img src="{{ $image }}" alt="" loading="lazy" class="size-12 rounded-md object-cover ring-2 ring-background" />
            @endforeach
            @if ($extra > 0)
                <span class="flex size-12 items-center justify-center rounded-md bg-muted text-xs font-medium text-muted-foreground ring-2 ring-background">+{{ $extra }}</span>
            @endif
        </div>
    @endif
    <div class="grid min-w-0 flex-1 gap-0.5">
        <p class="truncate text-sm font-medium">{{ $title }}</p>
        @if (filled($description))
            <p class="truncate text-xs text-muted-foreground">{{ $description }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="flex shrink-0 items-center gap-2">{{ $actions }}</div>
    @elseif (filled($href))
        <x-ui.button tag="a" :href="$href" variant="outline" size="sm" class="shrink-0">
            {{ $action }}<span class="sr-only">: {{ $title }}</span>
        </x-ui.button>
    @endisset
</div>
