# BladeCN docs

The component documentation site for BladeCN. It is a [Testbench workbench](https://packages.tools/workbench)
app that boots a throwaway Laravel application with the package and renders the **real** components from
`resources/views/components` and `src/View/Components`.

Nothing in `workbench/` ships with the package: the directory is `export-ignore`d in `.gitattributes`, its
namespace is in `autoload-dev`, and the docs routes are registered only by
`Workbench\App\Providers\DocsServiceProvider`, which only `testbench.yaml.dist` and `tests/DocsTest.php` load.

## Run locally

```bash
composer install
npm install
npm run docs:build            # or `npm run docs:dev` to rebuild CSS/JS on change
vendor/bin/testbench serve    # or `composer docs:serve`
```

Then open <http://127.0.0.1:8000/docs>. Blade, the registry and examples are read on every request, so only CSS/JS
need rebuilding.

## Static export

```bash
npm run docs:build
vendor/bin/testbench docs:export --base=/bladecn   # writes build/docs-site
```

`--base` is the public path the site is served from (`/` for a root domain). `.github/workflows/docs.yml` runs
this on every push to `main` and deploys `build/docs-site` to GitHub Pages (Settings → Pages → Source:
**GitHub Actions**).

## How it fits together

| Path | What it is |
| --- | --- |
| `resources/docs/pages.php` | Registry: nav, page titles, examples, composition notes, prop descriptions |
| `resources/views/examples/{page}/{example}.blade.php` | One file per example. Rendered for **Preview**, printed verbatim for **Code**, so they can't drift |
| `app/Docs/PropsExtractor.php` | Builds props tables from the component constructor (name, type, default) and/or the `@props([...])` array |
| `app/Docs/Docs.php` | Navigation, URLs, examples, props, and which components `bladecn:install` publishes (read from the installer) |
| `app/Providers/DocsServiceProvider.php` | Registers every package component as `<x-ui.*>` / `<x-icons.*>` / `<x-ai.*>` / `<x-charts.*>`, the docs views and routes |
| `app/Console/ExportDocsCommand.php` | `docs:export` static site generator |
| `resources/css/docs.css`, `resources/js/docs.js` | Import the package's own `app.css` / `app.js`, plus code highlighting |

## Adding or changing a component page

1. Add or edit the entry in `resources/docs/pages.php` (`components`, `examples`, `props` descriptions).
2. Add an example file per example key under `resources/views/examples/{slug}/`.
3. Run `vendor/bin/pest tests/DocsTest.php`. It fails if a page doesn't render, a `ui` component file isn't
   documented, an example file is missing or orphaned, or a prop description names a prop the component doesn't have.
