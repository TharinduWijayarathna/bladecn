<x-ui.card x-data="calendarComponent({{ json_encode($events) }})" x-init="init()">
    {{-- Calendar Header --}}
    <x-ui.card-header class="gap-2 border-b max-sm:grid-cols-1">
        <div class="flex items-center gap-2 sm:gap-3">
            <div class="flex flex-col items-center justify-center rounded-md border border-input">
                <span
                    class="text-[10px] font-medium text-muted-foreground uppercase tracking-widest bg-muted px-2 py-1 rounded-t-md sm:px-3 sm:py-1.5"
                    x-text="monthShort"></span>
                <span class="text-base sm:text-lg font-semibold text-foreground leading-none px-2 py-1 sm:px-3 sm:py-1.5"
                    x-text="dayNum"></span>
            </div>
            <div>
                <x-ui.card-title class="flex items-center gap-1 sm:gap-2">
                    <h2 x-text="headerText"></h2>
                    <span
                        class="rounded bg-muted px-1 py-0.5 sm:px-1.5 text-[10px] sm:text-xs font-medium text-muted-foreground"
                        x-text="'Week '+weekNum"></span>
                </x-ui.card-title>
                <x-ui.card-description x-text="calendarRange"></x-ui.card-description>
            </div>
        </div>

        <x-ui.card-action class="flex flex-row flex-wrap items-stretch sm:flex-nowrap sm:items-center gap-2 max-sm:col-start-1 max-sm:row-span-1 max-sm:row-start-auto max-sm:justify-self-stretch">
            <div class="flex items-center gap-1">
                <x-ui.button variant="outline" size="icon" @click="prevMonth">
                    <x-icons.chevron-left />
                </x-ui.button>
                <x-ui.button variant="outline" @click="goToday">Today</x-ui.button>
                <x-ui.button variant="outline" size="icon" @click="nextMonth">
                    <x-icons.chevron-right />
                </x-ui.button>
            </div>

            <div class="min-w-[9rem] flex-1 sm:flex-none [&>div]:w-full">
                <x-ui.native-select aria-label="Select view" x-model.change="view" @change="renderCalendar()"
                    class="w-full sm:w-auto">
                    <option value="month">Month view</option>
                    <option value="week">Week view</option>
                </x-ui.native-select>
            </div>
        </x-ui.card-action>
    </x-ui.card-header>

    {{-- Weekday Headers --}}
    <div
        class="hidden sm:grid grid-cols-7 border-b border-border/70 text-center text-xs sm:text-sm text-muted-foreground/70 px-6">
        <template x-for="day in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']" :key="day">
            <div x-text="day" class="p-1 sm:p-2"></div>
        </template>
    </div>


    {{-- Calendar Grid --}}
    <x-ui.card-content class="flex flex-col gap-1 sm:gap-2">
        <template x-for="(week, weekIndex) in weeks" :key="weekIndex">
            <div class="grid grid-cols-1 sm:grid-cols-7 gap-1 sm:gap-2">
                <template x-for="(day, dayIndex) in week" :key="day.date">
                    <div class="flex flex-col sm:block items-start sm:items-stretch p-2 sm:p-1 min-h-[80px] sm:min-h-[120px] border border-border rounded-lg"
                        :class="{ 'bg-muted/25 text-muted-foreground/70': !day.currentMonth }">
                        {{-- Mobile: weekday label before each day --}}
                        <div class="block sm:hidden text-[11px] font-medium text-muted-foreground mb-1"
                            x-text="['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][dayIndex]">
                        </div>

                        {{-- Day number --}}
                        <span
                            class="text-sm font-semibold mb-1 inline-flex items-center justify-center w-6 h-6 sm:w-7 sm:h-7"
                            :class="day.isToday ?
                                'bg-primary text-primary-foreground rounded-full' :
                                ''"
                            x-text="day.dayNum">
                        </span>

                        {{-- Events --}}
                        <div class="flex flex-col gap-1 mt-2 overflow-hidden w-full">
                            <template x-for="event in day.events.slice(0,3)" :key="event.id">
                                {{-- Event Modal --}}
                                <x-ui.dialog>
                                    <x-ui.dialog-trigger>
                                        <button class="calendar-event w-full min-w-0 max-w-full p-1 rounded-md text-left text-sm truncate"
                                            :class="'event-' + event.color" :title="event.title">
                                            <div class="font-medium truncate" x-text="event.title"></div>
                                            <div class="truncate text-[0.7rem] text-muted-foreground"
                                                x-text="event.allDay ? 'All day' : event.time"></div>
                                        </button>
                                    </x-ui.dialog-trigger>
                                    <x-ui.dialog-overlay />
                                    <x-ui.dialog-content>
                                        <x-ui.dialog-header>
                                            <x-ui.dialog-title x-text="event.title">
                                            </x-ui.dialog-title>
                                            <x-ui.dialog-description x-text="event.description">
                                            </x-ui.dialog-description>
                                        </x-ui.dialog-header>
                                        <div class="grid gap-4">
                                            <div
                                                class="space-y-2 rounded-md border border-input bg-muted/30 p-3 text-sm">
                                                <div class="flex items-start gap-2">
                                                    <x-icons.calendar-check
                                                        class="h-4 w-4 text-muted-foreground shrink-0 mt-[2px]" />
                                                    <p class="text-foreground"
                                                        x-text="event.allDay ? 'Time: All day' : 'Time: ' + event.time">
                                                    </p>
                                                </div>
                                                <div class="flex items-start gap-2">
                                                    <x-icons.pin
                                                        class="h-4 w-4 text-muted-foreground shrink-0 mt-[2px]" />
                                                    <p class="text-foreground" x-text="event.location"></p>
                                                </div>
                                            </div>
                                        </div>

                                        <x-ui.dialog-footer class="sm:justify-start">
                                            <x-ui.dialog-close>
                                                <x-ui.button variant="secondary" type="button">
                                                    Close
                                                </x-ui.button>
                                            </x-ui.dialog-close>
                                        </x-ui.dialog-footer>
                                    </x-ui.dialog-content>
                                </x-ui.dialog>
                            </template>

                            <template x-if="day.events.length > 3">
                                <button class="text-xs text-muted-foreground truncate">
                                    + <span x-text="day.events.length - 3"></span> more
                                </button>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </x-ui.card-content>
</x-ui.card>

@push('styles')
    <style>
        .calendar-event {
            position: relative;
            display: block;
            padding: 0.5rem 0.5rem 0.5rem 1.5rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            cursor: pointer;
            text-align: left;
            overflow: hidden;
            transition: all 0.15s ease-in-out;
        }

        .calendar-event::after {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0.25rem;
            bottom: 0.25rem;
            width: 0.25rem;
            border-radius: 9999px;
            background-color: currentColor;
        }

        .event-sky {
            background-color: rgba(224, 242, 254, 0.8);
            color: rgb(12, 74, 110);
        }

        .dark .event-sky {
            background-color: rgba(56, 189, 248, 0.25);
            color: rgb(186, 230, 253);
        }

        .event-amber {
            background-color: rgba(254, 243, 199, 0.8);
            color: rgb(120, 53, 15);
        }

        .dark .event-amber {
            background-color: rgba(251, 191, 36, 0.25);
            color: rgb(253, 230, 138);
        }

        .event-emerald {
            background-color: rgba(209, 250, 229, 0.8);
            color: rgb(6, 95, 70);
        }

        .dark .event-emerald {
            background-color: rgba(52, 211, 153, 0.25);
            color: rgb(167, 243, 208);
        }

        .event-rose {
            background-color: rgba(254, 205, 211, 0.8);
            color: rgb(136, 19, 55);
        }

        .dark .event-rose {
            background-color: rgba(251, 113, 133, 0.25);
            color: rgb(255, 228, 230);
        }

        .event-indigo {
            background-color: rgba(224, 231, 255, 0.8);
            color: rgb(49, 46, 129);
        }

        .dark .event-indigo {
            background-color: rgba(129, 140, 248, 0.25);
            color: rgb(199, 210, 254);
        }

        .event-fuchsia {
            background-color: rgba(250, 232, 255, 0.8);
            color: rgb(112, 26, 117);
        }

        .dark .event-fuchsia {
            background-color: rgba(232, 121, 249, 0.25);
            color: rgb(250, 232, 255);
        }

        .event-primary {
            background-color: rgba(239, 246, 255, 0.8);
            color: rgb(30, 58, 138);
        }

        .dark .event-primary {
            background-color: rgba(59, 130, 246, 0.25);
            color: rgb(219, 234, 254);
        }
    </style>
@endpush

@push('scripts')
    <script>
        function calendarComponent(events) {
            return {
                events,
                currentDate: new Date(),
                view: 'month',
                weeks: [],
                headerText: '',
                monthShort: '',
                dayNum: '',
                weekNum: 1,
                calendarRange: '',

                init() {
                    this.renderCalendar();
                },

                formatTime(date) {
                    const d = new Date(date);
                    const h = d.getHours(),
                        m = d.getMinutes();
                    const ampm = h >= 12 ? 'pm' : 'am';
                    const h12 = h % 12 || 12;
                    return m === 0 ? `${h12}${ampm}` : `${h12}:${String(m).padStart(2,'0')}${ampm}`;
                },

                renderCalendar() {
                    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                    let start, end;

                    if (this.view === 'month') {
                        const firstDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), 1);
                        const lastDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 0);
                        start = new Date(firstDay);
                        start.setDate(start.getDate() - start.getDay());
                        end = new Date(lastDay);
                        end.setDate(end.getDate() + (6 - end.getDay()));
                        this.headerText = `${monthNames[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;
                        this.calendarRange =
                            `${monthNames[firstDay.getMonth()]} ${firstDay.getDate()} - ${monthNames[lastDay.getMonth()]} ${lastDay.getDate()}`;
                    } else {
                        const day = this.currentDate.getDay();
                        start = new Date(this.currentDate);
                        start.setDate(this.currentDate.getDate() - day);
                        end = new Date(start);
                        end.setDate(start.getDate() + 6);
                        this.headerText =
                            `${monthNames[start.getMonth()]} ${start.getDate()} - ${monthNames[end.getMonth()]} ${end.getDate()}`;
                        this.calendarRange = this.headerText;
                    }

                    this.weeks = [];
                    let week = [];
                    let current = new Date(start);
                    while (current <= end) {
                        const dayEvents = this.events.filter(ev => {
                            const startEv = new Date(ev.start),
                                endEv = ev.end ? new Date(ev.end) : startEv;
                            const dayStart = new Date(current);
                            dayStart.setHours(0, 0, 0, 0);
                            const dayEnd = new Date(current);
                            dayEnd.setHours(23, 59, 59, 999);
                            if (startEv <= dayEnd && endEv >= dayStart) {
                                ev.time = ev.allDay ? 'All day' :
                                    `${this.formatTime(ev.start)} - ${this.formatTime(ev.end)}`;
                                return true;
                            }
                            return false;
                        });

                        week.push({
                            date: current.toISOString(),
                            dayNum: current.getDate(),
                            isToday: current.toDateString() === new Date().toDateString(),
                            currentMonth: current.getMonth() === this.currentDate.getMonth(),
                            events: dayEvents
                        });

                        if (week.length === 7) {
                            this.weeks.push(week);
                            week = [];
                        }
                        current.setDate(current.getDate() + 1);
                        if (this.view === 'week' && week.length === 7) break;
                    }

                    const today = new Date();
                    this.monthShort = monthNames[today.getMonth()];
                    this.dayNum = today.getDate();
                    const startOfYear = new Date(today.getFullYear(), 0, 1);
                    this.weekNum = Math.ceil(((today - startOfYear) / 86400000 + startOfYear.getDay() + 1) / 7);
                },

                goToday() {
                    this.currentDate = new Date();
                    this.renderCalendar();
                },
                prevMonth() {
                    this.view === 'month' ? this.currentDate.setMonth(this.currentDate.getMonth() - 1) : this.currentDate
                        .setDate(this.currentDate.getDate() - 7);
                    this.renderCalendar();
                },
                nextMonth() {
                    this.view === 'month' ? this.currentDate.setMonth(this.currentDate.getMonth() + 1) : this.currentDate
                        .setDate(this.currentDate.getDate() + 7);
                    this.renderCalendar();
                },
            }
        }
    </script>
@endpush
