{{-- Submits DELETE /projects/42 with the CSRF token. (The docs stop the submit and show a toast instead.) --}}
<x-ui.confirm-delete action="/projects/42" title="Delete this project?"
    description="All deployments, domains and settings will be removed. This cannot be undone."
    onsubmit="event.preventDefault(); window.toast?.success('Project deleted (demo)')" />
