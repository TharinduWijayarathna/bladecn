{{-- Landing hero for the introduction page. --}}
<section class="docs-hero relative -mx-4 overflow-hidden rounded-none border-y px-6 py-12 sm:mx-0 sm:rounded-2xl sm:border sm:px-10 sm:py-14" data-docs-hero>
    <div class="docs-hero-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <div class="docs-hero-glow pointer-events-none absolute -top-24 left-1/2 h-64 w-[36rem] -translate-x-1/2 rounded-full blur-3xl" aria-hidden="true"></div>

    <div class="relative">
        <div class="flex items-center gap-3">
            <span class="inline-flex size-12 items-center justify-center rounded-xl border bg-background shadow-sm">
                <x-icons.bladecn class="size-7" />
            </span>
            <span class="inline-flex items-center gap-1.5 rounded-full border bg-background/70 px-3 py-1 text-xs font-medium text-muted-foreground backdrop-blur">
                <span class="size-1.5 rounded-full bg-emerald-500"></span>
                Laravel · Tailwind CSS 4 · Alpine.js
            </span>
        </div>

        <h1 class="mt-6 max-w-2xl text-4xl font-bold tracking-tight text-balance sm:text-5xl">
            Beautiful <span class="docs-hero-accent">shadcn/ui</span> components for Laravel Blade.
        </h1>
        <p class="mt-4 max-w-xl text-lg leading-relaxed text-pretty text-muted-foreground">
            Copy-and-own Blade components with the look and API of shadcn/ui. Server-rendered, themeable,
            dark-mode ready and sprinkled with just enough Alpine.js.
        </p>

        <div class="mt-8 flex flex-wrap items-center gap-3">
            <x-ui.button tag="a" href="{{ $docs->url('installation') }}" size="lg" class="rounded-full px-6">
                Get started <x-icons.arrow-right />
            </x-ui.button>
            <x-ui.button tag="a" variant="outline" size="lg" href="{{ config('docs.repository') }}" target="_blank" rel="noopener noreferrer" class="rounded-full bg-background/70 px-6">
                <x-icons.github /> GitHub
            </x-ui.button>
            <x-ui.button tag="a" variant="ghost" size="lg" href="{{ $docs->url('components/button') }}" class="rounded-full">
                Browse components
            </x-ui.button>
        </div>

        <div class="mt-8 inline-flex max-w-full items-center gap-3 rounded-lg border bg-background/80 py-2 pr-2 pl-4 font-mono text-sm shadow-xs backdrop-blur"
            x-data="{ copied: false }">
            <span class="text-muted-foreground select-none">$</span>
            <code x-ref="cmd" class="truncate">composer require bladecn/bladecn</code>
            <button type="button" class="inline-flex size-7 shrink-0 items-center justify-center rounded-md text-muted-foreground hover:bg-accent hover:text-foreground"
                @click="navigator.clipboard.writeText($refs.cmd.textContent).then(() => { copied = true; setTimeout(() => copied = false, 1500) })"
                :aria-label="copied ? 'Copied' : 'Copy install command'">
                <x-icons.copy class="size-3.5" x-show="!copied" />
                <x-icons.check class="size-3.5 text-emerald-500" x-show="copied" x-cloak />
            </button>
        </div>
    </div>
</section>

{{-- Live component showcase: these are the package's real components. --}}
<section class="mt-8 grid gap-4 sm:grid-cols-2" aria-label="Component showcase" data-docs-showcase>
    <div class="flex flex-col gap-4 rounded-xl border bg-card p-5">
        <p class="text-xs font-medium tracking-wide text-muted-foreground uppercase">Buttons &amp; badges</p>
        <div class="flex flex-wrap gap-2">
            <x-ui.button size="sm">Primary</x-ui.button>
            <x-ui.button size="sm" variant="secondary">Secondary</x-ui.button>
            <x-ui.button size="sm" variant="outline">Outline</x-ui.button>
        </div>
        <div class="flex flex-wrap gap-2">
            <x-ui.badge>New</x-ui.badge>
            <x-ui.badge variant="secondary">Beta</x-ui.badge>
            <x-ui.badge variant="outline">v1.0</x-ui.badge>
            <x-ui.badge variant="destructive">Deprecated</x-ui.badge>
        </div>
    </div>
    <div class="flex flex-col gap-4 rounded-xl border bg-card p-5">
        <p class="text-xs font-medium tracking-wide text-muted-foreground uppercase">Forms</p>
        <div class="flex gap-2">
            <x-ui.input type="email" name="hero-email" placeholder="you@example.com" aria-label="Email" />
            <x-ui.button variant="outline">Subscribe</x-ui.button>
        </div>
        <div class="flex flex-wrap items-center gap-5">
            <div class="flex items-center gap-2">
                <x-ui.switch id="hero-switch" :checked="true" />
                <x-ui.label for="hero-switch">Notifications</x-ui.label>
            </div>
            <div class="flex items-center gap-2">
                <x-ui.checkbox id="hero-check" name="hero-check" :checked="true" />
                <x-ui.label for="hero-check">Remember me</x-ui.label>
            </div>
        </div>
    </div>
    <div class="flex flex-col gap-3 rounded-xl border bg-card p-5 sm:col-span-2">
        <div class="flex items-center justify-between">
            <p class="text-xs font-medium tracking-wide text-muted-foreground uppercase">Feedback</p>
            <span class="text-xs text-muted-foreground tabular-nums">72%</span>
        </div>
        <x-ui.progress :value="72" />
        <div class="flex flex-wrap items-center gap-2 text-sm text-muted-foreground">
            <x-ui.spinner class="size-4" /> Deploying your components…
            <x-ui.button size="sm" variant="ghost" class="ml-auto" x-on:click="window.toast?.success('Changes saved', { description: 'Rendered by the package toaster.' })">Show a toast</x-ui.button>
        </div>
    </div>
</section>
