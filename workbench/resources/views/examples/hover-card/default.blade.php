<x-ui.hover-card>
    <x-ui.hover-card-trigger href="https://laravel.com" class="text-sm font-medium">@laravelphp</x-ui.hover-card-trigger>
    <x-ui.hover-card-content class="w-80">
        <div class="flex justify-between gap-4">
            <x-ui.avatar>
                <x-ui.avatar-image src="https://github.com/laravel.png" alt="Laravel" />
                <x-ui.avatar-fallback name="Laravel" />
            </x-ui.avatar>
            <div class="space-y-1">
                <h4 class="text-sm font-semibold">@laravelphp</h4>
                <p class="text-sm">The PHP framework for web artisans.</p>
                <div class="flex items-center gap-1 text-xs text-muted-foreground">
                    <x-icons.calendar class="size-3.5" /> Joined June 2011
                </div>
            </div>
        </div>
    </x-ui.hover-card-content>
</x-ui.hover-card>
