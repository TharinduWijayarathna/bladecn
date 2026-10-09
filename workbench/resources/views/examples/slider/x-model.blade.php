<div x-data="{ temperature: 0.7 }" class="flex w-[60%] flex-col gap-3">
    <div class="flex items-center justify-between text-sm">
        <x-ui.label>Temperature</x-ui.label>
        <span class="text-muted-foreground" x-text="temperature"></span>
    </div>
    <x-ui.slider x-model="temperature" :value="0.7" :min="0" :max="1" :step="0.05" label="Temperature" />
</div>
