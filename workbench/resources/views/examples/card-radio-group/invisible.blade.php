@php
    $options = [
        [
            'value' => 'personal',
            'label' => 'Personal',
            'description' => 'Only you can see it.',
            'icon' => '<svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>',
        ],
        [
            'value' => 'team',
            'label' => 'Team',
            'description' => 'Everyone in your organisation.',
            'icon' => '<svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="4"/><path d="M2 21a7 7 0 0 1 14 0M17 4a4 4 0 0 1 0 8M22 21a7 7 0 0 0-4-6.3"/></svg>',
        ],
    ];
@endphp

<x-ui.card-radio-group
    name="workspace"
    variant="invisible"
    text-size="default"
    default-value="personal"
    :options="$options"
/>
