@props([
    'name' => 'radio-group',
    'options' => [],
    'value' => '',
    'class' => '',
])

@php
    $groupId = uniqid('radio-group-');
@endphp

<div class="flex gap-0 -space-x-px rounded-md shadow-xs {{ $class }}" data-radio-group="{{ $groupId }}" data-name="{{ $name }}" data-value="{{ $value }}">
    @foreach($options as $index => $option)
        @php
            $optionValue = is_array($option) ? ($option['value'] ?? $option) : $option;
            $optionLabel = is_array($option) ? ($option['label'] ?? $option['value'] ?? $option) : $option;
            $optionId = $groupId . '-' . $index;
            $isChecked = $value === $optionValue;
        @endphp
        <label
            for="{{ $optionId }}"
            class="relative flex h-9 cursor-default flex-col items-center justify-center border bg-transparent px-3 py-1 text-center text-sm font-medium shadow-xs outline-offset-2 transition-[color,box-shadow] outline-none first:rounded-s-md last:rounded-e-md has-[:focus-visible]:outline-2 has-[:focus-visible]:outline-ring/70 has-[[data-disabled]]:cursor-not-allowed has-[[data-disabled]]:opacity-50 {{ $isChecked ? 'z-10 border-ring bg-accent' : '' }}"
        >
            <input
                type="radio"
                name="{{ $name }}"
                id="{{ $optionId }}"
                value="{{ $optionValue }}"
                {{ $isChecked ? 'checked' : '' }}
                class="sr-only after:absolute after:inset-0"
                onchange="updateNotificationGroup(this.closest('[data-radio-group]').dataset.radioGroup, this.value)"
            />
            {{ $optionLabel }}
        </label>
    @endforeach
</div>

@pushOnce('scripts')
<script>
    function updateNotificationGroup(groupId, value) {
        const group = document.querySelector(`[data-radio-group="${groupId}"]`);
        if (!group) return;
        
        group.setAttribute('data-value', value);
        
        // Update checked state visually
        group.querySelectorAll('label').forEach(label => {
            const input = label.querySelector('input[type="radio"]');
            if (input && input.value === value) {
                label.classList.add('z-10', 'border-ring', 'bg-accent');
            } else {
                label.classList.remove('z-10', 'border-ring', 'bg-accent');
            }
        });
    }
    
    // Initialize checked state on page load
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-radio-group]').forEach(group => {
            const value = group.getAttribute('data-value');
            if (value) {
                const checkedInput = group.querySelector(`input[type="radio"][value="${value}"]`);
                if (checkedInput) {
                    const label = checkedInput.closest('label');
                    label?.classList.add('z-10', 'border-ring', 'bg-accent');
                }
            }
        });
    });
</script>
@endPushOnce

