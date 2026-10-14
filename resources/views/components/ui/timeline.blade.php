@props([
    'alternate' => false,
    'class' => '',
])

{{-- A vertical list of events. With `alternate`, items zig-zag around a centred line from the `md` breakpoint up. --}}
<ol role="list" data-slot="timeline" @if ($alternate) data-alternate="true" @endif
    {{ $attributes->merge(['class' => cn('relative flex flex-col', $class)]) }}>
    {{ $slot }}
</ol>
