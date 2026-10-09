@props([
    'class' => '',
])

<p data-slot="field-description" {{ $attributes->merge(['class' => cn(
    'text-sm font-normal leading-normal text-muted-foreground group-has-[[data-orientation=horizontal]]/field:text-balance',
    'last:mt-0 nth-last-2:-mt-1 [[data-variant=legend]+&]:-mt-1.5 [&>a]:underline [&>a]:underline-offset-4 [&>a:hover]:text-primary',
    $class,
)]) }}>
    {{ $slot }}
</p>
