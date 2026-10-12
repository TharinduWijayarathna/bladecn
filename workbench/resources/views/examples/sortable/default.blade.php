<div x-data="{ priorities: [] }" class="flex w-full max-w-sm flex-col gap-3">
    <x-ui.sortable x-model="priorities" name="priorities" label="Priorities">
        <x-ui.sortable-item value="perf">Performance</x-ui.sortable-item>
        <x-ui.sortable-item value="a11y">Accessibility</x-ui.sortable-item>
        <x-ui.sortable-item value="docs">Documentation</x-ui.sortable-item>
        <x-ui.sortable-item value="tests">Test coverage</x-ui.sortable-item>
    </x-ui.sortable>
    <p class="text-sm text-muted-foreground">Order: <code x-text="priorities.join(', ')"></code></p>
</div>
