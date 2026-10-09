<x-ui.dropdown>
    <x-slot:trigger>
        <x-ui.button variant="outline">Team</x-ui.button>
    </x-slot:trigger>

    <x-ui.dropdown-content>
        <x-ui.dropdown-item href="#">New team</x-ui.dropdown-item>
        <x-ui.dropdown-sub>
            <x-slot:trigger>
                <x-ui.dropdown-sub-trigger>Invite users</x-ui.dropdown-sub-trigger>
            </x-slot:trigger>
            <x-ui.dropdown-item href="#"><x-icons.mail /> Email</x-ui.dropdown-item>
            <x-ui.dropdown-item href="#"><x-icons.link /> Copy link</x-ui.dropdown-item>
        </x-ui.dropdown-sub>
    </x-ui.dropdown-content>
</x-ui.dropdown>
