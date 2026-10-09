@props([
    'href' => null,
    'disabled' => false,
    'label' => 'Next',
    'class' => '',
])

<x-ui.pagination-link :href="$href" :disabled="$disabled" size="default" aria-label="Go to next page"
    {{ $attributes->merge(['class' => cn('gap-1 px-2.5 sm:pr-2.5', $class)]) }}>
    <span class="hidden sm:block">{{ $slot->isNotEmpty() ? $slot : $label }}</span>
    <x-icons.chevron-right />
</x-ui.pagination-link>
