<x-ui.dropdown>
    <x-slot:trigger>
        <x-ui.button variant="outline">Open menu <x-icons.chevron-down /></x-ui.button>
    </x-slot:trigger>

    <x-ui.dropdown-content>
        <x-ui.dropdown-label>My Account</x-ui.dropdown-label>
        <x-ui.dropdown-separator />
        <x-ui.dropdown-item href="#">
            <x-icons.users /> Profile
            <x-ui.dropdown-shortcut>⇧⌘P</x-ui.dropdown-shortcut>
        </x-ui.dropdown-item>
        <x-ui.dropdown-item href="#">
            <x-icons.gear /> Settings
            <x-ui.dropdown-shortcut>⌘S</x-ui.dropdown-shortcut>
        </x-ui.dropdown-item>
        <x-ui.dropdown-separator />
        <x-ui.dropdown-item href="#">Support</x-ui.dropdown-item>
    </x-ui.dropdown-content>
</x-ui.dropdown>
