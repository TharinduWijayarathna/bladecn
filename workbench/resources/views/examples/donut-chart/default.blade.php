<div class="w-full max-w-xs">
    <x-charts.donut
        id="docsDonut"
        :series="[44, 26, 18, 12]"
        :labels="['Direct', 'Organic', 'Referral', 'Social']"
        :colors="['#2563eb', '#10b981', '#f59e0b', '#ef4444']"
        center-value="1,240"
        center-label="Visitors"
        :height="260"
    />
</div>
