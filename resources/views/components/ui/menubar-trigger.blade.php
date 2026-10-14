@props([
    'class' => '',
])

{{-- A keyboard "click" (Enter / Space) opens the menu with its first item focused. --}}
<button type="button" role="menuitem" data-slot="menubar-trigger" aria-haspopup="menu" aria-expanded="false" data-state="closed"
    :aria-expanded="(active === value).toString()" :aria-controls="value + '-content'" :data-state="active === value ? 'open' : 'closed'"
    @click="active === value ? closeAll(false) : openMenu(value, $event.detail === 0 ? 'first' : null)"
    @pointerenter="active !== null && active !== value && openMenu(value)"
    @focus="rove($el)" @keydown="triggerKey($event, value)"
    {{ $attributes->merge(['class' => cn(
        'flex items-center rounded-sm px-2 py-1 text-sm font-medium outline-hidden select-none',
        'hover:bg-accent focus:bg-accent focus:text-accent-foreground focus-visible:ring-[3px] focus-visible:ring-ring/50',
        'data-[state=open]:bg-accent data-[state=open]:text-accent-foreground',
        $class,
    )]) }}>
    {{ $slot }}
</button>
