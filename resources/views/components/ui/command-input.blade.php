@props([
    'placeholder' => 'Type a command or search...',
    'class' => '',
])

<div data-slot="command-input-wrapper" class="flex h-9 items-center gap-2 border-b px-3">
    <x-icons.search class="size-4 shrink-0 opacity-50" />
    <input type="text" data-slot="command-input" role="combobox" aria-autocomplete="list" aria-expanded="true"
        autocomplete="off" autocorrect="off" spellcheck="false" placeholder="{{ $placeholder }}"
        :aria-controls="$id('command-list')" :aria-activedescendant="active" x-model="query" @input="filter()"
        {{ $attributes->merge(['class' => cn(
            'flex h-10 w-full rounded-md bg-transparent py-3 text-sm outline-hidden placeholder:text-muted-foreground disabled:cursor-not-allowed disabled:opacity-50',
            $class,
        )]) }} />
</div>
