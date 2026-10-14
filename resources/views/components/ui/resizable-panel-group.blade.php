@props([
    'direction' => 'horizontal',
    'autoSaveId' => null,
    'keyboardStep' => 10,
    'class' => '',
])

{{-- Panels are flex items sized by flex-grow (their percentage); handles move the boundary between two neighbours. --}}
<div data-slot="resizable-panel-group" data-panel-group-direction="{{ $direction }}"
    x-data="{
        direction: @js($direction),
        storageKey: @js($autoSaveId ? 'bladecn:resizable:'.$autoSaveId : null),
        step: @js((float) $keyboardStep),
        sizes: [],
        groupEl: null,
        drag: null,
        init() {
            this.groupEl = this.$el;
            const panels = this.panels();
            const given = panels.map(el => el.dataset.defaultSize === undefined ? null : parseFloat(el.dataset.defaultSize));
            const rest = Math.max(0, 100 - given.reduce((sum, size) => sum + (size ?? 0), 0));
            const unsized = given.filter(size => size === null).length;
            let sizes = given.map(size => size ?? (unsized ? rest / unsized : 0));
            try {
                const saved = this.storageKey && JSON.parse(localStorage.getItem(this.storageKey) || 'null');
                if (Array.isArray(saved) && saved.length === panels.length) sizes = saved;
            } catch (e) {}
            this.sizes = sizes.map((size, i) => this.clamp(i, size));
            this.apply();
        },
        panels() {
            return [...this.groupEl.children].filter(el => el.dataset.slot === 'resizable-panel');
        },
        limits(i) {
            const el = this.panels()[i];
            return { min: parseFloat(el?.dataset.minSize ?? 0), max: parseFloat(el?.dataset.maxSize ?? 100) };
        },
        clamp(i, size) { const { min, max } = this.limits(i); return Math.min(max, Math.max(min, size)) },
        apply() {
            this.panels().forEach((el, i) => {
                el.style.flexGrow = this.sizes[i];
                el.dataset.panelSize = this.sizes[i].toFixed(1);
            });
            [...this.groupEl.children].filter(el => el.dataset.slot === 'resizable-handle').forEach(handle => {
                const before = this.neighbours(handle)[0];
                const { min, max } = this.limits(before);
                handle.setAttribute('aria-valuenow', Math.round(this.sizes[before]));
                handle.setAttribute('aria-valuemin', Math.round(min));
                handle.setAttribute('aria-valuemax', Math.round(max));
            });
        },
        neighbours(handle) {
            const panels = this.panels();
            let before = handle.previousElementSibling, after = handle.nextElementSibling;
            while (before && before.dataset.slot !== 'resizable-panel') before = before.previousElementSibling;
            while (after && after.dataset.slot !== 'resizable-panel') after = after.nextElementSibling;
            return [panels.indexOf(before), panels.indexOf(after)];
        },
        // Move the boundary between panels a and b by delta percent, honouring both panels' min/max.
        resize(a, b, delta, from = null) {
            if (a < 0 || b < 0) return;
            const base = from ?? [this.sizes[a], this.sizes[b]];
            const total = base[0] + base[1];
            let first = this.clamp(a, base[0] + delta);
            let second = this.clamp(b, total - first);
            first = total - second;
            this.sizes[a] = first;
            this.sizes[b] = second;
            this.apply();
        },
        save() {
            if (this.storageKey) try { localStorage.setItem(this.storageKey, JSON.stringify(this.sizes)) } catch (e) {}
            this.$dispatch('resizable-change', { sizes: [...this.sizes] });
        },
        start(event) {
            if (event.pointerType === 'mouse' && event.button !== 0) return;
            const handle = event.currentTarget;
            const [a, b] = this.neighbours(handle);
            const rect = this.groupEl.getBoundingClientRect();
            this.drag = {
                a, b,
                from: [this.sizes[a], this.sizes[b]],
                origin: this.direction === 'vertical' ? event.clientY : event.clientX,
                length: (this.direction === 'vertical' ? rect.height : rect.width) || 1,
            };
            handle.setPointerCapture?.(event.pointerId);
            handle.dataset.state = 'drag';
            event.preventDefault();
        },
        move(event) {
            if (! this.drag) return;
            const position = this.direction === 'vertical' ? event.clientY : event.clientX;
            this.resize(this.drag.a, this.drag.b, (position - this.drag.origin) / this.drag.length * 100, this.drag.from);
        },
        end(event) {
            if (! this.drag) return;
            this.drag = null;
            event.currentTarget.dataset.state = 'inactive';
            this.save();
        },
        key(event) {
            const [a, b] = this.neighbours(event.currentTarget);
            const back = this.direction === 'vertical' ? 'ArrowUp' : 'ArrowLeft';
            const forward = this.direction === 'vertical' ? 'ArrowDown' : 'ArrowRight';
            let delta = null;
            if (event.key === back) delta = -this.step;
            else if (event.key === forward) delta = this.step;
            else if (event.key === 'Home') delta = -100;
            else if (event.key === 'End') delta = 100;
            if (delta === null) return;
            event.preventDefault();
            this.resize(a, b, delta);
            this.save();
        },
    }"
    {{ $attributes->merge(['class' => cn(
        'flex h-full w-full',
        $direction === 'vertical' ? 'flex-col' : null,
        $class,
    )]) }}>
    {{ $slot }}
</div>
