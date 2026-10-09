@props([
    'orientation' => 'vertical',
    'class' => '',
])

@php
    $overflow = [
        'vertical' => 'overflow-y-auto overflow-x-hidden',
        'horizontal' => 'overflow-x-auto overflow-y-hidden',
        'both' => 'overflow-auto',
    ][$orientation] ?? 'overflow-y-auto overflow-x-hidden';
@endphp

<div data-slot="scroll-area" data-orientation="{{ $orientation }}" tabindex="0"
    {{ $attributes->merge(['class' => cn(
        'relative rounded-[inherit] outline-none transition-[color,box-shadow] focus-visible:ring-[3px] focus-visible:ring-ring/50',
        '[scrollbar-width:thin] [scrollbar-color:var(--border)_transparent]',
        '[&::-webkit-scrollbar]:size-2.5 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:border-2 [&::-webkit-scrollbar-thumb]:border-solid [&::-webkit-scrollbar-thumb]:border-transparent [&::-webkit-scrollbar-thumb]:bg-border [&::-webkit-scrollbar-thumb]:bg-clip-padding',
        $overflow,
        $class,
    )]) }}>
    {{ $slot }}
</div>
