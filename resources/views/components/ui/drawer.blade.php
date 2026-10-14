@props([
    'open' => false,
    'direction' => 'bottom',
    'dismissible' => true,
    'class' => '',
])

@php
    $axis = in_array($direction, ['left', 'right'], true) ? 'x' : 'y';
    $sign = in_array($direction, ['bottom', 'right'], true) ? 1 : -1;
@endphp

<div data-slot="drawer" data-direction="{{ $direction }}" x-modelable="open" x-id="['drawer-title', 'drawer-description']"
    x-data="{
        open: @js((bool) $open),
        dismissible: @js((bool) $dismissible),
        axis: @js($axis),
        sign: @js($sign),
        dragging: false,
        settling: false,
        offset: 0,
        start: 0,
        startedAt: 0,
        size: 1,
        drawerEl: null,
        init() { this.drawerEl = this.$el },
        close() { if (this.dismissible) this.open = false },
        dragStart(event) {
            if (! this.dismissible || (event.pointerType === 'mouse' && event.button !== 0)) return;
            this.dragging = true;
            this.start = this.axis === 'y' ? event.clientY : event.clientX;
            this.startedAt = Date.now();
            const rect = this.drawerEl.querySelector('[data-slot=drawer-content]').getBoundingClientRect();
            this.size = (this.axis === 'y' ? rect.height : rect.width) || 1;
            event.currentTarget.setPointerCapture?.(event.pointerId);
        },
        dragMove(event) {
            if (! this.dragging) return;
            const delta = ((this.axis === 'y' ? event.clientY : event.clientX) - this.start) * this.sign;
            // Dragging towards the open side is damped instead of blocked.
            this.offset = delta >= 0 ? delta : -Math.sqrt(-delta) * 2;
        },
        dragEnd() {
            if (! this.dragging) return;
            this.dragging = false;
            const velocity = this.offset / Math.max(1, Date.now() - this.startedAt);
            if (this.offset > this.size * 0.3 || (this.offset > 20 && velocity > 0.5)) {
                // Slide the rest of the way out with the inline style, then hide.
                this.offset = this.size;
                this.settling = true;
                setTimeout(() => { this.open = false; setTimeout(() => { this.offset = 0; this.settling = false }, 320) }, 300);
            } else {
                this.offset = 0;
                this.settling = true;
                setTimeout(() => this.settling = false, 300);
            }
        },
        dragStyle() {
            if (! this.dragging && ! this.settling && ! this.offset) return '';
            const px = (this.offset * this.sign) + 'px';
            return 'translate: ' + (this.axis === 'y' ? '0 ' + px : px + ' 0') + ';'
                + (this.dragging ? 'transition: none;' : 'transition: translate 300ms cubic-bezier(0.32, 0.72, 0, 1);');
        },
        overlayStyle() {
            return this.dragging || this.settling ? 'opacity: ' + Math.max(0, 1 - Math.max(0, this.offset) / this.size) + ';' : '';
        },
    }"
    {{ $attributes->merge(['class' => $class ?: null]) }}>
    {{ $slot }}
</div>
