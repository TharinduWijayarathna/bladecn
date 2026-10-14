@props([
    'rows' => [],
    'columns' => [],
    'rowKey' => 'id',
    'searchable' => true,
    'searchParam' => 'search',
    'searchPlaceholder' => 'Search...',
    'sortParam' => 'sort',
    'directionParam' => 'direction',
    'action' => null,
    'selectable' => false,
    'selectName' => 'selected',
    'form' => null,
    'columnToggle' => true,
    'storageKey' => null,
    'emptyMessage' => 'No results.',
    'onEachSide' => 1,
    'class' => '',
])

@php
    /*
     * Normalise column definitions. Accepted shapes:
     *   ['name' => 'Name']                                  key => label
     *   ['key' => 'name', 'label' => 'Name', 'sortable' => true, 'hideable' => true,
     *    'hidden' => false, 'class' => '', 'headerClass' => '', 'format' => fn ($value, $row) => ...]
     */
    $columns = collect($columns)
        ->map(function ($column, $key) {
            $column = is_array($column) ? $column : ['label' => (string) $column];
            $column['key'] ??= is_string($key) ? $key : null;

            return $column + [
                'label' => \Illuminate\Support\Str::headline((string) $column['key']),
                'sortable' => false,
                'hideable' => true,
                'hidden' => false,
                'class' => '',
                'headerClass' => '',
                'format' => null,
            ];
        })
        ->filter(fn ($column) => filled($column['key']))
        ->values();

    $isPaginator = $rows instanceof \Illuminate\Contracts\Pagination\Paginator;
    if ($isPaginator && method_exists($rows, 'withQueryString')) {
        // Keep ?search=&sort=&direction= on the pagination links.
        $rows->withQueryString();
    }
    $items = collect($isPaginator ? $rows->items() : $rows)->values();

    $request = request();
    $currentSort = (string) $request->query($sortParam, '');
    $currentDirection = strtolower((string) $request->query($directionParam, 'asc')) === 'desc' ? 'desc' : 'asc';
    $searchValue = (string) $request->query($searchParam, '');
    $action ??= $request->url();

    $sortUrl = function (string $key) use ($request, $currentSort, $currentDirection, $sortParam, $directionParam) {
        $direction = $currentSort === $key && $currentDirection === 'asc' ? 'desc' : 'asc';

        return $request->fullUrlWithQuery([$sortParam => $key, $directionParam => $direction, 'page' => null]);
    };

    $keyOf = fn ($row, $index) => (string) (data_get($row, $rowKey) ?? $index);
    $rowKeys = $items->map(fn ($row, $index) => $keyOf($row, $index))->all();
    $hiddenByDefault = $columns->where('hidden', true)->pluck('key')->values()->all();
    $hideable = $columns->where('hideable', true)->values();
    $colspan = $columns->count() + ($selectable ? 1 : 0);

    // Search keeps the other query parameters except the page.
    $preserved = collect($request->query())->except([$searchParam, 'page'])->filter(fn ($value) => is_scalar($value));
@endphp

<div data-slot="data-table"
    x-data="{
        hidden: @js($hiddenByDefault),
        selected: [],
        keys: @js($rowKeys),
        storageKey: @js($storageKey),
        init() {
            if (this.storageKey) {
                try {
                    const stored = JSON.parse(localStorage.getItem(this.storageKey) ?? 'null');
                    if (Array.isArray(stored)) this.hidden = stored;
                } catch (e) {}
                this.$watch('hidden', value => localStorage.setItem(this.storageKey, JSON.stringify(value)));
            }
        },
        isVisible(key) { return ! this.hidden.includes(key) },
        toggleColumn(key) { this.hidden = this.isVisible(key) ? [...this.hidden, key] : this.hidden.filter(k => k !== key) },
        get allSelected() { return this.keys.length > 0 && this.selected.length === this.keys.length },
        get someSelected() { return this.selected.length > 0 && ! this.allSelected },
        toggleAll() { this.selected = this.allSelected ? [] : [...this.keys] },
    }"
    {{ $attributes->merge(['class' => cn('flex w-full flex-col gap-4', $class)]) }}>

    @if ($searchable || ($columnToggle && $hideable->isNotEmpty()) || isset($toolbar))
        <div data-slot="data-table-toolbar" class="flex flex-wrap items-center gap-2">
            @if ($searchable)
                <form method="GET" action="{{ $action }}" role="search" class="flex flex-1 items-center gap-2">
                    @foreach ($preserved as $param => $value)
                        <input type="hidden" name="{{ $param }}" value="{{ $value }}">
                    @endforeach
                    <div class="relative w-full max-w-sm">
                        <x-icons.search class="pointer-events-none absolute left-2.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" aria-hidden="true" />
                        <x-ui.input type="search" :name="$searchParam" :value="$searchValue" :placeholder="$searchPlaceholder"
                            aria-label="{{ $searchPlaceholder }}" class="pl-8" />
                    </div>
                    <x-ui.button type="submit" variant="outline" class="sr-only focus:not-sr-only">Search</x-ui.button>
                </form>
            @endif

            {{ $toolbar ?? '' }}

            @if ($columnToggle && $hideable->isNotEmpty())
                <div class="relative ml-auto" x-data="{ open: false }" x-on:keydown.escape.stop="open = false; $refs.columnsButton.focus()"
                    x-on:click.outside="open = false">
                    <x-ui.button type="button" variant="outline" size="sm" x-ref="columnsButton" aria-haspopup="menu"
                        x-bind:aria-expanded="open.toString()" x-on:click="open = ! open">
                        Columns
                        <x-icons.chevron-down class="size-4 opacity-60" aria-hidden="true" />
                    </x-ui.button>
                    <div x-show="open" x-cloak x-transition.opacity role="menu" aria-label="Toggle columns"
                        class="absolute right-0 z-50 mt-2 min-w-[10rem] rounded-md border bg-popover p-1 text-popover-foreground shadow-md">
                        @foreach ($hideable as $column)
                            <button type="button" role="menuitemcheckbox" data-column="{{ $column['key'] }}"
                                x-bind:aria-checked="isVisible(@js($column['key'])).toString()"
                                aria-checked="{{ $column['hidden'] ? 'false' : 'true' }}"
                                x-on:click="toggleColumn(@js($column['key']))"
                                class="relative flex w-full cursor-default select-none items-center rounded-sm py-1.5 pl-8 pr-2 text-left text-sm outline-none hover:bg-accent hover:text-accent-foreground focus-visible:bg-accent focus-visible:text-accent-foreground">
                                <span class="absolute left-2 flex size-3.5 items-center justify-center"
                                    x-show="isVisible(@js($column['key']))" @if ($column['hidden']) style="display: none;" @endif>
                                    <x-icons.check class="size-4" aria-hidden="true" />
                                </span>
                                {{ $column['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    @if ($selectable && isset($bulkActions))
        <div data-slot="data-table-bulk-actions" x-show="selected.length > 0" x-cloak style="display: none;"
            role="region" aria-label="Bulk actions"
            class="flex flex-wrap items-center gap-2 rounded-md border bg-muted/50 px-3 py-2 text-sm">
            <span class="text-muted-foreground" aria-live="polite"><span x-text="selected.length"></span> selected</span>
            {{ $bulkActions }}
        </div>
    @endif

    <div class="rounded-md border">
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    @if ($selectable)
                        <x-ui.table-head class="w-10">
                            <input type="checkbox" aria-label="Select all rows" data-slot="data-table-select-all"
                                x-bind:checked="allSelected" x-effect="$el.indeterminate = someSelected" x-on:change="toggleAll()"
                                @disabled($items->isEmpty())
                                class="size-4 shrink-0 rounded-[4px] border border-input accent-primary shadow-xs outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:cursor-not-allowed disabled:opacity-50" />
                        </x-ui.table-head>
                    @endif

                    @foreach ($columns as $column)
                        @php
                            $isSorted = $column['sortable'] && $currentSort === $column['key'];
                            $ariaSort = $isSorted ? ($currentDirection === 'asc' ? 'ascending' : 'descending') : ($column['sortable'] ? 'none' : null);
                        @endphp
                        <x-ui.table-head :class="'whitespace-nowrap ps-2 pe-2 '.$column['headerClass']" data-column="{{ $column['key'] }}"
                            x-show="isVisible({{ \Illuminate\Support\Js::from($column['key']) }})"
                            :style="$column['hidden'] ? 'display: none;' : null" :aria-sort="$ariaSort" scope="col">
                            @if ($column['sortable'])
                                <a href="{{ $sortUrl($column['key']) }}" data-slot="data-table-sort"
                                    class="-ml-3 inline-flex h-8 items-center gap-1 rounded-md px-3 hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 {{ $isSorted ? 'text-foreground' : '' }}">
                                    {{ $column['label'] }}
                                    @if ($isSorted)
                                        @if ($currentDirection === 'asc')
                                            <x-icons.chevron-up class="size-4" aria-hidden="true" />
                                        @else
                                            <x-icons.chevron-down class="size-4" aria-hidden="true" />
                                        @endif
                                        <span class="sr-only">(sorted {{ $currentDirection === 'asc' ? 'ascending' : 'descending' }})</span>
                                    @else
                                        <x-icons.chevrons-up-down class="size-4 opacity-50" aria-hidden="true" />
                                    @endif
                                </a>
                            @else
                                {{ $column['label'] }}
                            @endif
                        </x-ui.table-head>
                    @endforeach
                </x-ui.table-row>
            </x-ui.table-header>

            <x-ui.table-body>
                @forelse ($items as $index => $row)
                    @php $key = $keyOf($row, $index); @endphp
                    <x-ui.table-row data-key="{{ $key }}"
                        x-bind:data-state="selected.includes({{ \Illuminate\Support\Js::from($key) }}) ? 'selected' : null">
                        @if ($selectable)
                            <x-ui.table-cell>
                                <input type="checkbox" name="{{ $selectName }}[]" value="{{ $key }}" x-model="selected"
                                    aria-label="Select row {{ $key }}" @if ($form) form="{{ $form }}" @endif
                                    class="size-4 shrink-0 rounded-[4px] border border-input accent-primary shadow-xs outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50" />
                            </x-ui.table-cell>
                        @endif

                        @foreach ($columns as $column)
                            @php
                                $value = data_get($row, $column['key']);
                                $display = is_callable($column['format']) ? ($column['format'])($value, $row) : $value;
                            @endphp
                            <x-ui.table-cell :class="'whitespace-nowrap px-2 '.$column['class']" data-column="{{ $column['key'] }}"
                                x-show="isVisible({{ \Illuminate\Support\Js::from($column['key']) }})"
                                :style="$column['hidden'] ? 'display: none;' : null">
                                @if ($display instanceof \Illuminate\Contracts\Support\Htmlable)
                                    {!! $display->toHtml() !!}
                                @else
                                    {{ is_scalar($display) || $display instanceof \Stringable ? $display : (is_null($display) ? '' : json_encode($display)) }}
                                @endif
                            </x-ui.table-cell>
                        @endforeach
                    </x-ui.table-row>
                @empty
                    <x-ui.table-row class="hover:bg-transparent">
                        <x-ui.table-cell :colspan="$colspan" class="h-24 text-center text-muted-foreground" data-slot="data-table-empty">
                            @if (isset($empty) && $empty->isNotEmpty())
                                {{ $empty }}
                            @else
                                {{ $emptyMessage }}
                            @endif
                        </x-ui.table-cell>
                    </x-ui.table-row>
                @endforelse
            </x-ui.table-body>
        </x-ui.table>
    </div>

    @if ($isPaginator && ($rows->hasPages() || $rows instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator))
        <div data-slot="data-table-footer" class="flex flex-col items-center justify-between gap-2 text-sm text-muted-foreground sm:flex-row">
            <div aria-live="polite" class="shrink-0 whitespace-nowrap">
                @if ($rows instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $rows->total() > 0)
                    Showing {{ $rows->firstItem() }}–{{ $rows->lastItem() }} of {{ $rows->total() }}
                @endif
                @if ($selectable)
                    <span x-show="selected.length > 0" x-cloak style="display: none;">· <span x-text="selected.length"></span> selected</span>
                @endif
            </div>
            <x-ui.pagination :paginator="$rows" :on-each-side="$onEachSide" class="mx-0 w-auto justify-end" />
        </div>
    @endif
</div>
