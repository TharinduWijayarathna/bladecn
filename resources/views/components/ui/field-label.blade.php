@props([
    'class' => '',
])

<label data-slot="field-label" {{ $attributes->merge(['class' => cn(
    'group/field-label peer/field-label flex w-fit select-none items-center gap-2 text-sm font-medium leading-snug group-data-[disabled=true]/field:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50',
    'has-[>[data-slot=field]]:w-full has-[>[data-slot=field]]:flex-col has-[>[data-slot=field]]:rounded-md has-[>[data-slot=field]]:border [&>*]:data-[slot=field]:p-4',
    'has-[:checked]:border-primary has-[:checked]:bg-primary/5 dark:has-[:checked]:bg-primary/10',
    $class,
)]) }}>
    {{ $slot }}
</label>
