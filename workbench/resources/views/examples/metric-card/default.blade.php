<div class="grid gap-4 sm:grid-cols-2">
    <x-ui.metric-card title="Total revenue" value="$45,231" change="20.1" trend="up">
        <x-slot:icon>
            <x-icons.analytics-up class="size-4" />
        </x-slot:icon>
    </x-ui.metric-card>

    <x-ui.metric-card
        title="Churn"
        value="2.4%"
        change="4.3"
        trend="down"
        icon-bg-color="bg-rose-100 dark:bg-rose-500/20"
        icon-color="text-rose-700 dark:text-rose-300"
    >
        <x-slot:icon>
            <x-icons.trending-down class="size-4" />
        </x-slot:icon>
    </x-ui.metric-card>
</div>
