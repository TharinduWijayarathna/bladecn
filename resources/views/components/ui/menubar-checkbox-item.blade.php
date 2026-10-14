@props([
    'checked' => false,
    'disabled' => false,
    'class' => '',
])

{{-- Toggles its own `checked` state (bind it with x-model) and closes the menu. --}}
<button type="button" role="menuitemcheckbox" tabindex="-1" data-slot="menubar-checkbox-item"
    x-data="{ checked: @js((bool) $checked) }" x-modelable="checked"
    aria-checked="{{ $checked ? 'true' : 'false' }}" :aria-checked="checked.toString()"
    data-state="{{ $checked ? 'checked' : 'unchecked' }}" :data-state="checked ? 'checked' : 'unchecked'"
    @if ($disabled) aria-disabled="true" @endif
    @mousemove="$el.focus()" @click="checked = ! checked; closeAll()"
    {{ $attributes->merge(['class' => cn(
        'relative flex w-full cursor-default select-none items-center gap-2 rounded-sm py-1.5 pr-2 pl-8 text-left text-sm outline-hidden',
        'focus:bg-accent focus:text-accent-foreground aria-disabled:pointer-events-none aria-disabled:opacity-50',
        "[&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4",
        $class,
    )]) }}>
    <span class="pointer-events-none absolute left-2 flex size-3.5 items-center justify-center">
        <x-icons.check class="size-4" x-show="checked" :style="$checked ? null : 'display: none;'" />
    </span>
    {{ $slot }}
</button>
