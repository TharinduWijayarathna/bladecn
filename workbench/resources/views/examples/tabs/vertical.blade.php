<x-ui.tabs default-value="general" orientation="vertical" class="w-full max-w-md">
    <x-ui.tabs-list>
        <x-ui.tabs-trigger value="general" class="w-full justify-start">General</x-ui.tabs-trigger>
        <x-ui.tabs-trigger value="security" class="w-full justify-start">Security</x-ui.tabs-trigger>
        <x-ui.tabs-trigger value="billing" class="w-full justify-start" disabled>Billing</x-ui.tabs-trigger>
    </x-ui.tabs-list>
    <x-ui.tabs-content value="general" class="rounded-md border p-4 text-sm">General settings.</x-ui.tabs-content>
    <x-ui.tabs-content value="security" class="rounded-md border p-4 text-sm">Security settings.</x-ui.tabs-content>
    <x-ui.tabs-content value="billing" class="rounded-md border p-4 text-sm">Billing settings.</x-ui.tabs-content>
</x-ui.tabs>
