<?php

namespace Workbench\App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
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
        $this->registerPackageComponents();

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'docs');

        // Docs pages are served without the session middleware, so share an
        // empty error bag for components that read `$errors`.
        View::share('errors', new ViewErrorBag);

        Route::middleware([])->group(__DIR__.'/../../routes/docs.php');

        if ($this->app->runningInConsole()) {
            $this->commands([ExportDocsCommand::class]);
        }
    }

    /**
     * Register every component shipped in the package so the docs render the
     * real source files from `resources/views/components` and
     * `src/View/Components`, exactly the files `bladecn:install` publishes.
     *
     * - Class-backed components are registered under their kebab-case tag
     *   (`BladeCN\...\Ui\InputOtpSlot` => `<x-ui.input-otp-slot>`).
     * - Everything else (anonymous components, icons, ai, charts) resolves
     *   through the package's components directory as an anonymous path.
     */
    protected function registerPackageComponents(): void
    {
        $packagePath = dirname(__DIR__, 3);

        foreach (['Ui' => 'ui', 'Layout' => 'layout'] as $namespace => $prefix) {
            foreach (glob($packagePath."/src/View/Components/{$namespace}/*.php") ?: [] as $file) {
                $name = basename($file, '.php');
                $class = "BladeCN\\BladeCN\\View\\Components\\{$namespace}\\{$name}";

                if (class_exists($class)) {
                    Blade::component($class, $prefix.'.'.Str::kebab($name));
                }
            }
        }

        Blade::anonymousComponentPath($packagePath.'/resources/views/components');
    }
}
