@props([
    'currentPath' => request()->path(),
])

<div class="px-4 py-6">
    <x-ui.heading title="Settings" description="Manage your profile and account settings" />
    
    <div class="flex flex-col space-y-8 lg:flex-row lg:space-y-0 lg:space-x-12">
        <aside class="w-full max-w-xl lg:w-48">
            <nav class="flex flex-col space-y-1 space-x-0">
                @php
                    $navItems = [
                        ['title' => 'Profile', 'route' => 'settings.profile'],
                        ['title' => 'Password', 'route' => 'settings.password'],
                        ['title' => 'Appearance', 'route' => 'settings.appearance'],
                    ];
                @endphp
                
                @foreach($navItems as $item)
                    @php
                        $route = $item['route'] ?? null;
                        $href = $route && Route::has($route) ? route($route) : '#';
                        $isActive = $route && request()->routeIs($route);
                    @endphp
                    <a 
                        href="{{ $href }}"
                        class="{{ cn('inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all [&_svg]:pointer-events-none [&_svg:not([class*="size-"])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none hover:bg-accent hover:text-accent-foreground dark:hover:bg-accent/50 h-8 rounded-md gap-1.5 px-3 has-[>svg]:px-2.5 w-full justify-start', $isActive ? 'bg-muted' : '') }}"
                    >
                        {{ $item['title'] }}
                    </a>
                @endforeach
            </nav>
        </aside>
        
        <x-ui.separator class="my-6 md:hidden" />
        
        <div class="flex-1 md:max-w-3xl">
            <section class="max-w-3xl space-y-12">
                {{ $slot }}
            </section>
        </div>
    </div>
</div>

