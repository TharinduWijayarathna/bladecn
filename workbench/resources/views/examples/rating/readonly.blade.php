<div class="flex flex-col items-center gap-3">
    <div class="flex items-center gap-2">
        <x-ui.rating :value="4.5" readonly label="Average rating" />
        <span class="text-sm text-muted-foreground">4.5 (1,284 reviews)</span>
    </div>
    <x-ui.rating :value="2.5" readonly size="sm" />
    <x-ui.rating :value="3.7" readonly size="lg" />
</div>
