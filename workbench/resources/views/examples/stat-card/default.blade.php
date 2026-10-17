<div class="@container w-full">
    <div class="grid gap-4 @md:grid-cols-2">
        <x-ui.stat-card title="Total revenue" value="$45,231.89" change="+20.1%" trend="up" description="from last month">
            <x-slot:icon><x-icons.analytics-up /></x-slot:icon>
        </x-ui.stat-card>
        <x-ui.stat-card title="Churn rate" value="2.4%" change="-0.3%" trend="down" description="vs. last quarter" />
    </div>
</div>
