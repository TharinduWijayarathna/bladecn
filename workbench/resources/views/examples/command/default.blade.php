<x-ui.command class="max-w-[450px] rounded-lg border shadow-md">
    <x-ui.command-input placeholder="Type a command or search..." />
    <x-ui.command-list>
        <x-ui.command-empty>No results found.</x-ui.command-empty>
        <x-ui.command-group heading="Suggestions">
            <x-ui.command-item><x-icons.calendar /> Calendar</x-ui.command-item>
            <x-ui.command-item keywords="emoji"><x-icons.smile /> Search Emoji</x-ui.command-item>
            <x-ui.command-item disabled><x-icons.calculator /> Calculator</x-ui.command-item>
        </x-ui.command-group>
        <x-ui.command-separator />
        <x-ui.command-group heading="Settings">
            <x-ui.command-item><x-icons.user /> Profile <x-ui.command-shortcut>⌘P</x-ui.command-shortcut></x-ui.command-item>
            <x-ui.command-item><x-icons.credit-card /> Billing <x-ui.command-shortcut>⌘B</x-ui.command-shortcut></x-ui.command-item>
            <x-ui.command-item><x-icons.gear /> Settings <x-ui.command-shortcut>⌘S</x-ui.command-shortcut></x-ui.command-item>
        </x-ui.command-group>
    </x-ui.command-list>
</x-ui.command>
