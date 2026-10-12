@props([
    'inset' => false,
    'class' => '',
])

<button type="button" role="menuitem" tabindex="-1" data-slot="menubar-sub-trigger" aria-haspopup="menu" aria-expanded="false"
    :aria-expanded="subOpen.toString()" :data-state="subOpen ? 'open' : 'closed'"
    @mouseenter="subOpen = true" @mousemove="$el.focus()"
    @click="subOpen = true; $nextTick(() => focusItem($el.closest('[data-slot=menubar-sub]').querySelector('[data-slot=menubar-sub-content]'), 'first'))"
    @keydown.arrow-right.prevent.stop="subOpen = true; $nextTick(() => focusItem($el.closest('[data-slot=menubar-sub]').querySelector('[data-slot=menubar-sub-content]'), 'first'))"
    {{ $attributes->merge(['class' => cn(
        'flex w-full cursor-default select-none items-center gap-2 rounded-sm px-2 py-1.5 text-left text-sm outline-none',
        'focus:bg-accent focus:text-accent-foreground data-[state=open]:bg-accent data-[state=open]:text-accent-foreground',
        $inset ? 'pl-8' : null,
        $class,
    )]) }}>
    {{ $slot }}
    <x-icons.chevron-right class="ml-auto size-4" aria-hidden="true" />
</button>
