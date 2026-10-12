@props([
    'class' => '',
])

{{--
    Menus, sub menus and checkbox items have their own x-data scopes, so the bar keeps a handle on its
    element (`menubarEl`) instead of relying on $root / $refs inside its methods.
--}}
<div role="menubar" data-slot="menubar" aria-orientation="horizontal"
    x-data="{
        active: null,
        menubarEl: null,
        init() {
            this.menubarEl = this.$el;
            this.$nextTick(() => this.rove(this.triggers()[0]));
        },
        triggers() {
            return [...this.menubarEl.querySelectorAll('[data-slot=menubar-trigger]')].filter(el => el.closest('[data-slot=menubar]') === this.menubarEl);
        },
        rove(current) { this.triggers().forEach(el => el.tabIndex = el === current ? 0 : -1) },
        menu(value) { return [...this.menubarEl.querySelectorAll('[data-slot=menubar-menu]')].find(el => el.dataset.value === value) },
        contentOf(value) { return this.menu(value)?.querySelector('[data-slot=menubar-content]') },
        triggerOf(value) { return this.menu(value)?.querySelector('[data-slot=menubar-trigger]') },
        items(menu) {
            return [...menu.querySelectorAll('[role^=menuitem]:not([aria-disabled=true])')].filter(el => el.closest('[role=menu]') === menu);
        },
        focusItem(menu, step) {
            if (! menu) return;
            const items = this.items(menu);
            if (! items.length) return menu.focus();
            const index = items.indexOf(document.activeElement);
            const next = step === 'first' ? 0 : step === 'last' ? items.length - 1
                : index === -1 ? (step > 0 ? 0 : items.length - 1) : (index + step + items.length) % items.length;
            items[next].focus();
        },
        openMenu(value, focus = null) {
            this.active = value;
            this.rove(this.triggerOf(value));
            this.$nextTick(() => focus ? this.focusItem(this.contentOf(value), focus) : null);
        },
        closeAll(restore = true) {
            const value = this.active;
            this.active = null;
            if (restore && value !== null) this.triggerOf(value)?.focus();
        },
        adjacent(value, step) {
            const triggers = this.triggers();
            const index = triggers.indexOf(this.triggerOf(value));
            return triggers[(index + step + triggers.length) % triggers.length];
        },
        switchTo(value, step, focus) {
            const trigger = this.adjacent(value, step);
            if (! trigger) return;
            const next = trigger.closest('[data-slot=menubar-menu]').dataset.value;
            trigger.focus();
            if (this.active !== null) this.openMenu(next, focus);
        },
        triggerKey(event, value) {
            const keys = { ArrowRight: 1, ArrowLeft: -1 };
            if (event.key in keys) { event.preventDefault(); this.switchTo(value, keys[event.key], null); }
            else if (event.key === 'ArrowDown') { event.preventDefault(); this.openMenu(value, 'first'); }
            else if (event.key === 'ArrowUp') { event.preventDefault(); this.openMenu(value, 'last'); }
            else if (event.key === 'Home' || event.key === 'End') {
                event.preventDefault();
                const triggers = this.triggers();
                (event.key === 'Home' ? triggers[0] : triggers[triggers.length - 1])?.focus();
            }
        },
        menuKey(event, value) {
            const menu = event.currentTarget;
            const keys = { ArrowDown: 1, ArrowUp: -1, Home: 'first', End: 'last' };
            if (event.key in keys) { event.preventDefault(); this.focusItem(menu, keys[event.key]); }
            else if (event.key === 'ArrowRight') { event.preventDefault(); this.switchTo(value, 1, 'first'); }
            else if (event.key === 'ArrowLeft') { event.preventDefault(); this.switchTo(value, -1, 'first'); }
            else if (event.key === 'Escape') { event.preventDefault(); this.closeAll(); }
            else if (event.key === 'Tab') { this.closeAll(false); }
            else if (event.key.length === 1 && /\S/.test(event.key)) {
                // Typeahead: jump to the next item starting with the typed character.
                const items = this.items(menu);
                const start = items.indexOf(document.activeElement) + 1;
                [...items.slice(start), ...items.slice(0, start)]
                    .find(el => el.textContent.trim().toLowerCase().startsWith(event.key.toLowerCase()))?.focus();
            }
        },
    }"
    @click.outside="closeAll(false)" @focusin.window="active !== null && ! menubarEl.contains($event.target) && closeAll(false)"
    {{ $attributes->merge(['class' => cn('flex h-9 items-center gap-1 rounded-md border bg-background p-1 shadow-xs', $class)]) }}>
    {{ $slot }}
</div>
