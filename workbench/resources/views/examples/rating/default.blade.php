<div x-data="{ score: 3 }" class="flex flex-col items-center gap-2">
    <x-ui.rating x-model="score" name="score" label="Your rating" />
    <p class="text-sm text-muted-foreground">You rated <span x-text="score"></span> / 5</p>
</div>
