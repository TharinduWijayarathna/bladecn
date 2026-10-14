{{-- min/max sizes, a visible grip, and sizes remembered in localStorage under "docs-sidebar". --}}
<x-ui.resizable-panel-group auto-save-id="docs-sidebar" class="min-h-[200px] max-w-md rounded-lg border md:min-w-[450px]">
    <x-ui.resizable-panel :default-size="25" :min-size="15" :max-size="50">
        <div class="flex h-full items-center justify-center p-6">
            <span class="font-semibold">Sidebar</span>
        </div>
    </x-ui.resizable-panel>
    <x-ui.resizable-handle with-handle />
    <x-ui.resizable-panel :default-size="75" :min-size="40">
        <div class="flex h-full items-center justify-center p-6">
            <span class="font-semibold">Content</span>
        </div>
    </x-ui.resizable-panel>
</x-ui.resizable-panel-group>
