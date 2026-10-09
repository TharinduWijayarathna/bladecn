@props([
    'class' => '',
])

{{-- Rendered in place (not teleported) so an action with type="submit" still submits its surrounding <form>. --}}
<div x-show="open" x-cloak class="fixed inset-0 z-50" data-slot="alert-dialog-portal">
    <div data-slot="alert-dialog-overlay" x-show="open" x-transition.opacity.duration.150ms
        class="fixed inset-0 bg-black/50" aria-hidden="true"></div>

    {{-- Unlike a dialog, clicking outside does not close it: the user must choose an action. --}}
    <div role="alertdialog" aria-modal="true" data-slot="alert-dialog-content"
        :aria-labelledby="$id('alert-dialog-title')" :aria-describedby="$id('alert-dialog-description')"
        x-show="open" x-trap.inert.noscroll="open" @keydown.escape.prevent.stop="open = false"
        x-transition:enter="duration-200 ease-out" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="duration-150 ease-in"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        {{ $attributes->merge(['class' => cn(
            'fixed left-1/2 top-1/2 z-50 grid w-full max-w-[calc(100%-2rem)] -translate-x-1/2 -translate-y-1/2 gap-4 rounded-lg border bg-background p-6 text-foreground shadow-lg sm:max-w-lg',
            $class,
        )]) }}>
        {{ $slot }}
    </div>
</div>
