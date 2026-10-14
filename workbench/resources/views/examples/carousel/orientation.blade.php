<x-ui.carousel orientation="vertical" class="my-12 w-full max-w-xs">
    <x-ui.carousel-content class="h-[200px]">
        @foreach (range(1, 5) as $n)
            <x-ui.carousel-item class="basis-1/2">
                <div class="p-1">
                    <x-ui.card>
                        <x-ui.card-content class="flex items-center justify-center p-6">
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
