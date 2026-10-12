<x-ui.tree-view label="Project files" :expanded="['app', 'resources']" selected="routes" class="w-64" :items="[
    ['id' => 'app', 'label' => 'app', 'children' => [
        ['id' => 'http', 'label' => 'Http', 'children' => [
            ['id' => 'controllers', 'label' => 'Controllers'],
            ['id' => 'middleware', 'label' => 'Middleware'],
        ]],
        ['id' => 'models', 'label' => 'Models'],
    ]],
    ['id' => 'resources', 'label' => 'resources', 'children' => [
        ['id' => 'views', 'label' => 'views'],
        ['id' => 'css', 'label' => 'css'],
    ]],
    ['id' => 'routes', 'label' => 'routes'],
    ['id' => 'composer', 'label' => 'composer.json'],
]" />
