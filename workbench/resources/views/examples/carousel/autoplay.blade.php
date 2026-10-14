<x-ui.carousel loop :autoplay="3000" class="w-full max-w-xs" label="Highlights">
    <x-ui.carousel-content>
        @foreach (['Design', 'Build', 'Ship', 'Repeat'] as $word)
            <x-ui.carousel-item>
                <div class="flex aspect-video items-center justify-center rounded-xl border bg-muted/50 text-2xl font-semibold">
                    {{ $word }}
                </div>
            </x-ui.carousel-item>
        @endforeach
    </x-ui.carousel-content>
    <x-ui.carousel-dots />
</x-ui.carousel>
