<x-ui.dialog>
    <x-ui.dialog-trigger>
        <x-ui.button>Edit profile</x-ui.button>
    </x-ui.dialog-trigger>
    <x-ui.dialog-overlay />
    <x-ui.dialog-content class="sm:max-w-[425px]">
        <form class="grid gap-4" onsubmit="event.preventDefault()">
            <x-ui.dialog-header>
                <x-ui.dialog-title>Edit profile</x-ui.dialog-title>
                <x-ui.dialog-description>Make changes to your profile here. Click save when you're done.</x-ui.dialog-description>
            </x-ui.dialog-header>
            <div class="grid gap-2">
                <x-ui.label for="dialog-name">Name</x-ui.label>
                <x-ui.input id="dialog-name" name="name" value="Pedro Duarte" />
            </div>
            <div class="grid gap-2">
                <x-ui.label for="dialog-username">Username</x-ui.label>
                <x-ui.input id="dialog-username" name="username" value="@peduarte" />
            </div>
            <x-ui.dialog-footer>
                <x-ui.dialog-close>
                    <x-ui.button type="button" variant="outline">Cancel</x-ui.button>
                </x-ui.dialog-close>
                <x-ui.button type="submit">Save changes</x-ui.button>
            </x-ui.dialog-footer>
        </form>
    </x-ui.dialog-content>
</x-ui.dialog>
