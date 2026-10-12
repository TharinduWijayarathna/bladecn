@props([
    'start' => null,
    'end' => null,
    'startName' => null,
    'endName' => null,
    'months' => 2,
    'min' => null,
    'max' => null,
    'weekStartsOn' => 0,
    'locale' => null,
    'class' => '',
])

@php
    $toIso = fn ($date) => $date ? \Illuminate\Support\Carbon::parse($date)->format('Y-m-d') : null;
    $start = $toIso($start);
    $end = $toIso($end);
    if ($start && $end && $end < $start) {
        [$start, $end] = [$end, $start];
    }
    $months = max(1, min(3, (int) $months));
@endphp

{{--
    A date-range calendar showing `months` months side by side (the Calendar's grid and keyboard model).
    The first click sets the start, the second the end. `x-model` binds `{ start, end }` (`Y-m-d` strings);
    `start-name` / `end-name` submit hidden inputs. Dispatches a bubbling `change` with `{ start, end }` once both are set.
--}}
<div data-slot="range-calendar" x-modelable="range"
    x-data="{
        range: { start: @js($start), end: @js($end) },
        min: @js($toIso($min)),
        max: @js($toIso($max)),
        count: @js($months),
        weekStartsOn: @js((int) $weekStartsOn % 7),
        locale: @js($locale ?? str_replace('_', '-', app()->getLocale())),
        view: null,
        focused: null,
        hovered: null,
        iso(d) { return [d.getFullYear(), String(d.getMonth() + 1).padStart(2, '0'), String(d.getDate()).padStart(2, '0')].join('-') },
        parse(s) { const [y, m, d] = s.split('-').map(Number); return new Date(y, m - 1, d) },
        today() { return this.iso(new Date()) },
        init() {
            this.focused = this.range.start || this.clamp(this.today());
            const f = this.parse(this.focused);
            this.view = new Date(f.getFullYear(), f.getMonth(), 1);
            this.$watch('range', r => {
                if (! r?.start) return;
                const d = this.parse(r.start);
                if (! this.shows(d)) this.view = new Date(d.getFullYear(), d.getMonth(), 1);
            });
        },
        clamp(s) { if (this.min && s < this.min) return this.min; if (this.max && s > this.max) return this.max; return s },
        disabled(s) { return (this.min && s < this.min) || (this.max && s > this.max) },
        shows(d) {
            const diff = (d.getFullYear() - this.view.getFullYear()) * 12 + d.getMonth() - this.view.getMonth();
            return diff >= 0 && diff < this.count;
        },
        get weekdays() {
            return Array.from({ length: 7 }, (_, i) => {
                const d = new Date(2024, 0, 7 + ((i + this.weekStartsOn) % 7));
                return { short: d.toLocaleDateString(this.locale, { weekday: 'short' }).slice(0, 2), long: d.toLocaleDateString(this.locale, { weekday: 'long' }) };
            });
        },
        get months() {
            return Array.from({ length: this.count }, (_, m) => {
                const month = new Date(this.view.getFullYear(), this.view.getMonth() + m, 1);
                const first = new Date(month);
                first.setDate(1 - ((first.getDay() - this.weekStartsOn + 7) % 7));
                return {
                    key: this.iso(month),
                    title: month.toLocaleDateString(this.locale, { month: 'long', year: 'numeric' }),
                    weeks: Array.from({ length: 6 }, (_, w) => Array.from({ length: 7 }, (_, d) => {
                        const date = new Date(first.getFullYear(), first.getMonth(), first.getDate() + w * 7 + d);
                        return { iso: this.iso(date), day: date.getDate(), outside: date.getMonth() !== month.getMonth(), label: date.toLocaleDateString(this.locale, { dateStyle: 'full' }) };
                    })),
                };
            });
        },
        get bounds() {
            const { start, end } = this.range;
            if (start && ! end && this.hovered) return this.hovered < start ? [this.hovered, start] : [start, this.hovered];
            return [start, end || start];
        },
        isStart(s) { return !! this.bounds[0] && s === this.bounds[0] },
        isEnd(s) { return !! this.bounds[1] && s === this.bounds[1] },
        inRange(s) { const [a, b] = this.bounds; return !! a && !! b && s > a && s < b },
        month(step) { this.view = new Date(this.view.getFullYear(), this.view.getMonth() + step, 1) },
        canMonth(step) {
            const edge = step > 0 ? new Date(this.view.getFullYear(), this.view.getMonth() + this.count, 1) : new Date(this.view.getFullYear(), this.view.getMonth(), 0);
            return step > 0 ? ! this.max || this.iso(edge) <= this.max : ! this.min || this.iso(edge) >= this.min;
        },
        select(s) {
            if (this.disabled(s)) return;
            const { start, end } = this.range;
            if (! start || end) this.range = { start: s, end: null };
            else this.range = s < start ? { start: s, end: start } : { start, end: s };
            this.focused = s;
            this.hovered = null;
            if (this.range.end) this.$nextTick(() => this.$root.dispatchEvent(new CustomEvent('change', { bubbles: true, detail: { ...this.range } })));
        },
        focusDay(s) {
            this.focused = this.clamp(s);
            const d = this.parse(this.focused);
            if (! this.shows(d)) this.view = d < this.view ? new Date(d.getFullYear(), d.getMonth(), 1) : new Date(d.getFullYear(), d.getMonth() - this.count + 1, 1);
            if (this.range.start && ! this.range.end) this.hovered = this.focused;
            this.$nextTick(() => this.$root.querySelector(`[data-day='${this.focused}']:not([data-outside=true])`)?.focus());
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
    @mouseleave="hovered = null"
    {{ $attributes->merge(['class' => cn('relative w-fit rounded-md bg-background p-3', $class)]) }}>
    @if ($startName)
        <input type="hidden" name="{{ $startName }}" value="{{ $start }}" :value="range?.start ?? ''">
    @endif
    @if ($endName)
        <input type="hidden" name="{{ $endName }}" value="{{ $end }}" :value="range?.end ?? ''">
    @endif
    <button type="button" data-slot="calendar-previous" aria-label="Go to the previous month" @click="month(-1)" :disabled="! canMonth(-1)"
        class="absolute left-3 top-3 z-10 inline-flex size-8 items-center justify-center rounded-md hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50">
        <x-icons.chevron-left class="size-4" />
    </button>
    <button type="button" data-slot="calendar-next" aria-label="Go to the next month" @click="month(1)" :disabled="! canMonth(1)"
        class="absolute right-3 top-3 z-10 inline-flex size-8 items-center justify-center rounded-md hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50">
        <x-icons.chevron-right class="size-4" />
    </button>
    <div class="flex flex-col gap-4 sm:flex-row">
        <template x-for="m in months" :key="m.key">
            <div data-slot="range-calendar-month">
                <div class="flex h-8 items-center justify-center text-sm font-medium" aria-live="polite" x-text="m.title"></div>
                <table role="grid" class="mt-4 border-collapse" :aria-label="m.title">
                    <thead>
                        <tr class="flex">
                            <template x-for="weekday in weekdays" :key="weekday.long">
                                <th scope="col" class="w-8 rounded-md text-[0.8rem] font-normal text-muted-foreground" :aria-label="weekday.long" x-text="weekday.short"></th>
                            </template>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(week, w) in m.weeks" :key="m.key + w">
                            <tr class="mt-2 flex w-full">
                                <template x-for="day in week" :key="day.iso">
                                    <td class="relative size-8 p-0 text-center text-sm" role="gridcell"
                                        :aria-selected="(! day.outside && (isStart(day.iso) || isEnd(day.iso) || inRange(day.iso))).toString()">
                                        <button type="button" data-slot="calendar-day" :data-day="day.iso" :aria-label="day.label"
                                            :tabindex="! day.outside && day.iso === focused ? 0 : -1" :disabled="day.outside || disabled(day.iso)"
                                            :data-outside="day.outside" :data-today="day.iso === today()"
                                            :data-range-start="! day.outside && isStart(day.iso)" :data-range-end="! day.outside && isEnd(day.iso)"
                                            :data-in-range="! day.outside && inRange(day.iso)"
                                            @click="select(day.iso)" @keydown="key($event)" @focus="focused = day.iso" @mouseenter="range.start && ! range.end && (hovered = day.iso)"
                                            x-text="day.day"
                                            class="inline-flex size-8 items-center justify-center rounded-md text-sm font-normal hover:bg-accent hover:text-accent-foreground focus-visible:relative focus-visible:z-10 focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50 data-[outside=true]:invisible data-[today=true]:bg-accent data-[today=true]:text-accent-foreground data-[in-range=true]:rounded-none data-[in-range=true]:bg-accent data-[in-range=true]:text-accent-foreground data-[range-start=true]:rounded-r-none data-[range-end=true]:rounded-l-none data-[range-start=true]:data-[range-end=true]:rounded-md data-[range-start=true]:bg-primary data-[range-start=true]:text-primary-foreground data-[range-end=true]:bg-primary data-[range-end=true]:text-primary-foreground data-[range-start=true]:hover:bg-primary data-[range-end=true]:hover:bg-primary"></button>
                                    </td>
                                </template>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </template>
    </div>
</div>
