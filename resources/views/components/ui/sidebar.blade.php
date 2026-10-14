@props([
    'side' => 'left',
    'variant' => 'sidebar',
    'collapsible' => 'offcanvas',
    'label' => 'Sidebar',
    'class' => '',
])

@aware(['defaultOpen' => true, 'open' => null, 'storageKey' => 'sidebar_state'])

@php
    // Mirror the provider's server-side state so the first paint matches (no flash before Alpine boots).
    $cookie = $storageKey ? request()->cookie($storageKey) : null;
    $initialOpen = $open !== null
        ? (bool) $open
        : (in_array($cookie, ['true', 'false'], true) ? $cookie === 'true' : (bool) $defaultOpen);
    $side = $side === 'right' ? 'right' : 'left';
    $floating = in_array($variant, ['floating', 'inset'], true);
@endphp

@if ($collapsible === 'none')
    <div data-slot="sidebar" data-variant="{{ $variant }}" data-side="{{ $side }}" data-collapsible="none"
        aria-label="{{ $label }}" role="navigation"
        {{ $attributes->merge(['class' => cn('flex h-full w-(--sidebar-width) flex-col bg-sidebar text-sidebar-foreground', $class)]) }}>
        {{ $slot }}
    </div>
@else
    <div data-slot="sidebar" class="group peer text-sidebar-foreground"
        data-state="{{ $initialOpen ? 'expanded' : 'collapsed' }}" data-collapsible="{{ $initialOpen ? '' : $collapsible }}"
        x-bind:data-state="state" x-bind:data-collapsible="state === 'collapsed' && ! isMobile ? @js($collapsible) : ''"
        data-variant="{{ $variant }}" data-side="{{ $side }}">

        {{-- Mobile: the sidebar slides in as a sheet over an overlay. --}}
        <div data-slot="sidebar-overlay" x-show="isMobile && openMobile" x-cloak style="display: none;"
            x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            x-on:click="setOpenMobile(false)" class="fixed inset-0 z-40 bg-black/50 md:hidden" aria-hidden="true"></div>

        {{-- Desktop: reserves the sidebar's width in the layout. --}}
        <div data-slot="sidebar-gap" @class([
            'relative hidden w-(--sidebar-width) bg-transparent transition-[width] duration-200 ease-linear md:block',
            'group-data-[collapsible=offcanvas]:w-0',
            'group-data-[side=right]:rotate-180',
            $floating
                ? 'group-data-[collapsible=icon]:w-[calc(var(--sidebar-width-icon)+1rem)]'
                : 'group-data-[collapsible=icon]:w-(--sidebar-width-icon)',
        ])></div>

        <div data-slot="sidebar-container"
            x-bind:role="isMobile && openMobile ? 'dialog' : null"
            x-bind:aria-modal="isMobile && openMobile ? 'true' : null"
            x-trap.inert.noscroll="isMobile && openMobile"
            x-on:keydown.escape="if (isMobile && openMobile) setOpenMobile(false)"
            x-bind:aria-label="isMobile && openMobile ? @js($label) : null"
            x-bind:class="{ @js($side === 'left' ? 'max-md:-translate-x-full' : 'max-md:translate-x-full'): ! openMobile, 'max-md:translate-x-0': openMobile }"
            {{ $attributes->merge(['class' => cn(
                'fixed inset-y-0 z-50 flex h-svh w-(--sidebar-width-mobile) transition-[left,right,width,transform] duration-200 ease-linear md:w-(--sidebar-width) md:translate-x-0',
                $side === 'left'
                    ? 'left-0 max-md:-translate-x-full md:group-data-[collapsible=offcanvas]:left-[calc(var(--sidebar-width)*-1)]'
                    : 'right-0 max-md:translate-x-full md:group-data-[collapsible=offcanvas]:right-[calc(var(--sidebar-width)*-1)]',
                $floating
                    ? 'md:p-2 md:group-data-[collapsible=icon]:w-[calc(var(--sidebar-width-icon)+1rem+2px)]'
                    : 'md:group-data-[collapsible=icon]:w-(--sidebar-width-icon) md:group-data-[side=left]:border-r md:group-data-[side=right]:border-l border-sidebar-border',
                $class,
            )]) }}>
            <nav data-slot="sidebar-inner" aria-label="{{ $label }}"
                class="flex h-full w-full flex-col overflow-hidden bg-sidebar group-data-[variant=floating]:md:rounded-lg group-data-[variant=floating]:md:border group-data-[variant=floating]:md:border-sidebar-border group-data-[variant=floating]:md:shadow-sm">
                {{ $slot }}
            </nav>
        </div>
    </div>
@endif
