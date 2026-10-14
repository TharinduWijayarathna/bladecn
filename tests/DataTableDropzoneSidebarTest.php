<?php

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

/*
 * Server-rendered markup of the data table, file dropzone and sidebar
 * primitives: query-string links, ARIA and initial state before Alpine runs.
 */

function bindRequest(string $uri): void
{
    app()->instance('request', Request::create($uri));
}

it('renders a data table with sortable headers, search and pagination from a paginator', function () {
    bindRequest('/users?sort=name&direction=asc&search=ann&page=2');

    $rows = collect(range(6, 10))->map(fn ($i) => ['id' => $i, 'name' => "User {$i}", 'email' => "u{$i}@example.com"]);
    $paginator = new LengthAwarePaginator($rows, 30, 5, 2, ['path' => '/users']);

    $html = Blade::render('<x-ui.data-table :rows="$p" :columns="$c" />', [
        'p' => $paginator,
        'c' => [
            ['key' => 'name', 'label' => 'Name', 'sortable' => true],
            ['key' => 'email', 'label' => 'Email', 'sortable' => true],
        ],
    ]);

    expect($html)->toContain('data-slot="data-table"', 'role="search"', 'name="search"', 'value="ann"')
        // Current column: next click flips to desc and drops the page.
        ->toContain('aria-sort="ascending"', 'direction=desc', 'aria-sort="none"')
        ->not->toContain('page=2&amp;sort')
        ->toContain('User 6', 'u10@example.com', 'Showing 6–10 of 30', 'aria-label="pagination"')
        // Pagination keeps the query string; search keeps sort/direction as hidden inputs.
        ->toContain('search=ann', 'type="hidden" name="sort" value="name"');
});

it('renders selection checkboxes, bulk actions and column toggles', function () {
    bindRequest('/users');

    $html = Blade::render(<<<'BLADE'
        <x-ui.data-table :rows="$rows" :columns="['name' => 'Name', 'email' => ['label' => 'Email', 'hidden' => true]]" selectable form="bulk" select-name="ids">
            <x-slot:bulk-actions><button>Delete</button></x-slot:bulk-actions>
        </x-ui.data-table>
        BLADE, ['rows' => [['id' => 7, 'name' => 'Ann', 'email' => 'ann@example.com'], ['id' => 9, 'name' => 'Bob', 'email' => 'bob@example.com']]]);

    expect($html)->toContain('aria-label="Select all rows"', 'name="ids[]"', 'value="7"', 'value="9"', 'form="bulk"')
        ->toContain('data-slot="data-table-bulk-actions"', 'Delete', 'role="menuitemcheckbox"', 'aria-checked="false"')
        ->and(substr_count($html, 'type="checkbox"'))->toBe(3)
        // The hidden-by-default column is hidden on the server too.
        ->and($html)->toMatch('/<th(?=[^>]*data-column="email")(?=[^>]*style="display: none;")/');
});

it('formats cells and shows the empty state', function () {
    bindRequest('/');

    $html = Blade::render('<x-ui.data-table :rows="$rows" :columns="$c" :searchable="false" />', [
        'rows' => collect([['id' => 1, 'status' => 'active', 'author' => ['name' => 'Jane']]]),
        'c' => [
            'author.name' => 'Author',
            ['key' => 'status', 'format' => fn ($v) => new HtmlString("<b>{$v}</b>")],
        ],
    ]);
    $empty = Blade::render('<x-ui.data-table :rows="[]" :columns="[\'name\' => \'Name\']" empty-message="Nothing here" selectable />');

    expect($html)->toContain('Jane', '<b>active</b>', 'Author', 'Status')->not->toContain('role="search"')
        ->and($empty)->toContain('Nothing here', 'colspan="2"', 'data-slot="data-table-empty"');
});

it('renders a file dropzone wired to a real multipart file input', function () {
    $html = Blade::render('<x-ui.file-dropzone id="docs" name="attachments" accept="image/*,.pdf" :max-files="3" :max-size="5" label="Attachments" progress />');

    expect($html)->toContain('type="file"', 'id="docs"', 'name="attachments[]"', 'multiple', 'accept="image/*,.pdf"')
        ->toContain('role="button"', 'tabindex="0"', 'aria-describedby="docs-description"', 'for="docs"')
        ->toContain('Max 5 MB per file', 'Up to 3 files', 'DataTransfer', 'role="progressbar"', 'role="alert"');

    $single = Blade::render('<x-ui.file-dropzone name="avatar" :multiple="false" disabled />');

    expect($single)->toContain('name="avatar"', 'aria-disabled="true"', 'disabled')
        ->not->toContain('name="avatar[]"', 'role="progressbar"');
});

it('renders the sidebar primitives with collapsible state and persistence', function () {
    bindRequest('/');

    $html = Blade::render(<<<'BLADE'
        <x-ui.sidebar-provider>
            <x-ui.sidebar collapsible="icon" variant="floating">
                <x-ui.sidebar-header>H</x-ui.sidebar-header>
                <x-ui.sidebar-content>
                    <x-ui.sidebar-group>
                        <x-ui.sidebar-group-label>Platform</x-ui.sidebar-group-label>
                        <x-ui.sidebar-group-content>
                            <x-ui.sidebar-menu>
                                <x-ui.sidebar-menu-item>
                                    <x-ui.sidebar-menu-button href="/dashboard" is-active tooltip="Dashboard"><span>Dashboard</span></x-ui.sidebar-menu-button>
                                    <x-ui.sidebar-menu-sub>
                                        <x-ui.sidebar-menu-sub-item><x-ui.sidebar-menu-sub-button href="/a">A</x-ui.sidebar-menu-sub-button></x-ui.sidebar-menu-sub-item>
                                    </x-ui.sidebar-menu-sub>
                                </x-ui.sidebar-menu-item>
                                <x-ui.sidebar-menu-item><x-ui.sidebar-menu-button>Plain</x-ui.sidebar-menu-button></x-ui.sidebar-menu-item>
                            </x-ui.sidebar-menu>
                        </x-ui.sidebar-group-content>
                    </x-ui.sidebar-group>
                    <x-ui.sidebar-separator />
                </x-ui.sidebar-content>
                <x-ui.sidebar-footer>F</x-ui.sidebar-footer>
            </x-ui.sidebar>
            <x-ui.sidebar-inset><x-ui.sidebar-trigger /></x-ui.sidebar-inset>
        </x-ui.sidebar-provider>
        BLADE);

    expect($html)->toContain('data-slot="sidebar-wrapper"', '--sidebar-width: 16rem', 'sidebar_state', 'localStorage', 'document.cookie')
        ->toContain('data-state="expanded"', 'data-variant="floating"', 'data-side="left"', 'x-trap.inert.noscroll')
        ->toContain('<nav data-slot="sidebar-inner" aria-label="Sidebar"', 'role="group"', 'role="separator"')
        ->toContain('href="/dashboard"', 'aria-current="page"', 'data-active="true"', 'type="button"', 'data-slot="sidebar-menu-sub-button"')
        ->toContain('data-slot="sidebar-trigger"', 'aria-label="Toggle sidebar"', 'toggleSidebar()', '<main data-slot="sidebar-inset"');
});

it('restores a collapsed sidebar from the cookie on the server', function () {
    $request = Request::create('/', cookies: ['sidebar_state' => 'false']);
    app()->instance('request', $request);

    $html = Blade::render('<x-ui.sidebar-provider><x-ui.sidebar collapsible="icon">x</x-ui.sidebar></x-ui.sidebar-provider>');

    expect($html)->toContain('data-sidebar-state="collapsed"', 'data-state="collapsed"', 'data-collapsible="icon"', 'open: false');

    $static = Blade::render('<x-ui.sidebar-provider><x-ui.sidebar collapsible="none">x</x-ui.sidebar></x-ui.sidebar-provider>');
    expect($static)->toContain('data-collapsible="none"', 'role="navigation"');
});
