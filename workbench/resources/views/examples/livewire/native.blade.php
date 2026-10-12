{{-- Native controls (input, textarea, select, native-select, checkbox) put wire:model straight on the element. --}}
<div class="grid w-full max-w-sm gap-4" x-data="{ email: 'ada@example.com', country: 'lk' }">
    <x-ui.input type="email" name="email" x-model="email" />
    <x-ui.native-select name="country" x-model="country">
        <option value="gb">United Kingdom</option>
        <option value="lk">Sri Lanka</option>
    </x-ui.native-select>
    <p class="text-sm text-muted-foreground">email = <span x-text="email"></span>, country = <span x-text="country"></span></p>
</div>
