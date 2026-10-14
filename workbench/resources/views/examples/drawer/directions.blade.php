<div class="flex flex-wrap gap-2">
    @foreach (['top', 'right', 'bottom', 'left'] as $direction)
        <x-ui.drawer :direction="$direction">
            <x-ui.drawer-trigger class="capitalize">{{ $direction }}</x-ui.drawer-trigger>
            <x-ui.drawer-content>
                <x-ui.drawer-header>
                    <x-ui.drawer-title>Drawer from the {{ $direction }}</x-ui.drawer-title>
                    <x-ui.drawer-description>Swipe it away, press Esc or click outside to close.</x-ui.drawer-description>
                </x-ui.drawer-header>
                <x-ui.drawer-footer>
                    <x-ui.drawer-close>Close</x-ui.drawer-close>
                </x-ui.drawer-footer>
            </x-ui.drawer-content>
        </x-ui.drawer>
    @endforeach
</div>
