{{--
    In an app the provider wraps the whole page: <x-ui.sidebar-provider> ... </x-ui.sidebar-provider>.
    Here it is boxed in (the `transform` makes the fixed sidebar position against the box).
--}}
<div class="h-[480px] w-full overflow-hidden rounded-lg border [transform:translateZ(0)]">
    <x-ui.sidebar-provider storage-key="docs_sidebar_state" class="h-full min-h-full!">
        <x-ui.sidebar collapsible="icon" class="h-full!">
            <x-ui.sidebar-header>
                <x-ui.sidebar-menu>
                    <x-ui.sidebar-menu-item>
                        <x-ui.sidebar-menu-button size="lg" href="#" tooltip="Acme Inc">
                            <span class="flex aspect-square size-8 items-center justify-center rounded-lg bg-sidebar-primary text-sidebar-primary-foreground">
                                <x-icons.app-logo class="size-4" />
                            </span>
                            <span class="grid flex-1 text-left leading-tight">
                                <span class="truncate font-medium">Acme Inc</span>
                                <span class="truncate text-xs text-muted-foreground">Enterprise</span>
                            </span>
                        </x-ui.sidebar-menu-button>
                    </x-ui.sidebar-menu-item>
                </x-ui.sidebar-menu>
            </x-ui.sidebar-header>

            <x-ui.sidebar-content>
                <x-ui.sidebar-group>
                    <x-ui.sidebar-group-label>Platform</x-ui.sidebar-group-label>
                    <x-ui.sidebar-group-content>
                        <x-ui.sidebar-menu>
                            <x-ui.sidebar-menu-item>
                                <x-ui.sidebar-menu-button href="#" is-active tooltip="Dashboard">
                                    <x-icons.layout-grid />
                                    <span>Dashboard</span>
                                </x-ui.sidebar-menu-button>
                            </x-ui.sidebar-menu-item>

                            <x-ui.collapsible open>
                                <x-ui.sidebar-menu-item>
                                    <x-ui.collapsible-trigger class="w-full">
                                        <x-ui.sidebar-menu-button tag="span" tooltip="Projects">
                                            <x-icons.notebook-pen />
                                            <span>Projects</span>
                                        </x-ui.sidebar-menu-button>
                                    </x-ui.collapsible-trigger>
                                    <x-ui.collapsible-content>
                                        <x-ui.sidebar-menu-sub>
                                            <x-ui.sidebar-menu-sub-item>
                                                <x-ui.sidebar-menu-sub-button href="#" is-active><span>Design system</span></x-ui.sidebar-menu-sub-button>
                                            </x-ui.sidebar-menu-sub-item>
                                            <x-ui.sidebar-menu-sub-item>
                                                <x-ui.sidebar-menu-sub-button href="#"><span>Marketing site</span></x-ui.sidebar-menu-sub-button>
                                            </x-ui.sidebar-menu-sub-item>
                                        </x-ui.sidebar-menu-sub>
                                    </x-ui.collapsible-content>
                                </x-ui.sidebar-menu-item>
                            </x-ui.collapsible>

                            <x-ui.sidebar-menu-item>
                                <x-ui.sidebar-menu-button href="#" tooltip="Calendar">
                                    <x-icons.calendar />
                                    <span>Calendar</span>
                                </x-ui.sidebar-menu-button>
                            </x-ui.sidebar-menu-item>
                            <x-ui.sidebar-menu-item>
                                <x-ui.sidebar-menu-button href="#" tooltip="Team">
                                    <x-icons.users />
                                    <span>Team</span>
                                </x-ui.sidebar-menu-button>
                            </x-ui.sidebar-menu-item>
                        </x-ui.sidebar-menu>
                    </x-ui.sidebar-group-content>
                </x-ui.sidebar-group>
            </x-ui.sidebar-content>

            <x-ui.sidebar-separator />

            <x-ui.sidebar-footer>
                <x-ui.sidebar-menu>
                    <x-ui.sidebar-menu-item>
                        <x-ui.sidebar-menu-button href="#" tooltip="Settings">
                            <x-icons.gear />
                            <span>Settings</span>
                        </x-ui.sidebar-menu-button>
                    </x-ui.sidebar-menu-item>
                </x-ui.sidebar-menu>
            </x-ui.sidebar-footer>
        </x-ui.sidebar>

        <x-ui.sidebar-inset>
            <header class="flex h-12 shrink-0 items-center gap-2 border-b px-4">
                <x-ui.sidebar-trigger class="-ml-1" />
                <x-ui.separator orientation="vertical" class="h-4" />
                <span class="text-sm font-medium">Dashboard</span>
            </header>
            <div class="grid flex-1 gap-4 p-4 sm:grid-cols-3">
                <div class="rounded-xl bg-muted/50"></div>
                <div class="rounded-xl bg-muted/50"></div>
                <div class="rounded-xl bg-muted/50"></div>
            </div>
        </x-ui.sidebar-inset>
    </x-ui.sidebar-provider>
</div>
