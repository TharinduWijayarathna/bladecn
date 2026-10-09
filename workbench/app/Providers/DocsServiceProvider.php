<?php

namespace Workbench\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\ViewErrorBag;
use Workbench\App\Console\ExportDocsCommand;
use Workbench\App\Docs\Docs;

/**
 * Boots the BladeCN documentation site.
 *
 * This provider only lives in the package's `workbench/` directory, which is
 * autoload-dev and export-ignored, so it is never shipped to, or registered in,
 * a consuming application.
 */
class DocsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/docs.php', 'docs');

        $this->app->singleton(Docs::class, fn () => new Docs(
            pages: require __DIR__.'/../../resources/docs/pages.php',
            packagePath: dirname(__DIR__, 3),
            workbenchPath: dirname(__DIR__, 2),
        ));
    }

    public function boot(): void
    {
        // Components (<x-ui.*>, <x-layout.*>, <x-icons.*>, …) are registered by the package's own
        // BladeCNServiceProvider, exactly as in a consuming app, so the docs prove they resolve.

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'docs');

        // Docs pages are served without the session middleware, so share an
        // empty error bag for components that read `$errors`.
        View::share('errors', new ViewErrorBag);

        Route::middleware([])->group(__DIR__.'/../../routes/docs.php');

        if ($this->app->runningInConsole()) {
            $this->commands([ExportDocsCommand::class]);
        }
    }
}
