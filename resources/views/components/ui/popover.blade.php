@props([
    'open' => false,
    'class' => '',
])

<div data-slot="popover" x-id="['popover-content']" x-modelable="open"
    x-data="{
        open: @js((bool) $open),
        toggle() { this.open ? this.close() : this.show() },
        show() {
            this.open = true;
            this.$nextTick(() => {
                const content = this.$refs.popoverContent;
                if (! content) return;
                this.fit(content);
                // In priority order: an explicit autofocus target, then the first form control, then anything focusable.
                const target = ['[autofocus], [data-autofocus]', 'input:not([type=hidden]), select, textarea', 'button, a[href], [tabindex]:not([tabindex=\'-1\'])']
                    .map(selector => content.querySelector(selector)).find(Boolean);
                (target || content).focus();
            });
        },
        // Keep top/bottom popovers inside the viewport by nudging them sideways (the enter transition scales from the centre).
        fit(content) {
            if (! ['top', 'bottom'].includes(content.dataset.side)) return;
            content.style.marginLeft = '0px';
            const rect = content.getBoundingClientRect();
            const centre = (rect.left + rect.right) / 2;
            const left = centre - content.offsetWidth / 2;
            const right = centre + content.offsetWidth / 2;
            const gutter = 8;
            const viewport = document.documentElement.clientWidth;
            let shift = 0;
            if (right > viewport - gutter) shift = viewport - gutter - right;
            if (left + shift < gutter) shift = gutter - left;
            if (shift) content.style.marginLeft = shift + 'px';
        },
        close(focusTrigger = true) {
            if (! this.open) return;
            this.open = false;
            if (focusTrigger) this.$nextTick(() => this.$refs.popoverTrigger?.focus());
        },
    }"
    @keydown.escape.stop="close()" @click.outside="close(false)" @focusin.window="! $root.contains($event.target) && close(false)"
    {{ $attributes->merge(['class' => cn('relative inline-block', $class)]) }}>
    {{ $slot }}
</div>
