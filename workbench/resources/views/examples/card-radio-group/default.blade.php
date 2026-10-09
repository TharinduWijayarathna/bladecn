<x-ui.card-radio-group
    name="plan"
    default-value="pro"
    class="max-w-md"
    :options="[
        ['value' => 'starter', 'label' => 'Starter', 'description' => 'For side projects.'],
        ['value' => 'pro', 'label' => 'Pro', 'description' => 'For growing teams.'],
        ['value' => 'enterprise', 'label' => 'Enterprise', 'description' => 'Custom contracts and SSO.'],
    ]"
/>
