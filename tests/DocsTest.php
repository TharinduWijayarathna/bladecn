<?php

use Workbench\App\Docs\Docs;
use Workbench\App\Docs\PropsExtractor;
use Workbench\App\Providers\DocsServiceProvider;

/*
 * Smoke tests for the documentation site in workbench/.
 * They render every page through the real package components and keep the
 * docs registry honest (coverage, examples, props).
 */

beforeEach(function () {
    $this->app->register(DocsServiceProvider::class);
});

it('renders every docs page without errors', function () {
    $docs = app(Docs::class);

    foreach ($docs->pages() as $page) {
        $path = $docs->path($page);
        $response = $this->get('/docs'.($path ? '/'.$path : ''));

        expect($response->status())->toBe(200, "/docs/{$path} returned {$response->status()}");
        $response->assertSee($page['title']);
    }
});

it('returns 404 for unknown pages', function () {
    $this->get('/docs/components/does-not-exist')->assertNotFound();
    $this->get('/docs/does-not-exist')->assertNotFound();
});

it('documents every ui component file', function () {
    $documented = collect(app(Docs::class)->componentPages())
        ->flatMap(fn ($page) => $page['components'])
        ->all();

    $files = collect(glob(dirname(__DIR__).'/resources/views/components/ui/*.blade.php'))
        ->map(fn ($file) => 'ui.'.basename($file, '.blade.php'));

    $missing = $files->reject(fn ($tag) => in_array($tag, $documented, true))->values()->all();

    expect($missing)->toBe([]);
});

it('has a blade file for every example and no orphaned example files', function () {
    $docs = app(Docs::class);
    $registered = [];

    foreach ($docs->componentPages() as $slug => $page) {
        foreach (array_keys($page['examples']) as $key) {
            $file = $docs->exampleFile($slug, $key);
            expect(is_file($file))->toBeTrue("Missing example file {$file}");
            $registered[] = realpath($file);
        }
    }

    $files = array_map('realpath', glob(dirname(__DIR__).'/workbench/resources/views/examples/*/*.blade.php'));

    expect(array_values(array_diff($files, $registered)))->toBe([]);
});

it('only describes props that exist in the component source', function () {
    $docs = app(Docs::class);

    foreach ($docs->pages() as $page) {
        foreach ($docs->props($page) as $table) {
            $actual = array_column($table['props'], 'name');
            $described = array_keys($page['props'][$table['tag']] ?? []);

            expect(array_values(array_diff($described, $actual)))
                ->toBe([], "Unknown props documented for <x-{$table['tag']}>");
        }
    }
});

it('reads props from component classes and @props', function () {
    $extractor = new PropsExtractor(dirname(__DIR__));

    $button = collect($extractor->extract('ui.button')['props'])->keyBy('name');
    expect($button->keys()->all())->toBe(['variant', 'size', 'tag', 'class'])
        ->and($button['variant']['default'])->toBe("'default'")
        ->and($button['variant']['type'])->toBe('string');

    $payment = collect($extractor->extract('ui.payment-card')['props'])->keyBy('name');
    expect($payment['card-number']['required'])->toBeTrue()
        ->and($payment['card-type']['default'])->toBe("'visa'");
});
