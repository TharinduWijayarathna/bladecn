<div class="w-full max-w-xl">
    <x-charts.area
        stacked
        label="Visitors, January to June"
        :labels="['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']"
        :series="[
            'Mobile' => [80, 200, 120, 190, 130, 140],
            'Desktop' => [186, 305, 237, 73, 209, 214],
        ]"
    />
</div>
