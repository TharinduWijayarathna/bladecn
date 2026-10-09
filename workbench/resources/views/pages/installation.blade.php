@php
    $composer = <<<'BASH'
composer require bladecn/bladecn
php artisan bladecn:install
BASH;

    $npm = <<<'BASH'
npm install tailwindcss @tailwindcss/vite tailwindcss-animate alpinejs @alpinejs/focus
BASH;

    $vite = <<<'JS'
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
JS;

    $js = <<<'JS'
import focus from '@alpinejs/focus';
import Alpine from 'alpinejs';

Alpine.plugin(focus);

window.Alpine = Alpine;

Alpine.start();
JS;

    $layout = <<<'BLADE'
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    {{ $slot }}

    @stack('scripts')
</body>
BLADE;

    $build = <<<'BASH'
npm run build   # or: npm run dev
composer dump-autoload
BASH;

    $config = <<<'BASH'
php artisan vendor:publish --tag="bladecn-config"
BASH;

    $usage = <<<'BLADE'
<x-ui.button variant="outline">Click me</x-ui.button>
BLADE;

    $docs = <<<'BASH'
# from a clone of the package
composer install
npm install
npm run docs:build          # or `npm run docs:dev` to rebuild on change
vendor/bin/testbench serve  # http://127.0.0.1:8000/docs
BASH;
@endphp

<div class="docs-prose">
    <p>BladeCN requires PHP 8.3+, Laravel 10, 11 or 12, and Tailwind CSS 4.</p>

    <h3>1. Install the package</h3>
    <p>
        Every component works straight from the package as soon as it is installed: <code>&lt;x-ui.button&gt;</code>,
        <code>&lt;x-layout.app&gt;</code>, <code>&lt;x-icons.check&gt;</code> and the rest resolve without publishing anything.
    </p>
    <p>
        <code>php artisan bladecn:install</code> turns it into a starter kit you own: it publishes the auth views and
        controllers, routes, <strong>all</strong> layout and UI components (views and classes), the
        <code>cn()</code> / <code>getInitials()</code> helpers, and the CSS / JS entry points. Published copies take
        precedence over the package's, so edit them freely.
    </p>
    <ul>
        <li>Files that already exist are <strong>kept</strong>; the summary lists them. Run it again with <code>--force</code> to overwrite them (e.g. to pull in upstream changes after <code>composer update</code>).</li>
        <li>It asks before replacing <code>routes/web.php</code>, <code>resources/css/app.css</code> and <code>resources/js/app.js</code>, which every new Laravel app ships with (the default answer is yes, also with <code>--no-interaction</code>). Commit your work first.</li>
    </ul>
</div>
@include('docs::partials.code', ['code' => $composer, 'language' => 'bash'])

<div class="docs-prose mt-8">
    <h3>2. Install the front-end dependencies</h3>
</div>
@include('docs::partials.code', ['code' => $npm, 'language' => 'bash'])

<div class="docs-prose mt-8">
    <h3>3. Configure Vite</h3>
    <p>Add the Tailwind Vite plugin if your <code>vite.config.js</code> doesn't have it yet:</p>
</div>
@include('docs::partials.code', ['code' => $vite, 'language' => 'javascript'])

<div class="docs-prose mt-8">
    <p>The published <code>resources/js/app.js</code> starts Alpine with the focus plugin (used by dialogs to trap focus):</p>
</div>
@include('docs::partials.code', ['code' => $js, 'language' => 'javascript'])

<div class="docs-prose mt-8">
    <h3>4. Make sure your layout has the stacks</h3>
    <p>
        Several components push their scripts and styles to the <code>scripts</code> and <code>styles</code> stacks, and
        method links read the CSRF token from a meta tag. The bundled <code>&lt;x-layout.app&gt;</code> and
        <code>&lt;x-layout.auth&gt;</code> already do this; for your own layouts:
    </p>
</div>
@include('docs::partials.code', ['code' => $layout])

<div class="docs-prose mt-8">
    <h3>5. Build</h3>
</div>
@include('docs::partials.code', ['code' => $build, 'language' => 'bash'])

<div class="docs-prose mt-8">
    <h3>Configuration (optional)</h3>
    <p>Publish the config file:</p>
</div>
@include('docs::partials.code', ['code' => $config, 'language' => 'bash'])

<div class="docs-prose mt-8">
    <h3>Usage</h3>
    <p>All UI components live under the <code>x-ui</code> prefix:</p>
</div>
@include('docs::partials.code', ['code' => $usage])
<div class="mt-4 flex justify-center rounded-lg border p-6">
    <x-ui.button variant="outline">Click me</x-ui.button>
</div>

<div class="docs-prose mt-12">
    <h3>Running these docs locally</h3>
    <p>
        This site lives in the package's <code>workbench/</code> directory and is served by
        <a href="https://packages.tools/testbench" target="_blank" rel="noreferrer">Orchestra Testbench</a>.
        It is development-only: <code>workbench/</code> is export-ignored and autoload-dev, so nothing here is installed
        into, or routed in, your application.
    </p>
</div>
@include('docs::partials.code', ['code' => $docs, 'language' => 'bash'])
