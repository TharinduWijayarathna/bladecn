{{-- Simulates what <x-ui.flash-toaster /> shows after `return back()->with(...)`. --}}
<x-ui.button variant="outline" x-data x-on:click="toast.success('Profile updated.')">with('success')</x-ui.button>
<x-ui.button variant="outline" x-data x-on:click="toast.error('Could not save your changes.')">with('error')</x-ui.button>
<x-ui.button variant="outline" x-data x-on:click="toast.warning('Your trial ends tomorrow.')">with('warning')</x-ui.button>
<x-ui.button variant="outline" x-data x-on:click="toast.info('A new version is available.')">with('info')</x-ui.button>
<x-ui.button variant="outline" x-data x-on:click="toast.success('We have emailed your password reset link.')">with('status')</x-ui.button>
