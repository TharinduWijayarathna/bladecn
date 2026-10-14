<x-ui.stepper orientation="vertical" :current="2" class="max-w-sm" :steps="[
    ['title' => 'Order placed', 'description' => 'We received your order.'],
    ['title' => 'Packed', 'description' => 'Your items are boxed up.'],
    ['title' => 'On the way', 'description' => 'Arriving Thursday.'],
    ['title' => 'Delivered'],
]" />
