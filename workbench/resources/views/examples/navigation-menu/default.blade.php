<x-ui.navigation-menu indicator>
    <x-ui.navigation-menu-list>
        <x-ui.navigation-menu-item>
            <x-ui.navigation-menu-trigger>Getting started</x-ui.navigation-menu-trigger>
            <x-ui.navigation-menu-content>
                <ul class="grid gap-2 md:w-[400px] lg:w-[500px] lg:grid-cols-[.75fr_1fr]">
                    <li class="row-span-3">
                        <x-ui.navigation-menu-link href="#" class="h-full justify-end rounded-md bg-gradient-to-b from-muted/50 to-muted p-6 no-underline">
                            <div class="mt-4 mb-2 text-lg font-medium">BladeCN</div>
                            <p class="text-sm leading-tight text-muted-foreground">Beautifully designed Blade components built with Tailwind CSS and Alpine.js.</p>
                        </x-ui.navigation-menu-link>
                    </li>
                    @foreach ([
                        'Introduction' => 'Re-usable components built using Tailwind CSS and Alpine.js.',
                        'Installation' => 'How to install the package and publish the components.',
                        'Theming' => 'CSS variables, dark mode and the appearance switcher.',
                    ] as $title => $text)
                        <li>
                            <x-ui.navigation-menu-link href="#">
                                <div class="text-sm leading-none font-medium">{{ $title }}</div>
                                <p class="line-clamp-2 text-sm leading-snug text-muted-foreground">{{ $text }}</p>
                            </x-ui.navigation-menu-link>
                        </li>
                    @endforeach
                </ul>
            </x-ui.navigation-menu-content>
        </x-ui.navigation-menu-item>
        <x-ui.navigation-menu-item>
            <x-ui.navigation-menu-trigger>Components</x-ui.navigation-menu-trigger>
            <x-ui.navigation-menu-content>
                <ul class="grid w-[400px] gap-2 md:w-[500px] md:grid-cols-2">
                    @foreach ([
                        'Alert Dialog' => 'A modal dialog that interrupts the user with important content.',
                        'Hover Card' => 'For sighted users to preview content available behind a link.',
                        'Progress' => 'Displays an indicator showing the completion progress of a task.',
                        'Scroll Area' => 'Visually or semantically separates content.',
                        'Tabs' => 'A set of layered sections of content, displayed one at a time.',
                        'Tooltip' => 'A popup that displays information related to an element.',
                    ] as $title => $text)
                        <li>
                            <x-ui.navigation-menu-link href="#">
                                <div class="text-sm leading-none font-medium">{{ $title }}</div>
                                <p class="line-clamp-2 text-sm leading-snug text-muted-foreground">{{ $text }}</p>
                            </x-ui.navigation-menu-link>
                        </li>
                    @endforeach
                </ul>
            </x-ui.navigation-menu-content>
        </x-ui.navigation-menu-item>
        <x-ui.navigation-menu-item>
            <x-ui.navigation-menu-link href="#" variant="trigger" active>Docs</x-ui.navigation-menu-link>
        </x-ui.navigation-menu-item>
    </x-ui.navigation-menu-list>
</x-ui.navigation-menu>
