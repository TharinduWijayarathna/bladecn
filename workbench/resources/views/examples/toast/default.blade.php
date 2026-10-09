<x-ui.button variant="outline"
    x-data x-on:click="toast('Event has been created', { description: 'Sunday, December 03, 2023 at 9:00 AM', action: { label: 'Undo', onClick: () => toast.info('Undone') } })">
    Show toast
</x-ui.button>
