<?php

/*
|--------------------------------------------------------------------------
| BladeCN docs registry
|--------------------------------------------------------------------------
|
| guides      Getting Started pages (views in resources/views/pages/{slug}).
| components  One page per component family:
|   title / description   page heading + one-line summary
|   components            component tags documented on the page; their props
|                         tables are generated from the PHP class / @props
|   examples              key => title|[title, description, preview]; each key is
|                         a Blade file in resources/views/examples/{slug}/{key}
|                         rendered for Preview and printed verbatim for Code
|   anatomy/composition   how compound components nest
|   props                 descriptions only, keyed by tag then prop. Names and
|                         types/defaults come from source; tests/DocsTest.php
|                         fails if a description names a prop that doesn't exist.
|   notes                 behaviour worth knowing, in markdown
|
*/

return [
    'guides' => [
        'introduction' => [
            'title' => 'Introduction',
            'description' => 'shadcn/ui-style components for Laravel Blade, built on Tailwind CSS 4 and Alpine.js.',
        ],
        'installation' => [
            'title' => 'Installation',
            'description' => 'Add BladeCN to a Laravel app and publish the components into your project.',
        ],
        'theming' => [
            'title' => 'Theming & Dark mode',
            'description' => 'CSS variables, the dark variant and the appearance switcher.',
        ],
        'layouts' => [
            'title' => 'Layouts',
            'description' => 'Page layouts for the authenticated app, auth screens and settings, published by the installer.',
            'components' => ['layout.app', 'layout.auth', 'layout.settings', 'layout.head'],
            'props' => [
                'layout.app' => [
                    'title' => 'Page title (passed to `<x-layout.head>`).',
                    'breadcrumbs' => 'Breadcrumb items shown in the header, same shape as `<x-ui.breadcrumb>`.',
                ],
                'layout.auth' => [
                    'title' => 'Heading above the form.',
                    'description' => 'Text under the heading.',
                    'appearance' => 'Server-side appearance (`light`, `dark`, `system`) used to add the `.dark` class.',
                    'background-image' => 'Optional image URL for the side panel.',
                ],
                'layout.settings' => [
                    'current-path' => 'Path used to highlight the active settings link. Defaults to `request()->path()`.',
                ],
                'layout.head' => [
                    'title' => 'Page title; combined with the app name.',
                ],
            ],
        ],
    ],

    'components' => [

        'appearance-tabs' => [
            'title' => 'Appearance Tabs',
            'description' => 'A light / dark / system switcher that toggles the `.dark` class and remembers the choice.',
            'components' => ['ui.appearance-tabs'],
            'examples' => [
                'default' => ['title' => 'Default', 'description' => 'Click a tab: the whole docs site follows, because it uses the same `appearance` key.'],
            ],
            'props' => [
                'ui.appearance-tabs' => [
                    'class' => 'Extra classes for the tab container.',
                    'value' => "Initially selected mode (`light`, `dark` or `system`). Defaults to `session('appearance', 'system')`. When set, it is written to `localStorage` on load; pass an empty string to keep the visitor's stored choice.",
                ],
            ],
            'notes' => <<<'MD'
- Clicking a tab stores the mode in `localStorage` (`appearance`) **and** an `appearance` cookie, then toggles `.dark` on `<html>`.
- `system` follows `prefers-color-scheme` and reacts to OS changes.
- The script is pushed to the `scripts` stack, so your layout needs `@stack('scripts')`.
MD,
        ],

        'approval-item' => [
            'title' => 'Approval Item',
            'description' => 'A list row with stacked thumbnails, a title, a timestamp and a "View" link.',
            'components' => ['ui.approval-item'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'block'],
            ],
            'props' => [
                'ui.approval-item' => [
                    'title' => 'Row title (also used as the images\' `alt`).',
                    'time' => 'Secondary text shown next to the clock icon.',
                    'images' => 'Array of image URLs, rendered as an overlapping stack.',
                    'href' => 'Target of the "View" button.',
                ],
            ],
        ],

        'avatar' => [
            'title' => 'Avatar',
            'description' => 'An image element with a fallback for representing the user.',
            'components' => ['ui.avatar', 'ui.avatar-image', 'ui.avatar-fallback'],
            'examples' => [
                'default' => 'Default',
                'fallback' => ['title' => 'Fallback', 'description' => '`avatar-fallback` derives initials from `name` (first letters of the first two words, or the first two letters of a single word).'],
                'sizes' => ['title' => 'Sizes', 'description' => 'Size the root with utility classes.'],
            ],
            'anatomy' => <<<'BLADE'
<x-ui.avatar>
    <x-ui.avatar-image src="..." alt="..." />
    <x-ui.avatar-fallback name="Jane Doe" />
</x-ui.avatar>
BLADE,
            'composition' => '`avatar-image` renders nothing when `src` is empty, so you can always include both parts: with a `src` the image fills the circle and the fallback after it is clipped by the root\'s `overflow-hidden`; without one, the fallback shows.',
            'props' => [
                'ui.avatar' => ['class' => 'Extra classes merged onto the root (e.g. `size-12`).'],
                'ui.avatar-image' => [
                    'src' => 'Image URL. When empty the component renders nothing.',
                    'alt' => 'Alternative text.',
                    'class' => 'Extra classes for the `<img>`.',
                ],
                'ui.avatar-fallback' => [
                    'name' => 'Full name; the component displays its initials via `getInitials()`.',
                    'class' => 'Extra classes for the fallback.',
                ],
            ],
        ],

        'avatar-upload' => [
            'title' => 'Avatar Upload',
            'description' => 'A round avatar picker with live preview, size validation and an edit button.',
            'components' => ['ui.avatar-upload'],
            'examples' => [
                'default' => 'Default',
                'with-image' => ['title' => 'With an existing image', 'description' => 'Pass `default-url` to show the current avatar.'],
            ],
            'props' => [
                'ui.avatar-upload' => [
                    'id' => 'Id of the file input. Element ids for the preview are derived from it, so it must be unique per page.',
                    'name' => 'Name of the `<input type="file">`.',
                    'default-url' => 'Initial image URL.',
                    'fallback-text' => 'Text shown when there is no image (e.g. initials).',
                    'maxSizeMB' => 'Maximum file size accepted by the client-side check.',
                    'class' => 'Extra classes for the wrapper.',
                ],
            ],
            'notes' => 'The preview script is pushed to the `scripts` stack and initialises every `[data-avatar-upload]` on the page. Validation is client-side only; validate the upload on the server too.',
        ],

        'badge' => [
            'title' => 'Badge',
            'description' => 'Displays a badge or a component that looks like a badge.',
            'components' => ['ui.badge'],
            'examples' => [
                'default' => 'Default',
                'variants' => 'Variants',
                'with-icon' => ['title' => 'With icon'],
            ],
            'props' => [
                'ui.badge' => [
                    'variant' => '`default`, `secondary`, `destructive` or `outline`.',
                    'class' => 'Extra classes merged with the variant classes.',
                ],
            ],
        ],

        'breadcrumb' => [
            'title' => 'Breadcrumb',
            'description' => 'Displays the path to the current resource from an array of links, collapsing long trails.',
            'components' => ['ui.breadcrumb'],
            'examples' => [
                'default' => ['title' => 'Default'],
                'collapsed' => ['title' => 'Collapsed', 'description' => 'With three items the middle one becomes an ellipsis; with four or more, the first, second-to-last and last items stay visible.'],
            ],
            'props' => [
                'ui.breadcrumb' => [
                    'breadcrumbs' => "Array of items: `['label' => ..., 'href' => ...]` (`title` is accepted instead of `label`). The last item is rendered as the current page and doesn't need an `href`.",
                ],
            ],
        ],

        'button' => [
            'title' => 'Button',
            'description' => 'Displays a button or a component that looks like a button.',
            'components' => ['ui.button'],
            'examples' => [
                'default' => 'Default',
                'variants' => 'Variants',
                'sizes' => 'Sizes',
                'icon' => ['title' => 'With icon'],
                'loading' => ['title' => 'Loading', 'description' => 'Combine with `<x-ui.spinner>` and `disabled`.'],
                'link' => ['title' => 'As a link', 'description' => 'Set `tag="a"` to render an anchor with button styles.'],
            ],
            'props' => [
                'ui.button' => [
                    'variant' => '`default`, `destructive`, `outline`, `secondary`, `ghost` or `link`.',
                    'size' => '`default`, `sm`, `lg`, `icon`, `icon-sm` or `icon-lg`.',
                    'tag' => 'HTML tag to render, e.g. `a` for links.',
                    'class' => 'Extra classes merged last.',
                ],
            ],
            'notes' => 'All other attributes (`type`, `href`, `disabled`, `@click`, …) are forwarded to the element.',
        ],

        'card' => [
            'title' => 'Card',
            'description' => 'Displays a card with header, content, and footer.',
            'components' => ['ui.card', 'ui.card-header', 'ui.card-title', 'ui.card-description', 'ui.card-action', 'ui.card-content', 'ui.card-footer'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'center'],
                'with-action' => ['title' => 'With action', 'description' => '`card-action` sits in the header\'s top-right corner.'],
            ],
            'anatomy' => <<<'BLADE'
<x-ui.card>
    <x-ui.card-header>
        <x-ui.card-title>...</x-ui.card-title>
        <x-ui.card-description>...</x-ui.card-description>
        <x-ui.card-action>...</x-ui.card-action>
    </x-ui.card-header>
    <x-ui.card-content>...</x-ui.card-content>
    <x-ui.card-footer>...</x-ui.card-footer>
</x-ui.card>
BLADE,
            'composition' => 'Every part is optional and accepts a `class` prop plus any HTML attribute. Each renders a `data-slot` attribute (`card`, `card-header`, …) that the header grid uses to place `card-action`.',
            'props' => [
                'ui.card' => ['class' => 'Extra classes for the card root.'],
                'ui.card-header' => ['class' => 'Extra classes for the header grid.'],
                'ui.card-title' => ['class' => 'Extra classes for the title.'],
                'ui.card-description' => ['class' => 'Extra classes for the description.'],
                'ui.card-action' => ['class' => 'Extra classes for the action slot.'],
                'ui.card-content' => ['class' => 'Extra classes for the content.'],
                'ui.card-footer' => ['class' => 'Extra classes for the footer.'],
            ],
        ],

        'card-radio-group' => [
            'title' => 'Card Radio Group',
            'description' => 'Radio options presented as selectable cards, with optional descriptions and icons.',
            'components' => ['ui.card-radio-group'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'block'],
                'invisible' => ['title' => 'Invisible radios', 'description' => '`variant="invisible"` (or `:show-radio="false"`) hides the radio dots; the whole card shows the checked state. Options may include an `icon` (raw HTML).', 'preview' => 'block'],
            ],
            'props' => [
                'ui.card-radio-group' => [
                    'name' => 'Name shared by the radio inputs.',
                    'options' => 'Array of values, or arrays with `value`, `label`, optional `description` and (for hidden radios) `icon` HTML.',
                    'default-value' => 'Value checked initially.',
                    'class' => 'Extra classes for the group wrapper.',
                    'show-radio' => 'Show the radio dot in each card.',
                    'variant' => '`default` or `invisible` (hides the radios).',
                    'text-size' => '`small` or `default` label/description size.',
                ],
            ],
        ],

        'checkbox' => [
            'title' => 'Checkbox',
            'description' => 'A control that allows the user to toggle between checked and not checked.',
            'components' => ['ui.checkbox'],
            'examples' => [
                'default' => 'Default',
                'with-label' => ['title' => 'With label'],
                'disabled' => 'Disabled',
            ],
            'props' => [
                'ui.checkbox' => [
                    'checked' => 'Render the checkbox checked. Bind with `:checked="..."`.',
                ],
            ],
            'notes' => 'Renders a native `<input type="checkbox">`; `id`, `name`, `value`, `disabled` and other attributes are forwarded.',
        ],

        'color-picker' => [
            'title' => 'Color Picker',
            'description' => 'A labelled colour swatch with a native picker and a HEX text input kept in sync.',
            'components' => ['ui.color-picker-simple'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'block'],
            ],
            'props' => [
                'ui.color-picker-simple' => [
                    'id' => 'Id of the text input; the swatch and native picker ids derive from it. Must be unique per page.',
                    'name' => 'Name of the HEX text input submitted with the form.',
                    'label' => 'Label text.',
                    'value' => 'Initial colour as `#RRGGBB`.',
                    'class' => 'Extra classes for the wrapper.',
                ],
            ],
            'notes' => 'Clicking the swatch opens the browser colour picker. Typing a valid `#RRGGBB` value updates the swatch.',
        ],

        'dialog' => [
            'title' => 'Dialog',
            'description' => 'A window overlaid on the primary content, rendered with Alpine.js.',
            'components' => ['ui.dialog', 'ui.dialog-trigger', 'ui.dialog-overlay', 'ui.dialog-content', 'ui.dialog-header', 'ui.dialog-title', 'ui.dialog-description', 'ui.dialog-footer', 'ui.dialog-close'],
            'examples' => [
                'default' => 'Default',
                'form' => ['title' => 'With a form'],
                'destructive' => ['title' => 'Confirmation'],
            ],
            'anatomy' => <<<'BLADE'
<x-ui.dialog>
    <x-ui.dialog-trigger>...</x-ui.dialog-trigger>
    <x-ui.dialog-overlay />
    <x-ui.dialog-content>
        <x-ui.dialog-header>
            <x-ui.dialog-title>...</x-ui.dialog-title>
            <x-ui.dialog-description>...</x-ui.dialog-description>
        </x-ui.dialog-header>
        ...
        <x-ui.dialog-footer>
            <x-ui.dialog-close>...</x-ui.dialog-close>
        </x-ui.dialog-footer>
    </x-ui.dialog-content>
</x-ui.dialog>
BLADE,
            'composition' => <<<'MD'
- `dialog` creates the Alpine scope `x-data="{ open: false }"`; every part must be inside it.
- `dialog-trigger` sets `open = true` on click; `dialog-close`, the overlay, the built-in ✕ button and <kbd>Esc</kbd> set it back to `false`.
- `dialog-content` traps focus with `x-trap.noscroll` (requires `@alpinejs/focus`).
- Include `dialog-overlay` to dim the page behind the dialog.
MD,
            'props' => [
                'ui.dialog-trigger' => ['class' => 'Extra classes for the trigger wrapper.'],
                'ui.dialog-overlay' => ['class' => 'Extra classes for the backdrop.'],
                'ui.dialog-content' => ['class' => 'Extra classes for the panel (e.g. `sm:max-w-md`).'],
                'ui.dialog-header' => ['class' => 'Extra classes for the header.'],
                'ui.dialog-title' => ['class' => 'Extra classes for the `<h2>`.'],
                'ui.dialog-description' => ['class' => 'Extra classes for the description.'],
                'ui.dialog-footer' => ['class' => 'Extra classes for the footer.'],
            ],
        ],

        'dropdown' => [
            'title' => 'Dropdown Menu',
            'description' => 'Displays a menu of actions or links, triggered by a button.',
            'components' => ['ui.dropdown', 'ui.dropdown-trigger', 'ui.dropdown-content', 'ui.dropdown-label', 'ui.dropdown-item', 'ui.dropdown-shortcut', 'ui.dropdown-separator', 'ui.dropdown-checkbox-item', 'ui.dropdown-radio-item', 'ui.dropdown-sub', 'ui.dropdown-sub-trigger', 'ui.dropdown-sub-content'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'center'],
                'checkboxes' => ['title' => 'Checkbox & radio items', 'description' => '`checked` / `selected` only control the indicator; wire them to your own state.'],
                'submenu' => ['title' => 'Sub-menu', 'description' => '`dropdown-sub` opens on hover.'],
                'methods' => ['title' => 'Non-GET actions', 'description' => 'Items with `method="post"` (or `put`, `patch`, `delete`) submit a hidden form with the CSRF token from `<meta name="csrf-token">`.'],
            ],
            'anatomy' => <<<'BLADE'
<x-ui.dropdown>
    <x-slot:trigger>
        <x-ui.button variant="outline">Open</x-ui.button>
    </x-slot:trigger>

    <x-ui.dropdown-content>
        <x-ui.dropdown-label>...</x-ui.dropdown-label>
        <x-ui.dropdown-separator />
        <x-ui.dropdown-item href="...">
            ... <x-ui.dropdown-shortcut>⌘K</x-ui.dropdown-shortcut>
        </x-ui.dropdown-item>
        <x-ui.dropdown-checkbox-item :checked="true">...</x-ui.dropdown-checkbox-item>
        <x-ui.dropdown-radio-item :selected="true">...</x-ui.dropdown-radio-item>
        <x-ui.dropdown-sub>
            <x-slot:trigger>
                <x-ui.dropdown-sub-trigger>...</x-ui.dropdown-sub-trigger>
            </x-slot:trigger>
            <x-ui.dropdown-item>...</x-ui.dropdown-item>
        </x-ui.dropdown-sub>
    </x-ui.dropdown-content>
</x-ui.dropdown>
BLADE,
            'composition' => <<<'MD'
- `dropdown` owns the Alpine state. Pass the trigger through the **named `trigger` slot**; clicking it toggles the menu and clicking outside closes it.
- Keyboard: <kbd>↓</kbd>/<kbd>↑</kbd> on the trigger open the menu on the first/last item, arrows/<kbd>Home</kbd>/<kbd>End</kbd> move between items, <kbd>Esc</kbd> closes and refocuses the trigger. The trigger gets `aria-haspopup` / `aria-expanded`.
- `dropdown-trigger` is an optional unstyled-ish `<button>` you can put in that slot instead of `x-ui.button`.
- The panel is right-aligned (`right-0`) and `w-48` by default.
- `dropdown-sub` also uses a named `trigger` slot; its default slot is the sub-menu panel. `dropdown-sub-content` is available if you want to build your own panel.
MD,
            'props' => [
                'ui.dropdown-trigger' => ['class' => 'Extra classes for the trigger button.'],
                'ui.dropdown-content' => ['class' => 'Extra classes for the menu body.'],
                'ui.dropdown-label' => ['class' => 'Extra classes for the label.'],
                'ui.dropdown-item' => [
                    'class' => 'Extra classes for the item.',
                    'href' => 'Link target.',
                    'method' => 'HTTP method. Anything other than `get` submits a generated form (with `_method` spoofing and CSRF).',
                    'params' => 'Extra fields to submit with non-GET requests.',
                ],
                'ui.dropdown-shortcut' => ['class' => 'Extra classes for the shortcut hint.'],
                'ui.dropdown-checkbox-item' => [
                    'class' => 'Extra classes for the item.',
                    'checked' => 'Show the check mark.',
                ],
                'ui.dropdown-radio-item' => [
                    'class' => 'Extra classes for the item.',
                    'selected' => 'Show the radio dot.',
                ],
                'ui.dropdown-sub-trigger' => ['class' => 'Extra classes for the sub-menu trigger.'],
                'ui.dropdown-sub-content' => ['class' => 'Extra classes for the sub-menu panel.'],
            ],
        ],

        'editable-input' => [
            'title' => 'Editable Input',
            'description' => 'An inline URL field with a protocol select and edit / save buttons that PUTs to a route.',
            'components' => ['ui.editable-input'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'block'],
            ],
            'props' => [
                'ui.editable-input' => [
                    'protocol' => 'Initial protocol, `http` or `https`.',
                    'domain' => 'Initial domain value.',
                    'route' => 'URL that receives `PUT` JSON `{ protocol, domain }` with the CSRF header when saving.',
                ],
            ],
            'notes' => 'Saving in these docs will fail (the route is `#`) and show the component\'s error alert. In your app, point `route` at an endpoint that returns a 2xx JSON response.',
        ],

        'empty' => [
            'title' => 'Empty',
            'description' => 'Use the Empty component to display an empty state.',
            'components' => ['ui.empty-state', 'ui.empty-header', 'ui.empty-media', 'ui.empty-title', 'ui.empty-description', 'ui.empty-content'],
            'examples' => [
                'default' => 'Default',
                'outline' => ['title' => 'Outline', 'description' => 'Add a dashed border with classes.'],
            ],
            'anatomy' => <<<'BLADE'
<x-ui.empty-state>
    <x-ui.empty-header>
        <x-ui.empty-media variant="icon">...</x-ui.empty-media>
        <x-ui.empty-title>...</x-ui.empty-title>
        <x-ui.empty-description>...</x-ui.empty-description>
    </x-ui.empty-header>
    <x-ui.empty-content>...</x-ui.empty-content>
</x-ui.empty-state>
BLADE,
            'props' => [
                'ui.empty-state' => ['class' => 'Extra classes for the root.'],
                'ui.empty-header' => ['class' => 'Extra classes for the header.'],
                'ui.empty-media' => [
                    'variant' => '`default` (transparent) or `icon` (muted rounded tile that sizes the svg).',
                    'class' => 'Extra classes.',
                ],
                'ui.empty-title' => ['class' => 'Extra classes for the title.'],
                'ui.empty-description' => ['class' => 'Extra classes for the description.'],
                'ui.empty-content' => ['class' => 'Extra classes for the actions area.'],
            ],
        ],

        'event-calendar' => [
            'title' => 'Event Calendar',
            'description' => 'A month / week calendar with event chips and a details dialog, powered by Alpine.js.',
            'components' => ['ui.event-calendar'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'block', 'description' => 'Dates below are generated relative to today so the demo always has events.'],
            ],
            'props' => [
                'ui.event-calendar' => [
                    'events' => 'Array of events: `id`, `title`, `start`, `end` (date strings parsable by JS `Date`), `allDay` (bool), `color` (`sky`, `amber`, `emerald`, `rose`, `indigo`, `fuchsia` or `primary`), optional `description` and `location`.',
                ],
            ],
            'notes' => 'Up to three events show per day; the rest collapse into “+ N more”. Clicking an event opens a dialog with its details.',
        ],

        'heading' => [
            'title' => 'Heading',
            'description' => 'Section headings with an optional description, used across the settings pages.',
            'components' => ['ui.heading', 'ui.heading-small'],
            'examples' => [
                'default' => ['title' => 'Heading', 'preview' => 'block'],
                'small' => ['title' => 'Heading small', 'preview' => 'block'],
            ],
            'props' => [
                'ui.heading' => [
                    'title' => 'Heading text (`<h2>`).',
                    'description' => 'Muted description below the title.',
                ],
                'ui.heading-small' => [
                    'title' => 'Heading text (`<h3>`).',
                    'description' => 'Muted description below the title.',
                ],
            ],
        ],

        'image-upload' => [
            'title' => 'Image Upload',
            'description' => 'A drag-and-drop image dropzone with preview and size validation.',
            'components' => ['ui.image-upload'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'block'],
            ],
            'props' => [
                'ui.image-upload' => [
                    'id' => 'Id of the file input; ids of the dropzone and preview derive from it. Must be unique per page.',
                    'name' => 'Name of the `<input type="file">`.',
                    'label' => 'Label text.',
                    'maxSizeMB' => 'Maximum file size accepted by the client-side check (also shown in the hint).',
                    'class' => 'Extra classes for the wrapper.',
                ],
            ],
        ],

        'input' => [
            'title' => 'Input',
            'description' => 'Displays a form input field, with an optional validation message.',
            'components' => ['ui.input', 'ui.input-error'],
            'examples' => [
                'default' => 'Default',
                'types' => ['title' => 'Types'],
                'with-label' => ['title' => 'With label'],
                'disabled' => 'Disabled',
                'error' => ['title' => 'With error', 'description' => 'Pair with `input-error` and `aria-invalid` for invalid fields. In a form, pass `:message="$errors->first(\'email\')"`.'],
            ],
            'props' => [
                'ui.input' => [
                    'type' => 'Input type (`text`, `email`, `password`, `file`, …).',
                    'class' => 'Extra classes merged last.',
                ],
                'ui.input-error' => [
                    'message' => 'Error text. Nothing is rendered when empty.',
                    'class' => 'Extra classes for the message.',
                ],
            ],
        ],

        'input-group' => [
            'title' => 'Input Group',
            'description' => 'Combine inputs with icons, text, buttons and textareas inside a single bordered control.',
            'components' => ['ui.input-group', 'ui.input-group-input', 'ui.input-group-addon', 'ui.input-group-text', 'ui.input-group-button', 'ui.input-group-textarea', 'ui.input-group-password'],
            'examples' => [
                'icon' => ['title' => 'With icon', 'description' => 'Addons are laid out in source order: put the addon before the input for a leading icon, after it for a trailing one.'],
                'text' => ['title' => 'With text'],
                'button' => ['title' => 'With button'],
                'password' => ['title' => 'Password', 'description' => '`input-group-password` adds a show/hide toggle. Give the group `relative` so the toggle is positioned inside it, and pass an `id`.'],
                'textarea' => ['title' => 'Textarea'],
            ],
            'anatomy' => <<<'BLADE'
<x-ui.input-group>
    <x-ui.input-group-addon>...</x-ui.input-group-addon>
    <x-ui.input-group-input />
    <x-ui.input-group-addon>
        <x-ui.input-group-text>...</x-ui.input-group-text>
        <x-ui.input-group-button>...</x-ui.input-group-button>
    </x-ui.input-group-addon>
</x-ui.input-group>
BLADE,
            'composition' => 'The group draws the border and focus ring; `input-group-input` and `input-group-textarea` are borderless controls meant to live inside it.',
            'props' => [
                'ui.input-group' => ['class' => 'Extra classes for the group (it is `h-9` by default; use `h-auto` for textareas).'],
                'ui.input-group-input' => [
                    'type' => 'Input type.',
                    'class' => 'Extra classes for the input.',
                ],
                'ui.input-group-addon' => [
                    'class' => 'Extra classes for the addon.',
                    'align' => '`inline-start` or `inline-end` pins the addon before / after the control whatever the source order; omit it to keep source order.',
                ],
                'ui.input-group-text' => ['class' => 'Extra classes for the text.'],
                'ui.input-group-button' => [
                    'size' => '`xs`, `sm`, `icon-xs` or `icon-sm`.',
                    'variant' => 'Any `x-ui.button` variant.',
                    'type' => 'Button type attribute.',
                    'class' => 'Extra classes.',
                ],
                'ui.input-group-textarea' => ['class' => 'Extra classes for the textarea.'],
                'ui.input-group-password' => ['class' => 'Extra classes for the password input.'],
            ],
        ],

        'input-otp' => [
            'title' => 'Input OTP',
            'description' => 'Accessible one-time password input with auto-advance, backspace and paste support.',
            'components' => ['ui.input-otp', 'ui.input-otp-group', 'ui.input-otp-slot', 'ui.input-otp-separator'],
            'examples' => [
                'default' => 'Default',
                'separator' => ['title' => 'With separator'],
            ],
            'anatomy' => <<<'BLADE'
<x-ui.input-otp name="code">
    <x-ui.input-otp-group>
        <x-ui.input-otp-slot :index="0" />
        <x-ui.input-otp-slot :index="1" />
        <x-ui.input-otp-slot :index="2" />
    </x-ui.input-otp-group>
    <x-ui.input-otp-separator />
    <x-ui.input-otp-group>
        ...
    </x-ui.input-otp-group>
</x-ui.input-otp>
BLADE,
            'composition' => 'The slots are combined into a hidden input named after `name` on the root, so your controller receives a single value. Slots accept digits only.',
            'props' => [
                'ui.input-otp' => [
                    'name' => 'Name of the hidden input that receives the combined code.',
                    'length' => 'Declared length. The number of rendered slots is what you place in the slot.',
                    'value' => 'Initial value of the hidden input.',
                ],
                'ui.input-otp-slot' => [
                    'index' => 'Zero-based position of the slot.',
                ],
            ],
            'notes' => 'The script focuses the first empty slot on page load. The hidden input and every slot are `required`.',
        ],

        'input-phone' => [
            'title' => 'Input Phone',
            'description' => 'A phone number input with a country picker, powered by intl-tel-input.',
            'components' => ['ui.input-phone'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'block'],
            ],
            'props' => [
                'ui.input-phone' => [
                    'id' => 'Id of the `<input type="tel">`. Must be unique per page.',
                    'default-country' => 'ISO 3166-1 alpha-2 code of the initially selected country.',
                    'class' => 'Extra classes for the wrapper.',
                ],
            ],
            'notes' => 'Loads `intl-tel-input` 25 from jsDelivr through the `styles` and `scripts` stacks, so your layout needs both `@stack(\'styles\')` and `@stack(\'scripts\')`. The `<input>` has no `name`; read its value with intl-tel-input or add a hidden field.',
        ],

        'input-tags' => [
            'title' => 'Input Tags',
            'description' => 'A keyword/tag input: press Enter or comma to add, click × to remove, paste lists.',
            'components' => ['ui.input-tags'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'block'],
            ],
            'props' => [
                'ui.input-tags' => [
                    'name' => 'Name of the text input; element ids derive from it, so it must be unique per page.',
                    'class' => 'Extra classes for the wrapper.',
                    'placeholder' => 'Placeholder text.',
                    'tags' => 'Initial tags.',
                ],
            ],
            'notes' => 'The slot is the label text. Tags are DOM-only chips; only the text currently typed is submitted under `name`.',
        ],

        'item' => [
            'title' => 'Item',
            'description' => 'A versatile flex container for displaying content with media, title, description and actions.',
            'components' => ['ui.item', 'ui.item-media', 'ui.item-content', 'ui.item-title', 'ui.item-description', 'ui.item-actions', 'ui.item-header', 'ui.item-footer', 'ui.item-group', 'ui.item-separator'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'block'],
                'variants' => ['title' => 'Variants & sizes', 'preview' => 'block'],
                'group' => ['title' => 'Group', 'preview' => 'block'],
            ],
            'anatomy' => <<<'BLADE'
<x-ui.item-group>
    <x-ui.item>
        <x-ui.item-header>...</x-ui.item-header>
        <x-ui.item-media variant="icon">...</x-ui.item-media>
        <x-ui.item-content>
            <x-ui.item-title>...</x-ui.item-title>
            <x-ui.item-description>...</x-ui.item-description>
        </x-ui.item-content>
        <x-ui.item-actions>...</x-ui.item-actions>
        <x-ui.item-footer>...</x-ui.item-footer>
    </x-ui.item>
    <x-ui.item-separator />
</x-ui.item-group>
BLADE,
            'props' => [
                'ui.item' => [
                    'variant' => '`default`, `outline` or `muted`.',
                    'size' => '`default` or `sm`.',
                    'class' => 'Extra classes.',
                ],
                'ui.item-media' => [
                    'variant' => '`default`, `icon` (bordered muted tile) or `image` (cropped 40px image).',
                    'class' => 'Extra classes.',
                ],
                'ui.item-content' => ['class' => 'Extra classes.'],
                'ui.item-title' => ['class' => 'Extra classes.'],
                'ui.item-description' => ['class' => 'Extra classes.'],
                'ui.item-actions' => ['class' => 'Extra classes.'],
                'ui.item-header' => ['class' => 'Extra classes.'],
                'ui.item-footer' => ['class' => 'Extra classes.'],
                'ui.item-group' => ['class' => 'Extra classes. Renders `role="list"`.'],
                'ui.item-separator' => ['class' => 'Extra classes for the `<hr>`.'],
            ],
        ],

        'label' => [
            'title' => 'Label',
            'description' => 'Renders an accessible label associated with controls.',
            'components' => ['ui.label'],
            'examples' => [
                'default' => 'Default',
            ],
            'props' => [
                'ui.label' => ['class' => 'Extra classes. Pass `for` to associate it with a control.'],
            ],
        ],

        'like-button' => [
            'title' => 'Like Button',
            'description' => 'A gradient “Liked” toggle that fades in on hover of a `group` parent.',
            'components' => ['ui.like-button'],
            'examples' => [
                'default' => ['title' => 'Default', 'description' => 'Unliked buttons are `opacity-0` until a parent with the `group` class is hovered.'],
            ],
            'props' => [
                'ui.like-button' => [
                    'liked' => 'Render the active (gradient) state.',
                    'class' => 'Extra classes.',
                ],
            ],
            'notes' => 'The click handler calls `event.stopPropagation()` before your own `onclick`, so it can sit on top of clickable cards. Toggle state on the server or with your own script.',
        ],

        'metric-card' => [
            'title' => 'Metric Card',
            'description' => 'A KPI card with a value, a trend badge and an icon.',
            'components' => ['ui.metric-card'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'block'],
            ],
            'props' => [
                'ui.metric-card' => [
                    'title' => 'Metric name.',
                    'value' => 'Main value.',
                    'change' => 'Change shown in the badge (a `%` sign is appended).',
                    'trend' => '`up` (green, trending-up icon) or `down` (red, trending-down icon).',
                    'icon-bg-color' => 'Tailwind background class for the icon tile.',
                    'icon-color' => 'Tailwind text class for the icon.',
                    'change-bg-color' => 'Override the badge background class.',
                    'change-color' => 'Override the badge text class.',
                ],
            ],
            'notes' => 'Requires a named `icon` slot.',
        ],

        'native-select' => [
            'title' => 'Native Select',
            'description' => 'A styled native `<select>` with a chevron.',
            'components' => ['ui.native-select'],
            'examples' => [
                'default' => 'Default',
                'disabled' => 'Disabled',
            ],
            'props' => [
                'ui.native-select' => ['class' => 'Extra classes for the `<select>`.'],
            ],
        ],

        'notification-group' => [
            'title' => 'Notification Group',
            'description' => 'A segmented radio group for notification preferences, wrapping Radio Group.',
            'components' => ['ui.notification-group'],
            'examples' => [
                'default' => 'Default',
            ],
            'props' => [
                'ui.notification-group' => [
                    'id' => 'Identifier; used as the radio `name` when `name` is not set.',
                    'options' => 'Options passed to `x-ui.radio-group`.',
                    'value' => 'Selected value.',
                    'name' => 'Radio input name.',
                    'class' => 'Extra classes.',
                ],
            ],
        ],

        'payment-card' => [
            'title' => 'Payment Card',
            'description' => 'A card summarising a saved payment method.',
            'components' => ['ui.payment-card'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'block'],
            ],
            'props' => [
                'ui.payment-card' => [
                    'title' => 'Card heading.',
                    'description' => 'Text under the heading.',
                    'card-type' => '`visa` or `master` show the card logo.',
                    'card-holder' => 'Name on the card.',
                    'card-number' => 'Card number; only the last four digits are displayed.',
                    'expiry-date' => 'Any date string Carbon can parse; displayed as `m/y`.',
                ],
            ],
        ],

        'progress' => [
            'title' => 'Progress',
            'description' => 'Displays an indicator showing the completion progress of a task.',
            'components' => ['ui.progress'],
            'examples' => [
                'default' => 'Default',
                'values' => ['title' => 'Values & sizes'],
            ],
            'props' => [
                'ui.progress' => [
                    'value' => 'Percentage from 0 to 100.',
                    'class' => 'Extra classes for the track (e.g. `h-2`).',
                ],
            ],
        ],

        'radio-group' => [
            'title' => 'Radio Group',
            'description' => 'A segmented set of radio buttons where only one can be checked at a time.',
            'components' => ['ui.radio-group'],
            'examples' => [
                'default' => 'Default',
                'associative' => ['title' => 'Value / label pairs'],
            ],
            'props' => [
                'ui.radio-group' => [
                    'name' => 'Name of the radio inputs.',
                    'options' => "Array of values, or arrays of `['value' => ..., 'label' => ...]`.",
                    'value' => 'Checked value.',
                    'class' => 'Extra classes for the group.',
                ],
            ],
        ],

        'segmented-progress' => [
            'title' => 'Segmented Progress',
            'description' => 'A gradient progress bar made of segments, optionally animating to 100%.',
            'components' => ['ui.segmented-progress'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'block'],
                'static' => ['title' => 'Static', 'preview' => 'block'],
            ],
            'props' => [
                'ui.segmented-progress' => [
                    'initial-progress' => 'Starting percentage.',
                    'segments' => 'Number of segments.',
                    'animate-to-end' => 'Animate from the initial value to 100%.',
                    'animation-delay' => 'Delay in ms before animating.',
                    'animation-speed' => 'Interval in ms between 1% steps.',
                    'show-badge' => 'Show the percentage badge.',
                ],
            ],
        ],

        'select' => [
            'title' => 'Select',
            'description' => 'A full-width native select with BladeCN input styling.',
            'components' => ['ui.select'],
            'examples' => [
                'default' => 'Default',
            ],
            'props' => [
                'ui.select' => ['class' => 'Extra classes for the `<select>`.'],
            ],
            'notes' => 'Place `<option>` elements in the slot. See also Native Select, which sizes to its content.',
        ],

        'separator' => [
            'title' => 'Separator',
            'description' => 'Visually or semantically separates content.',
            'components' => ['ui.separator'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'block'],
            ],
            'props' => [
                'ui.separator' => [
                    'orientation' => '`horizontal` or `vertical`.',
                    'decorative' => 'When true, renders `role="separator"`.',
                    'class' => 'Extra classes.',
                ],
            ],
        ],

        'sheet' => [
            'title' => 'Sheet',
            'description' => 'Extends the Dialog idea to display content that complements the main screen, sliding in from an edge.',
            'components' => ['ui.sheet', 'ui.sheet-trigger', 'ui.sheet-header', 'ui.sheet-title', 'ui.sheet-description', 'ui.sheet-footer', 'ui.sheet-close'],
            'examples' => [
                'default' => 'Default',
                'sides' => ['title' => 'Sides', 'description' => '`side` accepts `top`, `right`, `bottom` and `left`.'],
            ],
            'anatomy' => <<<'BLADE'
<x-ui.sheet side="right">
    <x-slot:trigger>
        <x-ui.button>Open</x-ui.button>
    </x-slot:trigger>

    <x-ui.sheet-header>
        <x-ui.sheet-title>...</x-ui.sheet-title>
        <x-ui.sheet-description>...</x-ui.sheet-description>
    </x-ui.sheet-header>
    ...
    <x-ui.sheet-footer>
        <x-ui.sheet-close>...</x-ui.sheet-close>
    </x-ui.sheet-footer>
</x-ui.sheet>
BLADE,
            'composition' => <<<'MD'
- The trigger goes in the **named `trigger` slot**; its first child element opens the sheet.
- Clicking the overlay, pressing <kbd>Esc</kbd> or any `sheet-close` (`data-action="close-sheet"`) closes it.
- `sheet-trigger` is a plain `<span>` wrapper if you need one.
- Sheet uses a small vanilla script (no Alpine); several sheets with different sides can live on one page.
MD,
            'props' => [
                'ui.sheet' => [
                    'side' => '`top`, `right`, `bottom` or `left`.',
                    'class' => 'Extra classes for the sliding panel.',
                ],
                'ui.sheet-trigger' => ['class' => 'Extra classes.'],
                'ui.sheet-header' => ['class' => 'Extra classes.'],
                'ui.sheet-title' => ['class' => 'Extra classes.'],
                'ui.sheet-description' => ['class' => 'Extra classes.'],
                'ui.sheet-footer' => ['class' => 'Extra classes.'],
                'ui.sheet-close' => ['class' => 'Extra classes for the outline close button (the panel also has a built-in ✕).'],
            ],
            'notes' => <<<'MD'
- The panel uses the `background` / `foreground` tokens, so it follows dark mode, and has `role="dialog"` labelled by its title and description.
- Opening moves focus into the sheet and keeps <kbd>Tab</kbd> inside it; closing returns focus to the trigger. A ✕ close button is built in.
- The script is pushed once to the `scripts` stack, so your layout needs `@stack('scripts')`. Sub-components forward extra attributes.
MD,
        ],

        'spinner' => [
            'title' => 'Spinner',
            'description' => 'An animated loading indicator.',
            'components' => ['ui.spinner'],
            'examples' => [
                'default' => 'Default',
                'sizes' => 'Sizes',
            ],
            'props' => [
                'ui.spinner' => [
                    'size' => '`xs`, `sm` (default), `md`, `lg`, `xl` or `default`.',
                    'class' => 'Extra classes (e.g. a text colour).',
                ],
            ],
        ],

        'subscription-card' => [
            'title' => 'Subscription Card',
            'description' => 'A plan summary card with price, renewal date, usage progress and an upgrade link.',
            'components' => ['ui.subscription-card'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'block'],
            ],
            'props' => [
                'ui.subscription-card' => [
                    'plan' => 'Plan name.',
                    'subscription' => 'Billing period shown in the badge (e.g. `monthly`).',
                    'description' => 'Optional description.',
                    'price' => 'Price.',
                    'currency' => 'Currency label shown before the price.',
                    'expire-date' => 'Due date; any string Carbon can parse.',
                    'progress' => 'Usage percentage for the progress bar.',
                    'upgrade-route' => 'When set, shows an “Upgrade plan” link in the footer.',
                ],
            ],
        ],

        'table' => [
            'title' => 'Table',
            'description' => 'A responsive table component.',
            'components' => ['ui.table', 'ui.table-header', 'ui.table-body', 'ui.table-row', 'ui.table-head', 'ui.table-cell'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'block'],
            ],
            'anatomy' => <<<'BLADE'
<x-ui.table>
    <x-ui.table-header>
        <x-ui.table-row>
            <x-ui.table-head>...</x-ui.table-head>
        </x-ui.table-row>
    </x-ui.table-header>
    <x-ui.table-body>
        <x-ui.table-row>
            <x-ui.table-cell>...</x-ui.table-cell>
        </x-ui.table-row>
    </x-ui.table-body>
</x-ui.table>
BLADE,
            'composition' => '`table` wraps the `<table>` in a horizontally scrollable container. Use a plain `<caption>` or `<tfoot>` where needed.',
            'props' => [
                'ui.table' => ['class' => 'Extra classes for the `<table>`.'],
                'ui.table-header' => ['class' => 'Extra classes for `<thead>`.'],
                'ui.table-body' => ['class' => 'Extra classes for `<tbody>`.'],
                'ui.table-row' => ['class' => 'Extra classes for `<tr>`.'],
                'ui.table-head' => ['class' => 'Extra classes for `<th>`.'],
                'ui.table-cell' => ['class' => 'Extra classes for `<td>`.'],
            ],
        ],

        'text-link' => [
            'title' => 'Text Link',
            'description' => 'An underlined inline link that can also submit POST / PUT / PATCH / DELETE requests.',
            'components' => ['ui.text-link'],
            'examples' => [
                'default' => 'Default',
                'method' => ['title' => 'Non-GET method', 'description' => 'With `method` other than `get`, clicking builds and submits a form with `_method` spoofing, the CSRF token from `<meta name="csrf-token">` and any `params`. (In these docs the target is a placeholder, so the request will fail.)'],
            ],
            'props' => [
                'ui.text-link' => [
                    'href' => 'Link target.',
                    'method' => 'HTTP method.',
                    'params' => 'Extra fields to submit with non-GET requests.',
                    'class' => 'Extra classes.',
                ],
            ],
        ],

        'textarea' => [
            'title' => 'Textarea',
            'description' => 'Displays a form textarea.',
            'components' => ['ui.textarea'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'block'],
                'with-label' => ['title' => 'With label', 'preview' => 'block'],
            ],
            'props' => [
                'ui.textarea' => ['class' => 'Extra classes.'],
            ],
            'notes' => 'The slot becomes the initial value. Write it on one line (`<x-ui.textarea>{{ $bio }}</x-ui.textarea>`) or use a `value` workflow of your own, because whitespace inside the tag is preserved.',
        ],

        'tone-wheel' => [
            'title' => 'Tone Wheel',
            'description' => 'A four-quadrant picker (Catchy, Professional, Informative, Empower) where a click places a knob.',
            'components' => ['ui.tone-wheel'],
            'examples' => [
                'default' => ['title' => 'Default'],
            ],
            'props' => [
                'ui.tone-wheel' => [
                    'class' => 'Extra classes for the outer card (e.g. a max width).',
                ],
            ],
        ],

        'tooltip' => [
            'title' => 'Tooltip',
            'description' => 'A popup that displays information related to an element when it is hovered.',
            'components' => ['ui.tooltip'],
            'examples' => [
                'default' => 'Default',
                'sides' => 'Sides',
            ],
            'props' => [
                'ui.tooltip' => [
                    'side' => '`top`, `bottom`, `left` or `right`.',
                    'side-offset' => 'Gap in pixels between trigger and tooltip.',
                    'class' => 'Extra classes for the tooltip bubble.',
                ],
            ],
            'notes' => <<<'MD'
- Set the text with the `content` attribute. The first child of the slot is the trigger.
- Opens on hover and keyboard focus (after a 100 ms delay), closes on leave, blur, <kbd>Esc</kbd> or scroll. The trigger gets `aria-describedby` pointing at the bubble.
- The bubble is `position: fixed` next to the trigger on `side`, `side-offset` pixels away; it flips to the opposite side when there is no room and is kept inside the viewport.
- The script is pushed once to the `scripts` stack.
MD,
        ],

        'accordion' => [
            'title' => 'Accordion',
            'description' => 'A vertically stacked set of interactive headings that each reveal a section of content.',
            'components' => ['ui.accordion', 'ui.accordion-item', 'ui.accordion-trigger', 'ui.accordion-content'],
            'examples' => [
                'default' => ['title' => 'Default', 'description' => '`type="single"` with `collapsible` lets the open item be closed again.', 'preview' => 'block'],
                'multiple' => ['title' => 'Multiple', 'description' => '`type="multiple"` keeps several items open; `default-value` takes an array. Disabled items are skipped by the keyboard.', 'preview' => 'block'],
            ],
            'anatomy' => <<<'BLADE'
<x-ui.accordion type="single" collapsible default-value="a">
    <x-ui.accordion-item value="a">
        <x-ui.accordion-trigger>...</x-ui.accordion-trigger>
        <x-ui.accordion-content>...</x-ui.accordion-content>
    </x-ui.accordion-item>
</x-ui.accordion>
BLADE,
            'notes' => <<<'MD'
- Triggers are `<button>`s inside `<h3>`s with `aria-expanded` / `aria-controls`; contents are `role="region"` labelled by their trigger.
- <kbd>↑</kbd> <kbd>↓</kbd> <kbd>Home</kbd> <kbd>End</kbd> move between triggers; <kbd>Enter</kbd> / <kbd>Space</kbd> toggle.
- Height animates with CSS grid rows; closed content is `inert`. Items listed in `default-value` are rendered open on the server, so they don't flash.
MD,
            'props' => [
                'ui.accordion' => [
                    'type' => '`single` (one item open at a time) or `multiple`.',
                    'collapsible' => 'With `type="single"`, allow closing the open item.',
                    'default-value' => 'Initially open item value, or an array of values for `multiple`.',
                    'class' => 'Extra classes for the root.',
                ],
                'ui.accordion-item' => [
                    'value' => 'Unique value identifying the item (required).',
                    'disabled' => 'Prevent the item from being toggled.',
                    'class' => 'Extra classes for the item.',
                ],
                'ui.accordion-trigger' => ['class' => 'Extra classes for the trigger button.'],
                'ui.accordion-content' => ['class' => 'Extra classes for the inner content wrapper.'],
            ],
        ],

        'alert' => [
            'title' => 'Alert',
            'description' => 'Displays a callout for user attention.',
            'components' => ['ui.alert', 'ui.alert-title', 'ui.alert-description'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'block'],
                'destructive' => ['title' => 'Destructive', 'preview' => 'block'],
            ],
            'anatomy' => <<<'BLADE'
<x-ui.alert>
    <x-icons.info />
    <x-ui.alert-title>...</x-ui.alert-title>
    <x-ui.alert-description>...</x-ui.alert-description>
</x-ui.alert>
BLADE,
            'composition' => 'An icon placed as the first child gets its own column; title and description line up next to it. The root has `role="alert"`.',
            'props' => [
                'ui.alert' => [
                    'variant' => '`default` or `destructive`.',
                    'class' => 'Extra classes for the alert.',
                ],
                'ui.alert-title' => ['class' => 'Extra classes for the title.'],
                'ui.alert-description' => ['class' => 'Extra classes for the description.'],
            ],
        ],

        'alert-dialog' => [
            'title' => 'Alert Dialog',
            'description' => 'A modal dialog that interrupts the user with important content and expects a response.',
            'components' => ['ui.alert-dialog', 'ui.alert-dialog-trigger', 'ui.alert-dialog-content', 'ui.alert-dialog-header', 'ui.alert-dialog-title', 'ui.alert-dialog-description', 'ui.alert-dialog-footer', 'ui.alert-dialog-cancel', 'ui.alert-dialog-action'],
            'examples' => [
                'default' => 'Default',
                'form' => ['title' => 'Confirming a form', 'description' => 'The dialog renders in place, so an action with `type="submit"` submits the surrounding form (here it is intercepted for the demo).'],
            ],
            'anatomy' => <<<'BLADE'
<x-ui.alert-dialog>
    <x-ui.alert-dialog-trigger>...</x-ui.alert-dialog-trigger>
    <x-ui.alert-dialog-content>
        <x-ui.alert-dialog-header>
            <x-ui.alert-dialog-title>...</x-ui.alert-dialog-title>
            <x-ui.alert-dialog-description>...</x-ui.alert-dialog-description>
        </x-ui.alert-dialog-header>
        <x-ui.alert-dialog-footer>
            <x-ui.alert-dialog-cancel>...</x-ui.alert-dialog-cancel>
            <x-ui.alert-dialog-action>...</x-ui.alert-dialog-action>
        </x-ui.alert-dialog-footer>
    </x-ui.alert-dialog-content>
</x-ui.alert-dialog>
BLADE,
            'notes' => <<<'MD'
- The panel has `role="alertdialog"`, `aria-modal`, and is labelled by its title and description.
- Focus is trapped inside (`x-trap`, requires `@alpinejs/focus`) and starts on the first button, the Cancel action; the page behind is made `inert`.
- <kbd>Esc</kbd> and Cancel close it. Unlike Dialog, clicking the overlay does **not** close it.
- `alert-dialog-trigger`, `-cancel` and `-action` render `<x-ui.button>`s; extra attributes are forwarded. Works with `x-model` on the root to control `open`.
MD,
            'props' => [
                'ui.alert-dialog' => [
                    'open' => 'Initial open state.',
                    'class' => 'Extra classes for the root.',
                ],
                'ui.alert-dialog-trigger' => [
                    'variant' => 'Button variant of the trigger.',
                    'size' => 'Button size of the trigger.',
                    'class' => 'Extra classes for the trigger.',
                ],
                'ui.alert-dialog-content' => ['class' => 'Extra classes for the panel (e.g. `sm:max-w-md`).'],
                'ui.alert-dialog-header' => ['class' => 'Extra classes.'],
                'ui.alert-dialog-title' => ['class' => 'Extra classes.'],
                'ui.alert-dialog-description' => ['class' => 'Extra classes.'],
                'ui.alert-dialog-footer' => ['class' => 'Extra classes.'],
                'ui.alert-dialog-cancel' => ['class' => 'Extra classes for the outline button.'],
                'ui.alert-dialog-action' => [
                    'variant' => 'Button variant, e.g. `destructive`.',
                    'class' => 'Extra classes for the button.',
                ],
            ],
        ],

        'aspect-ratio' => [
            'title' => 'Aspect Ratio',
            'description' => 'Displays content within a desired ratio.',
            'components' => ['ui.aspect-ratio'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'block'],
                'square' => ['title' => 'Ratios', 'preview' => 'block'],
            ],
            'notes' => 'Uses the CSS `aspect-ratio` property. A direct `<img>` child is stretched to cover the box.',
            'props' => [
                'ui.aspect-ratio' => [
                    'ratio' => 'Width / height as a number (`1.5`) or a string (`"16/9"`, `"4:3"`).',
                    'class' => 'Extra classes for the box.',
                ],
            ],
        ],

        'button-group' => [
            'title' => 'Button Group',
            'description' => 'A container that groups related buttons together with consistent styling.',
            'components' => ['ui.button-group', 'ui.button-group-text', 'ui.button-group-separator'],
            'examples' => [
                'default' => 'Default',
                'orientation' => ['title' => 'Orientation and separator', 'description' => 'Use a separator between buttons that don\'t have a border, such as `secondary` ones.'],
                'nested' => ['title' => 'Nested', 'description' => 'Nest groups to add spacing between them; inputs and text addons join seamlessly.'],
            ],
            'notes' => 'The group has `role="group"`; give it an `aria-label`. Children lose their inner borders and radii.',
            'props' => [
                'ui.button-group' => [
                    'orientation' => '`horizontal` or `vertical`.',
                    'class' => 'Extra classes for the group.',
                ],
                'ui.button-group-text' => ['class' => 'Extra classes for the text addon.'],
                'ui.button-group-separator' => [
                    'orientation' => 'Orientation of the separator line; `vertical` in a horizontal group.',
                    'class' => 'Extra classes.',
                ],
            ],
        ],

        'calendar' => [
            'title' => 'Calendar',
            'description' => 'A date field component that allows users to enter and edit a date.',
            'components' => ['ui.calendar'],
            'examples' => [
                'default' => 'Default',
                'min-max' => ['title' => 'Min / max and x-model', 'description' => 'Dates outside `min`…`max` are disabled; `week-starts-on="1"` starts weeks on Monday.'],
            ],
            'notes' => <<<'MD'
- Values are `Y-m-d` strings (anything Carbon can parse is accepted as a prop). With `name` a hidden input is submitted; `x-model` binds the value.
- Keyboard: arrows move by day/week, <kbd>PageUp</kbd>/<kbd>PageDown</kbd> by month (with <kbd>Shift</kbd> by year), <kbd>Home</kbd>/<kbd>End</kbd> to the week edges, <kbd>Enter</kbd>/<kbd>Space</kbd> select.
- Month and weekday names come from `Intl` in the app locale (or `locale`). Dispatches a bubbling `change` event with `{ value }`.
MD,
            'props' => [
                'ui.calendar' => [
                    'value' => 'Selected date.',
                    'name' => 'Name of the hidden input.',
                    'min' => 'Earliest selectable date.',
                    'max' => 'Latest selectable date.',
                    'week-starts-on' => '`0` = Sunday … `6` = Saturday.',
                    'locale' => 'BCP 47 locale for month/day names. Defaults to `app()->getLocale()`.',
                    'class' => 'Extra classes for the root.',
                ],
            ],
        ],

        'collapsible' => [
            'title' => 'Collapsible',
            'description' => 'An interactive component which expands/collapses a panel.',
            'components' => ['ui.collapsible', 'ui.collapsible-trigger', 'ui.collapsible-content'],
            'examples' => [
                'default' => 'Default',
            ],
            'anatomy' => <<<'BLADE'
<x-ui.collapsible>
    <x-ui.collapsible-trigger>...</x-ui.collapsible-trigger>
    <x-ui.collapsible-content>...</x-ui.collapsible-content>
</x-ui.collapsible>
BLADE,
            'composition' => 'The trigger is a `<button>` with `aria-expanded` and `aria-controls`. Give it a `variant` to render it as an `<x-ui.button>`. Use `x-model` on the root to control `open`.',
            'props' => [
                'ui.collapsible' => [
                    'open' => 'Initial open state (also rendered server-side).',
                    'disabled' => 'Disable the trigger.',
                    'class' => 'Extra classes for the root.',
                ],
                'ui.collapsible-trigger' => [
                    'variant' => 'Render as an `<x-ui.button>` with this variant. Omit for an unstyled `<button>`.',
                    'size' => 'Button size when `variant` is set.',
                    'class' => 'Extra classes.',
                ],
                'ui.collapsible-content' => ['class' => 'Extra classes for the panel.'],
            ],
        ],

        'combobox' => [
            'title' => 'Combobox',
            'description' => 'Autocomplete input and command palette with a list of suggestions.',
            'components' => ['ui.combobox'],
            'examples' => [
                'default' => ['title' => 'Default', 'description' => 'Selecting the current value again clears it, as in shadcn/ui.'],
                'x-model' => ['title' => 'With x-model'],
            ],
            'notes' => 'Built from Popover + Command, so filtering and keyboard navigation work the same way. With `name`, the selected value is submitted through a hidden input. For a custom layout, compose `x-ui.popover` and `x-ui.command` yourself and listen for `command-select`.',
            'props' => [
                'ui.combobox' => [
                    'options' => "`['value' => 'Label']`, a list of strings, or a list of `['value' => ..., 'label' => ...]`.",
                    'value' => 'Initially selected value.',
                    'name' => 'Name of the hidden input.',
                    'placeholder' => 'Trigger text when nothing is selected.',
                    'search-placeholder' => 'Placeholder of the search input.',
                    'empty' => 'Text shown when no option matches.',
                    'disabled' => 'Disable the trigger.',
                    'class' => 'Extra classes for the trigger button (e.g. a width).',
                ],
            ],
        ],

        'command' => [
            'title' => 'Command',
            'description' => 'Fast, composable command menu with filtering and keyboard navigation.',
            'components' => ['ui.command', 'ui.command-input', 'ui.command-list', 'ui.command-empty', 'ui.command-group', 'ui.command-item', 'ui.command-separator', 'ui.command-shortcut', 'ui.command-dialog'],
            'examples' => [
                'default' => 'Default',
                'dialog' => ['title' => 'Dialog', 'description' => 'Press <kbd>⌘</kbd>/<kbd>Ctrl</kbd> + <kbd>K</kbd>. Items with `href` navigate; listen for `command-select` to run an action.'],
            ],
            'anatomy' => <<<'BLADE'
<x-ui.command>
    <x-ui.command-input />
    <x-ui.command-list>
        <x-ui.command-empty>...</x-ui.command-empty>
        <x-ui.command-group heading="...">
            <x-ui.command-item>...</x-ui.command-item>
        </x-ui.command-group>
        <x-ui.command-separator />
    </x-ui.command-list>
</x-ui.command>
BLADE,
            'notes' => <<<'MD'
- Typing filters items by their text, `value` and `keywords` (all words must match); empty groups hide and `command-empty` shows when nothing matches.
- <kbd>↑</kbd>/<kbd>↓</kbd> move the highlighted item (`aria-activedescendant` on the `role="combobox"` input), <kbd>Enter</kbd> selects.
- Selecting dispatches a bubbling `command-select` event with `{ value }` and follows the item's `href`, if any.
MD,
            'props' => [
                'ui.command' => ['class' => 'Extra classes for the root.'],
                'ui.command-input' => [
                    'placeholder' => 'Placeholder text.',
                    'class' => 'Extra classes for the `<input>`.',
                ],
                'ui.command-list' => ['class' => 'Extra classes for the scrollable list.'],
                'ui.command-empty' => ['class' => 'Extra classes.'],
                'ui.command-group' => [
                    'heading' => 'Group heading, also its accessible name.',
                    'class' => 'Extra classes.',
                ],
                'ui.command-item' => [
                    'value' => 'Value passed with `command-select`. Defaults to the item text.',
                    'keywords' => 'Extra search terms (string or array).',
                    'href' => 'URL to visit when selected.',
                    'disabled' => 'Skip the item for filtering results, keyboard and clicks.',
                    'class' => 'Extra classes.',
                ],
                'ui.command-separator' => ['class' => 'Extra classes. Hidden while filtering.'],
                'ui.command-shortcut' => ['class' => 'Extra classes.'],
                'ui.command-dialog' => [
                    'shortcut' => 'Key that toggles the dialog with ⌘/Ctrl. Empty to disable.',
                    'title' => 'Accessible name of the dialog.',
                    'description' => 'Screen-reader description.',
                    'class' => 'Extra classes for the root.',
                ],
            ],
        ],

        'context-menu' => [
            'title' => 'Context Menu',
            'description' => 'Displays a menu to the user, such as a set of actions or functions, triggered by a right click.',
            'components' => ['ui.context-menu', 'ui.context-menu-trigger', 'ui.context-menu-content', 'ui.context-menu-item', 'ui.context-menu-label', 'ui.context-menu-separator', 'ui.context-menu-shortcut'],
            'examples' => [
                'default' => 'Default',
            ],
            'anatomy' => <<<'BLADE'
<x-ui.context-menu>
    <x-ui.context-menu-trigger>...</x-ui.context-menu-trigger>
    <x-ui.context-menu-content>
        <x-ui.context-menu-label>...</x-ui.context-menu-label>
        <x-ui.context-menu-item>...</x-ui.context-menu-item>
        <x-ui.context-menu-separator />
    </x-ui.context-menu-content>
</x-ui.context-menu>
BLADE,
            'notes' => <<<'MD'
- Opens at the pointer on right click, or centred on the trigger with <kbd>Shift</kbd>+<kbd>F10</kbd> / the Menu key; it is kept inside the viewport.
- `role="menu"` with `menuitem`s: <kbd>↑</kbd>/<kbd>↓</kbd>/<kbd>Home</kbd>/<kbd>End</kbd> and typeahead move focus; <kbd>Esc</kbd>, clicking outside, scrolling or choosing an item closes it and returns focus.
MD,
            'props' => [
                'ui.context-menu' => ['class' => 'Extra classes for the root.'],
                'ui.context-menu-trigger' => ['class' => 'Extra classes for the trigger area.'],
                'ui.context-menu-content' => ['class' => 'Extra classes for the menu.'],
                'ui.context-menu-item' => [
                    'href' => 'Render a link.',
                    'variant' => '`default` or `destructive`.',
                    'inset' => 'Indent to line up with items that have an icon.',
                    'disabled' => 'Disable the item.',
                    'class' => 'Extra classes.',
                ],
                'ui.context-menu-label' => [
                    'inset' => 'Indent the label.',
                    'class' => 'Extra classes.',
                ],
                'ui.context-menu-separator' => ['class' => 'Extra classes.'],
                'ui.context-menu-shortcut' => ['class' => 'Extra classes.'],
            ],
        ],

        'date-picker' => [
            'title' => 'Date Picker',
            'description' => 'A date picker built from Popover and Calendar.',
            'components' => ['ui.date-picker'],
            'examples' => [
                'default' => 'Default',
                'form' => ['title' => 'Formatting and x-model', 'description' => '`format` is an `Intl.DateTimeFormat` options array; the bound and submitted value stays `Y-m-d`.'],
            ],
            'props' => [
                'ui.date-picker' => [
                    'value' => 'Selected date.',
                    'name' => 'Name of the hidden input.',
                    'placeholder' => 'Text when no date is selected.',
                    'min' => 'Earliest selectable date.',
                    'max' => 'Latest selectable date.',
                    'week-starts-on' => '`0` = Sunday … `6` = Saturday.',
                    'locale' => 'Locale for formatting. Defaults to the app locale.',
                    'format' => "`Intl.DateTimeFormat` options, default `['dateStyle' => 'long']`.",
                    'class' => 'Extra classes for the trigger button.',
                ],
            ],
        ],

        'field' => [
            'title' => 'Field',
            'description' => 'Combine labels, controls, help text and errors into accessible form fields.',
            'components' => ['ui.field', 'ui.field-set', 'ui.field-legend', 'ui.field-group', 'ui.field-content', 'ui.field-label', 'ui.field-title', 'ui.field-description', 'ui.field-separator', 'ui.field-error'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'block'],
                'switch' => ['title' => 'Horizontal', 'description' => '`orientation="horizontal"` puts the control next to its label and description.'],
                'error' => ['title' => 'Errors', 'description' => 'Mark the field `invalid` and add `field-error`.'],
            ],
            'anatomy' => <<<'BLADE'
<x-ui.field-set>
    <x-ui.field-legend>...</x-ui.field-legend>
    <x-ui.field-group>
        <x-ui.field>
            <x-ui.field-label for="email">...</x-ui.field-label>
            <x-ui.input id="email" name="email" />
            <x-ui.field-description>...</x-ui.field-description>
            <x-ui.field-error name="email" />
        </x-ui.field>
    </x-ui.field-group>
</x-ui.field-set>
BLADE,
            'composition' => '`field-error name="email"` reads Laravel\'s validation errors for that field (from the shared `$errors` bag), so a failed form request shows up with no extra code. It renders nothing when there are no messages.',
            'props' => [
                'ui.field' => [
                    'orientation' => '`vertical`, `horizontal` or `responsive` (horizontal from the `md` container size of a `field-group`).',
                    'invalid' => 'Style the field as invalid.',
                    'class' => 'Extra classes.',
                ],
                'ui.field-set' => ['class' => 'Extra classes for the `<fieldset>`.'],
                'ui.field-legend' => [
                    'variant' => '`legend` or the smaller `label`.',
                    'class' => 'Extra classes.',
                ],
                'ui.field-group' => ['class' => 'Extra classes.'],
                'ui.field-content' => ['class' => 'Extra classes.'],
                'ui.field-label' => ['class' => 'Extra classes for the `<label>`.'],
                'ui.field-title' => ['class' => 'Extra classes.'],
                'ui.field-description' => ['class' => 'Extra classes.'],
                'ui.field-separator' => ['class' => 'Extra classes. The slot is optional text on the line.'],
                'ui.field-error' => [
                    'name' => 'Field name to read messages for from Laravel\'s error bag.',
                    'messages' => 'Messages to show (array or `MessageBag`), merged with the bag\'s.',
                    'bag' => 'Named error bag, for `validateWithBag`.',
                    'class' => 'Extra classes.',
                ],
            ],
        ],

        'hover-card' => [
            'title' => 'Hover Card',
            'description' => 'For sighted users to preview content available behind a link.',
            'components' => ['ui.hover-card', 'ui.hover-card-trigger', 'ui.hover-card-content'],
            'examples' => [
                'default' => 'Default',
            ],
            'notes' => 'Opens after `open-delay` on hover or keyboard focus, closes after `close-delay` when the pointer leaves both trigger and card, and immediately on blur or <kbd>Esc</kbd>. It is supplementary: keep essential content reachable without it.',
            'props' => [
                'ui.hover-card' => [
                    'open-delay' => 'Milliseconds before opening.',
                    'close-delay' => 'Milliseconds before closing.',
                    'class' => 'Extra classes for the root.',
                ],
                'ui.hover-card-trigger' => [
                    'href' => 'Link target. Without it the trigger is a focusable `<span>`.',
                    'class' => 'Extra classes.',
                ],
                'ui.hover-card-content' => [
                    'side' => '`top`, `right`, `bottom` or `left`.',
                    'align' => '`start`, `center` or `end`.',
                    'side-offset' => 'Gap in pixels between trigger and card.',
                    'class' => 'Extra classes for the card.',
                ],
            ],
        ],

        'kbd' => [
            'title' => 'Kbd',
            'description' => 'Used to display textual user input from keyboard.',
            'components' => ['ui.kbd', 'ui.kbd-group'],
            'examples' => [
                'default' => 'Default',
                'in-tooltip' => ['title' => 'In buttons and tooltips'],
            ],
            'props' => [
                'ui.kbd' => ['class' => 'Extra classes for the `<kbd>`.'],
                'ui.kbd-group' => ['class' => 'Extra classes for the group.'],
            ],
        ],

        'pagination' => [
            'title' => 'Pagination',
            'description' => 'Pagination with page navigation, next and previous links.',
            'components' => ['ui.pagination', 'ui.pagination-content', 'ui.pagination-item', 'ui.pagination-link', 'ui.pagination-previous', 'ui.pagination-next', 'ui.pagination-ellipsis'],
            'examples' => [
                'default' => 'Default',
                'paginator' => ['title' => 'From a Laravel paginator', 'description' => 'Pass any `LengthAwarePaginator` (`User::paginate()`) and the links are built for you with Laravel\'s own URL window. A simple paginator renders Previous / Next only.'],
            ],
            'anatomy' => <<<'BLADE'
<x-ui.pagination>
    <x-ui.pagination-content>
        <x-ui.pagination-item><x-ui.pagination-previous href="..." /></x-ui.pagination-item>
        <x-ui.pagination-item><x-ui.pagination-link href="..." is-active>1</x-ui.pagination-link></x-ui.pagination-item>
        <x-ui.pagination-item><x-ui.pagination-ellipsis /></x-ui.pagination-item>
        <x-ui.pagination-item><x-ui.pagination-next href="..." /></x-ui.pagination-item>
    </x-ui.pagination-content>
</x-ui.pagination>
BLADE,
            'props' => [
                'ui.pagination' => [
                    'paginator' => 'A Laravel paginator to render automatically.',
                    'on-each-side' => 'Pages shown on each side of the current one (with `paginator`).',
                    'class' => 'Extra classes for the `<nav>`.',
                ],
                'ui.pagination-content' => ['class' => 'Extra classes for the `<ul>`.'],
                'ui.pagination-item' => ['class' => 'Extra classes for the `<li>`.'],
                'ui.pagination-link' => [
                    'href' => 'Page URL. Without it (or when `disabled`) a non-interactive `<span>` is rendered.',
                    'is-active' => 'Mark as the current page (`aria-current="page"`).',
                    'disabled' => 'Render as disabled.',
                    'size' => '`icon`, `default`, `sm` or `lg`.',
                    'class' => 'Extra classes.',
                ],
                'ui.pagination-previous' => [
                    'href' => 'URL of the previous page.',
                    'disabled' => 'Disable on the first page.',
                    'label' => 'Visible text (hidden on small screens).',
                    'class' => 'Extra classes.',
                ],
                'ui.pagination-next' => [
                    'href' => 'URL of the next page.',
                    'disabled' => 'Disable on the last page.',
                    'label' => 'Visible text (hidden on small screens).',
                    'class' => 'Extra classes.',
                ],
                'ui.pagination-ellipsis' => ['class' => 'Extra classes.'],
            ],
        ],

        'popover' => [
            'title' => 'Popover',
            'description' => 'Displays rich content in a portal, triggered by a button.',
            'components' => ['ui.popover', 'ui.popover-trigger', 'ui.popover-content'],
            'examples' => [
                'default' => 'Default',
                'sides' => 'Sides',
            ],
            'anatomy' => <<<'BLADE'
<x-ui.popover>
    <x-ui.popover-trigger>...</x-ui.popover-trigger>
    <x-ui.popover-content side="bottom" align="center">...</x-ui.popover-content>
</x-ui.popover>
BLADE,
            'notes' => <<<'MD'
- Click the trigger to toggle. Opening moves focus into the content; <kbd>Esc</kbd> closes and returns focus to the trigger; clicking or tabbing outside closes it.
- The content is positioned with CSS relative to the root (`side`, `align`, `side-offset`); it does not flip, so pick a side with room.
- `popover-trigger` renders an outline `<x-ui.button>` (pass `variant=""` for a bare `<button>`). Use `x-model` on the root to control `open`.
MD,
            'props' => [
                'ui.popover' => [
                    'open' => 'Initial open state.',
                    'class' => 'Extra classes for the root.',
                ],
                'ui.popover-trigger' => [
                    'variant' => 'Button variant; empty for an unstyled button.',
                    'size' => 'Button size.',
                    'class' => 'Extra classes.',
                ],
                'ui.popover-content' => [
                    'side' => '`top`, `right`, `bottom` or `left`.',
                    'align' => '`start`, `center` or `end`.',
                    'side-offset' => 'Gap in pixels between trigger and content.',
                    'class' => 'Extra classes for the panel.',
                ],
            ],
        ],

        'scroll-area' => [
            'title' => 'Scroll Area',
            'description' => 'A scrollable region with thin, theme-coloured scrollbars.',
            'components' => ['ui.scroll-area'],
            'examples' => [
                'default' => 'Default',
                'horizontal' => 'Horizontal',
            ],
            'notes' => 'Native scrolling (so touch, wheel and keyboard behave as usual) with styled scrollbars via `scrollbar-width`/`scrollbar-color` and `::-webkit-scrollbar`. It is focusable so keyboard users can scroll it. Unlike Radix, there is no custom overlay scrollbar.',
            'props' => [
                'ui.scroll-area' => [
                    'orientation' => '`vertical`, `horizontal` or `both`.',
                    'class' => 'Extra classes; set a height or width to make it scroll.',
                ],
            ],
        ],

        'skeleton' => [
            'title' => 'Skeleton',
            'description' => 'Use to show a placeholder while content is loading.',
            'components' => ['ui.skeleton'],
            'examples' => [
                'default' => 'Default',
                'card' => 'Card',
            ],
            'props' => [
                'ui.skeleton' => ['class' => 'Size and shape classes (e.g. `h-4 w-48`, `rounded-full`).'],
            ],
        ],

        'slider' => [
            'title' => 'Slider',
            'description' => 'An input where the user selects a value from within a given range.',
            'components' => ['ui.slider'],
            'examples' => [
                'default' => ['title' => 'Default', 'preview' => 'center'],
                'x-model' => ['title' => 'With x-model and decimals'],
                'vertical' => ['title' => 'Vertical and disabled'],
            ],
            'notes' => <<<'MD'
- The thumb has `role="slider"` with `aria-valuenow/min/max`; give it an accessible name with `label`.
- Drag or click the track; <kbd>←</kbd>/<kbd>→</kbd>/<kbd>↑</kbd>/<kbd>↓</kbd> step, <kbd>PageUp</kbd>/<kbd>PageDown</kbd> jump by 10 %, <kbd>Home</kbd>/<kbd>End</kbd> go to the ends.
- With `name` the value is submitted through a hidden input; `x-model` binds it; a bubbling `change` event carries `{ value }`.
MD,
            'props' => [
                'ui.slider' => [
                    'value' => 'Initial value (defaults to `min`).',
                    'min' => 'Minimum value.',
                    'max' => 'Maximum value.',
                    'step' => 'Step between values; decimals are supported.',
                    'name' => 'Name of the hidden input.',
                    'disabled' => 'Disable the slider.',
                    'orientation' => '`horizontal` or `vertical` (set a height).',
                    'label' => 'Accessible name of the thumb.',
                    'class' => 'Extra classes for the root.',
                ],
            ],
        ],

        'switch' => [
            'title' => 'Switch',
            'description' => 'A control that allows the user to toggle between checked and not checked.',
            'components' => ['ui.switch'],
            'examples' => [
                'default' => 'Default',
                'states' => 'States and sizes',
                'form' => ['title' => 'In a form', 'description' => 'With `name`, a hidden checkbox submits `value` when on (and nothing when off, like a checkbox). `x-model` binds a boolean.'],
            ],
            'notes' => 'A `<button role="switch">` with `aria-checked`. `id` and other attributes go to the button, so `<x-ui.label for="...">` works. Dispatches a bubbling `change` event with `{ checked }`.',
            'props' => [
                'ui.switch' => [
                    'checked' => 'Initial state.',
                    'name' => 'Form field name.',
                    'value' => 'Submitted value when on.',
                    'disabled' => 'Disable the switch.',
                    'size' => '`default` or `sm`.',
                    'class' => 'Extra classes for the button.',
                ],
            ],
        ],

        'tabs' => [
            'title' => 'Tabs',
            'description' => 'A set of layered sections of content, known as tab panels, that are displayed one at a time.',
            'components' => ['ui.tabs', 'ui.tabs-list', 'ui.tabs-trigger', 'ui.tabs-content'],
            'examples' => [
                'default' => 'Default',
                'vertical' => ['title' => 'Vertical', 'description' => 'With `orientation="vertical"` the arrow keys are <kbd>↑</kbd>/<kbd>↓</kbd>. Disabled tabs are skipped.'],
            ],
            'anatomy' => <<<'BLADE'
<x-ui.tabs default-value="a">
    <x-ui.tabs-list>
        <x-ui.tabs-trigger value="a">...</x-ui.tabs-trigger>
        <x-ui.tabs-trigger value="b">...</x-ui.tabs-trigger>
    </x-ui.tabs-list>
    <x-ui.tabs-content value="a">...</x-ui.tabs-content>
    <x-ui.tabs-content value="b">...</x-ui.tabs-content>
</x-ui.tabs>
BLADE,
            'notes' => <<<'MD'
- WAI-ARIA tabs: `tablist` / `tab` / `tabpanel` with roving `tabindex`. <kbd>←</kbd>/<kbd>→</kbd> (or <kbd>↑</kbd>/<kbd>↓</kbd>), <kbd>Home</kbd>, <kbd>End</kbd> move between tabs and activate them (`activation="manual"` to activate on <kbd>Enter</kbd>/click only).
- The tab in `default-value` is rendered active on the server. `x-model` on the root binds the active value.
MD,
            'props' => [
                'ui.tabs' => [
                    'default-value' => 'Initially active tab. Defaults to the first one.',
                    'orientation' => '`horizontal` or `vertical`.',
                    'activation' => '`automatic` (focus activates) or `manual`.',
                    'class' => 'Extra classes for the root.',
                ],
                'ui.tabs-list' => ['class' => 'Extra classes for the list.'],
                'ui.tabs-trigger' => [
                    'value' => 'Value of the tab (required).',
                    'disabled' => 'Disable the tab.',
                    'class' => 'Extra classes.',
                ],
                'ui.tabs-content' => [
                    'value' => 'Value of the tab this panel belongs to (required).',
                    'class' => 'Extra classes.',
                ],
            ],
        ],

        'toast' => [
            'title' => 'Toast',
            'description' => 'A succinct, Sonner-style message that is displayed temporarily.',
            'components' => ['ui.toaster'],
            'examples' => [
                'default' => ['title' => 'Default', 'description' => 'This site has `<x-ui.toaster />` in its layout.'],
                'types' => 'Types',
            ],
            'anatomy' => <<<'BLADE'
{{-- once, in your layout (before @stack('scripts')) --}}
<x-ui.toaster :flash="session('toast')" />

{{-- anywhere --}}
<button x-data x-on:click="toast.success('Saved', { description: '...' })">Save</button>

{{-- from a controller --}}
return back()->with('toast', ['message' => 'Saved', 'type' => 'success']);
BLADE,
            'notes' => <<<'MD'
- `toast(message, options)`, `toast.success/error/warning/info(...)` are defined globally by the toaster's script; from Alpine you can also `$dispatch('toast', { message, ... })`.
- Options: `description`, `type`, `duration` (ms, `Infinity` to keep it), `action: { label, onClick }` (or `event` to dispatch a window event).
- Toasts are `role="status"` live regions; timers pause while hovered or focused. `max` limits how many are visible.
MD,
            'props' => [
                'ui.toaster' => [
                    'position' => '`top-left`, `top-center`, `top-right`, `bottom-left`, `bottom-center` or `bottom-right`.',
                    'duration' => 'Default auto-dismiss delay in milliseconds.',
                    'flash' => 'Toasts to show on load: a string, `[\'message\' => ..., \'type\' => ...]`, or a list of them. Pass `session(\'toast\')`.',
                    'max' => 'Maximum toasts visible at once.',
                    'class' => 'Extra classes for the container.',
                ],
            ],
        ],

        'toggle' => [
            'title' => 'Toggle',
            'description' => 'A two-state button that can be either on or off.',
            'components' => ['ui.toggle'],
            'examples' => [
                'default' => 'Default',
            ],
            'notes' => 'A `<button>` with `aria-pressed` and `data-state="on|off"`. `x-model` on the tag binds a boolean.',
            'props' => [
                'ui.toggle' => [
                    'variant' => '`default` or `outline`.',
                    'size' => '`default`, `sm` or `lg`.',
                    'pressed' => 'Initial state.',
                    'disabled' => 'Disable the toggle.',
                    'class' => 'Extra classes.',
                ],
            ],
        ],

        'toggle-group' => [
            'title' => 'Toggle Group',
            'description' => 'A set of two-state buttons that can be toggled on or off.',
            'components' => ['ui.toggle-group', 'ui.toggle-group-item'],
            'examples' => [
                'default' => ['title' => 'Multiple'],
                'single' => ['title' => 'Single, outline, x-model', 'description' => 'With `name`, the value is submitted through hidden inputs (`name[]` for `multiple`).'],
            ],
            'notes' => 'Items are `<button>`s with `aria-pressed`; arrow keys move focus between them. `x-model` on the group binds a string (`single`) or an array (`multiple`).',
            'props' => [
                'ui.toggle-group' => [
                    'type' => '`single` or `multiple`.',
                    'value' => 'Initially pressed value (array for `multiple`).',
                    'name' => 'Form field name.',
                    'variant' => '`default` or `outline`, applied to every item.',
                    'size' => '`default`, `sm` or `lg`, applied to every item.',
                    'orientation' => '`horizontal` or `vertical`.',
                    'class' => 'Extra classes for the group.',
                ],
                'ui.toggle-group-item' => [
                    'value' => 'Value of the item (required).',
                    'disabled' => 'Disable the item.',
                    'class' => 'Extra classes.',
                ],
            ],
        ],

        /* -------------------------------------------------------------- */

        'ai' => [
            'title' => 'AI Elements',
            'group' => 'Extras',
            'description' => 'Building blocks for chat UIs: conversation, messages and a prompt input.',
            'components' => ['ai.conversation', 'ai.conversation-content', 'ai.message', 'ai.message-content', 'ai.response', 'ai.prompt-input', 'ai.prompt-textarea', 'ai.prompt-toolbar', 'ai.prompt-tools'],
            'examples' => [
                'default' => ['title' => 'Chat', 'preview' => 'block'],
            ],
            'anatomy' => <<<'BLADE'
<x-ai.conversation>
    <x-ai.conversation-content>
        <x-ai.message from="user">
            <x-ai.message-content>...</x-ai.message-content>
        </x-ai.message>
        <x-ai.message from="assistant">
            <x-ai.message-content>
                <x-ai.response>...</x-ai.response>
            </x-ai.message-content>
        </x-ai.message>
    </x-ai.conversation-content>
</x-ai.conversation>

<x-ai.prompt-input action="...">
    <x-ai.prompt-textarea />
    <x-ai.prompt-toolbar>
        <x-ai.prompt-tools>...</x-ai.prompt-tools>
        <x-ui.button type="submit">...</x-ui.button>
    </x-ai.prompt-toolbar>
</x-ai.prompt-input>
BLADE,
            'props' => [
                'ai.conversation' => ['class' => 'Extra classes for the scrollable log.'],
                'ai.conversation-content' => ['class' => 'Extra classes.'],
                'ai.message' => ['from' => '`user` or `assistant`; styles the bubble and alignment.', 'class' => 'Extra classes.'],
                'ai.message-content' => ['class' => 'Extra classes for the bubble.'],
                'ai.response' => ['class' => 'Extra classes. Uses `prose` classes, which need `@tailwindcss/typography` to take effect.'],
                'ai.prompt-input' => ['action' => 'Form action.', 'method' => 'Form method. `@csrf` is included.', 'class' => 'Extra classes.'],
                'ai.prompt-textarea' => ['placeholder' => 'Placeholder.', 'name' => 'Textarea name.', 'value' => 'Initial value.', 'class' => 'Extra classes.'],
                'ai.prompt-toolbar' => ['class' => 'Extra classes.'],
                'ai.prompt-tools' => ['class' => 'Extra classes.'],
            ],
        ],

        'donut-chart' => [
            'title' => 'Donut Chart',
            'group' => 'Extras',
            'description' => 'An ApexCharts donut with a centred total.',
            'components' => ['charts.donut'],
            'examples' => [
                'default' => ['title' => 'Default'],
            ],
            'props' => [
                'charts.donut' => [
                    'id' => 'Id of the chart element; must be unique per page.',
                    'series' => 'Numeric values.',
                    'labels' => 'Label per value.',
                    'colors' => 'Colour per value.',
                    'center-value' => 'Large text in the centre.',
                    'center-label' => 'Small text under the centre value.',
                    'height' => 'Max height in px.',
                ],
            ],
            'notes' => 'Loads ApexCharts from jsDelivr through the `scripts` stack. Colours of the chart background are chosen once on load from the current theme.',
        ],

        'icons' => [
            'title' => 'Icons',
            'group' => 'Extras',
            'description' => 'Inline SVG icon components shipped with BladeCN.',
            'components' => [],
            'examples' => [
                'default' => ['title' => 'All icons', 'preview' => 'block', 'description' => 'Every file in `resources/views/components/icons`. Use as `<x-icons.name class="size-4" />`; attributes are forwarded to the `<svg>`.'],
            ],
        ],
    ],
];
