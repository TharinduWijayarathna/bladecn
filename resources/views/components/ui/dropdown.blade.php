<div class="relative inline-block text-left"
    x-data="{
        open: false,
        items() { return [...this.$refs.panel.querySelectorAll('a[href], button:not([disabled]), [role^=menuitem]')].filter(el => el.offsetParent !== null) },
        move(step) {
            const items = this.items();
            if (! items.length) return;
            const index = items.indexOf(document.activeElement);
            const next = step === 'first' ? 0 : step === 'last' ? items.length - 1
                : index === -1 ? (step > 0 ? 0 : items.length - 1) : (index + step + items.length) % items.length;
            items[next].focus();
        },
        close(restore = true) {
            if (! this.open) return;
            this.open = false;
            if (restore) this.$refs.trigger.querySelector('button, a, [tabindex]')?.focus();
        },
    }"
    @keydown.escape.stop="close()" @focusin.window="open && ! $root.contains($event.target) && close(false)">
    <div x-ref="trigger" @click="open = ! open" :data-state="open ? 'open' : 'closed'"
        @keydown.arrow-down.prevent="open = true; $nextTick(() => move('first'))"
        @keydown.arrow-up.prevent="open = true; $nextTick(() => move('last'))"
        x-init="$el.querySelector('button, a')?.setAttribute('aria-haspopup', 'menu')"
        x-effect="$el.querySelector('button, a')?.setAttribute('aria-expanded', open ? 'true' : 'false')">
        {{ $trigger ?? '' }}
    </div>

    <div x-ref="panel" role="menu" x-show="open" @click.outside="close(false)" x-transition x-cloak
        @keydown.arrow-down.prevent="move(1)" @keydown.arrow-up.prevent="move(-1)"
        @keydown.home.prevent="move('first')" @keydown.end.prevent="move('last')"
        class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-md border border-input bg-popover text-popover-foreground shadow-md">
        {{ $slot }}
    </div>
</div>
