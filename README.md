<p align="center">
  <a href="https://tharinduwijayarathna.github.io/bladecn/">
    <picture>
      <source media="(prefers-color-scheme: dark)" srcset=".github/images/hero-dark.png">
      <img src=".github/images/hero.png" alt="BladeCN: shadcn/ui components for Laravel Blade" width="100%">
    </picture>
  </a>
</p>

<p align="center"><strong>shadcn/ui for Laravel Blade.</strong> Accessible, themeable components on Tailwind CSS 4 and Alpine.js. No React, no Livewire required.</p>

<p align="center">
  <a href="https://packagist.org/packages/bladecn/bladecn"><img src="https://img.shields.io/packagist/v/bladecn/bladecn.svg?style=flat-square" alt="Latest Version"></a>
  <a href="https://packagist.org/packages/bladecn/bladecn"><img src="https://img.shields.io/packagist/dt/bladecn/bladecn.svg?style=flat-square" alt="Total Downloads"></a>
  <a href="https://github.com/TharinduWijayarathna/bladecn/actions/workflows/run-tests.yml"><img src="https://img.shields.io/github/actions/workflow/status/TharinduWijayarathna/bladecn/run-tests.yml?branch=main&label=tests&style=flat-square" alt="Tests"></a>
  <a href="LICENSE.md"><img src="https://img.shields.io/packagist/l/bladecn/bladecn.svg?style=flat-square" alt="License"></a>
</p>

<h3 align="center"><a href="https://tharinduwijayarathna.github.io/bladecn/">📚 Documentation →</a></h3>

## Install

```bash
composer require bladecn/bladecn
php artisan bladecn:install        # optional: publish the starter kit and components into your app
npm install tailwindcss @tailwindcss/vite tailwindcss-animate alpinejs @alpinejs/focus && npm run build
```

Components work straight from the package; the installer copies them (plus auth screens, layouts and assets) into your app so you own the code. See [Installation](https://tharinduwijayarathna.github.io/bladecn/installation) for Vite and layout setup.

## Usage

```blade
<x-ui.button variant="outline">Click me</x-ui.button>
```

Browse the [Components](https://tharinduwijayarathna.github.io/bladecn/components/accordion), [Theming & Dark mode](https://tharinduwijayarathna.github.io/bladecn/theming) and [Layouts](https://tharinduwijayarathna.github.io/bladecn/layouts) docs.

## Requirements

- PHP 8.3+
- Laravel 10, 11 or 12
- Tailwind CSS 4, Alpine.js 3 with `@alpinejs/focus`

## Contributing

Issues and pull requests are welcome. To work on the package and its docs site:

```bash
composer install && npm install
npm run docs:build && vendor/bin/testbench serve   # http://127.0.0.1:8000/docs
vendor/bin/pest && vendor/bin/phpstan analyse && vendor/bin/pint
```

Every component needs a docs page; [`workbench/README.md`](workbench/README.md) explains how the docs are built.

## License

MIT. See [LICENSE.md](LICENSE.md). Inspired by [shadcn/ui](https://ui.shadcn.com).
