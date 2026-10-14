@props([
    'handle' => null,
    'class' => '',
])

@aware(['direction' => 'bottom'])

@php
    $sides = [
        'bottom' => ['inset-x-0 bottom-0 mt-24 max-h-[80vh] rounded-t-lg border-t', 'translate-y-full'],
        'top' => ['inset-x-0 top-0 mb-24 max-h-[80vh] rounded-b-lg border-b', '-translate-y-full'],
        'right' => ['inset-y-0 right-0 w-3/4 border-l sm:max-w-sm', 'translate-x-full'],
        'left' => ['inset-y-0 left-0 w-3/4 border-r sm:max-w-sm', '-translate-x-full'],
    ];
    [$position, $hidden] = $sides[$direction] ?? $sides['bottom'];
    // The grab handle is shown on bottom drawers unless turned off.
    $showHandle = $handle ?? $direction === 'bottom';
@endphp

{{-- Rendered in place (not teleported) so buttons inside still submit a surrounding <form>. --}}
<div x-show="open" x-cloak class="fixed inset-0 z-50" data-slot="drawer-portal">
    <div data-slot="drawer-overlay" x-show="open" x-transition.opacity.duration.300ms x-on:click="close()" :style="overlayStyle()"
        class="fixed inset-0 bg-black/50" aria-hidden="true"></div>

    <div role="dialog" aria-modal="true" data-slot="drawer-content" data-direction="{{ $direction }}"
        :aria-labelledby="$id('drawer-title')" :aria-describedby="$id('drawer-description')"
        x-show="open" x-trap.inert.noscroll="open" @keydown.escape.prevent.stop="close()" :style="dragStyle()"
        x-transition:enter="transition duration-300 ease-[cubic-bezier(0.32,0.72,0,1)]" x-transition:enter-start="{{ $hidden }}"
        x-transition:enter-end="translate-x-0 translate-y-0" x-transition:leave="transition duration-300 ease-[cubic-bezier(0.32,0.72,0,1)]"
        x-transition:leave-start="translate-x-0 translate-y-0" x-transition:leave-end="{{ $hidden }}"
        {{ $attributes->merge(['class' => cn(
            'group/drawer-content fixed z-50 flex h-auto flex-col bg-background text-foreground shadow-lg outline-none',
            $position,
            $class,
        )]) }}>
        @if ($showHandle)
            <div data-slot="drawer-handle" aria-hidden="true"
                @pointerdown="dragStart($event)" @pointermove="dragMove($event)" @pointerup="dragEnd()" @pointercancel="dragEnd()"
                class="flex shrink-0 cursor-grab touch-none justify-center pt-4 pb-1 active:cursor-grabbing">
                <div class="h-2 w-[100px] rounded-full bg-muted"></div>
            </div>
        @endif
        {{ $slot }}
    </div>
</div>
