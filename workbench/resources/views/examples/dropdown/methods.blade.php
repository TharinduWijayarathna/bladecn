<x-ui.dropdown>
    <x-slot:trigger>
        <x-ui.dropdown-trigger class="border hover:bg-accent">
            <x-ui.avatar class="size-6">
                <x-ui.avatar-fallback name="Jane Doe" class="text-[10px]" />
            </x-ui.avatar>
            Jane Doe
            <x-icons.chevrons-up-down class="size-4 opacity-60" />
        </x-ui.dropdown-trigger>
    </x-slot:trigger>

    <x-ui.dropdown-content>
        <x-ui.dropdown-item href="#">Settings</x-ui.dropdown-item>
        <x-ui.dropdown-separator />
        {{-- In your app: href="{{ route('logout') }}" --}}
        <x-ui.dropdown-item href="#logout" method="post" class="text-destructive-foreground">
            Log out
        </x-ui.dropdown-item>
    </x-ui.dropdown-content>
</x-ui.dropdown>
