<div class="w-full max-w-xl">
    <x-charts.line
        curve="linear"
        dots
        :height="200"
        :y-axis="false"
        :legend="false"
        :labels="['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']"
        :series="[['name' => 'Revenue', 'data' => [1200, 1900, 1500, 2400, 2100, 3100, 2800], 'color' => 'var(--chart-2)']]"
        prefix="$"
    />
</div>
