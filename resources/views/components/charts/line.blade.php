@props([
    'id' => null,
    'labels' => [],
    'series' => [],
    'colors' => [],
    'height' => 250,
    'curve' => 'smooth',
    'dots' => false,
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

{{-- Server-rendered SVG line chart; see charts/cartesian.blade.php for the renderer. --}}
<x-charts.cartesian type="line" :id="$id" :labels="$labels" :series="$series" :colors="$colors" :height="$height" :curve="$curve" :dots="$dots"
    :grid="$grid" :x-axis="$xAxis" :y-axis="$yAxis" :legend="$legend" :tooltip="$tooltip" :prefix="$prefix" :suffix="$suffix"
    :label="$label" :class="$class" {{ $attributes }} />
