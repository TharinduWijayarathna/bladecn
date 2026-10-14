<x-ui.carousel class="w-full max-w-sm" label="Featured">
    <x-ui.carousel-content>
        @foreach (range(1, 6) as $n)
            <x-ui.carousel-item class="basis-1/2 lg:basis-1/3">
                <div class="p-1">
                    <x-ui.card>
                        <x-ui.card-content class="flex aspect-square items-center justify-center p-6">
                            <span class="text-3xl font-semibold">{{ $n }}</span>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>
            </x-ui.carousel-item>
        @endforeach
    </x-ui.carousel-content>
    <x-ui.carousel-previous />
    <x-ui.carousel-next />
</x-ui.carousel>
