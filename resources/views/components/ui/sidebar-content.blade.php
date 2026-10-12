@props(['class' => ''])

<div data-slot="sidebar-content"
    {{ $attributes->merge(['class' => cn('flex min-h-0 flex-1 flex-col gap-2 overflow-auto group-data-[collapsible=icon]:overflow-hidden', $class)]) }}>
    {{ $slot }}
</div>
