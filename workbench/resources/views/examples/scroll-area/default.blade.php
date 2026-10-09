<x-ui.scroll-area class="h-72 w-48 rounded-md border">
    <div class="p-4">
        <h4 class="mb-4 text-sm font-medium leading-none">Tags</h4>
        @foreach (range(50, 1) as $version)
            <div class="text-sm">v1.2.0-beta.{{ $version }}</div>
            <x-ui.separator class="my-2" />
        @endforeach
    </div>
</x-ui.scroll-area>
