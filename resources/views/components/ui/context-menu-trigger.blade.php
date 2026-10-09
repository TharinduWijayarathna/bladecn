@props([
    'class' => '',
])

{{-- Right-click (or Shift+F10 / the Menu key while focused) opens the menu at the pointer. --}}
<div data-slot="context-menu-trigger" tabindex="0" aria-haspopup="menu" :aria-expanded="open.toString()" :data-state="open ? 'open' : 'closed'"
    @contextmenu.prevent="show($event.clientX, $event.clientY)"
    @keydown.shift.f10.prevent="openFromKeyboard($event)" @keydown.context-menu.prevent="openFromKeyboard($event)"
    {{ $attributes->merge(['class' => cn('select-none outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50', $class)]) }}>
    {{ $slot }}
</div>
