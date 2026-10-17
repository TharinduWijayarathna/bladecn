<div class="w-full max-w-xs" x-data="{ tone: { x: 0.4, y: 0.3 } }">
    <x-ui.quadrant-picker x-model="tone" name="tone" label="Tone" :x="0.4" :y="0.3"
        :labels="['Playful', 'Formal', 'Casual', 'Technical']" />
    <p class="mt-3 text-center font-mono text-xs text-muted-foreground" x-text="JSON.stringify(tone)"></p>
</div>
