<x-ui.popover>
    <x-ui.popover-trigger>Open popover</x-ui.popover-trigger>
    <x-ui.popover-content class="w-80">
        <div class="grid gap-4">
            <div class="space-y-2">
                <h4 class="font-medium leading-none">Dimensions</h4>
                <p class="text-sm text-muted-foreground">Set the dimensions for the layer.</p>
            </div>
            <div class="grid gap-2">
                @foreach (['width' => '100%', 'max-width' => '300px', 'height' => '25px'] as $key => $value)
                    <div class="grid grid-cols-3 items-center gap-4">
                        <x-ui.label for="popover-{{ $key }}">{{ ucfirst(str_replace('-', ' ', $key)) }}</x-ui.label>
                        <x-ui.input id="popover-{{ $key }}" value="{{ $value }}" class="col-span-2 h-8" />
                    </div>
                @endforeach
            </div>
        </div>
    </x-ui.popover-content>
</x-ui.popover>
