<div class="flex flex-col items-center gap-3">
    <x-ui.rating :value="3.5" step="0.5" name="half" label="Half-star rating" />
    <x-ui.rating :value="7" :max="10" size="sm" label="Out of ten" />
    <x-ui.rating :value="2" disabled label="Disabled rating" />
</div>
