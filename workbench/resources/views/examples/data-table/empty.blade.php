<x-ui.data-table :rows="collect()" :columns="['name' => 'Name', 'email' => 'Email', 'role' => 'Role']" :searchable="false">
    <x-slot:empty>
        No users match your filters.
        <x-ui.text-link href="?">Clear filters</x-ui.text-link>
    </x-slot:empty>
</x-ui.data-table>
