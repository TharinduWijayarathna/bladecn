<div x-data="{ period: { start: '2026-06-01', end: '2026-06-14' } }" class="flex flex-col items-center gap-3">
    <x-ui.date-range-picker x-model="period" start-name="from" end-name="to" :presets="['Last week' => 'last7', 'Last 30 days' => 'last30', 'This year' => 'thisYear']" />
    <p class="text-sm text-muted-foreground">Submitted: <code x-text="`from=${period.start ?? ''}&to=${period.end ?? ''}`"></code></p>
</div>
