@props(['class' => ''])

<div data-slot="sidebar-group" role="group" {{ $attributes->merge(['class' => cn('relative flex w-full min-w-0 flex-col p-2', $class)]) }}>
    {{ $slot }}
</div>
