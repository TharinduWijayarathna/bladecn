{{--
    In a Livewire component, write wire:model="notify" (or wire:model.live, .blur, …) where this demo uses x-model.
    Both bind through the control's x-modelable state, so the preview behaves the same way.
--}}
<div class="grid w-full max-w-sm gap-5"
    x-data="{ notify: true, framework: 'laravel', due: null, volume: 40, keywords: ['blade', 'alpine'] }">
    <div class="flex items-center gap-2">
        <x-ui.switch id="lw-notify" x-model="notify" />
        <x-ui.label for="lw-notify">Notifications</x-ui.label>
    </div>
    <x-ui.combobox x-model="framework" :options="['laravel' => 'Laravel', 'livewire' => 'Livewire', 'alpine' => 'Alpine.js']" />
    <x-ui.date-picker x-model="due" />
    <x-ui.slider x-model="volume" label="Volume" />
    <x-ui.input-tags name="tags" x-model="keywords">Tags</x-ui.input-tags>
    <pre class="rounded-md bg-muted p-3 font-mono text-xs" x-text="JSON.stringify({ notify, framework, due, volume, keywords }, null, 2)"></pre>
</div>
