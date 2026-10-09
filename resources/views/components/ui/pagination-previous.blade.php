@props([
    'href' => null,
    'disabled' => false,
    'label' => 'Previous',
    'class' => '',
])

<x-ui.pagination-link :href="$href" :disabled="$disabled" size="default" aria-label="Go to previous page"
    {{ $attributes->merge(['class' => cn('gap-1 px-2.5 sm:pl-2.5', $class)]) }}>
    <x-icons.chevron-left />
    <span class="hidden sm:block">{{ $slot->isNotEmpty() ? $slot : $label }}</span>
</x-ui.pagination-link>
