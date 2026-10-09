<x-ui.dialog>
    <x-ui.dialog-trigger>
        <x-ui.button variant="outline">Open dialog</x-ui.button>
    </x-ui.dialog-trigger>
    <x-ui.dialog-overlay />
    <x-ui.dialog-content class="sm:max-w-md">
        <x-ui.dialog-header>
            <x-ui.dialog-title>Share link</x-ui.dialog-title>
            <x-ui.dialog-description>Anyone who has this link will be able to view this.</x-ui.dialog-description>
        </x-ui.dialog-header>
        <x-ui.input value="https://bladecn.dev/docs/installation" readonly />
        <x-ui.dialog-footer>
            <x-ui.dialog-close>
                <x-ui.button variant="secondary">Close</x-ui.button>
            </x-ui.dialog-close>
        </x-ui.dialog-footer>
    </x-ui.dialog-content>
</x-ui.dialog>
