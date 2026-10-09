<form class="flex flex-col items-center gap-3" x-data="{ notify: true }" onsubmit="event.preventDefault()">
    <div class="flex items-center gap-2">
        <x-ui.switch id="notify" name="notify" x-model="notify" checked />
        <x-ui.label for="notify">Email notifications</x-ui.label>
    </div>
    <p class="text-sm text-muted-foreground">notify = <code x-text="notify"></code></p>
</form>
