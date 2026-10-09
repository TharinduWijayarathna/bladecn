@props([
    'shortcut' => 'k',
    'title' => 'Command Palette',
    'description' => 'Search for a command to run...',
    'class' => '',
])

{{-- A command menu in a modal. Opens with ⌘/Ctrl + `shortcut` (pass shortcut="" to disable) or `$dispatch('command-dialog-open')`. --}}
<div data-slot="command-dialog" x-data="{ open: false }" x-modelable="open"
    @if ($shortcut) @keydown.window="if (($event.metaKey || $event.ctrlKey) && $event.key.toLowerCase() === @js(strtolower($shortcut))) { $event.preventDefault(); open = ! open }" @endif
    @command-dialog-open.window="open = true" @command-select="open = false"
    x-effect="if (open) $nextTick(() => $root.querySelector('[data-slot=command-input]')?.focus())"
    {{ $attributes->merge(['class' => $class ?: null]) }}>
    {{ $trigger ?? '' }}
    <div x-show="open" x-cloak class="fixed inset-0 z-50">
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/50" @click="open = false" aria-hidden="true"></div>
        <div role="dialog" aria-modal="true" aria-label="{{ $title }}" x-show="open" x-transition
            x-trap.noscroll="open" @keydown.escape.prevent.stop="open = false"
            class="fixed left-1/2 top-[20%] z-50 w-full max-w-[calc(100%-2rem)] -translate-x-1/2 overflow-hidden rounded-lg border bg-background p-0 shadow-lg sm:max-w-lg">
            <span class="sr-only">{{ $description }}</span>
            <x-ui.command class="[&_[data-slot=command-group-heading]]:px-2 [&_[data-slot=command-group-heading]]:font-medium [&_[data-slot=command-group-heading]]:text-muted-foreground [&_[data-slot=command-input-wrapper]]:h-12 [&_[data-slot=command-input]]:h-12 [&_[data-slot=command-item]]:px-2 [&_[data-slot=command-item]]:py-3">
                {{ $slot }}
            </x-ui.command>
        </div>
    </div>
</div>
