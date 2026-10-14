@props([
    'withHandle' => false,
    'label' => 'Resize',
    'class' => '',
])

@aware(['direction' => 'horizontal'])

{{-- A separator with aria-valuenow (the size of the panel before it). Drag it, or focus it and use the arrow keys / Home / End. --}}
<div role="separator" tabindex="0" data-slot="resizable-handle" data-state="inactive" data-panel-group-direction="{{ $direction }}"
    aria-label="{{ $label }}" aria-orientation="{{ $direction === 'vertical' ? 'horizontal' : 'vertical' }}"
    @pointerdown="start($event)" @pointermove="move($event)" @pointerup="end($event)" @pointercancel="end($event)"
    @keydown="key($event)"
    {{ $attributes->merge(['class' => cn(
        'relative flex shrink-0 touch-none items-center justify-center bg-border outline-none select-none',
        'focus-visible:ring-1 focus-visible:ring-ring focus-visible:ring-offset-1 focus-visible:ring-offset-background',
        $direction === 'vertical'
            ? 'h-px w-full cursor-row-resize after:absolute after:inset-x-0 after:top-1/2 after:h-2 after:-translate-y-1/2'
            : 'w-px cursor-col-resize after:absolute after:inset-y-0 after:left-1/2 after:w-2 after:-translate-x-1/2',
        $class,
    )]) }}>
    @if ($withHandle)
        <div class="z-10 flex h-4 w-3 items-center justify-center rounded-xs border bg-border {{ $direction === 'vertical' ? 'rotate-90' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" class="size-2.5" aria-hidden="true">
                <circle cx="9" cy="12" r="1" /><circle cx="9" cy="5" r="1" /><circle cx="9" cy="19" r="1" />
                <circle cx="15" cy="12" r="1" /><circle cx="15" cy="5" r="1" /><circle cx="15" cy="19" r="1" />
            </svg>
        </div>
    @endif
</div>
