@props(['class' => ''])

<x-ui.button type="button" variant="ghost" size="icon-sm" data-slot="sidebar-trigger" aria-label="Toggle sidebar"
    x-on:click="toggleSidebar()" x-bind:aria-expanded="(isMobile ? openMobile : open).toString()"
    :class="cn('size-7', $class)" {{ $attributes }}>
    @if ($slot->isEmpty())
        <x-icons.panel-left class="size-4" aria-hidden="true" />
    @else
        {{ $slot }}
    @endif
    <span class="sr-only">Toggle sidebar</span>
</x-ui.button>
