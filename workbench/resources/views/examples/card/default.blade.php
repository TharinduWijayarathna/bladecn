<x-ui.card class="w-full max-w-sm">
    <x-ui.card-header>
        <x-ui.card-title>Create project</x-ui.card-title>
        <x-ui.card-description>Deploy your new project in one click.</x-ui.card-description>
    </x-ui.card-header>
    <x-ui.card-content>
        <div class="grid gap-2">
            <x-ui.label for="project-name">Name</x-ui.label>
            <x-ui.input id="project-name" placeholder="Name of your project" />
        </div>
    </x-ui.card-content>
    <x-ui.card-footer class="justify-between">
        <x-ui.button variant="outline">Cancel</x-ui.button>
        <x-ui.button>Deploy</x-ui.button>
    </x-ui.card-footer>
</x-ui.card>
