<?php

namespace BladeCN\BladeCN\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallBladeCNCommand extends Command
{
    public $signature = 'bladecn:install {--force : Overwrite files that already exist in your application}';

    public $description = 'Install BladeCN authentication and UI components into your application';

    protected string $packagePath;

    protected string $basePath;

    /**
     * Published file counts: created, updated, unchanged, skipped.
     *
     * @var array<string, int>
     */
    protected array $stats = ['created' => 0, 'updated' => 0, 'unchanged' => 0, 'skipped' => 0];

    /**
     * Files that already existed and were left alone (without --force).
     *
     * @var array<int, string>
     */
    protected array $skipped = [];

    public function handle(): int
    {
        $this->packagePath = dirname(__DIR__, 2);
        $this->basePath = base_path();

        $this->info('🚀 Installing BladeCN Starter Kit...');
        $this->newLine();

        $this->installViews();
        $this->installComponents();
        $this->installControllers();
        $this->installRoutes();
        $this->installHelpers();
        $this->installAssets();

        if (! File::exists(app_path('Models/User.php'))) {
            $this->warn('User model not found. Make sure you have a User model with authentication.');
        }

        $this->newLine();
        $this->info(sprintf(
            '✅ BladeCN installed: %d created, %d updated, %d unchanged, %d skipped.',
            $this->stats['created'],
            $this->stats['updated'],
            $this->stats['unchanged'],
            $this->stats['skipped'],
        ));

        if ($this->skipped !== []) {
            $this->warn('   Some files already existed and were kept. Re-run with --force to overwrite them:');
            foreach (array_slice($this->skipped, 0, 10) as $path) {
                $this->line('   - '.$path);
            }
            if (count($this->skipped) > 10) {
                $this->line('   … and '.(count($this->skipped) - 10).' more');
            }
        }

        $this->newLine();
        $this->info('Available routes: /login, /register, /forgot-password, /reset-password/{token}, /dashboard, /profile, /logout (POST)');
        $this->info('All components are published to resources/views/components (use them as <x-ui.button>, <x-layout.app>, …).');
        $this->newLine();
        $this->info('📝 Next steps:');
        $this->line('  1. <fg=cyan>npm install tailwindcss @tailwindcss/vite tailwindcss-animate alpinejs @alpinejs/focus</>');
        $this->line('  2. <fg=cyan>npm run build</> (or <fg=cyan>npm run dev</>)');
        $this->line('  3. <fg=cyan>composer dump-autoload</>');
        $this->line('  4. Visit <fg=cyan>/login</>');

        return self::SUCCESS;
    }

    protected function installViews(): void
    {
        $this->info('📁 Installing views...');

        $source = $this->packagePath.'/resources/views';
        $destination = $this->basePath.'/resources/views';

        // Auth and settings screens, the dashboard and profile pages, and every
        // component directory (ui, layout, icons, settings, ai, charts, …).
        foreach (['auth', 'settings', 'components'] as $directory) {
            $this->publishDirectory($source.'/'.$directory, $destination.'/'.$directory);
        }

        foreach (['dashboard.blade.php', 'profile.blade.php'] as $file) {
            $this->publish($source.'/'.$file, $destination.'/'.$file);
        }

        $this->info('   ✓ Views installed to resources/views/');
    }

    protected function installComponents(): void
    {
        $this->info('🧩 Installing component classes...');

        foreach (['Ui' => 'ui', 'Layout' => 'layout'] as $namespace => $prefix) {
            $source = $this->packagePath.'/src/View/Components/'.$namespace;
            $destination = $this->basePath.'/app/View/Components/'.$namespace;

            foreach (File::files($source) as $file) {
                $this->publish($file->getPathname(), $destination.'/'.$file->getFilename(), function (string $content) use ($namespace, $prefix) {
                    $content = str_replace(
                        'namespace BladeCN\\BladeCN\\View\\Components\\'.$namespace.';',
                        'namespace App\\View\\Components\\'.$namespace.';',
                        $content
                    );

                    return (string) preg_replace(
                        "/view\\('bladecn::components\\.{$prefix}\\.([^']+)'\\)/",
                        "view('components.{$prefix}.$1')",
                        $content
                    );
                });
            }
        }

        $this->info('   ✓ Component classes installed to app/View/Components/');
    }

    protected function installControllers(): void
    {
        $this->info('🎮 Installing controllers...');

        $source = $this->packagePath.'/src/Http/Controllers';
        $destination = $this->basePath.'/app/Http/Controllers';

        foreach (File::allFiles($source) as $file) {
            $relative = $file->getRelativePathname();

            $this->publish($file->getPathname(), $destination.'/'.$relative, function (string $content) {
                $content = str_replace('namespace BladeCN\\BladeCN\\Http\\Controllers', 'namespace App\\Http\\Controllers', $content);

                return (string) preg_replace("/view\\('bladecn::(auth\\.[^']+)'/", "view('$1'", $content);
            });
        }

        $this->info('   ✓ Controllers installed to app/Http/Controllers/');
    }

    protected function installRoutes(): void
    {
        $this->info('🛣️  Installing routes...');

        $this->publish(
            $this->packagePath.'/src/routes/auth.php',
            $this->basePath.'/routes/auth.php',
            fn (string $content) => str_replace('BladeCN\\BladeCN\\Http\\Controllers\\', 'App\\Http\\Controllers\\', $content)
        );

        // routes/web.php exists in every new Laravel app, so ask before replacing it.
        $this->publish($this->packagePath.'/src/routes/web.php', $this->basePath.'/routes/web.php', replaceable: true);
    }

    protected function installHelpers(): void
    {
        $this->info('🔧 Installing helpers...');

        $this->publish($this->packagePath.'/src/helpers.php', $this->basePath.'/app/helpers.php');

        $composerPath = $this->basePath.'/composer.json';

        if (! File::exists($composerPath)) {
            return;
        }

        $composer = json_decode(File::get($composerPath), true);

        if (! is_array($composer)) {
            $this->warn('   ⚠️  Could not parse composer.json; add "app/helpers.php" to autoload.files yourself.');

            return;
        }

        $files = $composer['autoload']['files'] ?? [];

        if (! in_array('app/helpers.php', $files, true)) {
            $composer['autoload']['files'] = [...$files, 'app/helpers.php'];
            File::put($composerPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");
            $this->info('   ✓ Added app/helpers.php to composer.json autoload (run composer dump-autoload)');
        }
    }

    protected function installAssets(): void
    {
        $this->info('🎨 Installing assets (CSS & JS)...');

        // Both files exist in every new Laravel app, so ask before replacing them.
        $this->publish($this->packagePath.'/resources/css/app.css', $this->basePath.'/resources/css/app.css', replaceable: true);
        $this->publish($this->packagePath.'/resources/js/app.js', $this->basePath.'/resources/js/app.js', replaceable: true);

        $packageJsonPath = $this->basePath.'/package.json';
        $required = ['tailwindcss', '@tailwindcss/vite', 'tailwindcss-animate', 'alpinejs', '@alpinejs/focus'];
        $installed = [];

        if (File::exists($packageJsonPath)) {
            $packageJson = json_decode(File::get($packageJsonPath), true) ?: [];
            $installed = array_keys(array_merge($packageJson['dependencies'] ?? [], $packageJson['devDependencies'] ?? []));
        }

        $missing = array_values(array_diff($required, $installed));

        if ($missing !== []) {
            $this->warn('   ⚠️  Missing npm packages: '.implode(', ', $missing));
            $this->line('      <fg=cyan>npm install '.implode(' ', $missing).'</>');
        } else {
            $this->info('   ✓ All required npm packages are installed');
        }
    }

    protected function publishDirectory(string $source, string $destination): void
    {
        if (! File::isDirectory($source)) {
            return;
        }

        foreach (File::allFiles($source) as $file) {
            $this->publish($file->getPathname(), $destination.'/'.$file->getRelativePathname());
        }
    }

    /**
     * Copy one file into the application.
     *
     * Existing files are only overwritten with --force. `$replaceable` files
     * (the app's CSS/JS entry points and routes/web.php, which every new Laravel
     * app ships with) are replaced after confirmation, defaulting to yes.
     *
     * @param  (callable(string): string)|null  $transform
     */
    protected function publish(string $source, string $destination, ?callable $transform = null, bool $replaceable = false): void
    {
        if (! File::exists($source)) {
            $this->warn('   ⚠️  Source file not found: '.$source);

            return;
        }

        $content = File::get($source);
        $content = $transform ? $transform($content) : $content;
        $relative = ltrim(str_replace($this->basePath, '', $destination), '/\\');

        if (File::exists($destination)) {
            if (File::get($destination) === $content) {
                $this->stats['unchanged']++;

                return;
            }

            $overwrite = $this->option('force')
                || ($replaceable && $this->confirm("{$relative} already exists. Replace it with the BladeCN version?", true));

            if (! $overwrite) {
                $this->stats['skipped']++;
                $this->skipped[] = $relative;

                return;
            }

            File::put($destination, $content);
            $this->stats['updated']++;

            return;
        }

        File::ensureDirectoryExists(dirname($destination));
        File::put($destination, $content);
        $this->stats['created']++;
    }
}
