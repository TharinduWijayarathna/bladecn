@props([
    'id' => null,
    'labels' => [],
    'series' => [],
    'colors' => [],
    'height' => 250,
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

{{-- Server-rendered SVG bar chart; see charts/cartesian.blade.php for the renderer. --}}
<x-charts.cartesian type="bar" :id="$id" :labels="$labels" :series="$series" :colors="$colors" :height="$height" :stacked="$stacked"
    :grid="$grid" :x-axis="$xAxis" :y-axis="$yAxis" :legend="$legend" :tooltip="$tooltip" :prefix="$prefix" :suffix="$suffix"
    :label="$label" :class="$class" {{ $attributes }} />
