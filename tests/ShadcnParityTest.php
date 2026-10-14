<?php

use Illuminate\Support\Facades\Blade;

/*
 * Server-rendered markup of the shadcn/ui parity components (carousel, drawer,
 * navigation menu, menubar, resizable, charts): roles, ARIA and initial state
 * that must be correct before Alpine runs.
 */

it('renders a carousel region with slides, controls and dots', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui.carousel orientation="vertical" :autoplay="3000" label="Photos">
            <x-ui.carousel-content class="h-48">
                <x-ui.carousel-item>One</x-ui.carousel-item>
                <x-ui.carousel-item>Two</x-ui.carousel-item>
            </x-ui.carousel-content>
            <x-ui.carousel-previous />
            <x-ui.carousel-next />
            <x-ui.carousel-dots />
        </x-ui.carousel>
        BLADE);

    expect($html)->toContain('role="region"', 'aria-roledescription="carousel"', 'aria-label="Photos"', 'data-orientation="vertical"',
        'aria-roledescription="slide"', 'snap-y', 'snap-mandatory', 'h-48', 'pt-4', 'Previous slide', 'Next slide', 'rotate-90', 'x-for="i in count"', 'autoplay: 3000')
        ->and(substr_count($html, 'data-slot="carousel-item"'))->toBe(2)
        // Without `loop`, "previous" starts disabled on the server.
        ->and($html)->toMatch('/<button[^>]*data-slot="carousel-previous"[^>]*disabled/s');
});

it('renders a drawer as a modal dialog with a drag handle', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui.drawer>
            <x-ui.drawer-trigger>Open</x-ui.drawer-trigger>
            <x-ui.drawer-content>
                <x-ui.drawer-header>
                    <x-ui.drawer-title>Move goal</x-ui.drawer-title>
                    <x-ui.drawer-description>Set your daily goal.</x-ui.drawer-description>
                </x-ui.drawer-header>
                <x-ui.drawer-footer><x-ui.drawer-close>Cancel</x-ui.drawer-close></x-ui.drawer-footer>
            </x-ui.drawer-content>
        </x-ui.drawer>
        BLADE);

    expect($html)->toContain('role="dialog"', 'aria-modal="true"', 'x-trap.inert.noscroll="open"', 'aria-haspopup="dialog"',
        'data-slot="drawer-handle"', 'translate-y-full', 'rounded-t-lg', 'data-slot="drawer-close"', "\$id('drawer-title')", "\$id('drawer-description')")
        ->not->toContain('x-teleport');

    $side = Blade::render('<x-ui.drawer direction="left" :dismissible="false"><x-ui.drawer-content>Nav</x-ui.drawer-content></x-ui.drawer>');

    expect($side)->toContain('data-direction="left"', '-translate-x-full', 'border-r', 'dismissible: false')
        ->not->toContain('data-slot="drawer-handle"');
});

it('renders a navigation menu with expandable triggers and links', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui.navigation-menu indicator label="Primary">
            <x-ui.navigation-menu-list>
                <x-ui.navigation-menu-item value="learn">
                    <x-ui.navigation-menu-trigger>Learn</x-ui.navigation-menu-trigger>
                    <x-ui.navigation-menu-content align="end">
                        <x-ui.navigation-menu-link href="/docs">Docs</x-ui.navigation-menu-link>
                    </x-ui.navigation-menu-content>
                </x-ui.navigation-menu-item>
                <x-ui.navigation-menu-item>
                    <x-ui.navigation-menu-link href="/blog" variant="trigger" active>Blog</x-ui.navigation-menu-link>
                </x-ui.navigation-menu-item>
            </x-ui.navigation-menu-list>
        </x-ui.navigation-menu>
        BLADE);

    expect($html)->toContain('<nav', 'aria-label="Primary"', '<ul', '<li', "value: 'learn'", 'aria-expanded="false"', ':aria-controls',
        'data-slot="navigation-menu-content"', 'right-0', 'href="/docs"', 'href="/blog"', 'aria-current="page"', 'data-slot="navigation-menu-indicator"')
        ->and(substr_count($html, 'style="display: none;"'))->toBe(1);
});

it('renders a menubar with menus, checkable items and sub menus', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui.menubar>
            <x-ui.menubar-menu value="file">
                <x-ui.menubar-trigger>File</x-ui.menubar-trigger>
                <x-ui.menubar-content>
                    <x-ui.menubar-item>New <x-ui.menubar-shortcut>⌘N</x-ui.menubar-shortcut></x-ui.menubar-item>
                    <x-ui.menubar-item disabled>Print</x-ui.menubar-item>
                    <x-ui.menubar-separator />
                    <x-ui.menubar-label>View</x-ui.menubar-label>
                    <x-ui.menubar-checkbox-item checked>Bookmarks</x-ui.menubar-checkbox-item>
                    <x-ui.menubar-radio-group value="b">
                        <x-ui.menubar-radio-item value="a">A</x-ui.menubar-radio-item>
                        <x-ui.menubar-radio-item value="b" checked>B</x-ui.menubar-radio-item>
                    </x-ui.menubar-radio-group>
                    <x-ui.menubar-sub>
                        <x-ui.menubar-sub-trigger>Share</x-ui.menubar-sub-trigger>
                        <x-ui.menubar-sub-content><x-ui.menubar-item href="/mail">Email</x-ui.menubar-item></x-ui.menubar-sub-content>
                    </x-ui.menubar-sub>
                </x-ui.menubar-content>
            </x-ui.menubar-menu>
        </x-ui.menubar>
        BLADE);

    expect($html)->toContain('role="menubar"', 'data-value="file"', 'aria-haspopup="menu"', 'role="menu"', 'role="menuitem"',
        'role="menuitemcheckbox"', 'role="menuitemradio"', 'role="separator"', 'aria-disabled="true"', 'href="/mail"', '⌘N',
        'data-slot="menubar-sub-content"')
        ->and(substr_count($html, 'aria-checked="true"'))->toBe(2)
        ->and(substr_count($html, 'role="menu"'))->toBe(2);
});

it('renders resizable panels with server-side sizes and separator handles', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui.resizable-panel-group direction="vertical" auto-save-id="layout">
            <x-ui.resizable-panel :default-size="30" :min-size="20" :max-size="60">Top</x-ui.resizable-panel>
            <x-ui.resizable-handle with-handle />
            <x-ui.resizable-panel :default-size="70">Bottom</x-ui.resizable-panel>
        </x-ui.resizable-panel-group>
        BLADE);

    expect($html)->toContain('data-panel-group-direction="vertical"', 'flex-col', 'bladecn:resizable:layout',
        'style="flex: 30 1 0px;"', 'style="flex: 70 1 0px;"', 'data-min-size="20"', 'data-max-size="60"',
        'role="separator"', 'aria-orientation="horizontal"', 'tabindex="0"', 'cursor-row-resize', '<svg');
});

it('renders line, bar and area charts as server-side svg', function (string $tag, array $expected) {
    $html = Blade::render(
        "<x-charts.{$tag} :labels=\"['Jan', 'Feb', 'Mar']\" :series=\"['Desktop' => [100, 250, 50], 'Mobile' => [80, null, 120]]\" label=\"Visitors\" prefix=\"$\" />"
    );

    expect($html)->toContain('role="figure"', 'aria-label="Visitors"', '<svg', 'viewBox="0 0 100 100"', 'preserveAspectRatio="none"',
        '--color-0: var(--chart-1);', '--color-1: var(--chart-2);', 'data-slot="chart-tooltip"', 'data-slot="chart-legend"', 'aria-live="polite"',
        '<table class="sr-only">', '<th scope="row">Feb</th>', '$250', '$300', ...$expected);
})->with([
    'line' => ['line', ['data-chart-type="line"', 'vector-effect="non-scaling-stroke"', ' C']],
    'bar' => ['bar', ['data-chart-type="bar"', '<rect', 'bg-muted/60']],
    'area' => ['area', ['data-chart-type="area"', 'fill-opacity: 0.4;', ' Z"']],
]);

it('stacks chart series and honours custom colours and toggles', function () {
    $bar = Blade::render('<x-charts.bar stacked :labels="[\'A\']" :series="[[\'name\' => \'X\', \'data\' => [3], \'color\' => \'red\'], [\'name\' => \'Y\', \'data\' => [5]]]" :legend="false" :tooltip="false" :y-axis="false" />');

    expect($bar)->toContain('--color-0: red;', '--color-1: var(--chart-2);')
        ->not->toContain('data-slot="chart-legend"', 'data-slot="chart-tooltip"', 'x-data')
        // Stacked to 8, so the top of the scale is 8 and the second series starts where the first ends.
        ->and($bar)->toMatch('/<rect data-series="1"[^>]*y="0"/');
});
