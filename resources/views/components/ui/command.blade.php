@props([
    'class' => '',
])

{{--
    Filtering and keyboard navigation run on the rendered DOM, so items can be plain Blade loops.
    Selecting an item (click / Enter) dispatches a bubbling `command-select` event with `{ value }`
    and follows the item's `href` when it has one.
--}}
<div data-slot="command" x-id="['command-list']"
    x-data="{
        query: '',
        active: null,
        items() { return [...this.$root.querySelectorAll('[data-slot=command-item]')].filter(el => el.closest('[data-slot=command]') === this.$root) },
        visible() { return this.items().filter(el => ! el.hidden && el.dataset.disabled !== 'true') },
        filter() {
            const terms = this.query.trim().toLowerCase().split(/\s+/).filter(Boolean);
            this.items().forEach(item => {
                const haystack = ((item.dataset.value || '') + ' ' + (item.dataset.keywords || '') + ' ' + item.textContent).toLowerCase();
                item.hidden = ! terms.every(term => haystack.includes(term));
            });
            this.$root.querySelectorAll('[data-slot=command-group]').forEach(group => {
                group.hidden = ! group.querySelector('[data-slot=command-item]:not([hidden])');
            });
            this.$root.querySelectorAll('[data-slot=command-separator]').forEach(sep => sep.hidden = terms.length > 0);
            const empty = this.$root.querySelector('[data-slot=command-empty]');
            if (empty) empty.hidden = this.visible().length > 0;
            this.highlight(this.visible()[0] || null);
        },
        highlight(item, scroll = true) {
            this.items().forEach(el => el.setAttribute('aria-selected', el === item ? 'true' : 'false'));
            this.items().forEach(el => el.toggleAttribute('data-selected', el === item));
            this.active = item ? item.id : null;
            if (item && scroll) item.scrollIntoView({ block: 'nearest' });
        },
        move(step) {
            const items = this.visible();
            if (! items.length) return;
            const index = items.findIndex(el => el.id === this.active);
            const next = step === 'first' ? 0 : step === 'last' ? items.length - 1
                : index === -1 ? (step > 0 ? 0 : items.length - 1) : (index + step + items.length) % items.length;
            this.highlight(items[next]);
        },
        select(item) {
            if (! item || item.dataset.disabled === 'true') return;
            item.dispatchEvent(new CustomEvent('command-select', { bubbles: true, detail: { value: item.dataset.value } }));
            if (item.dataset.href) window.location.href = item.dataset.href;
        },
        key(event) {
            const keys = { ArrowDown: 1, ArrowUp: -1, Home: 'first', End: 'last' };
            if (event.key in keys && (event.key.startsWith('Arrow') || event.target.tagName !== 'INPUT')) { event.preventDefault(); this.move(keys[event.key]); }
            else if (event.key === 'Enter') { event.preventDefault(); this.select(this.items().find(el => el.id === this.active)); }
        },
        init() {
            this.items().forEach((el, i) => el.id ||= this.$id('command-list') + '-item-' + i);
            this.$nextTick(() => this.filter());
        },
    }"
    @keydown="key($event)"
    {{ $attributes->merge(['class' => cn('flex h-full w-full flex-col overflow-hidden rounded-md bg-popover text-popover-foreground', $class)]) }}>
    {{ $slot }}
</div>
