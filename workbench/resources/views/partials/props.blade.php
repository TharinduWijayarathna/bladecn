<div class="mb-8">
    <div class="mb-2 flex flex-wrap items-baseline gap-x-3 gap-y-1">
        <h3 class="font-mono text-sm font-semibold">&lt;x-{{ $table['tag'] }}&gt;</h3>
        <span class="text-xs text-muted-foreground">
            {{ $table['file'] }}@if ($table['class']) · {{ class_basename($table['class']) }}.php @else · anonymous component @endif
        </span>
    </div>
    @if (count($table['props']) === 0)
        <p class="rounded-md border px-4 py-3 text-sm text-muted-foreground">No props. Content goes in the default slot.</p>
    @else
        <div class="overflow-x-auto rounded-md border">
            <table class="w-full text-left text-sm">
                <thead class="bg-muted/50 text-xs text-muted-foreground">
                    <tr>
                        <th class="px-3 py-2 font-medium">Prop</th>
                        <th class="px-3 py-2 font-medium">Type</th>
                        <th class="px-3 py-2 font-medium">Default</th>
                        <th class="px-3 py-2 font-medium">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table['props'] as $prop)
                        <tr class="border-t align-top">
                            <td class="px-3 py-2 font-mono text-xs whitespace-nowrap">{{ $prop['name'] }}@if ($prop['required'])<span class="text-destructive-foreground" title="Required">*</span>@endif</td>
                            <td class="px-3 py-2 font-mono text-xs whitespace-nowrap text-muted-foreground">{{ $prop['type'] }}</td>
                            <td class="px-3 py-2 font-mono text-xs text-muted-foreground">{{ $prop['required'] ? 'required' : $prop['default'] }}</td>
                            <td class="docs-prose px-3 py-2 text-sm">{!! $prop['description'] ? $docs->markdown($prop['description']) : '<span class="text-muted-foreground">—</span>' !!}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
