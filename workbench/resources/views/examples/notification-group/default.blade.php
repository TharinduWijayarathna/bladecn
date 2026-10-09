<x-ui.notification-group
    id="email_notifications"
    value="mentions"
    :options="[
        ['value' => 'all', 'label' => 'All'],
        ['value' => 'mentions', 'label' => 'Mentions'],
        ['value' => 'none', 'label' => 'None'],
    ]"
/>
