@props([
    'defaultOpen' => true,
    'open' => null,
    'storageKey' => 'sidebar_state',
    'width' => '16rem',
    'widthIcon' => '3rem',
    'widthMobile' => '18rem',
    'keyboardShortcut' => 'b',
    'class' => '',
])

@php
    // Server-side initial state: explicit `open`, else the persisted cookie, else `default-open`.
    // Add the cookie to EncryptCookies::except() so Laravel can read the value written by the browser.
    $cookie = $storageKey ? request()->cookie($storageKey) : null;
    $initialOpen = $open !== null
        ? (bool) $open
        : (in_array($cookie, ['true', 'false'], true) ? $cookie === 'true' : (bool) $defaultOpen);
@endphp

<div data-slot="sidebar-wrapper" data-sidebar-state="{{ $initialOpen ? 'expanded' : 'collapsed' }}"
    x-data="{
        open: @js($initialOpen),
        openMobile: false,
        isMobile: false,
        storageKey: @js($storageKey),
        shortcut: @js($keyboardShortcut),
        get state() { return this.open ? 'expanded' : 'collapsed' },
        init() {
            const query = window.matchMedia('(max-width: 767px)');
            this.isMobile = query.matches;
            query.addEventListener('change', (event) => { this.isMobile = event.matches; if (! event.matches) this.openMobile = false; });
            if (this.storageKey) {
                try {
                    const stored = localStorage.getItem(this.storageKey);
                    if (stored === 'true' || stored === 'false') this.open = stored === 'true';
                } catch (e) {}
            }
        },
        setOpen(value) {
            this.open = value;
            if (! this.storageKey) return;
            document.cookie = `${this.storageKey}=${value}; path=/; max-age=${60 * 60 * 24 * 7}; SameSite=Lax`;
            try { localStorage.setItem(this.storageKey, String(value)); } catch (e) {}
        },
        setOpenMobile(value) { this.openMobile = value },
        toggleSidebar() { this.isMobile ? this.setOpenMobile(! this.openMobile) : this.setOpen(! this.open) },
    }"
    x-bind:data-sidebar-state="state"
    x-on:keydown.window="if (shortcut && $event.key === shortcut && ($event.metaKey || $event.ctrlKey)) { $event.preventDefault(); toggleSidebar(); }"
    style="--sidebar-width: {{ $width }}; --sidebar-width-icon: {{ $widthIcon }}; --sidebar-width-mobile: {{ $widthMobile }};"
    {{ $attributes->merge(['class' => cn('group/sidebar-wrapper flex min-h-svh w-full has-data-[variant=inset]:bg-sidebar', $class)]) }}>
    {{ $slot }}
</div>
