<div class="h-[380px] w-full overflow-hidden rounded-lg border [transform:translateZ(0)]">
    <x-ui.sidebar-provider storage-key="" :keyboard-shortcut="null" class="h-full min-h-full!">
        <x-ui.sidebar variant="floating" class="h-full!">
            <x-ui.sidebar-content>
                <x-ui.sidebar-group>
                    <x-ui.sidebar-group-label>Documents</x-ui.sidebar-group-label>
                    <x-ui.sidebar-menu>
                        <x-ui.sidebar-menu-item>
                            <x-ui.sidebar-menu-button is-active><x-icons.file-check /><span>Contracts</span></x-ui.sidebar-menu-button>
                        </x-ui.sidebar-menu-item>
                        <x-ui.sidebar-menu-item>
                            <x-ui.sidebar-menu-button><x-icons.notebook-pen /><span>Notes</span></x-ui.sidebar-menu-button>
                        </x-ui.sidebar-menu-item>
                    </x-ui.sidebar-menu>
                </x-ui.sidebar-group>
            </x-ui.sidebar-content>
        </x-ui.sidebar>
        <x-ui.sidebar-inset class="p-4">
            <x-ui.sidebar-trigger />
            <p class="mt-4 text-sm text-muted-foreground">Off-canvas floating variant without persistence or keyboard shortcut.</p>
        </x-ui.sidebar-inset>
    </x-ui.sidebar-provider>
</div>
