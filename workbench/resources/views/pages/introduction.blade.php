@php
    $usage = <<<'BLADE'
<x-ui.card>
    <x-ui.card-header>
        <x-ui.card-title>Welcome back</x-ui.card-title>
        <x-ui.card-description>Sign in to continue.</x-ui.card-description>
    </x-ui.card-header>
    <x-ui.card-content class="grid gap-2">
        <x-ui.label for="email">Email</x-ui.label>
        <x-ui.input id="email" type="email" name="email" />
    </x-ui.card-content>
    <x-ui.card-footer>
        <x-ui.button class="w-full">Sign in</x-ui.button>
    </x-ui.card-footer>
</x-ui.card>
BLADE;
@endphp

<h2 class="mb-3 text-2xl font-semibold tracking-tight">What is BladeCN?</h2>
<div class="docs-prose">
    <p>
        BladeCN brings the look and API of <a href="https://ui.shadcn.com" target="_blank" rel="noreferrer">shadcn/ui</a>
        to server-rendered Laravel Blade. No React, no Livewire: just Blade components, Tailwind CSS 4 utility classes
        and a sprinkle of Alpine.js for interactive pieces like dialogs and dropdown menus.
    </p>
    <p>
        Like shadcn/ui, it is a starting point rather than a black box. <code>php artisan bladecn:install</code> copies the
        component views and classes into your app, so you own the code and can change any of it.
    </p>
</div>

<div class="mt-8 grid gap-4 sm:grid-cols-3">
    <x-ui.card class="shadow-none">
        <x-ui.card-header>
            <x-ui.card-title>Blade-native</x-ui.card-title>
            <x-ui.card-description>Anonymous and class-based components under the <code>x-ui</code> prefix.</x-ui.card-description>
        </x-ui.card-header>
    </x-ui.card>
    <x-ui.card class="shadow-none">
        <x-ui.card-header>
            <x-ui.card-title>Themeable</x-ui.card-title>
            <x-ui.card-description>CSS variables for every colour, with a built-in dark mode.</x-ui.card-description>
        </x-ui.card-header>
    </x-ui.card>
    <x-ui.card class="shadow-none">
        <x-ui.card-header>
            <x-ui.card-title>Starter kit</x-ui.card-title>
            <x-ui.card-description>Auth, dashboard, profile and settings pages included.</x-ui.card-description>
        </x-ui.card-header>
    </x-ui.card>
</div>

<div class="docs-prose mt-10">
    <h3>What's included</h3>
    <ul>
        <li><strong>Components</strong>: buttons, forms, cards, dialogs, dropdown menus, sheets, tables, avatars, empty states, items and more, with every page in the sidebar.</li>
        <li><strong>Authentication</strong>: login, registration and password reset views, controllers and routes.</li>
        <li><strong>Dashboard, profile &amp; settings</strong>: ready-made pages built on the <a href="{{ $docs->url('layouts') }}">layouts</a>.</li>
        <li><strong>Dark mode</strong> across all components (see <a href="{{ $docs->url('theming') }}">Theming</a>).</li>
        <li><strong>Responsive</strong>, mobile-first markup, with PHP component classes and type hints for the core set.</li>
    </ul>
</div>

<h2 class="mt-12 mb-3 text-2xl font-semibold tracking-tight">At a glance</h2>
<div class="grid gap-4 lg:grid-cols-2">
    <div class="flex items-center justify-center rounded-lg border p-6">
        <x-ui.card class="w-full max-w-sm">
            <x-ui.card-header>
                <x-ui.card-title>Welcome back</x-ui.card-title>
                <x-ui.card-description>Sign in to continue.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content class="grid gap-2">
                <x-ui.label for="intro-email">Email</x-ui.label>
                <x-ui.input id="intro-email" type="email" name="email" />
            </x-ui.card-content>
            <x-ui.card-footer>
                <x-ui.button class="w-full">Sign in</x-ui.button>
            </x-ui.card-footer>
        </x-ui.card>
    </div>
    @include('docs::partials.code', ['code' => $usage])
</div>

<div class="docs-prose mt-12">
    <h3>About these docs</h3>
    <p>
        Every preview on this site renders the package's real component files from <code>resources/views/components</code>
        and <code>src/View/Components</code>. The code next to each preview is the exact Blade file that produced it, and
        the props tables are read from each component's constructor or <code>@@props</code> directive, so the docs can't
        drift from the source.
    </p>
    <p>
        Start with <a href="{{ $docs->url('installation') }}">Installation</a>, or jump to a component in the sidebar.
    </p>
</div>
