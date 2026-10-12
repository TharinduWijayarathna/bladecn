<?php

use Illuminate\Support\Facades\Blade;

/*
 * Server-rendered markup of the extra components (stepper, timeline, sortable, kanban, rating,
 * tree view, rich text editor, code block, date range picker): roles, ARIA, form fields and the
 * initial state that must be correct before Alpine runs.
 */

it('renders a stepper with completed, current and upcoming steps', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui.stepper :current="1" :steps="['Account', ['title' => 'Shipping', 'description' => 'Where to?'], 'Payment']" />
        BLADE);

    expect($html)->toContain('data-slot="stepper"', 'aria-label="Progress"', 'Where to?')->not->toContain('<button')
        ->and(substr_count($html, 'data-state="completed"'))->toBe(1)
        ->and(substr_count($html, 'data-state="current"'))->toBe(1)
        ->and(substr_count($html, 'data-state="upcoming"'))->toBe(1)
        ->and(substr_count($html, 'aria-current="step"'))->toBe(1)
        ->and(substr_count($html, 'data-slot="stepper-separator"'))->toBe(2);
});

it('renders clickable and vertical steppers', function () {
    $any = Blade::render('<x-ui.stepper clickable orientation="vertical" :current="0" :steps="[\'A\', \'B\']" />');
    $completed = Blade::render('<x-ui.stepper clickable="completed" :current="1" :steps="[\'A\', \'B\', \'C\']" />');

    expect($any)->toContain('data-orientation="vertical"', 'type="button"', 'flex-col')
        ->and(substr_count($any, '<button'))->toBe(2)
        ->and(substr_count($completed, 'disabled'))->toBeGreaterThanOrEqual(1);
});

it('renders a timeline with times, icons and the alternate layout', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui.timeline alternate>
            <x-ui.timeline-item time="9:00" datetime="2026-06-12T09:00" title="Created">Order placed.</x-ui.timeline-item>
            <x-ui.timeline-item title="Shipped" variant="success"><x-slot:icon><svg data-test-icon></svg></x-slot:icon></x-ui.timeline-item>
        </x-ui.timeline>
        BLADE);

    expect($html)->toContain('data-slot="timeline"', 'data-alternate="true"', 'datetime="2026-06-12T09:00"', 'Order placed.', 'data-test-icon', 'md:grid-cols-[1fr_auto_1fr]', 'bg-primary')
        ->and(substr_count($html, 'data-slot="timeline-item"'))->toBe(2);
});

it('renders a sortable list whose hidden inputs post the order', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui.sortable name="priority" handle>
            <x-ui.sortable-item value="a">Alpha</x-ui.sortable-item>
            <x-ui.sortable-item value="b" label="Beta">Beta <b>item</b></x-ui.sortable-item>
        </x-ui.sortable>
        BLADE);

    expect($html)->toContain('data-slot="sortable"', 'role="list"', 'x-modelable="order"', 'aria-label="Reorder Alpha"', 'aria-label="Reorder Beta"', 'aria-live="assertive"', "dispatchEvent(new CustomEvent('sorted'")
        ->and(substr_count($html, 'name="priority[]"'))->toBe(2)
        ->and(strpos($html, 'value="a"'))->toBeLessThan(strpos($html, 'value="b"'))
        ->and(substr_count($html, 'draggable="false"'))->toBe(2);
});

it('renders a kanban board with columns, counts and cards', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui.kanban name="board">
            <x-ui.kanban-column value="todo" title="To do">
                <x-ui.kanban-card value="1">Write docs</x-ui.kanban-card>
                <x-ui.kanban-card value="2">Ship it</x-ui.kanban-card>
            </x-ui.kanban-column>
            <x-ui.kanban-column value="done" title="Done" />
        </x-ui.kanban>
        BLADE);

    expect($html)->toContain('data-slot="kanban"', 'data-column="todo"', 'data-column="done"', 'aria-label="Move Write docs"', 'draggable="true"', '${name}[${column}][]', "CustomEvent('sorted'")
        ->and(substr_count($html, 'data-slot="kanban-card"'))->toBe(2)
        ->and($html)->toMatch('/data-slot="kanban-column-count"[^>]*>2</');
});

it('renders read-only and interactive ratings', function () {
    $readonly = Blade::render('<x-ui.rating :value="3.5" readonly />');
    $interactive = Blade::render('<x-ui.rating :value="4" name="score" :max="10" step="0.5" label="Quality" />');

    expect($readonly)->toContain('role="img"', 'aria-label="Rating: 3.5 out of 5"', 'width: 50%', 'width: 100%', 'width: 0%')
        ->not->toContain('role="slider"', 'tabindex="0"')
        ->and(substr_count($readonly, 'data-slot="rating-star"'))->toBe(5)
        ->and($interactive)->toContain('role="slider"', 'aria-valuenow="4"', 'aria-valuemax="10"', 'aria-valuetext="4 out of 10"', 'aria-label="Quality"', 'tabindex="0"', 'name="score"', 'value="4"')
        ->and(substr_count($interactive, 'data-slot="rating-star"'))->toBe(10);
});

it('renders a tree view with levels, expansion and indeterminate parents', function () {
    $items = [
        ['id' => 'src', 'label' => 'src', 'children' => [
            ['id' => 'app', 'label' => 'app.js'],
            ['id' => 'css', 'label' => 'app.css'],
        ]],
        ['id' => 'readme', 'label' => 'README.md'],
    ];
    $html = Blade::render('<x-ui.tree-view :items="$items" checkboxes name="files" :checked="[\'app\']" :expanded="[\'src\']" label="Files" />', ['items' => $items]);
    $collapsed = Blade::render('<x-ui.tree-view :items="$items" selected="readme" />', ['items' => $items]);

    expect($html)->toContain('role="tree"', 'aria-label="Files"', 'aria-multiselectable="true"', 'aria-level="2"', 'aria-setsize="2"', 'aria-expanded="true"', 'aria-checked="mixed"', 'name="files[]"')
        ->and(substr_count($html, 'role="treeitem"'))->toBe(4)
        ->and(substr_count($html, 'aria-checked="true"'))->toBe(1)
        ->and(preg_match_all('/\schecked[\s>]/', $html))->toBe(1)
        ->and(substr_count($html, 'display: none;'))->toBe(0)
        ->and($collapsed)->toContain('aria-expanded="false"', 'aria-selected="true"')->not->toContain('type="checkbox"')
        ->and(substr_count($collapsed, 'display: none;'))->toBe(2);
});

it('renders the rich text editor as a textarea that Tiptap enhances', function () {
    $html = Blade::render('<x-ui.rich-text-editor name="body" value="<p>Hello <strong>world</strong></p>" placeholder="Write…" />');
    $custom = Blade::render('<x-ui.rich-text-editor :toolbar="[\'bold\', \'|\', \'undo\', \'nope\']" cdn="https://cdn.example.com/" version="3.0.0" />');

    expect($html)->toContain('<textarea', 'name="body"', '&lt;p&gt;Hello &lt;strong&gt;world&lt;/strong&gt;&lt;/p&gt;', 'role="toolbar"', 'aria-label="Bold"', 'aria-label="Italic"', 'aria-label="Underline"', '@tiptap/core@', 'BladeCNTiptap', 'x-show="ready"')
        ->and($custom)->toContain('data-command="bold"', 'data-command="undo"', 'role="separator"', 'cdn.example.com', '3.0.0')
        ->not->toContain('data-command="nope"', 'data-command="italic"');
});

it('renders a code block with escaped code, filename, line numbers and a copy button', function () {
    $html = Blade::render('<x-ui.code-block :code="$code" language="php" filename="routes/web.php" line-numbers />', ['code' => "<?php\n\necho 'hi';\n"]);
    $plain = Blade::render('<x-ui.code-block code="npm i" :copyable="false" />');

    expect($html)->toContain('data-slot="code-block"', 'routes/web.php', 'language-php', '&lt;?php', 'aria-label="Copy code"', 'data-slot="code-block-line-numbers"', 'navigator.clipboard')
        ->and($html)->toMatch('/<div>3<\/div>/')
        ->not->toMatch('/<div>4<\/div>/')
        ->and($plain)->toContain('npm i')->not->toContain('data-slot="code-block-copy"', 'code-block-line-numbers', '<figcaption');
});

it('renders a date range picker with presets and start / end inputs', function () {
    $html = Blade::render('<x-ui.date-range-picker name="period" start="2026-06-01" end="2026-06-07" />');
    $named = Blade::render('<x-ui.date-range-picker start-name="from" end-name="to" :presets="[\'Past week\' => \'last7\']" />');
    $calendar = Blade::render('<x-ui.range-calendar start="2026-06-10" end="2026-06-01" start-name="a" end-name="b" />');

    expect($html)->toContain('data-slot="date-range-picker"', 'name="period[start]"', 'value="2026-06-01"', 'name="period[end]"', 'value="2026-06-07"', 'data-slot="range-calendar"', 'role="grid"', 'Last 7 days', 'This month', 'aria-haspopup="dialog"')
        ->and($named)->toContain('name="from"', 'name="to"', 'Past week')->not->toContain('Last 30 days')
        ->and($calendar)->toContain('name="a" value="2026-06-01"', 'name="b" value="2026-06-10"');
});
