@php
    $app = <<<'BLADE'
<x-layout.app title="Dashboard" :breadcrumbs="[['label' => 'Dashboard']]">
    <div class="space-y-6">
        <h1 class="text-2xl font-semibold">Dashboard</h1>

        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Welcome</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                Your content here
            </x-ui.card-content>
        </x-ui.card>
    </div>
</x-layout.app>
BLADE;

    $auth = <<<'BLADE'
<x-layout.auth title="Welcome back" description="Sign in to your account">
    <form method="POST" action="{{ route('login') }}" class="grid gap-4">
        @csrf
        <x-ui.label for="email">Email</x-ui.label>
        <x-ui.input id="email" type="email" name="email" required />

        <x-ui.label for="password">Password</x-ui.label>
        <x-ui.input id="password" type="password" name="password" required />

        <x-ui.button type="submit" class="w-full">Sign in</x-ui.button>
    </form>
</x-layout.auth>
BLADE;

    $settings = <<<'BLADE'
<x-layout.app title="Settings">
    <x-layout.settings>
        {{-- settings form --}}
    </x-layout.settings>
</x-layout.app>
BLADE;

    $propTables = $docs->props($page);
@endphp

<div class="docs-prose">
    <p>
        Layouts are full HTML documents (they render <code>&lt;html&gt;</code>, <code>&lt;head&gt;</code> and the
        <code>@@vite</code> assets), so they are shown here as code rather than live previews. Run
        <code>php artisan bladecn:install</code> and visit <code>/dashboard</code>, <code>/login</code> or
        <code>/settings/profile</code> to see them in your app.
    </p>

    <h3>App layout</h3>
    <p>Sidebar, header with breadcrumbs and the dark-mode bootstrap script. Used by the dashboard and profile pages.</p>
</div>
@include('docs::partials.code', ['code' => $app])

<div class="docs-prose mt-8">
    <h3>Auth layout</h3>
    <p>Centered card layout for login, registration and password reset.</p>
</div>
@include('docs::partials.code', ['code' => $auth])

<div class="docs-prose mt-8">
    <h3>Settings layout</h3>
    <p>Settings navigation (Profile, Password, Appearance) next to your content, meant to be nested in the app layout.</p>
</div>
@include('docs::partials.code', ['code' => $settings])

<h2 class="mt-12 mb-3 text-xl font-semibold tracking-tight">Props</h2>
@foreach ($propTables as $table)
    @include('docs::partials.props', ['table' => $table])
@endforeach
