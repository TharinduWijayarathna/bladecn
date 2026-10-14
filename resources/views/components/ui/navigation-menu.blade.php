@props([
    'delay' => 150,
    'closeDelay' => 250,
    'indicator' => false,
    'label' => 'Main',
    'class' => '',
])

<nav data-slot="navigation-menu" aria-label="{{ $label }}"
    x-data="{
        active: null,
        timer: null,
        delay: @js((int) $delay),
        closeDelay: @js((int) $closeDelay),
        navEl: null,
        // Items have their own x-data scope, so keep a handle on the <nav> instead of using $root / $refs.
        init() { this.navEl = this.$el },
        // Nudge an open panel sideways so it stays inside the viewport (the enter transition scales from the centre).
        fit(panel) {
            panel.style.marginLeft = '0px';
            const rect = panel.getBoundingClientRect();
            const centre = (rect.left + rect.right) / 2;
            const left = centre - panel.offsetWidth / 2;
            const right = centre + panel.offsetWidth / 2;
            const gutter = 8;
            const viewport = document.documentElement.clientWidth;
            let shift = 0;
            if (right > viewport - gutter) shift = viewport - gutter - right;
            if (left + shift < gutter) shift = gutter - left;
            if (shift) panel.style.marginLeft = shift + 'px';
        },
        open(value, delay = 0) {
            clearTimeout(this.timer);
            // Once a menu is open, moving to another trigger switches instantly.
            if (delay && this.active === null) this.timer = setTimeout(() => this.active = value, delay);
            else this.active = value;
        },
        close(delay = 0) {
            clearTimeout(this.timer);
            if (delay) this.timer = setTimeout(() => this.active = null, delay);
            else this.active = null;
        },
        toggle(value) { clearTimeout(this.timer); this.active = this.active === value ? null : value },
        topLevel() {
            return [...this.navEl.querySelectorAll('[data-slot=navigation-menu-trigger], [data-slot=navigation-menu-link]')]
                .filter(el => ! el.closest('[data-slot=navigation-menu-content]') && el.closest('[data-slot=navigation-menu]') === this.navEl);
        },
        moveTop(event, step) {
            const items = this.topLevel();
            const index = items.indexOf(event.currentTarget);
            const next = step === 'first' ? items[0] : step === 'last' ? items[items.length - 1]
                : items[(index + step + items.length) % items.length];
            next?.focus();
        },
        topKey(event) {
            const keys = { ArrowRight: 1, ArrowLeft: -1, Home: 'first', End: 'last' };
            if (event.key in keys) { event.preventDefault(); this.moveTop(event, keys[event.key]); }
        },
        links(panel) { return [...panel.querySelectorAll('a[href], button:not([disabled]), [tabindex]:not([tabindex=\'-1\'])')] },
        enterPanel(value) {
            this.open(value);
            this.$nextTick(() => this.links(this.panel(value))[0]?.focus());
        },
        panel(value) { return this.navEl.querySelector('[data-slot=navigation-menu-content][data-value=\'' + value + '\']') },
        trigger(value) { return this.navEl.querySelector('[data-slot=navigation-menu-trigger][data-value=\'' + value + '\']') },
        panelKey(event, value) {
            const links = this.links(event.currentTarget);
            const index = links.indexOf(document.activeElement);
            if (event.key === 'ArrowDown') { event.preventDefault(); links[(index + 1) % links.length]?.focus(); }
            else if (event.key === 'ArrowUp') { event.preventDefault(); links[(index - 1 + links.length) % links.length]?.focus(); }
            else if (event.key === 'Escape') { event.preventDefault(); this.close(); this.trigger(value)?.focus(); }
        },
        indicatorStyle() {
            const trigger = this.active === null ? null : this.trigger(this.active);
            if (! trigger) return 'opacity: 0;';
            const root = this.navEl.getBoundingClientRect();
            const rect = trigger.getBoundingClientRect();
            return 'left: ' + (rect.left - root.left) + 'px; width: ' + rect.width + 'px; opacity: 1;';
        },
    }"
    @mouseleave="close(closeDelay)" @mouseenter="clearTimeout(timer)"
    @focusout="! navEl.contains($event.relatedTarget) && close()" @keydown.escape="close()" @click.outside="close()"
    {{ $attributes->merge(['class' => cn('relative z-10 flex max-w-max flex-1 items-center justify-center', $class)]) }}>
    {{ $slot }}

    @if ($indicator)
        <div data-slot="navigation-menu-indicator" aria-hidden="true" :style="indicatorStyle()" style="opacity: 0;"
            class="pointer-events-none absolute top-full z-[1] flex h-1.5 items-end justify-center overflow-hidden transition-all duration-200">
            <div class="relative top-[60%] size-2 rotate-45 rounded-tl-sm bg-border shadow-md"></div>
        </div>
    @endif
</nav>
