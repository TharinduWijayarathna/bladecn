<div class="grid w-full max-w-md grid-cols-3 gap-3">
    @foreach (['1/1', '4/3', '3/4'] as $ratio)
        <x-ui.aspect-ratio :ratio="$ratio" class="flex items-center justify-center rounded-md border bg-muted text-sm text-muted-foreground">
            {{ $ratio }}
        </x-ui.aspect-ratio>
    @endforeach
</div>
