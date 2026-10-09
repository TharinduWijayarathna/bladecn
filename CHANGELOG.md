# Changelog

All notable changes to `bladecn/bladecn` will be documented in this file.

## Unreleased

### Added

- New shadcn/ui components, each with a docs page: Accordion, Alert, Alert Dialog, Aspect Ratio, Button Group, Calendar, Collapsible, Combobox, Command (+ Command Dialog), Context Menu, Date Picker, Field, Hover Card, Kbd, Pagination (renders Laravel paginators), Popover, Scroll Area, Skeleton, Slider, Switch, Tabs, Toast (`<x-ui.toaster>` + `toast()`), Toggle and Toggle Group.
- Icons: `bladecn` (logo mark), `bold`, `calculator`, `calendar`, `chevron-up`, `circle-alert`, `copy`, `credit-card`, `info`, `italic`, `minus`, `plus`, `smile`, `trash`, `triangle-alert`, `underline`, `user`, `volume`.
- `input-group-addon` accepts `align="inline-start|inline-end"`.
- `[x-cloak]` rule in the published `app.css`.
- BladeCN logo, favicon and a README / social-preview hero generated from real components (`npm run docs:hero`).
- Icons: `github` (GitHub mark) and `menu`.
- Docs site redesign: translucent sticky header with ⌘K / `/` search across every page (built on Command Dialog), GitHub and theme buttons; collapsible sidebar groups with a mobile drawer; "On this page" table of contents with scroll-spy and heading anchors; breadcrumb eyebrows, code blocks with language label + copy, preview/code tabs, previous/next cards, "Edit this page on GitHub" links and a landing hero with a live component showcase.

### Changed

- **Components resolve without `bladecn:install`.** Class components are registered as kebab-case `<x-ui.*>` / `<x-layout.*>` tags (previously `ui.Button`, which `<x-ui.button>` never matched) and anonymous components, icons, `ai` and `charts` resolve from the package. A class or view published into the app still takes precedence.
- **`bladecn:install` publishes every component** (all `ui`, `layout`, `icons`, `settings`, `ai`, `charts` views and every `Ui`/`Layout` class) and **honours `--force`**: existing files are kept and listed unless `--force` is passed; `routes/web.php`, `resources/css/app.css` and `resources/js/app.js` are replaced after a confirmation (default yes).
- Tooltip is positioned (`position: fixed`) on `side` with `side-offset`, flips when out of room, opens on focus as well as hover and links `aria-describedby`.
- Sheet uses theme tokens (works in dark mode), has `role="dialog"`, a built-in close button, focus handling, and supports several sheets with different sides on one page. `sheet-close` is now an outline button and sheet sub-components forward attributes.
- Dropdown supports <kbd>Esc</kbd>, arrow-key navigation and `aria-haspopup` / `aria-expanded`.
- Checkbox uses theme tokens instead of fixed grays.

### Fixed

- `payment-card` / `subscription-card` passed `as="a"` to the button (now `tag="a"`), so their links rendered as `<button>`.
- `editable-input` passed `align` to `input-group-addon`, which ignored it; the route is now JSON-encoded in its script.
- `item-footer` had `data-slot="item-header"`.
- `x-icons.chevron-down` carried absolute-positioning classes meant for `native-select`.
- Destructive `badge` text was invisible in light mode.
- `textarea` added whitespace around its value; `breadcrumb` errored on items without `href`; `radio-group` pushed its script once per instance and built inline JS from unescaped values; dialog root duplicated `role="dialog"`.
- Registration used `App\Models\User` directly; it now uses the configured `auth.providers.users.model`.
- Package fallback `/dashboard` route referenced an app view that only exists after installing.
- PHPStan errors in `src/Http/Controllers`, `src/routes` and `src/helpers.php`.
- CI `prefer-lowest` jobs installed `laravel/framework` dev branches because Composer blocks releases with security advisories; dev installs now allow them so the lowest supported releases are tested.

## v0.8 - 2025-12-20

**Full Changelog**: https://github.com/TharinduWijayarathna/bladecn/compare/v0.7...v0.8

## v0.7 - 2025-12-18

**Full Changelog**: https://github.com/TharinduWijayarathna/bladecn/compare/v0.6...v0.7

## v0.6 - 2025-12-18

### What's Changed

* build(deps): bump actions/checkout from 5 to 6 by @dependabot[bot] in https://github.com/TharinduWijayarathna/bladecn/pull/4

**Full Changelog**: https://github.com/TharinduWijayarathna/bladecn/compare/v0.5...v0.6

## v0.5 - 2025-11-10

- changed the package name
- fixed some component rendering issues

## v0.4 - 2025-11-10

- updated default logo
- updated auth pages background image

## v0.3 - 2025-11-09

- fixed some route and component issues

## v0.2beta - 2025-11-09

- fixed some bugs
- updated some components
- added new components
