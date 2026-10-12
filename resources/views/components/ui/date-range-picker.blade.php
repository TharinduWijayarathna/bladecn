@props([
    'start' => null,
    'end' => null,
    'name' => null,
    'startName' => null,
    'endName' => null,
    'placeholder' => 'Pick a date range',
    'presets' => ['today', 'yesterday', 'last7', 'last30', 'thisMonth', 'lastMonth'],
    'months' => 2,
    'min' => null,
    'max' => null,
    'weekStartsOn' => 0,
    'locale' => null,
    'format' => null,
    'class' => '',
])

@php
    $toIso = fn ($date) => $date ? \Illuminate\Support\Carbon::parse($date)->format('Y-m-d') : null;
    $start = $toIso($start);
    $end = $toIso($end);
    $locale ??= str_replace('_', '-', app()->getLocale());
    $startName ??= $name ? $name.'[start]' : null;
    $endName ??= $name ? $name.'[end]' : null;
    $labels = [
        'today' => 'Today', 'yesterday' => 'Yesterday', 'last7' => 'Last 7 days', 'last14' => 'Last 14 days',
        'last30' => 'Last 30 days', 'last90' => 'Last 90 days', 'thisWeek' => 'This week', 'thisMonth' => 'This month',
        'lastMonth' => 'Last month', 'thisYear' => 'This year', 'lastYear' => 'Last year',
    ];
    // Presets: a list of keys above, or `label => key` pairs to rename them. `false` hides the list.
    $presets = collect($presets ?: [])
        ->map(fn ($key, $label) => ['key' => $key, 'label' => is_string($label) ? $label : ($labels[$key] ?? $key)])
        ->filter(fn ($preset) => isset($labels[$preset['key']]))
        ->values()->all();
@endphp

{{--
    A popover with a two-month Range Calendar and quick presets. Submits two hidden inputs: `{name}[start]` and
    `{name}[end]` (or `start-name` / `end-name`). `x-model` binds `{ start, end }`; `format` is an Intl.DateTimeFormat options array.
--}}
<div data-slot="date-range-picker" class="inline-block" x-modelable="range"
    x-data="{
        range: { start: @js($start), end: @js($end) },
        placeholder: @js($placeholder),
        weekStartsOn: @js((int) $weekStartsOn % 7),
        iso(d) { return [d.getFullYear(), String(d.getMonth() + 1).padStart(2, '0'), String(d.getDate()).padStart(2, '0')].join('-') },
        parse(s) { const [y, m, d] = s.split('-').map(Number); return new Date(y, m - 1, d) },
        fmt(s) { return this.parse(s).toLocaleDateString(@js($locale), @js($format ?? ['month' => 'short', 'day' => 'numeric', 'year' => 'numeric'])) },
        formatted() {
            if (! this.range.start) return this.placeholder;
            return this.range.end && this.range.end !== this.range.start ? `${this.fmt(this.range.start)} – ${this.fmt(this.range.end)}` : this.fmt(this.range.start);
        },
        preset(key) {
            const t = new Date();
            const y = t.getFullYear(), m = t.getMonth(), d = t.getDate();
            const day = (offset) => new Date(y, m, d + offset);
            const weekStart = d - ((t.getDay() - this.weekStartsOn + 7) % 7);
            return {
                today: [t, t], yesterday: [day(-1), day(-1)], last7: [day(-6), t], last14: [day(-13), t], last30: [day(-29), t], last90: [day(-89), t],
                thisWeek: [new Date(y, m, weekStart), new Date(y, m, weekStart + 6)], thisMonth: [new Date(y, m, 1), new Date(y, m + 1, 0)],
                lastMonth: [new Date(y, m - 1, 1), new Date(y, m, 0)], thisYear: [new Date(y, 0, 1), new Date(y, 11, 31)], lastYear: [new Date(y - 1, 0, 1), new Date(y - 1, 11, 31)],
            }[key].map(date => this.iso(date));
        },
        isPreset(key) { const [a, b] = this.preset(key); return this.range.start === a && this.range.end === b },
        apply(key) {
            const [start, end] = this.preset(key);
            this.range = { start, end };
            this.$nextTick(() => this.$root.dispatchEvent(new CustomEvent('change', { bubbles: true, detail: { start, end } })));
        },
    }"
    {{ $attributes->except('class') }}>
    @if ($startName)
        <input type="hidden" name="{{ $startName }}" value="{{ $start }}" :value="range.start ?? ''">
    @endif
    @if ($endName)
        <input type="hidden" name="{{ $endName }}" value="{{ $end }}" :value="range.end ?? ''">
    @endif
    <x-ui.popover x-on:change="if ($event.target.matches('[data-slot=range-calendar]')) close()">
        <x-ui.popover-trigger :class="cn('w-[280px] justify-start text-left font-normal', $class)" x-bind:data-empty="! range.start">
            <x-icons.calendar class="size-4" />
            <span x-text="formatted()" :class="! range.start && 'text-muted-foreground'">{{ $start ? ($end && $end !== $start ? $start.' – '.$end : $start) : $placeholder }}</span>
        </x-ui.popover-trigger>
        <x-ui.popover-content align="start" class="flex w-auto flex-col p-0 sm:flex-row">
            @if ($presets !== [])
                <div role="group" aria-label="Presets" data-slot="date-range-picker-presets" class="flex gap-1 overflow-x-auto border-b p-2 sm:w-36 sm:flex-col sm:border-r sm:border-b-0">
                    @foreach ($presets as $preset)
                        <button type="button" data-preset="{{ $preset['key'] }}" @click="apply(@js($preset['key'])); close()"
                            :aria-pressed="isPreset(@js($preset['key'])).toString()"
                            class="shrink-0 rounded-md px-2 py-1.5 text-left text-sm whitespace-nowrap hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 aria-pressed:bg-accent aria-pressed:font-medium">{{ $preset['label'] }}</button>
                    @endforeach
                </div>
            @endif
            <x-ui.range-calendar x-model="range" :months="$months" :min="$min" :max="$max" :week-starts-on="$weekStartsOn" :locale="$locale" />
        </x-ui.popover-content>
    </x-ui.popover>
</div>
