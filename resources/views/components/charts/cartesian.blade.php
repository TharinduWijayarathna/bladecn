@props([
    'type' => 'line',
    'id' => null,
    'labels' => [],
    'series' => [],
    'colors' => [],
    'height' => 250,
    'curve' => 'smooth',
    'dots' => false,
    'stacked' => false,
    'grid' => true,
    'xAxis' => true,
    'yAxis' => true,
    'legend' => true,
    'tooltip' => true,
    'prefix' => '',
    'suffix' => '',
    'label' => null,
    'class' => '',
])

{{--
    Shared renderer behind <x-charts.line>, <x-charts.bar> and <x-charts.area>.

    Everything is computed here and rendered as SVG on the server. The SVG uses a 0–100 viewBox stretched
    to the plot area (preserveAspectRatio="none") with non-scaling strokes, so it is fluid in width;
    labels, dots and the tooltip are HTML positioned in percentages so they never distort. Colours are
    CSS variables (--color-0, --color-1, … defaulting to the theme's --chart-1…5), so dark mode just works.
--}}
@php
    $id ??= 'chart-'.\Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(8));
    $type = in_array($type, ['line', 'bar', 'area'], true) ? $type : 'line';

    // Series: ['Desktop' => [..]] or [['name' => 'Desktop', 'data' => [..], 'color' => '...'], ...]
    $list = [];
    foreach ((array) $series as $key => $entry) {
        $i = count($list);
        $isEntry = is_array($entry) && array_key_exists('data', $entry);
        $values = $isEntry ? (array) $entry['data'] : (array) $entry;
        $list[] = [
            'name' => (string) ($isEntry ? ($entry['name'] ?? (is_string($key) ? $key : 'Series '.($i + 1))) : (is_string($key) ? $key : 'Series '.($i + 1))),
            'values' => array_values(array_map(fn ($v) => is_numeric($v) ? (float) $v : null, $values)),
            'color' => (string) (($isEntry ? ($entry['color'] ?? null) : null) ?? ($colors[$i] ?? 'var(--chart-'.(($i % 5) + 1).')')),
        ];
    }

    $labels = array_values((array) $labels);
    $count = count($labels);
    foreach ($list as $s) {
        $count = max($count, count($s['values']));
    }
    for ($i = 0; $i < $count; $i++) {
        $labels[$i] = (string) ($labels[$i] ?? ($i + 1));
    }

    // Stacked bars and areas sit on top of the previous series.
    $stack = $stacked && $type !== 'line';
    $bases = [];
    $tops = [];
    $running = array_fill(0, $count, 0.0);
    foreach ($list as $k => $s) {
        for ($i = 0; $i < $count; $i++) {
            $v = $s['values'][$i] ?? null;
            $bases[$k][$i] = $stack ? $running[$i] : 0.0;
            $tops[$k][$i] = $v === null ? ($stack ? $running[$i] : null) : $bases[$k][$i] + $v;
            if ($stack && $v !== null) {
                $running[$i] += $v;
            }
        }
    }

    $all = array_filter(array_merge([], ...array_map(fn ($t) => array_values($t), $tops ?: [[]])), fn ($v) => $v !== null);
    $lo = min(0, $all ? min($all) : 0);
    $hi = max(0, $all ? max($all) : 1);
    if ($hi == $lo) {
        $hi = $lo + 1;
    }

    // "Nice" ticks: about four steps of 1, 2 or 5 × 10^n.
    $nice = function (float $range, bool $round): float {
        if ($range <= 0) {
            return 1;
        }
        $exp = floor(log10($range));
        $f = $range / 10 ** $exp;
        $nf = $round ? ($f < 1.5 ? 1 : ($f < 3 ? 2 : ($f < 7 ? 5 : 10))) : ($f <= 1 ? 1 : ($f <= 2 ? 2 : ($f <= 5 ? 5 : 10)));

        return $nf * 10 ** $exp;
    };
    $step = $nice($nice($hi - $lo, false) / 4, true);
    $lo = floor($lo / $step) * $step;
    $hi = ceil($hi / $step) * $step;
    $ticks = [];
    for ($t = $lo; $t <= $hi + $step / 2; $t += $step) {
        $ticks[] = round($t, 10);
    }

    $num = fn (float $n): string => rtrim(rtrim(number_format($n, 3, '.', ''), '0'), '.') ?: '0';
    $y = fn (?float $v): ?float => $v === null ? null : round(100 - ($v - $lo) / ($hi - $lo) * 100, 3);
    $xs = [];
    for ($i = 0; $i < $count; $i++) {
        $xs[$i] = round($type === 'bar' ? ($i + 0.5) / $count * 100 : ($count === 1 ? 50 : $i / ($count - 1) * 100), 3);
    }
    $compact = function (float $v) use ($prefix, $suffix): string {
        $abs = abs($v);
        $text = $abs >= 1e9 ? round($v / 1e9, 1).'B' : ($abs >= 1e6 ? round($v / 1e6, 1).'M' : ($abs >= 1e4 ? round($v / 1e3, 1).'k' : (string) round($v, 2)));

        return $prefix.$text.$suffix;
    };

    // Path through points; `smooth` is a monotone cubic (no overshoot), `step` steps at the midpoints.
    $path = function (array $pts, bool $move = true) use ($curve, $num): string {
        $n = count($pts);
        if (! $n) {
            return '';
        }
        $d = ($move ? 'M' : 'L').$num($pts[0][0]).' '.$num($pts[0][1]);
        if ($n === 1) {
            return $d;
        }
        if ($curve === 'step') {
            for ($i = 0; $i < $n - 1; $i++) {
                $mid = ($pts[$i][0] + $pts[$i + 1][0]) / 2;
                $d .= ' H'.$num($mid).' V'.$num($pts[$i + 1][1]).' H'.$num($pts[$i + 1][0]);
            }

            return $d;
        }
        if ($curve !== 'smooth' || $n < 3) {
            for ($i = 1; $i < $n; $i++) {
                $d .= ' L'.$num($pts[$i][0]).' '.$num($pts[$i][1]);
            }

            return $d;
        }
        $dx = $s = $t = [];
        for ($i = 0; $i < $n - 1; $i++) {
            $dx[$i] = $pts[$i + 1][0] - $pts[$i][0];
            $s[$i] = $dx[$i] == 0 ? 0 : ($pts[$i + 1][1] - $pts[$i][1]) / $dx[$i];
        }
        $t[0] = $s[0];
        $t[$n - 1] = $s[$n - 2];
        for ($i = 1; $i < $n - 1; $i++) {
            $t[$i] = $s[$i - 1] * $s[$i] <= 0 ? 0
                : 3 * ($dx[$i - 1] + $dx[$i]) / ((2 * $dx[$i] + $dx[$i - 1]) / $s[$i - 1] + ($dx[$i] + 2 * $dx[$i - 1]) / $s[$i]);
        }
        for ($i = 0; $i < $n - 1; $i++) {
            $h = $dx[$i] / 3;
            $d .= ' C'.$num($pts[$i][0] + $h).' '.$num($pts[$i][1] + $t[$i] * $h)
                .' '.$num($pts[$i + 1][0] - $h).' '.$num($pts[$i + 1][1] - $t[$i + 1] * $h)
                .' '.$num($pts[$i + 1][0]).' '.$num($pts[$i + 1][1]);
        }

        return $d;
    };

    // Contiguous runs of non-null points (gaps break the line).
    $segments = function (array $values, array $baseValues = []) use ($count, $xs, $y): array {
        $runs = [];
        $run = [];
        for ($i = 0; $i < $count; $i++) {
            if (($values[$i] ?? null) === null) {
                if ($run) {
                    $runs[] = $run;
                }
                $run = [];

                continue;
            }
            $run[] = ['top' => [$xs[$i], $y($values[$i])], 'base' => [$xs[$i], $y($baseValues[$i] ?? 0.0)]];
        }
        if ($run) {
            $runs[] = $run;
        }

        return $runs;
    };

    $zero = $y(max($lo, min($hi, 0)));
    $shapes = [];
    foreach ($list as $k => $s) {
        if ($type === 'bar') {
            $slot = 100 / max(1, $count);
            $n = max(1, count($list));
            for ($i = 0; $i < $count; $i++) {
                if (($s['values'][$i] ?? null) === null) {
                    continue;
                }
                if ($stack) {
                    $w = $slot * 0.6;
                    $x = $i * $slot + $slot * 0.2;
                } else {
                    $band = $slot * 0.8 / $n;
                    $w = $band * 0.9;
                    $x = $i * $slot + $slot * 0.1 + $k * $band + $band * 0.05;
                }
                $a = $y($tops[$k][$i]);
                $b = $y($bases[$k][$i]);
                $shapes[] = ['kind' => 'rect', 'k' => $k, 'x' => $num($x), 'y' => $num(min($a, $b)), 'w' => $num($w), 'h' => $num(abs($a - $b))];
            }

            continue;
        }

        foreach ($segments($tops[$k], $bases[$k]) as $run) {
            $top = array_column($run, 'top');
            if ($type === 'area') {
                $base = array_reverse(array_column($run, 'base'));
                $fill = $path($top).' '.($stack ? $path($base, false) : 'L'.$num(end($top)[0]).' '.$num($zero).' L'.$num($top[0][0]).' '.$num($zero)).' Z';
                $shapes[] = ['kind' => 'area', 'k' => $k, 'd' => $fill];
            }
            $shapes[] = ['kind' => 'line', 'k' => $k, 'd' => $path($top)];
        }
    }
    // Areas first so every line is drawn on top of every fill.
    usort($shapes, fn ($a, $b) => ($a['kind'] === 'line') <=> ($b['kind'] === 'line'));

    $points = array_map(fn ($k) => array_map(fn ($v) => $y($v), $tops[$k] ?? []), array_keys($list));
    $every = max(1, (int) ceil($count / 12));
    $style = collect($list)->map(fn ($s, $k) => "--color-{$k}: {$s['color']};")->implode(' ');
    $plotClasses = cn(
        'absolute top-2 right-3 touch-pan-y outline-none',
        $xAxis ? 'bottom-7' : 'bottom-2',
        $yAxis ? 'left-12' : 'left-3',
        $tooltip ? 'focus-visible:ring-[3px] focus-visible:ring-ring/50 rounded-sm' : null,
    );
    $alpine = [
        'type' => $type,
        'n' => $count,
        'xs' => $xs,
        'labels' => $labels,
        'prefix' => $prefix,
        'suffix' => $suffix,
        'series' => collect($list)->map(fn ($s, $k) => ['name' => $s['name'], 'color' => "var(--color-{$k})", 'values' => $s['values'], 'ys' => $points[$k]])->all(),
    ];
@endphp

<div id="{{ $id }}" data-slot="chart" data-chart-type="{{ $type }}" role="figure" aria-label="{{ $label ?? ucfirst($type).' chart' }}"
    style="{{ $style }}" {{ $attributes->merge(['class' => cn('flex w-full flex-col gap-3 text-xs', $class)]) }}>
    <div class="relative w-full" style="height: {{ (int) $height }}px;">
        <div data-slot="chart-plot" class="{{ $plotClasses }}"
            @if ($tooltip)
                tabindex="0" aria-label="{{ $label ?? ucfirst($type).' chart' }}: use the arrow keys to read values"
                x-data="{
                    active: null,
                    ...@js($alpine),
                    fmt(v) { return v === null || v === undefined ? '–' : this.prefix + Number(v).toLocaleString() + this.suffix },
                    pick(event) {
                        const rect = event.currentTarget.getBoundingClientRect();
                        const p = Math.min(1, Math.max(0, (event.clientX - rect.left) / rect.width)) * 100;
                        if (this.type === 'bar') { this.active = Math.min(this.n - 1, Math.floor(p / 100 * this.n)); return; }
                        let best = 0;
                        this.xs.forEach((x, i) => { if (Math.abs(x - p) < Math.abs(this.xs[best] - p)) best = i });
                        this.active = best;
                    },
                    key(event) {
                        const last = this.n - 1;
                        const next = { ArrowRight: Math.min(last, (this.active ?? -1) + 1), ArrowLeft: Math.max(0, (this.active ?? 1) - 1), Home: 0, End: last }[event.key];
                        if (next !== undefined) { event.preventDefault(); this.active = next; }
                        else if (event.key === 'Escape') this.active = null;
                    },
                    summary() {
                        return this.active === null ? '' : this.labels[this.active] + ': '
                            + this.series.map(s => s.name + ' ' + this.fmt(s.values[this.active])).join(', ');
                    },
                }"
                @pointermove="pick($event)" @pointerleave="active = null" @focus="active = active ?? 0" @blur="active = null" @keydown="key($event)"
            @endif>

            @if ($yAxis)
                <div aria-hidden="true">
                    @foreach ($ticks as $tick)
                        <span class="absolute right-full -translate-y-1/2 pr-2 text-right whitespace-nowrap tabular-nums text-muted-foreground"
                            style="top: {{ $num($y($tick)) }}%;">{{ $compact($tick) }}</span>
                    @endforeach
                </div>
            @endif

            @if ($tooltip)
                @if ($type === 'bar')
                    <div x-show="active !== null" style="display: none;" aria-hidden="true" class="pointer-events-none absolute inset-y-0 rounded-sm bg-muted/60"
                        :style="{ left: (active ?? 0) * 100 / n + '%', width: 100 / n + '%' }"></div>
                @else
                    <div x-show="active !== null" style="display: none;" aria-hidden="true" class="pointer-events-none absolute inset-y-0 w-px bg-border"
                        :style="{ left: xs[active ?? 0] + '%' }"></div>
                @endif
            @endif

            <svg class="absolute inset-0 size-full overflow-visible" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true" focusable="false">
                @if ($grid)
                    <g data-slot="chart-grid">
                        @foreach ($ticks as $tick)
                            <line x1="0" x2="100" y1="{{ $num($y($tick)) }}" y2="{{ $num($y($tick)) }}" class="stroke-border"
                                stroke-dasharray="3 3" vector-effect="non-scaling-stroke" />
                        @endforeach
                    </g>
                @endif
                @foreach ($shapes as $shape)
                    @if ($shape['kind'] === 'rect')
                        <rect data-series="{{ $shape['k'] }}" x="{{ $shape['x'] }}" y="{{ $shape['y'] }}" width="{{ $shape['w'] }}" height="{{ $shape['h'] }}"
                            style="fill: var(--color-{{ $shape['k'] }});" />
                    @elseif ($shape['kind'] === 'area')
                        <path data-series="{{ $shape['k'] }}" d="{{ $shape['d'] }}" style="fill: var(--color-{{ $shape['k'] }}); fill-opacity: 0.4;" stroke="none" />
                    @else
                        <path data-series="{{ $shape['k'] }}" d="{{ $shape['d'] }}" fill="none" style="stroke: var(--color-{{ $shape['k'] }});" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke" />
                    @endif
                @endforeach
            </svg>

            @if ($dots && $type === 'line')
                <div aria-hidden="true">
                    @foreach ($list as $k => $s)
                        @foreach ($points[$k] as $i => $py)
                            @if ($py !== null)
                                <span class="pointer-events-none absolute size-2 -translate-x-1/2 -translate-y-1/2 rounded-full"
                                    style="left: {{ $num($xs[$i]) }}%; top: {{ $num($py) }}%; background: var(--color-{{ $k }});"></span>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            @endif

            @if ($tooltip && $type !== 'bar')
                <template x-for="s in series" :key="s.name">
                    <span x-show="active !== null && s.ys[active] !== null" aria-hidden="true"
                        class="pointer-events-none absolute size-2.5 -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-background shadow-sm"
                        :style="{ left: xs[active ?? 0] + '%', top: (s.ys[active ?? 0] ?? 0) + '%', background: s.color }"></span>
                </template>
            @endif

            @if ($xAxis)
                <div aria-hidden="true">
                    @foreach ($labels as $i => $text)
                        @if ($i % $every === 0)
                            <span class="absolute top-full mt-2 -translate-x-1/2 whitespace-nowrap text-muted-foreground"
                                style="left: {{ $num($xs[$i]) }}%;">{{ $text }}</span>
                        @endif
                    @endforeach
                </div>
            @endif

            @if ($tooltip)
                <div data-slot="chart-tooltip" x-show="active !== null" style="display: none;" aria-hidden="true"
                    class="pointer-events-none absolute top-0 z-10 grid min-w-32 gap-1.5 rounded-lg border border-border/50 bg-background px-2.5 py-1.5 text-xs text-foreground shadow-xl"
                    :class="xs[active ?? 0] > 55 ? '-translate-x-full -ml-3' : 'ml-3'" :style="{ left: xs[active ?? 0] + '%' }">
                    <div class="font-medium" x-text="labels[active ?? 0]"></div>
                    <template x-for="s in series" :key="s.name">
                        <div class="flex items-center gap-2">
                            <span class="size-2.5 shrink-0 rounded-[2px]" :style="{ background: s.color }"></span>
                            <span class="text-muted-foreground" x-text="s.name"></span>
                            <span class="ml-auto pl-3 font-mono font-medium tabular-nums" x-text="fmt(s.values[active ?? 0])"></span>
                        </div>
                    </template>
                </div>
                <span class="sr-only" aria-live="polite" x-text="summary()"></span>
            @endif
        </div>
    </div>

    @if ($legend && count($list))
        <div data-slot="chart-legend" class="flex flex-wrap items-center justify-center gap-4" aria-hidden="true">
            @foreach ($list as $k => $s)
                <div class="flex items-center gap-1.5">
                    <span class="size-2 shrink-0 rounded-[2px]" style="background: var(--color-{{ $k }});"></span>
                    <span>{{ $s['name'] }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <table class="sr-only">
        <caption>{{ $label ?? ucfirst($type).' chart' }}</caption>
        <thead>
            <tr>
                <th scope="col"><span class="sr-only">Label</span></th>
                @foreach ($list as $s)
                    <th scope="col">{{ $s['name'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($labels as $i => $text)
                <tr>
                    <th scope="row">{{ $text }}</th>
                    @foreach ($list as $s)
                        <td>{{ isset($s['values'][$i]) ? $prefix.number_format($s['values'][$i], (floor($s['values'][$i]) == $s['values'][$i]) ? 0 : 2).$suffix : '–' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
