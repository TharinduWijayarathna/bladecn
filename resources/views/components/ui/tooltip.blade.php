<span class="tooltip-wrapper relative inline-flex" data-tooltip data-tooltip-side="{{ $side }}"
    data-tooltip-offset="{{ $sideOffset }}">
    {{ $slot }}

    <span class="tooltip-content {{ $tooltipClasses() }}" role="tooltip" data-side="{{ $side }}" data-state="closed">
        {{ $attributes->get('content') }}
    </span>
</span>

@pushOnce('scripts')
    <script>
        (function() {
            const GAP = 8; // keep the bubble this far from the viewport edge

            function place(trigger, content, side, offset) {
                // A transformed ancestor (e.g. a dialog) becomes the containing block
                // of a fixed element, so measure its origin and compensate.
                content.style.left = '0px';
                content.style.top = '0px';
                const origin = content.getBoundingClientRect();
                const r = trigger.getBoundingClientRect();
                const w = content.offsetWidth;
                const h = content.offsetHeight;
                const vw = document.documentElement.clientWidth;
                const vh = document.documentElement.clientHeight;

                // Flip to the opposite side when there is no room.
                if (side === 'top' && r.top - h - offset < GAP) side = 'bottom';
                else if (side === 'bottom' && r.bottom + h + offset > vh - GAP) side = 'top';
                else if (side === 'left' && r.left - w - offset < GAP) side = 'right';
                else if (side === 'right' && r.right + w + offset > vw - GAP) side = 'left';

                let top, left;
                if (side === 'top' || side === 'bottom') {
                    left = r.left + r.width / 2 - w / 2;
                    top = side === 'top' ? r.top - h - offset : r.bottom + offset;
                } else {
                    top = r.top + r.height / 2 - h / 2;
                    left = side === 'left' ? r.left - w - offset : r.right + offset;
                }

                content.style.left = Math.round(Math.min(Math.max(left, GAP), vw - w - GAP) - origin.left) + 'px';
                content.style.top = Math.round(Math.min(Math.max(top, GAP), vh - h - GAP) - origin.top) + 'px';
                content.dataset.side = side;
            }

            function init(wrapper) {
                if (wrapper.dataset.tooltipReady) return;
                wrapper.dataset.tooltipReady = '1';

                const trigger = wrapper.firstElementChild;
                const content = wrapper.querySelector(':scope > .tooltip-content');
                if (!trigger || trigger === content) return;

                const side = wrapper.dataset.tooltipSide || 'top';
                const offset = parseInt(wrapper.dataset.tooltipOffset, 10) || 4;
                content.id ||= 'tooltip-' + Math.random().toString(36).slice(2, 9);
                trigger.setAttribute('aria-describedby', content.id);

                let timer;
                const show = () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => {
                        place(trigger, content, side, offset);
                        content.dataset.state = 'open';
                    }, 100);
                };
                const hide = () => {
                    clearTimeout(timer);
                    content.dataset.state = 'closed';
                };

                trigger.addEventListener('mouseenter', show);
                trigger.addEventListener('mouseleave', hide);
                trigger.addEventListener('focusin', show);
                trigger.addEventListener('focusout', hide);
                trigger.addEventListener('keydown', (event) => event.key === 'Escape' && hide());
                window.addEventListener('scroll', hide, true);
            }

            function boot() { document.querySelectorAll('[data-tooltip]').forEach(init); }
            document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', boot) : boot();
        })();
    </script>
@endPushOnce
