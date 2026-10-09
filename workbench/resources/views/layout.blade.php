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
@php
    $repository = rtrim(config('docs.repository'), '/');
    $navigation = $docs->navigation();
    $all = array_values($docs->pages());
    $index = array_search($page['slug'], array_column($all, 'slug'));
    $prev = $index > 0 ? $all[$index - 1] : null;
    $next = $all[$index + 1] ?? null;
    $editPath = $page['type'] === 'component'
        ? 'workbench/resources/docs/pages.php'
        : 'workbench/resources/views/pages/'.$page['slug'].'.blade.php';
@endphp
<body class="min-h-screen bg-background font-sans text-foreground antialiased" x-data="{ nav: false }"
    @keydown.escape.window="nav = false">
    <a href="#docs-content" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[60] focus:rounded-md focus:bg-background focus:px-3 focus:py-2 focus:ring-2 focus:ring-ring">Skip to content</a>

    {{-- Top bar --}}
    <header class="docs-header sticky top-0 z-40 border-b border-border/70 bg-background/80 backdrop-blur-xl supports-[backdrop-filter]:bg-background/65">
        <div class="mx-auto flex h-16 max-w-[90rem] items-center gap-3 px-4 md:px-6 lg:px-8">
            <button type="button" class="-ml-1 inline-flex size-9 items-center justify-center rounded-md text-muted-foreground hover:bg-accent hover:text-foreground lg:hidden"
                @click="nav = true" aria-label="Open navigation" :aria-expanded="nav">
                <x-icons.menu class="size-5" />
            </button>

            <a href="{{ $docs->url() }}" class="flex shrink-0 items-center gap-2.5 font-semibold tracking-tight" aria-label="BladeCN docs home">
                <x-icons.bladecn class="size-7" />
                <span class="text-[15px]">BladeCN</span>
                <span class="hidden rounded-full border px-2 py-0.5 text-[11px] font-medium text-muted-foreground sm:inline">Docs</span>
            </a>

            <div class="flex flex-1 justify-center px-2 md:px-6">
                <button type="button" data-docs-search-trigger @click="$dispatch('docs-search')"
                    class="group hidden h-9 w-full max-w-md items-center gap-2 rounded-full border bg-muted/40 px-3.5 text-sm text-muted-foreground shadow-xs transition-colors hover:border-foreground/20 hover:bg-muted/70 hover:text-foreground md:flex">
                    <x-icons.search class="size-4" />
                    <span>Search documentation…</span>
                    <x-ui.kbd-group class="ml-auto">
                        <x-ui.kbd class="bg-background">⌘</x-ui.kbd><x-ui.kbd class="bg-background">K</x-ui.kbd>
                    </x-ui.kbd-group>
                </button>
            </div>

            <nav class="hidden items-center gap-1 text-sm xl:flex" aria-label="Primary">
                <a href="{{ $docs->url() }}" @class(['rounded-md px-3 py-2 transition-colors hover:text-foreground', 'font-medium text-foreground' => $page['type'] === 'guide', 'text-muted-foreground' => $page['type'] !== 'guide'])>Guides</a>
                <a href="{{ $docs->url('components/button') }}" @class(['rounded-md px-3 py-2 transition-colors hover:text-foreground', 'font-medium text-foreground' => $page['type'] === 'component', 'text-muted-foreground' => $page['type'] !== 'component'])>Components</a>
            </nav>

            <div class="flex items-center gap-1">
                <button type="button" @click="$dispatch('docs-search')"
                    class="inline-flex size-9 items-center justify-center rounded-md text-muted-foreground hover:bg-accent hover:text-foreground md:hidden"
                    aria-label="Search documentation">
                    <x-icons.search class="size-[18px]" />
                </button>
                <a href="{{ $repository }}" target="_blank" rel="noopener noreferrer" data-github-link
                    class="inline-flex size-9 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
                    aria-label="BladeCN on GitHub (opens in a new tab)" title="View on GitHub">
                    <x-icons.github class="size-[18px]" />
                </a>
                <button type="button" data-theme-toggle
                    class="inline-flex size-9 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
                    aria-label="Toggle dark mode" title="Toggle dark mode">
                    <x-icons.sun class="size-[18px] dark:hidden" />
                    <x-icons.moon class="hidden size-[18px] dark:block" />
                </button>
            </div>
        </div>
    </header>

    @unless ($docs->assetsBuilt())
        <div class="border-b border-amber-300 bg-amber-50 px-4 py-2 text-center text-sm text-amber-900">
            Docs assets are not built yet. Run <code class="font-mono">npm install &amp;&amp; npm run docs:build</code> (or <code class="font-mono">npm run docs:dev</code>) in the package root.
        </div>
    @endunless

    {{-- Mobile drawer backdrop --}}
    <div x-show="nav" x-cloak x-transition.opacity class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm lg:hidden" @click="nav = false" aria-hidden="true"></div>

    <div class="mx-auto flex max-w-[90rem]">
        {{-- Sidebar (drawer on mobile) --}}
        <aside id="docs-sidebar" data-docs-sidebar
            :class="nav ? 'translate-x-0' : '-translate-x-full'"
            class="docs-sidebar fixed inset-y-0 left-0 z-50 w-[18rem] -translate-x-full overflow-y-auto overscroll-contain border-r bg-background px-4 pt-4 pb-10 transition-transform duration-200 ease-out lg:sticky lg:top-16 lg:z-auto lg:h-[calc(100vh-4rem)] lg:w-72 lg:shrink-0 lg:translate-x-0! lg:border-r-0 lg:bg-transparent lg:px-6 lg:pt-8">
            <div class="sticky -top-4 z-10 -mx-4 mb-4 flex items-center justify-between border-b bg-background px-4 pt-0 pb-3 lg:hidden">
                <a href="{{ $docs->url() }}" class="flex items-center gap-2 font-semibold"><x-icons.bladecn class="size-6" /> BladeCN</a>
                <button type="button" class="inline-flex size-8 items-center justify-center rounded-md text-muted-foreground hover:bg-accent hover:text-foreground"
                    @click="nav = false" aria-label="Close navigation">
                    <x-icons.x class="size-4" />
                </button>
            </div>

            <nav aria-label="Documentation" class="grid gap-6 text-sm">
                @foreach ($navigation as $group => $items)
                    @php $hasActive = in_array($page['slug'], array_column($items, 'slug'), true); @endphp
                    <div x-data="{ open: true }" data-nav-group>
                        <button type="button" @click="open = ! open" :aria-expanded="open"
                            class="group mb-1.5 flex w-full items-center justify-between rounded-md px-2.5 py-1 text-left text-xs font-semibold tracking-wide text-foreground uppercase">
                            <span class="flex items-center gap-2">
                                {{ $group }}
                                <span class="rounded-full bg-muted px-1.5 text-[10px] font-medium text-muted-foreground tabular-nums">{{ count($items) }}</span>
                            </span>
                            <x-icons.chevron-down class="size-3.5 text-muted-foreground transition-transform duration-200" ::class="open ? '' : '-rotate-90'" />
                        </button>
                        <ul x-show="open" class="grid gap-px border-l border-border/80 ml-2.5">
                            @foreach ($items as $item)
                                @php $active = $item['slug'] === $page['slug']; @endphp
                                <li>
                                    <a href="{{ $docs->pageUrl($item) }}" @class([
                                        '-ml-px flex items-center border-l py-1.5 pr-2 pl-3.5 transition-colors',
                                        'border-foreground font-medium text-foreground' => $active,
                                        'border-transparent text-muted-foreground hover:border-muted-foreground/50 hover:text-foreground' => ! $active,
                                    ]) @if ($active) aria-current="page" data-active @endif>
                                        {{ $item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </nav>
        </aside>

        {{-- Content --}}
        <main id="docs-content" class="min-w-0 flex-1 px-4 pt-8 pb-16 sm:px-6 lg:px-10 lg:pt-10">
            <div class="mx-auto flex max-w-[60rem] gap-12">
                <article class="docs-article min-w-0 flex-1 xl:max-w-[44rem]" data-docs-article>
                    @yield('content')

                    <div class="mt-16 flex flex-wrap items-center justify-between gap-3 border-t pt-6 text-sm text-muted-foreground">
                        <a href="{{ $repository }}/edit/main/{{ $editPath }}" target="_blank" rel="noopener noreferrer" data-edit-link
                            class="inline-flex items-center gap-2 transition-colors hover:text-foreground">
                            <x-icons.pencil class="size-3.5" /> Edit this page on GitHub
                        </a>
                        <a href="{{ $repository }}/issues/new" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 transition-colors hover:text-foreground">
                            <x-icons.github class="size-3.5" /> Report an issue
                        </a>
                    </div>

                    <nav class="mt-8 grid gap-4 sm:grid-cols-2" aria-label="Pagination">
                        @if ($prev)
                            <a href="{{ $docs->pageUrl($prev) }}" data-prev-link
                                class="group flex flex-col gap-1 rounded-xl border p-4 transition-colors hover:border-foreground/25 hover:bg-muted/40">
                                <span class="flex items-center gap-1 text-xs text-muted-foreground"><x-icons.chevron-left class="size-3.5 transition-transform group-hover:-translate-x-0.5" /> Previous</span>
                                <span class="font-medium">{{ $prev['title'] }}</span>
                            </a>
                        @else
                            <span class="hidden sm:block"></span>
                        @endif
                        @if ($next)
                            <a href="{{ $docs->pageUrl($next) }}" data-next-link
                                class="group flex flex-col items-end gap-1 rounded-xl border p-4 text-right transition-colors hover:border-foreground/25 hover:bg-muted/40">
                                <span class="flex items-center gap-1 text-xs text-muted-foreground">Next <x-icons.chevron-right class="size-3.5 transition-transform group-hover:translate-x-0.5" /></span>
                                <span class="font-medium">{{ $next['title'] }}</span>
                            </a>
                        @endif
                    </nav>

                    <footer class="mt-16 flex flex-col gap-4 border-t pt-8 text-sm text-muted-foreground sm:flex-row sm:items-center sm:justify-between">
                        <p class="flex items-center gap-2">
                            <x-icons.bladecn class="size-5" />
                            BladeCN · MIT licensed · Built with its own components.
                        </p>
                        <a href="{{ $repository }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 hover:text-foreground">
                            <x-icons.github class="size-4" /> TharinduWijayarathna/bladecn
                        </a>
                    </footer>
                </article>

                {{-- On this page --}}
                <aside class="sticky top-16 hidden h-[calc(100vh-4rem)] w-56 shrink-0 overflow-y-auto pt-0 pb-10 text-sm xl:block" aria-label="On this page">
                    <div data-toc @hasSection('toc') @else data-toc-auto @endif>
                        <p class="mb-3 flex items-center gap-2 text-xs font-semibold tracking-wide uppercase">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-3.5" aria-hidden="true"><path d="M3 12h12M3 6h18M3 18h8" stroke-linecap="round" /></svg>
                            On this page
                        </p>
                        <ul class="grid gap-px border-l border-border/80 text-[13px] text-muted-foreground" data-toc-list>
                            @yield('toc')
                        </ul>
                    </div>
                </aside>
            </div>
        </main>
    </div>

    {{-- ⌘K search over every docs page, built from the package's own Command Dialog. --}}
    <div x-data="{ search: false }" @docs-search.window="search = true"
        @keydown.window="if (($event.metaKey || $event.ctrlKey) && $event.key.toLowerCase() === 'k') { $event.preventDefault(); search = ! search } else if ($event.key === '/' && ! ['INPUT', 'TEXTAREA', 'SELECT'].includes($event.target.tagName) && ! $event.target.isContentEditable) { $event.preventDefault(); search = true }">
        <x-ui.command-dialog x-model="search" shortcut="" title="Search documentation" description="Search every BladeCN docs page">
            <x-ui.command-input placeholder="Search components and guides…" />
            <x-ui.command-list class="max-h-[min(420px,60vh)]">
                <x-ui.command-empty>No pages found.</x-ui.command-empty>
                @foreach ($navigation as $group => $items)
                    <x-ui.command-group :heading="$group">
                        @foreach ($items as $item)
                            <x-ui.command-item :href="$docs->pageUrl($item)" :value="$item['title']" :keywords="trim(($item['description'] ?? '').' '.implode(' ', $item['components'] ?? []))">
                                @if ($item['type'] === 'component')
                                    <x-icons.layout-grid />
                                @else
                                    <x-icons.notebook-pen />
                                @endif
                                <span class="truncate">{{ $item['title'] }}</span>
                                <span class="ml-auto hidden max-w-[50%] truncate text-xs text-muted-foreground sm:block">{{ $item['description'] ?? '' }}</span>
                            </x-ui.command-item>
                        @endforeach
                    </x-ui.command-group>
                @endforeach
            </x-ui.command-list>
        </x-ui.command-dialog>
    </div>

    <x-ui.toaster />

    @stack('scripts')
</body>
</html>
