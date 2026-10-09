@php
    // Every icon file in resources/views/components/icons.
    $icons = app(\Workbench\App\Docs\Docs::class)->icons();
@endphp

<div class="grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-4">
    @foreach ($icons as $icon)
        <div class="flex items-center gap-3 rounded-md border p-3 text-sm">
            <x-dynamic-component :component="'icons.'.$icon" class="size-5 shrink-0" />
            <code class="truncate font-mono text-xs text-muted-foreground">icons.{{ $icon }}</code>
        </div>
    @endforeach
</div>
