<x-ui.drawer>
    <x-ui.drawer-trigger>Open Drawer</x-ui.drawer-trigger>
    <x-ui.drawer-content>
        <div class="mx-auto w-full max-w-sm" x-data="{ goal: 350 }">
            <x-ui.drawer-header>
                <x-ui.drawer-title>Move Goal</x-ui.drawer-title>
                <x-ui.drawer-description>Set your daily activity goal.</x-ui.drawer-description>
            </x-ui.drawer-header>
            <div class="flex items-center justify-center gap-4 p-4 pb-0">
                <x-ui.button variant="outline" size="icon" class="size-8 shrink-0 rounded-full" x-on:click="goal = Math.max(200, goal - 10)" x-bind:disabled="goal <= 200">
                    <x-icons.minus />
                    <span class="sr-only">Decrease</span>
                </x-ui.button>
                <div class="flex-1 text-center">
                    <div class="text-7xl font-bold tracking-tighter" x-text="goal">350</div>
                    <div class="text-[0.70rem] uppercase text-muted-foreground">Calories/day</div>
                </div>
                <x-ui.button variant="outline" size="icon" class="size-8 shrink-0 rounded-full" x-on:click="goal = Math.min(400, goal + 10)" x-bind:disabled="goal >= 400">
                    <x-icons.plus />
                    <span class="sr-only">Increase</span>
                </x-ui.button>
            </div>
            <x-ui.drawer-footer>
                <x-ui.button x-on:click="open = false">Submit</x-ui.button>
                <x-ui.drawer-close>Cancel</x-ui.drawer-close>
            </x-ui.drawer-footer>
        </div>
    </x-ui.drawer-content>
</x-ui.drawer>
