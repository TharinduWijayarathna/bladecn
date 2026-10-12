{{-- <x-ui.flash-toaster validation="summary" /> after a failed $request->validate(): --}}
<x-ui.button variant="outline" x-data
    x-on:click="toast.error('There are 2 errors in the form.', { description: 'The email field is required.' })">
    validation="summary"
</x-ui.button>
<x-ui.button variant="outline" x-data
    x-on:click="toast.error('The email field is required.'); toast.error('The password must be at least 8 characters.')">
    validation (each message)
</x-ui.button>
