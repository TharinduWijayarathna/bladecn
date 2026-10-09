<div x-data="{ day: null }" class="flex flex-col items-center gap-3">
    <x-ui.calendar x-model="day" min="2026-06-08" max="2026-06-26" :week-starts-on="1" class="rounded-md border" />
    <p class="text-sm text-muted-foreground">Selected: <span x-text="day ?? 'none'"></span></p>
</div>
