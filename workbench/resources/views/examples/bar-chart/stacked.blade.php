<div class="w-full max-w-xl">
    <x-charts.bar
        stacked
        label="Tickets by channel"
        :labels="['Q1', 'Q2', 'Q3', 'Q4']"
        :series="['Email' => [120, 150, 170, 160], 'Chat' => [80, 95, 140, 180], 'Phone' => [40, 35, 30, 25]]"
    />
</div>
