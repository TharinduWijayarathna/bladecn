<div class="group/native-select relative w-fit has-[select:disabled]:opacity-50">
    <select {{ $attributes->merge(['class' => $nativeSelectClasses()]) }}>
        {{ $slot }}
    </select>
    <x-icons.chevron-down class="text-muted-foreground pointer-events-none absolute top-1/2 right-3.5 size-4 -translate-y-1/2 opacity-50 select-none" />
</div>
