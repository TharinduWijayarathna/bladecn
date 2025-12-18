<header class="flex h-14 shrink-0 items-center gap-2 border-b transition-[width,height] ease-linear">
    <div class="flex w-full items-center gap-1 px-4 lg:gap-2 lg:px-6">
        <x-ui.button variant="ghost" size="icon" @click="sidebarOpen = !sidebarOpen" class="inline-flex lg:hidden">
            <x-icons.panel-left />
            <span class="sr-only">Toggle sidebar</span>
        </x-ui.button>
        <div class="block lg:hidden bg-border shrink-0 w-px mx-2 h-4"></div>

        {{-- Breadcrumb --}}
        <x-ui.breadcrumb :breadcrumbs="$breadcrumbs" />

        @auth
            <div class="ml-auto flex items-center gap-2">
                {{-- User Avatar --}}
                <x-ui.dropdown>
                    <x-slot:trigger>
                        <x-ui.avatar class="size-8 overflow-hidden rounded-full">
                            {{-- <x-ui.avatar-image src="" :alt="Auth::user()->name" /> --}}
                            <x-ui.avatar-fallback :name="Auth::user()->name"
                                class="rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white" />
                        </x-ui.avatar>
                    </x-slot:trigger>

                    <x-ui.dropdown-content>
                        <x-ui.dropdown-label>
                            <p class="text-sm leading-none font-medium">{{ Auth::user()->name }}</p>
                            <p class="text-muted-foreground text-xs leading-none">{{ Auth::user()->email }}</p>
                        </x-ui.dropdown-label>
                        <x-ui.dropdown-separator />
                        @if (Route::has('profile.edit'))
                            <x-ui.dropdown-item :href="route('profile.edit')">Profile</x-ui.dropdown-item>
                        @endif
                        <x-ui.dropdown-item href="#">Settings</x-ui.dropdown-item>

                        <x-ui.dropdown-separator />

                        {{-- Theme Toggle --}}
                        <div class="px-2 py-1.5">
                            <p class="text-xs font-medium text-muted-foreground mb-1.5 px-2">Theme</p>
                            <div
                                class="flex items-center gap-0 -space-x-px rounded-md border border-input overflow-hidden shadow-xs">
                                <button type="button" onclick="updateTheme('light')"
                                    class="flex-1 flex items-center justify-center px-2 py-1.5 text-xs transition-colors hover:bg-accent first:rounded-l-md last:rounded-r-md {{ session('appearance', 'system') === 'light' ? 'bg-accent z-10 border-r border-input' : 'bg-transparent' }}"
                                    title="Light">
                                    <x-icons.sun class="size-3.5" />
                                </button>
                                <button type="button" onclick="updateTheme('dark')"
                                    class="flex-1 flex items-center justify-center px-2 py-1.5 text-xs transition-colors hover:bg-accent border-x border-input {{ session('appearance', 'system') === 'dark' ? 'bg-accent z-10' : 'bg-transparent' }}"
                                    title="Dark">
                                    <x-icons.moon class="size-3.5" />
                                </button>
                                <button type="button" onclick="updateTheme('system')"
                                    class="flex-1 flex items-center justify-center px-2 py-1.5 text-xs transition-colors hover:bg-accent {{ session('appearance', 'system') === 'system' ? 'bg-accent z-10 border-l border-input' : 'bg-transparent' }}"
                                    title="System">
                                    <x-icons.monitor class="size-3.5" />
                                </button>
                            </div>
                        </div>
                        <x-ui.dropdown-separator />

                        @if (Route::has('logout'))
                            <x-ui.dropdown-item :href="route('logout')" method="post">Logout</x-ui.dropdown-item>
                        @endif
                    </x-ui.dropdown-content>
                </x-ui.dropdown>
            </div>
        @endauth
    </div>
</header>
