<x-ui.sheet>
    <x-slot:trigger>
        <x-ui.button variant="outline">Open sheet</x-ui.button>
    </x-slot:trigger>

    <x-ui.sheet-header>
        <x-ui.sheet-title>Edit profile</x-ui.sheet-title>
        <x-ui.sheet-description>Make changes to your profile here. Click save when you're done.</x-ui.sheet-description>
    </x-ui.sheet-header>

    <div class="grid gap-4 py-6">
        <div class="grid gap-2">
            <x-ui.label for="sheet-name">Name</x-ui.label>
            <x-ui.input id="sheet-name" value="Pedro Duarte" />
        </div>
    </div>

    <x-ui.sheet-footer>
        <x-ui.sheet-close>Close</x-ui.sheet-close>
    </x-ui.sheet-footer>
</x-ui.sheet>
