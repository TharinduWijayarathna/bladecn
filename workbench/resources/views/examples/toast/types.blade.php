<x-ui.button variant="outline" x-data x-on:click="toast.success('Profile saved')">Success</x-ui.button>
<x-ui.button variant="outline" x-data x-on:click="toast.error('Something went wrong', { description: 'Please try again.' })">Error</x-ui.button>
<x-ui.button variant="outline" x-data x-on:click="toast.warning('Your trial ends tomorrow')">Warning</x-ui.button>
<x-ui.button variant="outline" x-data x-on:click="toast.info('A new version is available')">Info</x-ui.button>
<x-ui.button variant="outline" x-data x-on:click="$dispatch('toast', { message: 'Dispatched from Alpine' })">$dispatch</x-ui.button>
