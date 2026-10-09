@props([
    'class' => '',
])

<x-ui.button type="button" variant="outline" :class="$class" data-slot="alert-dialog-cancel" x-on:click="open = false" {{ $attributes }}>
    {{ $slot }}
</x-ui.button>
