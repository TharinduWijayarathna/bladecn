@props([
    'name' => null,
    'handle' => false,
    'orientation' => 'vertical',
    'label' => 'Sortable list',
    'class' => '',
])

@php
    $horizontal = $orientation === 'horizontal';
@endphp

{{--
    A reorderable list using native HTML5 drag and drop (no dependencies).
    Each <x-ui.sortable-item> carries a hidden `{name}[]` input, so the form posts the current order.
    Dispatches a bubbling `sorted` event with `{ order, item, from, to }`; `x-model` receives the order array.
--}}
<div data-slot="sortable" data-orientation="{{ $orientation }}" x-id="['sortable-help']" x-modelable="order"
    x-data="{
        order: [],
        dragging: null,
        startIndex: -1,
        announcement: '',
        horizontal: @js($horizontal),
        items() { return [...this.$refs.list.children].filter(el => el.matches('[data-slot=sortable-item]')) },
        read() { return this.items().map(el => el.dataset.value) },
        labelOf(el) { return el.dataset.label || el.textContent.trim() },
        init() {
            this.order = this.read();
            // The DOM is the source of truth: re-sync after x-model entangles (it copies the outer value in first).
            this.$nextTick(() => this.order = this.read());
        },
        start(event) {
            const item = event.target.closest?.('[data-slot=sortable-item]');
            if (! item || ! this.$refs.list.contains(item)) return;
            this.dragging = item;
            this.startIndex = this.items().indexOf(item);
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', item.dataset.value);
            requestAnimationFrame(() => item.setAttribute('data-dragging', 'true'));
        },
        over(event) {
            if (! this.dragging) return;
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
            const target = event.target.closest?.('[data-slot=sortable-item]');
            if (! target || target === this.dragging || ! this.$refs.list.contains(target)) return;
            const rect = target.getBoundingClientRect();
            const after = this.horizontal ? event.clientX > rect.left + rect.width / 2 : event.clientY > rect.top + rect.height / 2;
            this.$refs.list.insertBefore(this.dragging, after ? target.nextSibling : target);
        },
        end() {
            if (! this.dragging) return;
            const item = this.dragging;
            item.removeAttribute('data-dragging');
            item.setAttribute('draggable', @js($handle ? 'false' : 'true'));
            this.dragging = null;
            this.commit(item, this.startIndex);
        },
        commit(item, from) {
            const next = this.read();
            if (JSON.stringify(next) === JSON.stringify(this.order)) return;
            this.order = next;
            const to = next.indexOf(item.dataset.value);
            this.announcement = `${this.labelOf(item)} moved to position ${to + 1} of ${next.length}.`;
            this.$nextTick(() => this.$root.dispatchEvent(new CustomEvent('sorted', { bubbles: true, detail: { order: next, item: item.dataset.value, from, to } })));
        },
        arm(event) { event.target.closest('[data-slot=sortable-item]')?.setAttribute('draggable', 'true') },
        disarm(event) { if (! this.dragging) event.target.closest('[data-slot=sortable-item]')?.setAttribute('draggable', @js($handle ? 'false' : 'true')) },
        key(event) {
            const item = event.target.closest('[data-slot=sortable-item]');
            const items = this.items();
            const from = items.indexOf(item);
            const prev = this.horizontal ? 'ArrowLeft' : 'ArrowUp';
            const next = this.horizontal ? 'ArrowRight' : 'ArrowDown';
            let to = { [prev]: from - 1, [next]: from + 1, Home: 0, End: items.length - 1 }[event.key];
            if (to === undefined) return;
            event.preventDefault();
            to = Math.max(0, Math.min(items.length - 1, to));
            if (to === from) return;
            this.$refs.list.insertBefore(item, to > from ? items[to].nextSibling : items[to]);
            this.commit(item, from);
            this.$nextTick(() => item.querySelector('[data-sortable-handle]')?.focus());
        },
    }"
    @dragstart="start($event)" @dragover="over($event)" @drop.prevent="end()" @dragend="end()"
    {{ $attributes->merge(['class' => cn('w-full', $class)]) }}>
    <ul role="list" x-ref="list" aria-label="{{ $label }}" :aria-describedby="$id('sortable-help')"
        class="flex gap-2 {{ $horizontal ? 'flex-row flex-wrap' : 'flex-col' }}">
        {{ $slot }}
    </ul>
    <p :id="$id('sortable-help')" class="sr-only">Drag items to reorder, or focus a drag handle and use the arrow keys, Home and End.</p>
    <div class="sr-only" aria-live="assertive" aria-atomic="true" x-text="announcement"></div>
</div>
