<x-ui.scroll-area orientation="horizontal" class="w-96 whitespace-nowrap rounded-md border">
    <div class="flex w-max gap-4 p-4">
        @foreach (['Ornella Binni', 'Tom Byrom', 'Vladimir Malyavko', 'Jake Hills', 'Pawel Czerwinski'] as $artist)
            <figure class="shrink-0">
                <div class="flex h-40 w-32 items-center justify-center rounded-md bg-muted text-xs text-muted-foreground">{{ $loop->iteration }}</div>
                <figcaption class="pt-2 text-xs text-muted-foreground">Photo by <span class="font-semibold text-foreground">{{ $artist }}</span></figcaption>
            </figure>
        @endforeach
    </div>
</x-ui.scroll-area>
