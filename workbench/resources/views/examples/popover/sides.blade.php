@foreach (['top', 'right', 'bottom', 'left'] as $side)
    <x-ui.popover>
        <x-ui.popover-trigger class="capitalize">{{ $side }}</x-ui.popover-trigger>
        <x-ui.popover-content :side="$side" class="w-40 text-sm">Popover on the {{ $side }}.</x-ui.popover-content>
    </x-ui.popover>
@endforeach
