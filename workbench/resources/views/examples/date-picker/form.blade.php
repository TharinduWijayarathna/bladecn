<div x-data="{ start: '2026-06-01' }" class="flex flex-col items-center gap-3">
    <x-ui.date-picker x-model="start" min="2026-05-01" :format="['weekday' => 'short', 'month' => 'short', 'day' => 'numeric']" />
    <p class="text-sm text-muted-foreground">Submitted value: <code x-text="start"></code></p>
</div>
