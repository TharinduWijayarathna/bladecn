<form method="POST" action="#" onsubmit="event.preventDefault(); window.toast?.success('Project deleted (demo)')">
    @csrf
    @method('DELETE')
    <x-ui.alert-dialog>
        <x-ui.alert-dialog-trigger variant="destructive">Delete project</x-ui.alert-dialog-trigger>
        <x-ui.alert-dialog-content>
            <x-ui.alert-dialog-header>
                <x-ui.alert-dialog-title>Delete this project?</x-ui.alert-dialog-title>
                <x-ui.alert-dialog-description>All deployments and settings will be removed.</x-ui.alert-dialog-description>
            </x-ui.alert-dialog-header>
            <x-ui.alert-dialog-footer>
                <x-ui.alert-dialog-cancel>Cancel</x-ui.alert-dialog-cancel>
                <x-ui.alert-dialog-action type="submit" variant="destructive">Delete</x-ui.alert-dialog-action>
            </x-ui.alert-dialog-footer>
        </x-ui.alert-dialog-content>
    </x-ui.alert-dialog>
</form>
