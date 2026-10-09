@props([
    'class' => '',
])

<h3 class="flex" data-slot="accordion-header">
    <button type="button" data-slot="accordion-trigger" :id="$id('accordion-trigger')" :aria-controls="$id('accordion-content')"
        :aria-expanded="isOpen(itemValue).toString()" :data-state="isOpen(itemValue) ? 'open' : 'closed'"
        :disabled="itemDisabled" @click="toggle(itemValue)"
        @keydown.arrow-down.prevent="focusTrigger($el, 1)" @keydown.arrow-up.prevent="focusTrigger($el, -1)"
        @keydown.home.prevent="focusTrigger($el, 'first')" @keydown.end.prevent="focusTrigger($el, 'last')"
        {{ $attributes->merge(['class' => cn(
            'flex flex-1 items-start justify-between gap-4 rounded-md py-4 text-left text-sm font-medium outline-none transition-all hover:underline focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50 [&[data-state=open]>svg]:rotate-180',
            $class,
        )]) }}>
        {{ $slot }}
        <x-icons.chevron-down class="pointer-events-none size-4 shrink-0 translate-y-0.5 text-muted-foreground transition-transform duration-200" />
    </button>
</h3>
