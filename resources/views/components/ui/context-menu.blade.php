@props([
    'class' => '',
])

<div data-slot="context-menu" x-id="['context-menu']"
    x-data="{
        open: false,
        x: 0,
        y: 0,
        returnFocus: null,
        items() { return [...this.$refs.menu.querySelectorAll('[role^=menuitem]:not([aria-disabled=true])')] },
        show(x, y) {
            this.returnFocus = document.activeElement;
            this.x = x; this.y = y; this.open = true;
            this.$nextTick(() => {
                const menu = this.$refs.menu;
                // Keep the menu inside the viewport, compensating for a transformed ancestor.
                menu.style.left = '0px'; menu.style.top = '0px';
                const origin = menu.getBoundingClientRect();
                const vw = document.documentElement.clientWidth, vh = document.documentElement.clientHeight;
                const left = Math.max(4, Math.min(this.x, vw - menu.offsetWidth - 4));
                const top = Math.max(4, Math.min(this.y, vh - menu.offsetHeight - 4));
                menu.style.left = (left - origin.left) + 'px';
                menu.style.top = (top - origin.top) + 'px';
                menu.focus();
            });
        },
        openFromKeyboard(event) {
            const rect = event.currentTarget.getBoundingClientRect();
            this.show(rect.left + rect.width / 2, rect.top + rect.height / 2);
        },
        close(restore = true) {
            if (! this.open) return;
            this.open = false;
            if (restore) this.returnFocus?.focus?.();
        },
        move(step) {
            const items = this.items();
            if (! items.length) return;
            const index = items.indexOf(document.activeElement);
            const next = step === 'first' ? 0 : step === 'last' ? items.length - 1
                : index === -1 ? (step > 0 ? 0 : items.length - 1) : (index + step + items.length) % items.length;
            items[next].focus();
        },
        key(event) {
            const keys = { ArrowDown: 1, ArrowUp: -1, Home: 'first', End: 'last' };
            if (event.key in keys) { event.preventDefault(); this.move(keys[event.key]); }
            else if (event.key === 'Escape') { event.preventDefault(); this.close(); }
            else if (event.key === 'Tab') { event.preventDefault(); }
            else if (event.key.length === 1 && /\S/.test(event.key)) {
                // Typeahead: jump to the next item starting with the typed character.
                const items = this.items();
                const start = items.indexOf(document.activeElement) + 1;
                const match = [...items.slice(start), ...items.slice(0, start)]
                    .find(el => el.textContent.trim().toLowerCase().startsWith(event.key.toLowerCase()));
                match?.focus();
            }
        },
    }"
    @scroll.window="close(false)" @resize.window="close(false)"
    {{ $attributes->merge(['class' => $class ?: null]) }}>
    {{ $slot }}
</div>
