<x-ui.confirm-delete action="/sessions/other" method="post" title="Log out other browsers?"
    description="Every other session on your account will be signed out." confirm-text="Log out"
    onsubmit="event.preventDefault(); window.toast?.info('Signed out other sessions (demo)')">
    <x-slot:trigger>
        <x-ui.alert-dialog-trigger variant="ghost" size="sm">
            Log out other browsers
        </x-ui.alert-dialog-trigger>
    </x-slot:trigger>
    <input type="hidden" name="keep_current" value="1">
</x-ui.confirm-delete>
