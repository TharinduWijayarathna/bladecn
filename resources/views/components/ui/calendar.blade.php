@props([
    'value' => null,
    'name' => null,
    'min' => null,
    'max' => null,
    'weekStartsOn' => 0,
    'locale' => null,
    'class' => '',
])

@php
    $toIso = fn ($date) => $date ? \Illuminate\Support\Carbon::parse($date)->format('Y-m-d') : null;
    $value = $toIso($value);
@endphp

{{-- A single-date calendar. Dates are `Y-m-d` strings; works with `x-model` and submits through `name`. --}}
<div data-slot="calendar" x-modelable="value"
    x-data="{
        value: @js($value),
        min: @js($toIso($min)),
        max: @js($toIso($max)),
        weekStartsOn: @js((int) $weekStartsOn % 7),
        locale: @js($locale ?? str_replace('_', '-', app()->getLocale())),
        view: null,
        focused: null,
        iso(d) { return [d.getFullYear(), String(d.getMonth() + 1).padStart(2, '0'), String(d.getDate()).padStart(2, '0')].join('-') },
        parse(s) { const [y, m, d] = s.split('-').map(Number); return new Date(y, m - 1, d) },
        today() { return this.iso(new Date()) },
        init() {
            this.focused = this.value || this.clamp(this.today());
            const f = this.parse(this.focused);
            this.view = new Date(f.getFullYear(), f.getMonth(), 1);
            this.$watch('value', v => { if (v) { this.focused = v; const d = this.parse(v); this.view = new Date(d.getFullYear(), d.getMonth(), 1) } });
        },
        clamp(s) { if (this.min && s < this.min) return this.min; if (this.max && s > this.max) return this.max; return s },
        disabled(s) { return (this.min && s < this.min) || (this.max && s > this.max) },
        get title() { return this.view.toLocaleDateString(this.locale, { month: 'long', year: 'numeric' }) },
        get weekdays() {
            return Array.from({ length: 7 }, (_, i) => {
                const d = new Date(2024, 0, 7 + ((i + this.weekStartsOn) % 7));
                return { short: d.toLocaleDateString(this.locale, { weekday: 'short' }).slice(0, 2), long: d.toLocaleDateString(this.locale, { weekday: 'long' }) };
            });
        },
        get weeks() {
            const first = new Date(this.view);
            first.setDate(1 - ((first.getDay() - this.weekStartsOn + 7) % 7));
            return Array.from({ length: 6 }, (_, w) => Array.from({ length: 7 }, (_, d) => {
                const date = new Date(first.getFullYear(), first.getMonth(), first.getDate() + w * 7 + d);
                return { iso: this.iso(date), day: date.getDate(), outside: date.getMonth() !== this.view.getMonth(), label: date.toLocaleDateString(this.locale, { dateStyle: 'full' }) };
            }));
        },
        month(step) { this.view = new Date(this.view.getFullYear(), this.view.getMonth() + step, 1) },
        canMonth(step) {
            const edge = new Date(this.view.getFullYear(), this.view.getMonth() + step + (step > 0 ? 0 : 1), step > 0 ? 1 : 0);
            return step > 0 ? ! this.max || this.iso(edge) <= this.max : ! this.min || this.iso(edge) >= this.min;
        },
        select(s) {
            if (this.disabled(s)) return;
            this.value = s;
            this.focused = s;
            this.$nextTick(() => this.$root.dispatchEvent(new CustomEvent('change', { bubbles: true, detail: { value: s } })));
        },
        focusDay(s) {
            this.focused = this.clamp(s);
            const d = this.parse(this.focused);
            if (d.getMonth() !== this.view.getMonth() || d.getFullYear() !== this.view.getFullYear()) this.view = new Date(d.getFullYear(), d.getMonth(), 1);
            this.$nextTick(() => this.$root.querySelector(`[data-day='${this.focused}']`)?.focus());
        },
        key(event) {
            const d = this.parse(this.focused);
            const shift = { ArrowLeft: -1, ArrowRight: 1, ArrowUp: -7, ArrowDown: 7 }[event.key];
            if (shift) d.setDate(d.getDate() + shift);
            else if (event.key === 'PageUp' || event.key === 'PageDown') d.setMonth(d.getMonth() + (event.key === 'PageUp' ? -1 : 1) * (event.shiftKey ? 12 : 1));
            else if (event.key === 'Home') d.setDate(d.getDate() - ((d.getDay() - this.weekStartsOn + 7) % 7));
            else if (event.key === 'End') d.setDate(d.getDate() + 6 - ((d.getDay() - this.weekStartsOn + 7) % 7));
            else if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); this.select(this.focused); return; }
            else return;
            event.preventDefault();
            this.focusDay(this.iso(d));
        },
    }"
    {{ $attributes->merge(['class' => cn('w-fit rounded-md bg-background p-3', $class)]) }}>
    @if ($name)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}" :value="value ?? ''">
    @endif
    <div class="relative flex h-8 items-center justify-center">
        <button type="button" data-slot="calendar-previous" aria-label="Go to the previous month" @click="month(-1)" :disabled="! canMonth(-1)"
            class="absolute left-0 inline-flex size-8 items-center justify-center rounded-md hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50">
            <x-icons.chevron-left class="size-4" />
        </button>
        <div class="text-sm font-medium" aria-live="polite" x-text="title"></div>
        <button type="button" data-slot="calendar-next" aria-label="Go to the next month" @click="month(1)" :disabled="! canMonth(1)"
            class="absolute right-0 inline-flex size-8 items-center justify-center rounded-md hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50">
            <x-icons.chevron-right class="size-4" />
        </button>
    </div>
    <table role="grid" class="mt-4 w-full border-collapse" :aria-label="title">
        <thead>
            <tr class="flex">
                <template x-for="weekday in weekdays" :key="weekday.long">
                    <th scope="col" class="w-8 rounded-md text-[0.8rem] font-normal text-muted-foreground" :aria-label="weekday.long" x-text="weekday.short"></th>
                </template>
            </tr>
        </thead>
        <tbody>
            <template x-for="(week, w) in weeks" :key="w">
                <tr class="mt-2 flex w-full">
                    <template x-for="day in week" :key="day.iso">
                        <td class="relative size-8 p-0 text-center text-sm" role="gridcell" :aria-selected="(day.iso === value).toString()">
                            <button type="button" data-slot="calendar-day" :data-day="day.iso" :aria-label="day.label"
                                :tabindex="day.iso === focused ? 0 : -1" :data-autofocus="day.iso === focused" :disabled="disabled(day.iso)"
                                :data-selected="day.iso === value" :data-today="day.iso === today()" :data-outside="day.outside"
                                @click="select(day.iso)" @keydown="key($event)" @focus="focused = day.iso" x-text="day.day"
                                class="inline-flex size-8 items-center justify-center rounded-md text-sm font-normal hover:bg-accent hover:text-accent-foreground focus-visible:relative focus-visible:z-10 focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50 data-[outside=true]:text-muted-foreground data-[today=true]:bg-accent data-[today=true]:text-accent-foreground data-[selected=true]:bg-primary data-[selected=true]:text-primary-foreground data-[selected=true]:hover:bg-primary"></button>
                        </td>
                    </template>
                </tr>
            </template>
        </tbody>
    </table>
</div>
