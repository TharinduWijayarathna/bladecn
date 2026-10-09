@props([
    'class' => '',
])

<div role="listbox" data-slot="command-list" :id="$id('command-list')"
    {{ $attributes->merge(['class' => cn('max-h-[300px] scroll-py-1 overflow-y-auto overflow-x-hidden', $class)]) }}>
    {{ $slot }}
</div>
