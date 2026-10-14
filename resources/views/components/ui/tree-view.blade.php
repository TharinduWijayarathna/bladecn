@props([
    'items' => [],
    'checkboxes' => false,
    'name' => null,
    'checked' => [],
    'expanded' => [],
    'selected' => null,
    'label' => 'Tree',
    'class' => '',
])

@php
    // Flatten the nested items into rows (ARIA allows a flat tree with aria-level / setsize / posinset).
    $rows = [];
    $walk = function (array $nodes, int $level, array $ancestors) use (&$walk, &$rows) {
        $position = 0;
        foreach ($nodes as $node) {
            $position++;
            $id = (string) ($node['id'] ?? $node['value'] ?? $node['label']);
            $children = $node['children'] ?? [];
            $rows[] = [
                'id' => $id,
                'label' => $node['label'] ?? $id,
                'level' => $level,
                'ancestors' => $ancestors,
                'parent' => end($ancestors) ?: null,
                'branch' => $children !== [],
                'setsize' => count($nodes),
                'posinset' => $position,
                'disabled' => (bool) ($node['disabled'] ?? false),
            ];
            if ($children !== []) {
                $walk($children, $level + 1, [...$ancestors, $id]);
            }
        }
    };
    $walk(array_values($items), 1, []);

    // Leaves under every node; a branch is checked when all of its leaves are.
    $leaves = [];
    foreach ($rows as $row) {
        if (! $row['branch']) {
            foreach ([$row['id'], ...$row['ancestors']] as $owner) {
                $leaves[$owner][] = $row['id'];
            }
        }
    }

    $checkedLeaves = [];
    foreach ((array) $checked as $id) {
        foreach ($leaves[(string) $id] ?? [] as $leaf) {
            $checkedLeaves[$leaf] = true;
        }
    }
    $stateOf = function (string $id) use ($leaves, $checkedLeaves) {
        $own = $leaves[$id] ?? [];
        $on = count(array_filter($own, fn ($leaf) => isset($checkedLeaves[$leaf])));

        return $own === [] || $on === 0 ? 'false' : ($on === count($own) ? 'true' : 'mixed');
    };

    $open = $expanded === true || $expanded === 'all'
        ? array_fill_keys(array_column(array_filter($rows, fn ($row) => $row['branch']), 'id'), true)
        : array_fill_keys(array_map('strval', (array) $expanded), true);
    $visible = fn (array $row) => collect($row['ancestors'])->every(fn ($id) => isset($open[$id]));
    $checkboxes = filter_var($checkboxes, FILTER_VALIDATE_BOOLEAN);
    $focus = $selected !== null ? (string) $selected : ($rows[0]['id'] ?? null);
@endphp

{{--
    A hierarchical list. Branches expand and collapse; with `checkboxes`, checking a branch checks every
    descendant and partially checked branches show as indeterminate. Checked ids post as `{name}[]`.
    Dispatches `select` with `{ id }`, `toggle` with `{ id, expanded }` and `change` with `{ checked }`.
--}}
<ul role="tree" data-slot="tree-view" aria-label="{{ $label }}" @if ($checkboxes) aria-multiselectable="true" @endif
    x-data="{
        open: @js((object) $open),
        on: @js((object) $checkedLeaves),
        leaves: @js((object) $leaves),
        selected: @js($selected !== null ? (string) $selected : null),
        focused: @js($focus),
        checkboxes: @js($checkboxes),
        rows() { return [...this.$root.querySelectorAll('[role=treeitem]')].filter(el => el.style.display !== 'none') },
        visible(ancestors) { return ancestors.every(id => this.open[id]) },
        state(id) {
            const own = this.leaves[id] || [];
            const count = own.filter(leaf => this.on[leaf]).length;
            return own.length === 0 || count === 0 ? 'false' : (count === own.length ? 'true' : 'mixed');
        },
        toggle(id, force) {
            const expanded = force ?? ! this.open[id];
            if (!! this.open[id] === expanded) return;
            this.open = { ...this.open, [id]: expanded };
            this.$root.dispatchEvent(new CustomEvent('toggle', { bubbles: true, detail: { id, expanded } }));
        },
        select(id) {
            this.selected = id;
            this.focused = id;
            this.$root.dispatchEvent(new CustomEvent('select', { bubbles: true, detail: { id } }));
        },
        check(id) {
            const value = this.state(id) !== 'true';
            const next = { ...this.on };
            (this.leaves[id] || []).forEach(leaf => value ? next[leaf] = true : delete next[leaf]);
            this.on = next;
            this.$nextTick(() => this.$root.dispatchEvent(new CustomEvent('change', { bubbles: true, detail: { checked: Object.keys(this.leaves).filter(key => this.state(key) === 'true') } })));
        },
        activate(id) { this.checkboxes ? this.check(id) : this.select(id) },
        move(el) {
            if (! el) return;
            this.focused = el.dataset.id;
            el.focus();
        },
        key(event) {
            const el = event.currentTarget;
            const id = el.dataset.id;
            const rows = this.rows();
            const index = rows.indexOf(el);
            const branch = el.hasAttribute('aria-expanded');
            switch (event.key) {
                case 'ArrowDown': this.move(rows[index + 1]); break;
                case 'ArrowUp': this.move(rows[index - 1]); break;
                case 'Home': this.move(rows[0]); break;
                case 'End': this.move(rows.at(-1)); break;
                case 'ArrowRight':
                    if (branch && ! this.open[id]) this.toggle(id, true);
                    else if (branch) this.move(rows[index + 1]);
                    break;
                case 'ArrowLeft':
                    if (branch && this.open[id]) this.toggle(id, false);
                    else this.move(rows.find(row => row.dataset.id === el.dataset.parent));
                    break;
                case 'Enter': branch && ! this.checkboxes ? this.toggle(id) : this.activate(id); break;
                case ' ': this.activate(id); break;
                default: return;
            }
            event.preventDefault();
            event.stopPropagation();
        },
    }"
    {{ $attributes->merge(['class' => cn('flex flex-col gap-px text-sm', $class)]) }}>
    @foreach ($rows as $row)
        @php
            $state = $stateOf($row['id']);
            $isOpen = isset($open[$row['id']]);
            $rowId = \Illuminate\Support\Js::from($row['id']);
            $onClick = $row['disabled'] ? '' : ($checkboxes ? 'focused = $el.dataset.id' : ($row['branch'] ? "toggle({$rowId}); select({$rowId})" : "select({$rowId})"));
            $style = 'padding-left: '.(0.25 + ($row['level'] - 1) * 1.25).'rem;'.($visible($row) ? '' : ' display: none;');
        @endphp
        <li role="treeitem" data-slot="tree-view-item" data-id="{{ $row['id'] }}" @if ($row['parent'] !== null) data-parent="{{ $row['parent'] }}" @endif
            aria-level="{{ $row['level'] }}" aria-setsize="{{ $row['setsize'] }}" aria-posinset="{{ $row['posinset'] }}"
            @if ($row['branch']) aria-expanded="{{ $isOpen ? 'true' : 'false' }}" :aria-expanded="(!! open[@js($row['id'])]).toString()" @endif
            @if ($checkboxes)
                aria-checked="{{ $state }}" :aria-checked="state(@js($row['id']))"
            @else
                aria-selected="{{ $selected !== null && (string) $selected === $row['id'] ? 'true' : 'false' }}" :aria-selected="(selected === @js($row['id'])).toString()"
            @endif
            @if ($row['disabled']) aria-disabled="true" @endif
            tabindex="{{ $row['id'] === $focus ? 0 : -1 }}" :tabindex="focused === @js($row['id']) ? 0 : -1"
            style="{{ $style }}" x-show="visible(@js($row['ancestors']))"
            @keydown="key($event)" @focus="focused = $el.dataset.id"
            @click.stop="{{ $onClick }}"
            class="group flex cursor-default select-none items-center gap-1.5 rounded-md py-1.5 pr-2 outline-none hover:bg-accent hover:text-accent-foreground focus-visible:ring-[3px] focus-visible:ring-ring/50 aria-selected:bg-accent aria-selected:font-medium aria-selected:text-accent-foreground aria-disabled:pointer-events-none aria-disabled:opacity-50"
            >
            @if ($row['branch'])
                <span aria-hidden="true" @click.stop="toggle(@js($row['id']))"
                    class="inline-flex size-4 shrink-0 items-center justify-center rounded-sm text-muted-foreground transition-transform group-aria-expanded:rotate-90 hover:text-foreground">
                    <x-icons.chevron-right class="size-3.5" />
                </span>
            @else
                <span aria-hidden="true" class="size-4 shrink-0"></span>
            @endif
            @if ($checkboxes)
                <input type="checkbox" tabindex="-1" aria-hidden="true" @if ($name) name="{{ $name }}[]" @endif value="{{ $row['id'] }}"
                    @checked($state === 'true') @disabled($row['disabled'])
                    x-effect="$el.checked = state(@js($row['id'])) === 'true'; $el.indeterminate = state(@js($row['id'])) === 'mixed'"
                    @click.stop="check(@js($row['id'])); focused = @js($row['id'])" @change.stop
                    class="size-4 shrink-0 rounded-[4px] border border-input accent-primary">
            @endif
            <span data-slot="tree-view-label" class="truncate">{{ $row['label'] }}</span>
        </li>
    @endforeach
</ul>
