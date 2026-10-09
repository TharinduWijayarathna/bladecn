@props([
    'variant' => 'outline',
    'size' => 'default',
    'class' => '',
])

<x-ui.button type="button" :variant="$variant" :size="$size" :class="$class" data-slot="alert-dialog-trigger"
    aria-haspopup="dialog" x-bind:aria-expanded="open.toString()" x-on:click="open = true" {{ $attributes }}>
    {{ $slot }}
</x-ui.button>
