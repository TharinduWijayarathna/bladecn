<x-ui.confirm-delete action="/teams/acme" trigger="Delete team" confirm-text="Delete team"
    title="Delete the Acme team?" description="Members lose access immediately." confirmation="acme"
    onsubmit="event.preventDefault(); window.toast?.success('Team deleted (demo)')" />
