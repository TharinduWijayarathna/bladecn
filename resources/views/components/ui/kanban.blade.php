@props([
    'name' => null,
    'label' => 'Board',
    'class' => '',
])

{{--
    A board of <x-ui.kanban-column>s holding <x-ui.kanban-card>s, reordered with native HTML5 drag and drop.
    With `name`, hidden `{name}[{column}][]` inputs post every card id in order. Dispatches a bubbling
    `sorted` event with `{ columns, item, from, to, index }`; `x-model` receives `{ column: [ids] }`.
--}}
<div data-slot="kanban" role="group" aria-label="{{ $label }}" x-id="['kanban-help']" x-modelable="columns"
    x-data="{
        name: @js($name),
        columns: {},
        dragging: null,
        from: null,
        announcement: '',
        lists() { return [...this.$root.querySelectorAll('[data-slot=kanban-column-list]')] },
        cards(list) { return [...list.children].filter(el => el.matches('[data-slot=kanban-card]')) },
        listOf(card) { return card.closest('[data-slot=kanban-column-list]') },
        read() { return Object.fromEntries(this.lists().map(list => [list.dataset.column, this.cards(list).map(card => card.dataset.value)])) },
        titleOf(list) { return list.getAttribute('aria-label') || list.dataset.column },
        init() {
            this.columns = this.read();
            // The DOM is the source of truth: re-sync after x-model entangles (it copies the outer value in first).
            this.$nextTick(() => this.columns = this.read());
        },
        start(event) {
            const card = event.target.closest?.('[data-slot=kanban-card]');
            if (! card || ! this.$root.contains(card)) return;
            this.dragging = card;
            this.from = this.listOf(card).dataset.column;
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', card.dataset.value);
            requestAnimationFrame(() => card.setAttribute('data-dragging', 'true'));
        },
        over(event) {
            if (! this.dragging) return;
            const list = event.target.closest?.('[data-slot=kanban-column]')?.querySelector('[data-slot=kanban-column-list]');
            if (! list) return;
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
            const before = this.cards(list).find(card => {
                if (card === this.dragging) return false;
                const rect = card.getBoundingClientRect();
                return event.clientY < rect.top + rect.height / 2;
            });
            if (before) { if (before.previousElementSibling !== this.dragging) list.insertBefore(this.dragging, before) }
            else if (this.cards(list).at(-1) !== this.dragging) list.appendChild(this.dragging);
        },
        end() {
            if (! this.dragging) return;
            const card = this.dragging;
            card.removeAttribute('data-dragging');
            this.dragging = null;
            this.commit(card, this.from);
        },
        commit(card, from) {
            const next = this.read();
            if (JSON.stringify(next) === JSON.stringify(this.columns)) return;
            this.columns = next;
            const list = this.listOf(card);
            const to = list.dataset.column;
            const index = next[to].indexOf(card.dataset.value);
            const label = card.dataset.label || card.textContent.trim();
            this.announcement = `${label} moved to ${this.titleOf(list)}, position ${index + 1} of ${next[to].length}.`;
            this.$nextTick(() => this.$root.dispatchEvent(new CustomEvent('sorted', { bubbles: true, detail: { columns: next, item: card.dataset.value, from, to, index } })));
        },
        key(event) {
            const card = event.target.closest('[data-slot=kanban-card]');
            const list = this.listOf(card);
            const lists = this.lists();
            const cards = this.cards(list);
            const index = cards.indexOf(card);
            const from = list.dataset.column;
            if (event.key === 'ArrowUp' || event.key === 'ArrowDown') {
                const to = index + (event.key === 'ArrowUp' ? -1 : 1);
                if (to < 0 || to >= cards.length) return event.preventDefault();
                list.insertBefore(card, event.key === 'ArrowUp' ? cards[to] : cards[to].nextSibling);
            } else if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
                const target = lists[lists.indexOf(list) + (event.key === 'ArrowLeft' ? -1 : 1)];
                if (! target) return event.preventDefault();
                const targetCards = this.cards(target);
                targetCards[index] ? target.insertBefore(card, targetCards[index]) : target.appendChild(card);
            } else return;
            event.preventDefault();
            this.commit(card, from);
            this.$nextTick(() => card.querySelector('[data-kanban-handle]')?.focus());
        },
    }"
    @dragstart="start($event)" @dragover="over($event)" @drop.prevent="end()" @dragend="end()"
    {{ $attributes->merge(['class' => cn('flex w-full gap-4 overflow-x-auto pb-2', $class)]) }}>
    {{ $slot }}
    <p :id="$id('kanban-help')" class="sr-only">Drag cards between columns, or focus a card's drag handle and use the arrow keys: up and down to reorder, left and right to move to another column.</p>
    <div class="sr-only" aria-live="assertive" aria-atomic="true" x-text="announcement"></div>
    <template x-if="name">
        <div hidden>
            <template x-for="(ids, column) in columns" :key="column">
                <div>
                    <template x-for="id in ids" :key="id">
                        <input type="hidden" :name="`${name}[${column}][]`" :value="id">
                    </template>
                </div>
            </template>
        </div>
    </template>
</div>
