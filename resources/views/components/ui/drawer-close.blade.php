@props([
    'variant' => 'outline',
    'size' => 'default',
    'class' => '',
])

<x-ui.button type="button" :variant="$variant" :size="$size" :class="$class" data-slot="drawer-close" x-on:click="open = false" {{ $attributes }}>
    {{ $slot }}
</x-ui.button>
