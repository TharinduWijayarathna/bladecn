<div x-data="{ last: null }" class="flex w-full max-w-sm flex-col gap-3">
    <x-ui.sortable handle name="tracks" label="Playlist" x-on:sorted="last = $event.detail">
        @foreach (['Intro' => '1:05', 'Verse' => '2:40', 'Chorus' => '3:12', 'Bridge' => '1:48', 'Outro' => '2:01'] as $track => $length)
            <x-ui.sortable-item :value="$loop->iteration" :label="$track">
                <div class="flex items-center justify-between gap-4">
                    <span>{{ $track }}</span>
                    <x-ui.badge variant="secondary">{{ $length }}</x-ui.badge>
                </div>
            </x-ui.sortable-item>
        @endforeach
    </x-ui.sortable>
    <p class="text-sm text-muted-foreground" x-show="last" x-cloak>Moved track <code x-text="last?.item"></code> from position <span x-text="last?.from + 1"></span> to <span x-text="last?.to + 1"></span>.</p>
</div>
