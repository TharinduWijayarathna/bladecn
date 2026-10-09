<div x-data="{ size: 'm' }" class="flex flex-col items-center gap-3">
    <x-ui.toggle-group type="single" variant="outline" value="m" name="size" x-model="size" aria-label="Size">
        @foreach (['s' => 'S', 'm' => 'M', 'l' => 'L', 'xl' => 'XL'] as $value => $label)
            <x-ui.toggle-group-item :value="$value">{{ $label }}</x-ui.toggle-group-item>
        @endforeach
    </x-ui.toggle-group>
    <p class="text-sm text-muted-foreground">size = <code x-text="size ?? 'null'"></code></p>
</div>
