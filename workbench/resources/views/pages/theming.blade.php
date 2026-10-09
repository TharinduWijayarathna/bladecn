@php
    $variables = <<<'CSS'
:root {
    --background: oklch(1 0 0);
    --foreground: oklch(0.145 0 0);
    --primary: oklch(0.205 0 0);
    --primary-foreground: oklch(0.985 0 0);
    --muted: oklch(0.97 0 0);
    --muted-foreground: oklch(0.556 0 0);
    --border: oklch(0.922 0 0);
    --ring: oklch(0.87 0 0);
    --radius: 0.625rem;
    /* card, popover, secondary, accent, destructive, input, chart-1..5, sidebar-* */
}

.dark {
    --background: oklch(0.145 0 0);
    --foreground: oklch(0.985 0 0);
    --primary: oklch(0.985 0 0);
    --primary-foreground: oklch(0.205 0 0);
    /* ... */
}
CSS;

    $variant = <<<'CSS'
@custom-variant dark (&:is(.dark *));
CSS;

    $brand = <<<'CSS'
:root {
    --primary: oklch(0.55 0.22 264);
    --primary-foreground: oklch(0.985 0 0);
    --ring: oklch(0.55 0.22 264);
    --radius: 0.75rem;
}
CSS;

    $head = <<<'BLADE'
<html @class(['dark' => ($appearance ?? 'system') == 'dark'])>
<head>
    <script>
        (function () {
            const appearance = localStorage.getItem('appearance') || 'system';
            const dark = appearance === 'dark'
                || (appearance === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', dark);
        })();
    </script>
BLADE;

    $tabs = <<<'BLADE'
<x-ui.appearance-tabs />
BLADE;
@endphp

<div class="docs-prose">
    <h3>CSS variables</h3>
    <p>
        Colours, radius and sidebar tokens are CSS variables declared in <code>resources/css/app.css</code> and mapped to
        Tailwind colours with <code>@@theme</code> (<code>bg-primary</code>, <code>text-muted-foreground</code>,
        <code>border-border</code>, …). Values use <code>oklch()</code>.
    </p>
</div>
@include('docs::partials.code', ['code' => $variables, 'language' => 'css'])

<div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-4">
    @foreach (['background', 'foreground', 'primary', 'secondary', 'muted', 'accent', 'destructive', 'border'] as $token)
        <div class="overflow-hidden rounded-lg border">
            <div class="h-14" style="background: var(--{{ $token }})"></div>
            <div class="px-3 py-2 font-mono text-xs">--{{ $token }}</div>
        </div>
    @endforeach
</div>

<div class="docs-prose mt-10">
    <h3>Customising</h3>
    <p>Override any variable after the defaults in your <code>app.css</code>, e.g. a blue primary and larger radius:</p>
</div>
@include('docs::partials.code', ['code' => $brand, 'language' => 'css'])

<div class="docs-prose mt-10">
    <h3>Dark mode</h3>
    <p>
        Dark mode is class-based. <code>app.css</code> declares a custom <code>dark</code> variant that applies inside an
        element with the <code>.dark</code> class, and redefines the variables under <code>.dark</code>:
    </p>
</div>
@include('docs::partials.code', ['code' => $variant, 'language' => 'css'])

<div class="docs-prose mt-6">
    <p>
        The user's choice is stored as <code>appearance</code> (<code>light</code>, <code>dark</code> or
        <code>system</code>) in <code>localStorage</code> and in an <code>appearance</code> cookie. The bundled
        <code>&lt;x-layout.app&gt;</code> renders the class server-side from <code>$appearance</code> and runs a small
        inline script before paint to avoid a flash:
    </p>
</div>
@include('docs::partials.code', ['code' => $head])

<div class="docs-prose mt-10">
    <h3>Appearance switcher</h3>
    <p>
        <code>&lt;x-ui.appearance-tabs&gt;</code> writes the same keys, so it works with the layout above. Try it: this
        docs site follows the same convention, as does the moon / sun button in the header.
    </p>
</div>
@include('docs::partials.code', ['code' => $tabs])
<div class="mt-4 flex justify-center rounded-lg border p-6">
    <x-ui.appearance-tabs value="" />
</div>
<p class="mt-3 text-sm text-muted-foreground">
    See <a class="underline underline-offset-4" href="{{ $docs->url('components/appearance-tabs') }}">Appearance Tabs</a> for its props.
</p>
