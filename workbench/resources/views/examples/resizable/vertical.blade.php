<x-ui.resizable-panel-group direction="vertical" class="min-h-[200px] max-w-md rounded-lg border md:min-w-[450px]">
    <x-ui.resizable-panel :default-size="25">
        <div class="flex h-full items-center justify-center p-6">
            <span class="font-semibold">Header</span>
        </div>
    </x-ui.resizable-panel>
    <x-ui.resizable-handle />
    <x-ui.resizable-panel :default-size="75">
        <div class="flex h-full items-center justify-center p-6">
            <span class="font-semibold">Content</span>
        </div>
    </x-ui.resizable-panel>
</x-ui.resizable-panel-group>
