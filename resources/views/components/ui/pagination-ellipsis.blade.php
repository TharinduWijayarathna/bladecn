@props([
    'class' => '',
])

<span aria-hidden="true" data-slot="pagination-ellipsis" {{ $attributes->merge(['class' => cn('flex size-9 items-center justify-center', $class)]) }}>
    <x-icons.ellipsis class="size-4" />
    <span class="sr-only">More pages</span>
</span>
