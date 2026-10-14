@props([
    'value',
    'checked' => false,
    'disabled' => false,
    'class' => '',
])

{{-- Selects `value` in the surrounding <x-ui.menubar-radio-group>. `checked` only sets the server-rendered state. --}}
<button type="button" role="menuitemradio" tabindex="-1" data-slot="menubar-radio-item" data-value="{{ $value }}"
    aria-checked="{{ $checked ? 'true' : 'false' }}" :aria-checked="(selected === @js((string) $value)).toString()"
    data-state="{{ $checked ? 'checked' : 'unchecked' }}" :data-state="selected === @js((string) $value) ? 'checked' : 'unchecked'"
    @if ($disabled) aria-disabled="true" @endif
    @mousemove="$el.focus()" @click="selected = @js((string) $value); closeAll()"
    {{ $attributes->merge(['class' => cn(
        'group/radio relative flex w-full cursor-default select-none items-center gap-2 rounded-sm py-1.5 pr-2 pl-8 text-left text-sm outline-hidden',
        'focus:bg-accent focus:text-accent-foreground aria-disabled:pointer-events-none aria-disabled:opacity-50',
        $class,
    )]) }}>
    <span class="pointer-events-none absolute left-2 flex size-3.5 items-center justify-center">
        <span class="hidden size-2 rounded-full bg-current group-data-[state=checked]/radio:block"></span>
    </span>
    {{ $slot }}
</button>
