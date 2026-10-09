@foreach (['top', 'right', 'bottom', 'left'] as $side)
    <x-ui.sheet :side="$side">
        <x-slot:trigger>
            <x-ui.button variant="outline" class="capitalize">{{ $side }}</x-ui.button>
        </x-slot:trigger>

        <x-ui.sheet-header>
            <x-ui.sheet-title>Sheet from the {{ $side }}</x-ui.sheet-title>
            <x-ui.sheet-description>Click outside or press Esc to close.</x-ui.sheet-description>
        </x-ui.sheet-header>
    </x-ui.sheet>
@endforeach
