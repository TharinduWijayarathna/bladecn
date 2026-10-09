@foreach (['top', 'right', 'bottom', 'left'] as $side)
    <x-ui.tooltip :side="$side" :side-offset="8" content="Tooltip on {{ $side }}">
        <x-ui.button variant="outline" class="capitalize">{{ $side }}</x-ui.button>
    </x-ui.tooltip>
@endforeach
