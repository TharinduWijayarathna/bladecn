<div class="w-full max-w-xl">
    <x-charts.area
        label="Visitors, January to June"
        :labels="['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']"
        :series="['Desktop' => [186, 305, 237, 73, 209, 214]]"
        :legend="false"
    />
</div>
