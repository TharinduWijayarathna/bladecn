<x-ui.dropdown>
    <x-slot:trigger>
        <x-ui.button variant="outline">View options</x-ui.button>
    </x-slot:trigger>

    <x-ui.dropdown-content>
        <x-ui.dropdown-label>Appearance</x-ui.dropdown-label>
        <x-ui.dropdown-separator />
        <x-ui.dropdown-checkbox-item :checked="true">Status bar</x-ui.dropdown-checkbox-item>
        <x-ui.dropdown-checkbox-item>Activity bar</x-ui.dropdown-checkbox-item>
        <x-ui.dropdown-separator />
        <x-ui.dropdown-label>Panel position</x-ui.dropdown-label>
        <x-ui.dropdown-radio-item>Top</x-ui.dropdown-radio-item>
        <x-ui.dropdown-radio-item :selected="true">Bottom</x-ui.dropdown-radio-item>
        <x-ui.dropdown-radio-item>Right</x-ui.dropdown-radio-item>
    </x-ui.dropdown-content>
</x-ui.dropdown>
