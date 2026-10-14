@props([
    'class' => '',
])

<button type="button" data-slot="navigation-menu-trigger" :data-value="value" aria-haspopup="true" aria-expanded="false"
    :aria-expanded="(active === value).toString()" :aria-controls="value + '-content'" :data-state="active === value ? 'open' : 'closed'"
    data-state="closed" @click="toggle(value)" @keydown="topKey($event)"
    @keydown.arrow-down.prevent="enterPanel(value)"
    {{ $attributes->merge(['class' => cn(
        'group inline-flex h-9 w-max items-center justify-center gap-1 rounded-md bg-background px-4 py-2 text-sm font-medium transition-[color,box-shadow] outline-none',
        'hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground focus-visible:ring-[3px] focus-visible:ring-ring/50 focus-visible:outline-1',
        'disabled:pointer-events-none disabled:opacity-50 data-[state=open]:bg-accent/50 data-[state=open]:text-accent-foreground dark:bg-transparent dark:hover:bg-accent/50',
        $class,
    )]) }}>
    {{ $slot }}
    <x-icons.chevron-down class="relative top-px ml-1 size-3 transition duration-300 group-data-[state=open]:rotate-180" aria-hidden="true" />
</button>
