<?php

namespace BladeCN\BladeCN;

use BladeCN\BladeCN\Commands\BladeCNCommand;
use BladeCN\BladeCN\Commands\InstallBladeCNCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BladeCNServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('bladecn')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_migration_table_name_table')
            ->hasCommand(BladeCNCommand::class)
            ->hasCommand(InstallBladeCNCommand::class);
    }

    public function packageBooted(): void
    {
        $this->registerPackageComponents();
    }

    /**
     * Make every packaged component resolvable as `<x-ui.*>`, `<x-layout.*>`,
     * `<x-icons.*>`, `<x-ai.*>`, `<x-charts.*>` without running `bladecn:install`.
     *
     * Blade resolves a tag in this order: registered aliases, then a class in
     * `App\View\Components`, then an anonymous view in `resources/views/components`,
     * then the anonymous component paths registered here. So:
     *
     * - Class-backed components get a kebab-case alias (`Ui\InputOtpSlot` =>
     *   `ui.input-otp-slot`) unless the app has published its own class, which
     *   then wins through Blade's normal class lookup.
     * - Anonymous components resolve from the package's components directory,
     *   registered as the last fallback, so a published view always wins.
     */
    protected function registerPackageComponents(): void
    {
        foreach (['Ui' => 'ui', 'Layout' => 'layout'] as $namespace => $prefix) {
            foreach (glob(__DIR__."/View/Components/{$namespace}/*.php") ?: [] as $file) {
                $name = basename($file, '.php');
                $class = "BladeCN\\BladeCN\\View\\Components\\{$namespace}\\{$name}";

                if ($this->appHasComponentClass($namespace, $name) || ! class_exists($class)) {
                    continue;
                }

                Blade::component($class, $prefix.'.'.Str::kebab($name));
            }
        }

        Blade::anonymousComponentPath(dirname(__DIR__).'/resources/views/components');
    }

    /**
     * Whether the application has published (or written) its own class for a
     * component, e.g. `App\View\Components\Ui\Button`.
     */
    protected function appHasComponentClass(string $namespace, string $name): bool
    {
        return File::exists(app_path("View/Components/{$namespace}/{$name}.php"));
    }

    public function boot(): void
    {
        parent::boot();

        // Load helpers
        if (file_exists(__DIR__.'/helpers.php')) {
            require_once __DIR__.'/helpers.php';
        }

        // Register authentication routes if they don't exist
        $this->registerAuthRoutes();
    }

    protected function registerAuthRoutes(): void
    {
        // Check if routes are installed in app
        $appRoutesPath = base_path('routes/auth.php');

        if (File::exists($appRoutesPath)) {
            // Routes are installed in app, don't load package routes
            return;
        }

        // Fallback: Load package routes if not installed
        if (! $this->app->routesAreCached()) {
            $authRoutesPath = __DIR__.'/routes/auth.php';

            if (file_exists($authRoutesPath)) {
                $this->loadRoutesFrom($authRoutesPath);
            }
        }
    }
}
