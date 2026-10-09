<header class="mb-2">
    <nav aria-label="Breadcrumb" class="mb-3 flex items-center gap-1.5 text-sm">
        <a href="{{ $docs->url() }}" class="text-muted-foreground transition-colors hover:text-foreground">Docs</a>
        <x-icons.chevron-right class="size-3.5 text-muted-foreground/60" />
        <span class="font-medium text-primary" data-eyebrow>{{ $page['group'] }}</span>
    </nav>
    <h1 class="scroll-m-20 text-3xl font-bold tracking-tight text-balance sm:text-4xl">{{ $page['title'] }}</h1>
    @if (! empty($page['description']))
        <p class="mt-3 text-lg leading-relaxed text-pretty text-muted-foreground">{{ $page['description'] }}</p>
    @endif
</header>
