<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $page['slug'] === 'introduction' ? 'BladeCN — shadcn for Laravel Blade' : $page['title'].' — BladeCN' }}</title>
    <meta name="description" content="{{ $page['description'] ?? '' }}">

    {{-- Same dark-mode convention as the package: `appearance` in localStorage (light | dark | system) and a `.dark` class on <html>. --}}
    <script>
        (function () {
            var appearance = 'system';
            try { appearance = localStorage.getItem('appearance') || 'system'; } catch (e) {}
            var dark = appearance === 'dark' || (appearance === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', dark);
        })();
    </script>

    <link rel="icon" href="{{ $docs->asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ $docs->asset('favicon-32.png') }}" type="image/png" sizes="32x32">
    <link rel="stylesheet" href="{{ $docs->asset('docs.css') }}">
    <script defer src="{{ $docs->asset('docs.js') }}"></script>
    @stack('styles')
</head>
<body class="min-h-screen bg-background font-sans text-foreground antialiased" x-data="{ nav: false }">
    <header class="sticky top-0 z-40 border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/70">
        <div class="mx-auto flex h-14 max-w-screen-2xl items-center gap-3 px-4 md:px-6">
            <button type="button" class="inline-flex size-9 items-center justify-center rounded-md hover:bg-accent md:hidden"
                @click="nav = !nav" aria-label="Toggle navigation">
                <x-icons.panel-left class="size-5" />
            </button>
            <a href="{{ $docs->url() }}" class="flex items-center gap-2 font-semibold">
                <x-icons.bladecn class="size-7" />
                BladeCN
            </a>
            <nav class="ml-4 hidden items-center gap-5 text-sm text-muted-foreground md:flex">
                <a href="{{ $docs->url() }}" class="transition-colors hover:text-foreground">Docs</a>
                <a href="{{ $docs->url('components/button') }}" class="transition-colors hover:text-foreground">Components</a>
                <a href="{{ $docs->url('theming') }}" class="transition-colors hover:text-foreground">Theming</a>
            </nav>
            <div class="ml-auto flex items-center gap-1">
                <a href="{{ config('docs.repository') }}" target="_blank" rel="noreferrer"
                    class="inline-flex h-9 items-center rounded-md px-3 text-sm font-medium hover:bg-accent hover:text-accent-foreground">
                    GitHub
                </a>
                <button type="button" data-theme-toggle
                    class="inline-flex size-9 items-center justify-center rounded-md hover:bg-accent hover:text-accent-foreground"
                    aria-label="Toggle dark mode" title="Toggle dark mode">
                    <x-icons.sun class="size-4 dark:hidden" />
                    <x-icons.moon class="hidden size-4 dark:block" />
                </button>
            </div>
        </div>
    </header>

    @unless ($docs->assetsBuilt())
        <div class="border-b border-amber-300 bg-amber-50 px-4 py-2 text-center text-sm text-amber-900">
            Docs assets are not built yet. Run <code class="font-mono">npm install &amp;&amp; npm run docs:build</code> (or <code class="font-mono">npm run docs:dev</code>) in the package root.
        </div>
    @endunless

    <div class="mx-auto flex max-w-screen-2xl">
        {{-- Sidebar --}}
        <aside :class="nav ? 'block' : 'hidden'"
            class="fixed inset-x-0 top-14 bottom-0 z-30 hidden overflow-y-auto border-r bg-background px-4 py-6 md:sticky md:block md:h-[calc(100vh-3.5rem)] md:w-64 md:shrink-0">
            @foreach ($docs->navigation() as $group => $items)
                <div class="mb-6">
                    <h4 class="mb-2 px-2 text-sm font-semibold">{{ $group }}</h4>
                    <ul class="grid gap-0.5 text-sm">
                        @foreach ($items as $item)
                            @php $active = $item['slug'] === $page['slug']; @endphp
                            <li>
                                <a href="{{ $docs->pageUrl($item) }}" @class([
                                    'flex items-center rounded-md px-2 py-1.5 transition-colors',
                                    'bg-accent font-medium text-accent-foreground' => $active,
                                    'text-muted-foreground hover:bg-accent/60 hover:text-foreground' => ! $active,
                                ]) @if ($active) aria-current="page" @endif>
                                    {{ $item['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </aside>

        <main class="min-w-0 flex-1 px-4 py-8 md:px-10 lg:py-10">
            <div class="mx-auto flex max-w-5xl gap-10">
                <article class="min-w-0 flex-1">
                    @yield('content')

                    @php
                        $all = array_values($docs->pages());
                        $index = array_search($page['slug'], array_column($all, 'slug'));
                        $prev = $index > 0 ? $all[$index - 1] : null;
                        $next = $all[$index + 1] ?? null;
                    @endphp
                    <div class="mt-16 flex items-center justify-between border-t pt-6 text-sm">
                        @if ($prev)
                            <x-ui.button tag="a" variant="outline" href="{{ $docs->pageUrl($prev) }}">
                                <x-icons.chevron-left /> {{ $prev['title'] }}
                            </x-ui.button>
                        @else
                            <span></span>
                        @endif
                        @if ($next)
                            <x-ui.button tag="a" variant="outline" href="{{ $docs->pageUrl($next) }}">
                                {{ $next['title'] }} <x-icons.chevron-right />
                            </x-ui.button>
                        @endif
                    </div>
                </article>

                @hasSection('toc')
                    <aside class="sticky top-24 hidden h-fit w-48 shrink-0 text-sm xl:block">
                        <p class="mb-2 font-medium">On this page</p>
                        <ul class="grid gap-1.5 text-muted-foreground">
                            @yield('toc')
                        </ul>
                    </aside>
                @endif
            </div>
        </main>
    </div>

    <x-ui.toaster />

    @stack('scripts')
</body>
</html>
