<x-ui.command-dialog>
    <x-slot:trigger>
        <p class="text-sm text-muted-foreground">
            Press <x-ui.kbd-group><x-ui.kbd>⌘</x-ui.kbd><x-ui.kbd>K</x-ui.kbd></x-ui.kbd-group> or
            <x-ui.button variant="link" class="h-auto p-0" x-on:click="open = true">open the palette</x-ui.button>
        </p>
    </x-slot:trigger>

    <x-ui.command-input placeholder="Type a command or search..." />
    <x-ui.command-list>
        <x-ui.command-empty>No results found.</x-ui.command-empty>
        <x-ui.command-group heading="Pages">
            <x-ui.command-item href="#">Dashboard</x-ui.command-item>
            <x-ui.command-item href="#">Projects</x-ui.command-item>
            <x-ui.command-item x-on:command-select="window.toast?.('Signed out (demo)')">Sign out</x-ui.command-item>
        </x-ui.command-group>
    </x-ui.command-list>
</x-ui.command-dialog>
