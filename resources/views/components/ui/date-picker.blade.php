@props([
    'value' => null,
    'name' => null,
    'placeholder' => 'Pick a date',
    'min' => null,
    'max' => null,
    'weekStartsOn' => 0,
    'locale' => null,
    'format' => null,
    'class' => '',
])

@php
    $value = $value ? \Illuminate\Support\Carbon::parse($value)->format('Y-m-d') : null;
    $locale ??= str_replace('_', '-', app()->getLocale());
@endphp

{{-- A popover with a calendar. `format` is an Intl.DateTimeFormat options object; defaults to { dateStyle: 'long' }. --}}
<div data-slot="date-picker" class="inline-block" x-modelable="date"
    x-data="{
        date: @js($value),
        placeholder: @js($placeholder),
        formatted() {
            if (! this.date) return this.placeholder;
            const [y, m, d] = this.date.split('-').map(Number);
            return new Date(y, m - 1, d).toLocaleDateString(@js($locale), @js($format ?? ['dateStyle' => 'long']));
        },
    }"
    {{ $attributes->except('class') }}>
    @if ($name)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}" :value="date ?? ''">
    @endif
    <x-ui.popover x-on:change="if ($event.target.matches('[data-slot=calendar]')) { date = $event.detail.value; close() }">
        <x-ui.popover-trigger :class="cn('w-[240px] justify-start text-left font-normal', $class)" x-bind:data-empty="! date">
            <x-icons.calendar class="size-4" />
            <span x-text="formatted()" :class="! date && 'text-muted-foreground'">{{ $value ?? $placeholder }}</span>
        </x-ui.popover-trigger>
        <x-ui.popover-content align="start" class="w-auto p-0">
            <x-ui.calendar x-model="date" :min="$min" :max="$max" :week-starts-on="$weekStartsOn" :locale="$locale" />
        </x-ui.popover-content>
    </x-ui.popover>
</div>
