@props([
    'value',
    'class' => '',
])

@aware(['defaultValue' => null])

@php $selected = (string) $defaultValue === (string) $value; @endphp

<div role="tabpanel" data-slot="tabs-content" tabindex="0"
    :id="$id('tabs') + '-content-' + @js((string) $value)" :aria-labelledby="$id('tabs') + '-trigger-' + @js((string) $value)"
    x-show="tab === @js((string) $value)" :data-state="tab === @js((string) $value) ? 'active' : 'inactive'"
    data-state="{{ $selected ? 'active' : 'inactive' }}" @unless ($selected) style="display: none;" @endunless
    {{ $attributes->merge(['class' => cn('flex-1 outline-none', $class)]) }}>
    {{ $slot }}
</div>
