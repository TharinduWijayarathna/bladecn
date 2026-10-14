@props(['class' => ''])

<main data-slot="sidebar-inset"
    {{ $attributes->merge(['class' => cn('relative flex w-full min-w-0 flex-1 flex-col bg-background md:peer-data-[variant=inset]:m-2 md:peer-data-[variant=inset]:ml-0 md:peer-data-[variant=inset]:rounded-xl md:peer-data-[variant=inset]:shadow-sm md:peer-data-[variant=inset]:peer-data-[state=collapsed]:ml-2', $class)]) }}>
    {{ $slot }}
</main>
