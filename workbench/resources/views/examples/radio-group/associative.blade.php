<x-ui.radio-group
    name="billing"
    value="yearly"
    :options="[
        ['value' => 'monthly', 'label' => 'Monthly'],
        ['value' => 'yearly', 'label' => 'Yearly (save 20%)'],
    ]"
/>
