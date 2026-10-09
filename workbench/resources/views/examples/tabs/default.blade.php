<x-ui.tabs default-value="account" class="w-full max-w-sm">
    <x-ui.tabs-list>
        <x-ui.tabs-trigger value="account">Account</x-ui.tabs-trigger>
        <x-ui.tabs-trigger value="password">Password</x-ui.tabs-trigger>
    </x-ui.tabs-list>
    <x-ui.tabs-content value="account">
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Account</x-ui.card-title>
                <x-ui.card-description>Make changes to your account here.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content class="grid gap-3">
                <x-ui.label for="tabs-name">Name</x-ui.label>
                <x-ui.input id="tabs-name" value="Pedro Duarte" />
            </x-ui.card-content>
        </x-ui.card>
    </x-ui.tabs-content>
    <x-ui.tabs-content value="password">
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Password</x-ui.card-title>
                <x-ui.card-description>Change your password here.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content class="grid gap-3">
                <x-ui.label for="tabs-current">Current password</x-ui.label>
                <x-ui.input id="tabs-current" type="password" />
            </x-ui.card-content>
        </x-ui.card>
    </x-ui.tabs-content>
</x-ui.tabs>
