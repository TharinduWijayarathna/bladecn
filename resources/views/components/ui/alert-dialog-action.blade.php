@props([
    'variant' => 'default',
    'class' => '',
])

{{-- Closes the dialog on click. Add `type="submit"` / `form="..."` to submit a form, or your own `x-on:click`. --}}
<x-ui.button type="button" :variant="$variant" :class="$class" data-slot="alert-dialog-action" x-on:click="open = false" {{ $attributes }}>
    {{ $slot }}
</x-ui.button>
