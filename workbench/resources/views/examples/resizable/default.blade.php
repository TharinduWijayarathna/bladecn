<x-ui.resizable-panel-group class="max-w-md rounded-lg border md:min-w-[450px]">
    <x-ui.resizable-panel :default-size="50">
        <div class="flex h-[200px] items-center justify-center p-6">
            <span class="font-semibold">One</span>
        </div>
    </x-ui.resizable-panel>
    <x-ui.resizable-handle />
    <x-ui.resizable-panel :default-size="50">
        <x-ui.resizable-panel-group direction="vertical">
            <x-ui.resizable-panel :default-size="25">
                <div class="flex h-full items-center justify-center p-6">
                    <span class="font-semibold">Two</span>
                </div>
            </x-ui.resizable-panel>
            <x-ui.resizable-handle />
            <x-ui.resizable-panel :default-size="75">
                <div class="flex h-full items-center justify-center p-6">
                    <span class="font-semibold">Three</span>
                </div>
            </x-ui.resizable-panel>
        </x-ui.resizable-panel-group>
    </x-ui.resizable-panel>
</x-ui.resizable-panel-group>
