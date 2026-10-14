@props([
    'orientation' => 'horizontal',
    'loop' => false,
    'autoplay' => 0,
    'label' => 'Carousel',
    'class' => '',
])

{{-- Scroll-snap carousel: the browser does the scrolling, Alpine tracks the current slide and drives the controls. --}}
<div data-slot="carousel" role="region" aria-roledescription="carousel" aria-label="{{ $label }}"
    data-orientation="{{ $orientation }}" x-id="['carousel-viewport']"
    x-data="{
        orientation: @js($orientation),
        loop: @js((bool) $loop),
        autoplay: @js((int) $autoplay),
        index: 0,
        count: 0,
        canPrev: @js((bool) $loop),
        canNext: true,
        paused: false,
        timer: null,
        carouselEl: null,
        get vertical() { return this.orientation === 'vertical' },
        get reduced() { return window.matchMedia('(prefers-reduced-motion: reduce)').matches },
        slides() {
            return [...this.viewport().querySelectorAll('[data-slot=carousel-item]')]
                .filter(el => el.closest('[data-slot=carousel]') === this.carouselEl);
        },
        // Slides may hold their own x-data, so keep a handle on the root instead of using $root / $refs in methods.
        viewport() { return [...this.carouselEl.querySelectorAll('[data-slot=carousel-content]')].find(el => el.closest('[data-slot=carousel]') === this.carouselEl) },
        init() {
            this.carouselEl = this.$el;
            this.$nextTick(() => {
                const slides = this.slides();
                slides.forEach((el, i) => el.setAttribute('aria-label', (i + 1) + ' of ' + slides.length));
                this.update();
            });
            if (this.autoplay > 0 && ! this.reduced) {
                this.timer = setInterval(() => { if (! this.paused && ! document.hidden) this.next(true) }, this.autoplay);
            }
        },
        destroy() { clearInterval(this.timer) },
        offset(el) {
            const first = this.slides()[0];
            return this.vertical ? el.offsetTop - first.offsetTop : el.offsetLeft - first.offsetLeft;
        },
        metrics() {
            const v = this.viewport();
            return this.vertical
                ? { pos: v.scrollTop, max: v.scrollHeight - v.clientHeight }
                : { pos: v.scrollLeft, max: v.scrollWidth - v.clientWidth };
        },
        update() {
            const slides = this.slides();
            this.count = slides.length;
            if (! slides.length) return;
            const { pos, max } = this.metrics();
            let best = 0;
            slides.forEach((el, i) => { if (Math.abs(this.offset(el) - pos) < Math.abs(this.offset(slides[best]) - pos)) best = i });
            // At the very end, the last slide is the current one even if several are visible.
            this.index = max > 0 && pos >= max - 1 ? slides.length - 1 : best;
            this.canPrev = this.loop || pos > 1;
            this.canNext = this.loop || pos < max - 1;
        },
        scrollTo(i) {
            const slides = this.slides();
            if (! slides.length) return;
            i = Math.max(0, Math.min(i, slides.length - 1));
            this.viewport().scrollTo({ [this.vertical ? 'top' : 'left']: this.offset(slides[i]), behavior: this.reduced ? 'auto' : 'smooth' });
            this.index = i;
        },
        prev() {
            const { pos } = this.metrics();
            if (pos <= 1) { if (this.loop) this.scrollTo(this.count - 1); return; }
            this.scrollTo(this.index - 1);
        },
        next(wrap = false) {
            const { pos, max } = this.metrics();
            if (pos >= max - 1) { if (this.loop || wrap) this.scrollTo(0); return; }
            this.scrollTo(this.index + 1);
        },
        key(event) {
            const back = this.vertical ? 'ArrowUp' : 'ArrowLeft';
            const forward = this.vertical ? 'ArrowDown' : 'ArrowRight';
            if (event.key === back) { event.preventDefault(); this.prev(); }
            else if (event.key === forward) { event.preventDefault(); this.next(); }
            else if (event.key === 'Home') { event.preventDefault(); this.scrollTo(0); }
            else if (event.key === 'End') { event.preventDefault(); this.scrollTo(this.count - 1); }
        },
    }"
    @keydown="key($event)" @resize.window.debounce.100ms="update()"
    @mouseenter="paused = true" @mouseleave="paused = false"
    @focusin="paused = true" @focusout="paused = carouselEl.contains($event.relatedTarget)"
    {{ $attributes->merge(['class' => cn('relative', $class)]) }}>
    {{ $slot }}
</div>
