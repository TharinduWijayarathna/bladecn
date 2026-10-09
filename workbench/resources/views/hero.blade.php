{{--
    README / social-preview hero: a mosaic of real BladeCN components with the logo in the middle.
    Rendered at /docs/hero/{light|dark} and screenshotted at 1280×640 by `npm run docs:hero`.
--}}
<!DOCTYPE html>
<html lang="en" class="{{ $theme === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <title>BladeCN</title>
    <link rel="stylesheet" href="{{ $docs->asset('docs.css') }}">
    <script defer src="{{ $docs->asset('docs.js') }}"></script>
    <style>
        html, body { width: 1280px; height: 640px; overflow: hidden; }
        body { font-family: Inter, 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
        .hero-grid {
            mask-image: radial-gradient(ellipse 62% 70% at 50% 50%, #000 40%, transparent 100%);
            -webkit-mask-image: radial-gradient(ellipse 62% 70% at 50% 50%, #000 40%, transparent 100%);
        }
        .hero-col > * { flex: none; }
    </style>
</head>
<body class="relative bg-background font-sans text-foreground antialiased">
    <div class="hero-grid pointer-events-none absolute -inset-x-16 -top-10 flex justify-center gap-5" aria-hidden="true">
        {{-- column 1 --}}
        <div class="hero-col flex w-64 flex-col gap-5 pt-6">
            <x-ui.card class="gap-4 py-5">
                <x-ui.card-header class="px-5">
                    <x-ui.card-title>Create project</x-ui.card-title>
                    <x-ui.card-description>Deploy in one click.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content class="grid gap-2 px-5">
                    <x-ui.label>Name</x-ui.label>
                    <x-ui.input value="bladecn-app" />
                </x-ui.card-content>
                <x-ui.card-footer class="justify-between px-5">
                    <x-ui.button variant="outline" size="sm">Cancel</x-ui.button>
                    <x-ui.button size="sm">Deploy</x-ui.button>
                </x-ui.card-footer>
            </x-ui.card>
            <div class="rounded-xl border bg-card p-4">
                <x-ui.toggle-group type="single" variant="outline" value="m">
                    <x-ui.toggle-group-item value="s">S</x-ui.toggle-group-item>
                    <x-ui.toggle-group-item value="m">M</x-ui.toggle-group-item>
                    <x-ui.toggle-group-item value="l">L</x-ui.toggle-group-item>
                    <x-ui.toggle-group-item value="xl">XL</x-ui.toggle-group-item>
                </x-ui.toggle-group>
            </div>
            <x-ui.alert>
                <x-icons.circle-check />
                <x-ui.alert-title>Changes saved</x-ui.alert-title>
                <x-ui.alert-description>Your profile is up to date.</x-ui.alert-description>
            </x-ui.alert>
            <x-ui.command class="rounded-xl border">
                <x-ui.command-input placeholder="Search…" />
                <x-ui.command-list>
                    <x-ui.command-group heading="Suggestions">
                        <x-ui.command-item><x-icons.calendar /> Calendar</x-ui.command-item>
                        <x-ui.command-item><x-icons.smile /> Search Emoji</x-ui.command-item>
                        <x-ui.command-item><x-icons.user /> Profile <x-ui.command-shortcut>⌘P</x-ui.command-shortcut></x-ui.command-item>
                    </x-ui.command-group>
                </x-ui.command-list>
            </x-ui.command>
        </div>

        {{-- column 2 --}}
        <div class="hero-col flex w-72 flex-col gap-5 pt-24">
            <div class="rounded-xl border bg-card p-4">
                <x-ui.tabs default-value="account">
                    <x-ui.tabs-list class="w-full">
                        <x-ui.tabs-trigger value="account">Account</x-ui.tabs-trigger>
                        <x-ui.tabs-trigger value="password">Password</x-ui.tabs-trigger>
                    </x-ui.tabs-list>
                    <x-ui.tabs-content value="account" class="grid gap-2 pt-2">
                        <x-ui.label>Email</x-ui.label>
                        <x-ui.input value="taylor@laravel.com" />
                    </x-ui.tabs-content>
                    <x-ui.tabs-content value="password">…</x-ui.tabs-content>
                </x-ui.tabs>
            </div>
            <div class="flex flex-col gap-3 rounded-xl border bg-card p-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="font-medium">Notifications</span>
                    <x-ui.switch checked />
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="font-medium">Marketing</span>
                    <x-ui.switch />
                </div>
                <x-ui.slider :value="64" label="Volume" />
            </div>
            <div class="flex flex-wrap gap-2 rounded-xl border bg-card p-4">
                <x-ui.badge>Badge</x-ui.badge>
                <x-ui.badge variant="secondary">Secondary</x-ui.badge>
                <x-ui.badge variant="outline">Outline</x-ui.badge>
                <x-ui.badge variant="destructive">Error</x-ui.badge>
            </div>
            <div class="rounded-xl border bg-card p-4">
                <x-ui.button-group>
                    <x-ui.button variant="outline" size="sm">Archive</x-ui.button>
                    <x-ui.button variant="outline" size="sm">Report</x-ui.button>
                    <x-ui.button variant="outline" size="sm">Snooze</x-ui.button>
                </x-ui.button-group>
            </div>
            <div class="flex items-center gap-4 rounded-xl border bg-card p-4">
                <x-ui.skeleton class="size-10 rounded-full" />
                <div class="flex-1 space-y-2">
                    <x-ui.skeleton class="h-3 w-full" />
                    <x-ui.skeleton class="h-3 w-2/3" />
                </div>
            </div>
        </div>

        {{-- column 3 (behind the logo) --}}
        <div class="hero-col flex w-72 flex-col gap-5 pt-2">
            <div class="flex items-center gap-2 rounded-xl border bg-card p-4">
                <x-ui.button>Button</x-ui.button>
                <x-ui.button variant="secondary">Secondary</x-ui.button>
                <x-ui.button variant="outline" size="icon" aria-label="Add"><x-icons.plus /></x-ui.button>
            </div>
            <div class="h-72"></div>
            <div class="space-y-2 rounded-xl border bg-card p-4">
                <div class="flex justify-between text-sm"><span>Storage</span><span class="text-muted-foreground">72%</span></div>
                <x-ui.progress :value="72" class="h-2" />
            </div>
            <x-ui.pagination>
                <x-ui.pagination-content>
                    <x-ui.pagination-item><x-ui.pagination-link href="#">1</x-ui.pagination-link></x-ui.pagination-item>
                    <x-ui.pagination-item><x-ui.pagination-link href="#" is-active>2</x-ui.pagination-link></x-ui.pagination-item>
                    <x-ui.pagination-item><x-ui.pagination-link href="#">3</x-ui.pagination-link></x-ui.pagination-item>
                    <x-ui.pagination-item><x-ui.pagination-ellipsis /></x-ui.pagination-item>
                </x-ui.pagination-content>
            </x-ui.pagination>
            <x-ui.alert variant="destructive">
                <x-icons.circle-alert />
                <x-ui.alert-title>Payment failed</x-ui.alert-title>
            </x-ui.alert>
        </div>

        {{-- column 4 --}}
        <div class="hero-col flex w-72 flex-col gap-5 pt-20">
            <div class="w-fit rounded-xl border bg-card">
                <x-ui.calendar value="2026-06-12" />
            </div>
            <div class="flex items-center gap-3 rounded-xl border bg-card p-4">
                <div class="flex -space-x-2">
                    <x-ui.avatar class="ring-2 ring-background"><x-ui.avatar-fallback name="Taylor Otwell" /></x-ui.avatar>
                    <x-ui.avatar class="ring-2 ring-background"><x-ui.avatar-fallback name="Jess Archer" /></x-ui.avatar>
                    <x-ui.avatar class="ring-2 ring-background"><x-ui.avatar-fallback name="Nuno Maduro" /></x-ui.avatar>
                </div>
                <span class="text-sm text-muted-foreground">+12 collaborators</span>
            </div>
            <div class="rounded-xl border bg-card p-4">
                <x-ui.field invalid>
                    <x-ui.field-label>Email</x-ui.field-label>
                    <x-ui.input value="not-an-email" aria-invalid="true" />
                    <x-ui.field-error :messages="['Enter a valid email address.']" />
                </x-ui.field>
            </div>
        </div>

        {{-- column 5 --}}
        <div class="hero-col flex w-64 flex-col gap-5 pt-8">
            <div class="w-56 rounded-md border bg-popover p-1 text-popover-foreground shadow-md">
                <x-ui.dropdown-label>My Account</x-ui.dropdown-label>
                <x-ui.dropdown-separator />
                <x-ui.dropdown-item href="#"><x-icons.user /> Profile <x-ui.dropdown-shortcut>⇧⌘P</x-ui.dropdown-shortcut></x-ui.dropdown-item>
                <x-ui.dropdown-item href="#"><x-icons.credit-card /> Billing <x-ui.dropdown-shortcut>⌘B</x-ui.dropdown-shortcut></x-ui.dropdown-item>
                <x-ui.dropdown-item href="#"><x-icons.gear /> Settings <x-ui.dropdown-shortcut>⌘S</x-ui.dropdown-shortcut></x-ui.dropdown-item>
            </div>
            <div class="flex w-full items-start gap-3 rounded-lg border bg-popover p-4 text-sm shadow-lg">
                <span class="mt-0.5 text-emerald-600 dark:text-emerald-400"><x-icons.circle-check class="size-4" /></span>
                <div class="grid gap-1">
                    <div class="font-medium">Event has been created</div>
                    <div class="text-muted-foreground">Sunday, June 12 at 9:00 AM</div>
                </div>
            </div>
            <div class="rounded-xl border bg-card p-4">
                <x-ui.accordion type="single" default-value="a">
                    <x-ui.accordion-item value="a">
                        <x-ui.accordion-trigger class="py-2">Is it accessible?</x-ui.accordion-trigger>
                        <x-ui.accordion-content class="pb-2">Yes. WAI-ARIA patterns.</x-ui.accordion-content>
                    </x-ui.accordion-item>
                    <x-ui.accordion-item value="b">
                        <x-ui.accordion-trigger class="py-2">Is it styled?</x-ui.accordion-trigger>
                    </x-ui.accordion-item>
                </x-ui.accordion>
            </div>
            <div class="flex items-center gap-2 rounded-xl border bg-card p-4">
                <x-ui.toggle variant="outline" pressed aria-label="Bold"><x-icons.bold /></x-ui.toggle>
                <x-ui.toggle variant="outline" aria-label="Italic"><x-icons.italic /></x-ui.toggle>
                <x-ui.toggle variant="outline" aria-label="Underline"><x-icons.underline /></x-ui.toggle>
                <x-ui.kbd-group class="ml-auto"><x-ui.kbd>⌘</x-ui.kbd><x-ui.kbd>K</x-ui.kbd></x-ui.kbd-group>
            </div>
        </div>
    </div>

    {{-- centre: logo --}}
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="absolute inset-0" style="background: radial-gradient(ellipse 36% 32% at 50% 50%, var(--background) 60%, transparent 100%);"></div>
        <div class="relative flex flex-col items-center gap-4">
            <div class="flex items-center gap-4">
                <x-icons.bladecn class="size-20" />
                <span class="text-7xl font-semibold tracking-tight">BladeCN</span>
            </div>
            <p class="text-xl text-muted-foreground">shadcn/ui for Laravel Blade</p>
            <div class="mt-1 flex items-center gap-2 font-mono text-sm text-muted-foreground">
                <x-ui.kbd>composer require bladecn/bladecn</x-ui.kbd>
            </div>
        </div>
    </div>
</body>
</html>
