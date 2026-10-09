<div x-data="{ open: false }" x-modelable="open" @keydown.escape.window="open = false" {{ $attributes }}>
    {{ $slot }}
</div>
