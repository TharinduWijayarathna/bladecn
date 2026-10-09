<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/*
 * Server-rendered markup of the shadcn/ui components added in this release:
 * roles, ARIA and initial state that must be correct before Alpine runs.
 */

it('renders an accordion with the default item open on the server', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui.accordion type="single" collapsible default-value="b">
            <x-ui.accordion-item value="a"><x-ui.accordion-trigger>A</x-ui.accordion-trigger><x-ui.accordion-content>Body A</x-ui.accordion-content></x-ui.accordion-item>
            <x-ui.accordion-item value="b"><x-ui.accordion-trigger>B</x-ui.accordion-trigger><x-ui.accordion-content>Body B</x-ui.accordion-content></x-ui.accordion-item>
        </x-ui.accordion>
        BLADE);

    expect($html)->toContain('role="region"', 'data-slot="accordion-trigger"', '<h3')
        ->and(substr_count($html, 'data-state="open" '))->toBe(1)
        ->and(substr_count($html, ' inert'))->toBe(1);
});

it('renders tabs with the default tab selected', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui.tabs default-value="two">
            <x-ui.tabs-list><x-ui.tabs-trigger value="one">One</x-ui.tabs-trigger><x-ui.tabs-trigger value="two">Two</x-ui.tabs-trigger></x-ui.tabs-list>
            <x-ui.tabs-content value="one">First</x-ui.tabs-content>
            <x-ui.tabs-content value="two">Second</x-ui.tabs-content>
        </x-ui.tabs>
        BLADE);

    expect($html)->toContain('role="tablist"', 'role="tab"', 'role="tabpanel"')
        ->and(substr_count($html, 'aria-selected="true"'))->toBe(1)
        ->and(substr_count($html, 'style="display: none;"'))->toBe(1);
});

it('renders a switch as a role=switch button with a form checkbox', function () {
    $html = Blade::render('<x-ui.switch id="s" name="notify" checked />');

    expect($html)->toContain('role="switch"', 'aria-checked="true"', 'id="s"', 'name="notify"', 'type="checkbox"');
});

it('renders an alert dialog with alertdialog semantics and an in-place form-friendly action', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui.alert-dialog>
            <x-ui.alert-dialog-trigger>Delete</x-ui.alert-dialog-trigger>
            <x-ui.alert-dialog-content>
                <x-ui.alert-dialog-title>Sure?</x-ui.alert-dialog-title>
                <x-ui.alert-dialog-action type="submit" variant="destructive">Yes</x-ui.alert-dialog-action>
            </x-ui.alert-dialog-content>
        </x-ui.alert-dialog>
        BLADE);

    expect($html)->toContain('role="alertdialog"', 'aria-modal="true"', 'x-trap.inert.noscroll', 'type="submit"', 'bg-destructive')
        ->not->toContain('x-teleport');
});

it('builds pagination links from a Laravel paginator', function () {
    $paginator = new LengthAwarePaginator(range(1, 10), 200, 10, 7, ['path' => '/users']);
    $html = Blade::render('<x-ui.pagination :paginator="$p" />', ['p' => $paginator]);

    expect($html)->toContain('aria-label="pagination"', 'href="/users?page=6"', 'href="/users?page=8"', 'aria-current="page"', 'href="/users?page=20"')
        ->and(substr_count($html, 'data-slot="pagination-ellipsis"'))->toBe(2);
});

it('shows field errors from the shared validation error bag', function () {
    View::share('errors', (new ViewErrorBag)->put('default', new MessageBag(['email' => ['The email field is required.']])));

    expect(Blade::render('<x-ui.field-error name="email" />'))->toContain('The email field is required.', 'role="alert"')
        ->and(trim(Blade::render('<x-ui.field-error name="name" />')))->toBe('');
});

it('renders command items, combobox options and a toaster with flash messages', function () {
    $command = Blade::render('<x-ui.command><x-ui.command-input /><x-ui.command-list><x-ui.command-item value="cal" keywords="date">Calendar</x-ui.command-item></x-ui.command-list></x-ui.command>');
    $combobox = Blade::render('<x-ui.combobox name="fw" value="laravel" :options="[\'laravel\' => \'Laravel\', \'vue\' => \'Vue\']" />');
    $toaster = Blade::render('<x-ui.toaster :flash="[\'message\' => \'Saved\', \'type\' => \'success\']" />');

    expect($command)->toContain('role="combobox"', 'role="listbox"', 'role="option"', 'data-value="cal"', 'data-keywords="date"')
        ->and($combobox)->toContain('name="fw"', 'value="laravel"', '>Laravel<')
        ->and($toaster)->toContain('aria-label="Notifications"', 'Saved');
});

it('renders the remaining primitives', function (string $blade, array $expected) {
    expect(Blade::render($blade))->toContain(...$expected);
})->with([
    'aspect ratio' => ['<x-ui.aspect-ratio ratio="16/9">x</x-ui.aspect-ratio>', ['aspect-ratio: 1.7777']],
    'button group' => ['<x-ui.button-group><x-ui.button>A</x-ui.button><x-ui.button-group-separator /></x-ui.button-group>', ['role="group"', 'role="separator"']],
    'calendar' => ['<x-ui.calendar value="2026-06-12" name="d" />', ['role="grid"', 'value="2026-06-12"']],
    'collapsible' => ['<x-ui.collapsible open><x-ui.collapsible-trigger>T</x-ui.collapsible-trigger><x-ui.collapsible-content>C</x-ui.collapsible-content></x-ui.collapsible>', ['aria-expanded', 'data-state="open"']],
    'context menu' => ['<x-ui.context-menu><x-ui.context-menu-trigger>T</x-ui.context-menu-trigger><x-ui.context-menu-content><x-ui.context-menu-item>Copy</x-ui.context-menu-item></x-ui.context-menu-content></x-ui.context-menu>', ['role="menu"', 'role="menuitem"']],
    'date picker' => ['<x-ui.date-picker name="due" value="2026-01-02" />', ['name="due"', 'value="2026-01-02"', 'role="grid"']],
    'hover card' => ['<x-ui.hover-card><x-ui.hover-card-trigger href="/u">@u</x-ui.hover-card-trigger><x-ui.hover-card-content>Bio</x-ui.hover-card-content></x-ui.hover-card>', ['href="/u"', 'Bio']],
    'kbd' => ['<x-ui.kbd-group><x-ui.kbd>⌘</x-ui.kbd></x-ui.kbd-group>', ['<kbd', '⌘']],
    'popover' => ['<x-ui.popover><x-ui.popover-trigger>Open</x-ui.popover-trigger><x-ui.popover-content side="top">P</x-ui.popover-content></x-ui.popover>', ['aria-haspopup="dialog"', 'role="dialog"', 'bottom-full']],
    'scroll area' => ['<x-ui.scroll-area class="h-20">x</x-ui.scroll-area>', ['overflow-y-auto', 'tabindex="0"']],
    'skeleton' => ['<x-ui.skeleton class="h-4" />', ['animate-pulse']],
    'slider' => ['<x-ui.slider :value="30" name="v" label="Volume" />', ['role="slider"', 'aria-valuenow="30"', 'aria-label="Volume"', 'name="v"']],
    'toggle' => ['<x-ui.toggle pressed>B</x-ui.toggle>', ['aria-pressed="true"', 'data-state="on"']],
    'toggle group' => ['<x-ui.toggle-group type="multiple" name="f"><x-ui.toggle-group-item value="b">B</x-ui.toggle-group-item></x-ui.toggle-group>', ['data-slot="toggle-group-item"', 'name="f[]"']],
    'field' => ['<x-ui.field orientation="horizontal" invalid><x-ui.field-label for="a">A</x-ui.field-label></x-ui.field>', ['data-invalid="true"', 'data-orientation="horizontal"']],
]);

it('positions tooltips and themes sheets', function () {
    $tooltip = Blade::render('<x-ui.tooltip side="right" :side-offset="8" content="Tip"><button>Hover</button></x-ui.tooltip>');
    $sheet = Blade::render('<x-ui.sheet side="left">Body</x-ui.sheet>');

    expect($tooltip)->toContain('data-tooltip-side="right"', 'data-tooltip-offset="8"', 'fixed')
        ->and($sheet)->toContain('bg-background', 'role="dialog"')->not->toContain('bg-white');
});
