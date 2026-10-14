@props([
    'class' => '',
])

{{-- ← closes the sub menu and returns to its trigger; the other keys work as in the parent menu. --}}
<div role="menu" data-slot="menubar-sub-content" tabindex="-1" aria-orientation="vertical"
    x-show="subOpen" style="display: none;"
    @keydown.stop="$event.key === 'ArrowLeft'
        ? ($event.preventDefault(), subOpen = false, $el.closest('[data-slot=menubar-sub]').querySelector('[data-slot=menubar-sub-trigger]').focus())
        : ($event.key === 'ArrowRight' ? $event.preventDefault() : menuKey($event, value))"
    x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    {{ $attributes->merge(['class' => cn(
        'absolute top-0 left-full z-50 -mt-1 ml-1 min-w-[8rem] origin-top-left rounded-md border bg-popover p-1 text-popover-foreground shadow-lg outline-none',
        $class,
    )]) }}>
    {{ $slot }}
</div>
