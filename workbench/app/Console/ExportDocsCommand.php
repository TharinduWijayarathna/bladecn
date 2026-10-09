<?php

namespace Workbench\App\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Workbench\App\Docs\Docs;

/**
 * Renders every docs page to static HTML (for GitHub Pages or any static host).
 *
 *   vendor/bin/testbench docs:export --base=/bladecn
 */
class ExportDocsCommand extends Command
{
    protected $signature = 'docs:export
        {--base=/bladecn : Public base path the site will be served from ("/" for a root domain)}
        {--output= : Output directory (defaults to build/docs-site in the package root)}';

    protected $description = 'Export the BladeCN docs as a static site';

    public function handle(Docs $docs, Kernel $kernel): int
    {
        if (! $docs->assetsBuilt()) {
            $this->error('Docs assets are missing. Run `npm install && npm run docs:build` first.');

            return self::FAILURE;
        }

        $output = $this->option('output') ?: $docs->packagePath('build/docs-site');
        $base = '/'.trim((string) $this->option('base'), '/');

        File::deleteDirectory($output);
        File::ensureDirectoryExists($output);

        $prefix = '/'.trim(config('docs.prefix'), '/');
        config(['docs.base_url' => $base === '/' ? '' : $base]);

        $failures = 0;

        foreach ($docs->pages() as $page) {
            $path = $docs->path($page);
            $response = $kernel->handle(Request::create($prefix.($path ? '/'.$path : '')));

            if ($response->getStatusCode() !== 200) {
                $this->error("  ✗ /{$path} returned {$response->getStatusCode()}");

                if (isset($response->exception)) {
                    $this->line('    '.$response->exception->getMessage());
                }
                $failures++;

                continue;
            }

            $target = rtrim($output.'/'.$path, '/').'/index.html';
            File::ensureDirectoryExists(dirname($target));
            File::put($target, $response->getContent());

            $this->line("  ✓ /{$path}");
        }

        File::copyDirectory($docs->distPath(), $output.'/assets');
        File::put($output.'/.nojekyll', '');

        if (is_file($output.'/index.html')) {
            File::copy($output.'/index.html', $output.'/404.html');
        }

        if ($failures > 0) {
            $this->error("{$failures} page(s) failed to render.");

            return self::FAILURE;
        }

        $this->info('Exported '.count($docs->pages())." pages to {$output}");

        return self::SUCCESS;
    }
}
