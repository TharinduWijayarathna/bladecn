<div class="w-full max-w-xl">
    <x-charts.bar
        label="Visitors, January to June"
        :labels="['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']"
        :series="[
            'Desktop' => [186, 305, 237, 73, 209, 214],
            'Mobile' => [80, 200, 120, 190, 130, 140],
        ]"
    />
</div>
