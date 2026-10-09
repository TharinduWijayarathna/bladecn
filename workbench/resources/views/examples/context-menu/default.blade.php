<x-ui.context-menu>
    <x-ui.context-menu-trigger class="flex h-[150px] w-[300px] items-center justify-center rounded-md border border-dashed text-sm">
        Right click here
    </x-ui.context-menu-trigger>
    <x-ui.context-menu-content class="w-52">
        <x-ui.context-menu-item inset>Back <x-ui.context-menu-shortcut>⌘[</x-ui.context-menu-shortcut></x-ui.context-menu-item>
        <x-ui.context-menu-item inset disabled>Forward <x-ui.context-menu-shortcut>⌘]</x-ui.context-menu-shortcut></x-ui.context-menu-item>
        <x-ui.context-menu-item inset>Reload <x-ui.context-menu-shortcut>⌘R</x-ui.context-menu-shortcut></x-ui.context-menu-item>
        <x-ui.context-menu-separator />
        <x-ui.context-menu-label inset>Edit</x-ui.context-menu-label>
        <x-ui.context-menu-item inset x-on:click="window.toast?.('Copied')"><x-icons.copy /> Copy</x-ui.context-menu-item>
        <x-ui.context-menu-item inset variant="destructive"><x-icons.trash /> Delete</x-ui.context-menu-item>
    </x-ui.context-menu-content>
</x-ui.context-menu>
