<?php

namespace BladeCN\BladeCN\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallBladeCNCommand extends Command
{
    public $signature = 'bladecn:install {--force : Overwrite existing files}';

    public $description = 'Install BladeCN authentication and UI components into your application';

    protected $packagePath;

    protected $basePath;

    public function handle(): int
    {
        $this->packagePath = __DIR__.'/../../';
        $this->basePath = base_path();

        $this->info('🚀 Installing BladeCN Starter Kit...');
        $this->newLine();

        // Install views
        $this->installViews();

        // Install component classes
        $this->installComponents();

        // Install controllers
        $this->installControllers();

        // Install routes
        $this->installRoutes();

        // Install helpers
        $this->installHelpers();

        // Install assets (CSS & JS)
        $this->installAssets();

        // Check if User model exists
        $userModelPath = app_path('Models/User.php');
        if (! File::exists($userModelPath)) {
            $this->warn('User model not found. Make sure you have a User model with authentication.');
        }

        $this->newLine();
        $this->info('✅ BladeCN installed successfully!');
        $this->newLine();
        $this->info('Available routes:');
        $this->line('  - /login');
        $this->line('  - /register');
        $this->line('  - /forgot-password');
        $this->line('  - /reset-password/{token}');
        $this->line('  - /dashboard');
        $this->line('  - /profile');
        $this->line('  - /logout (POST)');
        $this->newLine();
        $this->info('Essential shadcn/ui components installed:');
        $this->line('  <x-layout.auth> - Authentication layout');
        $this->line('  <x-layout.app> - Application layout with sidebar');
        $this->line('  <x-ui.button>, <x-ui.input>, <x-ui.card>');
        $this->line('  <x-ui.dialog>, <x-ui.dropdown>, <x-ui.sheet>');
        $this->line('  <x-ui.table>, <x-ui.checkbox>, <x-ui.select>');
        $this->line('  And 50+ essential components!');
        $this->newLine();
        $this->newLine();
        $this->info('📝 Next steps:');
        $this->newLine();
        $this->line('  1. Install npm dependencies:');
        $this->line('     <fg=cyan>npm install tailwindcss @tailwindcss/vite tailwindcss-animate alpinejs @alpinejs/focus</>');
        $this->newLine();
        $this->line('  2. Build assets:');
        $this->line('     <fg=cyan>npm run build</> (or <fg=cyan>npm run dev</> for development)');
        $this->newLine();
        $this->line('  3. Update composer autoload:');
        $this->line('     <fg=cyan>composer dump-autoload</>');
        $this->newLine();
        $this->line('  4. Visit <fg=cyan>/login</> to see your beautiful authentication pages!');
        $this->newLine();
        $this->warn('⚠️  IMPORTANT: Install npm packages before building assets!');

        return self::SUCCESS;
    }

    protected function installViews(): void
    {
        $this->info('📁 Installing views...');

        $source = $this->packagePath.'resources/views';
        $destination = $this->basePath.'/resources/views';

        // Copy auth views
        if (File::isDirectory($source.'/auth')) {
            File::copyDirectory($source.'/auth', $destination.'/auth');
        }

        // Copy essential component views only
        $essentialComponents = [
            'ui' => [
                'avatar', 'avatar-fallback', 'avatar-image', 'badge', 'button', 'breadcrumb',
                'card', 'card-content', 'card-description', 'card-footer', 'card-header', 'card-title',
                'checkbox', 'dialog', 'dialog-close', 'dialog-content', 'dialog-description',
                'dialog-footer', 'dialog-header', 'dialog-overlay', 'dialog-title', 'dialog-trigger',
                'dropdown', 'dropdown-checkbox-item', 'dropdown-content', 'dropdown-item',
                'dropdown-label', 'dropdown-radio-item', 'dropdown-separator', 'dropdown-shortcut',
                'dropdown-sub', 'dropdown-sub-content', 'dropdown-sub-trigger', 'dropdown-trigger',
                'input', 'input-error', 'input-group', 'input-group-addon', 'input-group-input', 'label', 'native-select', 'select', 'radio-group',
                'separator', 'sheet', 'sheet-close', 'sheet-description', 'sheet-footer',
                'sheet-header', 'sheet-title', 'sheet-trigger', 'spinner', 'table',
                'table-body', 'table-cell', 'table-head', 'table-header', 'table-row',
                'textarea', 'text-link', 'tooltip', 'progress',
            ],
            'layout' => ['app', 'app-header', 'app-sidebar', 'auth', 'head'],
            'icons' => [], // Copy all icons
        ];

        // Copy UI components
        File::ensureDirectoryExists($destination.'/components/ui');
        foreach ($essentialComponents['ui'] as $component) {
            $sourceFile = $source.'/components/ui/'.$component.'.blade.php';
            if (File::exists($sourceFile)) {
                File::copy($sourceFile, $destination.'/components/ui/'.$component.'.blade.php');
            }
        }

        // Copy layout components
        File::ensureDirectoryExists($destination.'/components/layout');
        foreach ($essentialComponents['layout'] as $component) {
            $sourceFile = $source.'/components/layout/'.$component.'.blade.php';
            if (File::exists($sourceFile)) {
                File::copy($sourceFile, $destination.'/components/layout/'.$component.'.blade.php');
            }
        }

        // Copy all icons
        if (File::isDirectory($source.'/components/icons')) {
            File::copyDirectory($source.'/components/icons', $destination.'/components/icons');
        }

        // Explicitly ensure app-logo is updated (replace if exists)
        $appLogoSource = $source.'/components/icons/app-logo.blade.php';
        $appLogoDestination = $destination.'/components/icons/app-logo.blade.php';
        if (File::exists($appLogoSource)) {
            if (File::exists($appLogoDestination)) {
                $this->warn('   ⚠️  app-logo.blade.php already exists. Replacing with BladeCN version.');
            }
            File::ensureDirectoryExists(dirname($appLogoDestination));
            File::copy($appLogoSource, $appLogoDestination);
            $this->info('   ✓ App logo icon updated');
        }

        // Copy dashboard and profile views
        if (File::exists($source.'/dashboard.blade.php')) {
            File::copy($source.'/dashboard.blade.php', $destination.'/dashboard.blade.php');
        }
        if (File::exists($source.'/profile.blade.php')) {
            File::copy($source.'/profile.blade.php', $destination.'/profile.blade.php');
        }

        // Copy settings views
        if (File::isDirectory($source.'/settings')) {
            File::copyDirectory($source.'/settings', $destination.'/settings');
            $this->info('   ✓ Settings views installed');
        }

        // Copy additional UI components needed for settings
        $settingsComponents = [
            'avatar-upload', 'heading-small', 'appearance-tabs', 'input-group-password',
            'notification-group', 'heading',
        ];
        foreach ($settingsComponents as $component) {
            $sourceFile = $source.'/components/ui/'.$component.'.blade.php';
            if (File::exists($sourceFile)) {
                File::copy($sourceFile, $destination.'/components/ui/'.$component.'.blade.php');
            }
        }

        // Copy settings layout component
        if (File::exists($source.'/components/layout/settings.blade.php')) {
            File::copy($source.'/components/layout/settings.blade.php', $destination.'/components/layout/settings.blade.php');
        }

        // Copy settings components (like delete-user)
        if (File::isDirectory($source.'/components/settings')) {
            File::copyDirectory($source.'/components/settings', $destination.'/components/settings');
        }

        $this->info('   ✓ Views installed to resources/views/');
    }

    protected function installComponents(): void
    {
        $this->info('🧩 Installing component classes...');

        $source = $this->packagePath.'src/View/Components';
        $destination = $this->basePath.'/app/View/Components';

        // Create directory structure
        File::ensureDirectoryExists($destination.'/Ui');
        File::ensureDirectoryExists($destination.'/Layout');

        // Essential UI components only
        $essentialUiComponents = [
            'Avatar', 'AvatarFallback', 'AvatarImage', 'Badge', 'Button', 'Breadcrumb',
            'Card', 'CardContent', 'CardDescription', 'CardFooter', 'CardHeader', 'CardTitle',
            'Checkbox', 'Dialog', 'DialogClose', 'DialogContent', 'DialogDescription',
            'DialogFooter', 'DialogHeader', 'DialogOverlay', 'DialogTitle', 'DialogTrigger',
            'Dropdown', 'DropdownCheckboxItem', 'DropdownContent', 'DropdownItem',
            'DropdownLabel', 'DropdownRadioItem', 'DropdownSeparator', 'DropdownShortcut',
            'DropdownSub', 'DropdownSubContent', 'DropdownSubTrigger', 'DropdownTrigger',
            'Input', 'InputError', 'InputGroup', 'InputGroupAddon', 'InputGroupInput', 'InputGroupPassword', 'Label', 'NativeSelect', 'Select', 'RadioGroup',
            'Separator', 'Sheet', 'SheetClose', 'SheetDescription', 'SheetFooter',
            'SheetHeader', 'SheetTitle', 'SheetTrigger', 'Spinner', 'Table',
            'TableBody', 'TableCell', 'TableHead', 'TableHeader', 'TableRow',
            'Textarea', 'TextLink', 'Tooltip', 'Progress',
        ];

        // Copy essential UI components
        foreach ($essentialUiComponents as $component) {
            $sourceFile = $source.'/Ui/'.$component.'.php';
            if (File::exists($sourceFile)) {
                $content = File::get($sourceFile);

                // Update namespace
                $content = str_replace(
                    'namespace BladeCN\\BladeCN\\View\\Components\\Ui;',
                    'namespace App\\View\\Components\\Ui;',
                    $content
                );

                // Update view paths
                $content = preg_replace(
                    "/view\('bladecn::components\.ui\.([^']+)'\)/",
                    "view('components.ui.$1')",
                    $content
                );

                File::put($destination.'/Ui/'.$component.'.php', $content);
            }
        }

        // Copy all Layout components
        $layoutComponents = File::allFiles($source.'/Layout');
        foreach ($layoutComponents as $file) {
            $content = File::get($file->getPathname());

            // Update namespace
            $content = str_replace(
                'namespace BladeCN\\BladeCN\\View\\Components\\Layout;',
                'namespace App\\View\\Components\\Layout;',
                $content
            );

            // Update view paths
            $content = preg_replace(
                "/view\('bladecn::components\.layout\.([^']+)'\)/",
                "view('components.layout.$1')",
                $content
            );

            $destinationFile = $destination.'/Layout/'.basename($file->getPathname());
            File::put($destinationFile, $content);
        }

        $this->info('   ✓ Component classes installed to app/View/Components/');
    }

    protected function installControllers(): void
    {
        $this->info('🎮 Installing controllers...');

        $source = $this->packagePath.'src/Http/Controllers';
        $destination = $this->basePath.'/app/Http/Controllers';

        File::ensureDirectoryExists($destination.'/Auth');

        // Install Auth controllers
        $authControllers = File::allFiles($source.'/Auth');
        foreach ($authControllers as $file) {
            $content = File::get($file->getPathname());

            // Update namespace
            $content = str_replace(
                'namespace BladeCN\\BladeCN\\Http\\Controllers\\Auth;',
                'namespace App\\Http\\Controllers\\Auth;',
                $content
            );

            // Update view paths
            $content = preg_replace(
                "/view\('bladecn::auth\.([^']+)'\)/",
                "view('auth.$1')",
                $content
            );

            $destinationFile = $destination.'/Auth/'.basename($file->getPathname());
            File::put($destinationFile, $content);
        }

        // Install ProfileController
        if (File::exists($source.'/ProfileController.php')) {
            $content = File::get($source.'/ProfileController.php');

            // Update namespace
            $content = str_replace(
                'namespace BladeCN\\BladeCN\\Http\\Controllers;',
                'namespace App\\Http\\Controllers;',
                $content
            );

            // Update view paths
            $content = preg_replace(
                "/view\('bladecn::([^']+)'\)/",
                "view('$1')",
                $content
            );

            File::put($destination.'/ProfileController.php', $content);
        }

        // Install AppearanceController
        if (File::exists($source.'/AppearanceController.php')) {
            $content = File::get($source.'/AppearanceController.php');

            // Update namespace
            $content = str_replace(
                'namespace BladeCN\\BladeCN\\Http\\Controllers;',
                'namespace App\\Http\\Controllers;',
                $content
            );

            // Update view paths
            $content = preg_replace(
                "/view\('bladecn::([^']+)'\)/",
                "view('$1')",
                $content
            );

            File::put($destination.'/AppearanceController.php', $content);
        }

        $this->info('   ✓ Controllers installed to app/Http/Controllers/');
    }

    protected function installRoutes(): void
    {
        $this->info('🛣️  Installing routes...');

        // Install auth.php routes
        $authSource = $this->packagePath.'src/routes/auth.php';
        $authDestination = $this->basePath.'/routes/auth.php';

        if (! File::exists($authSource)) {
            $this->warn('   ⚠️  Source routes file not found: '.$authSource);
        } else {
            $content = File::get($authSource);

            // Update controller namespaces
            $content = str_replace(
                'BladeCN\\BladeCN\\Http\\Controllers\\Auth\\',
                'App\\Http\\Controllers\\Auth\\',
                $content
            );

            // Also update ProfileController namespace
            $content = str_replace(
                'BladeCN\\BladeCN\\Http\\Controllers\\',
                'App\\Http\\Controllers\\',
                $content
            );

            // Write routes file
            File::put($authDestination, $content);
            $this->info('   ✓ Routes file created: routes/auth.php');
        }

        // Install web.php routes (replace existing)
        $webSource = $this->packagePath.'src/routes/web.php';
        $webDestination = $this->basePath.'/routes/web.php';

        if (! File::exists($webSource)) {
            $this->warn('   ⚠️  Source web.php file not found: '.$webSource);
        } else {
            if (File::exists($webDestination)) {
                $this->warn('   ⚠️  routes/web.php already exists. Replacing with BladeCN version.');
            }
            File::copy($webSource, $webDestination);
            $this->info('   ✓ Routes file installed/replaced: routes/web.php');
        }

        $this->info('   ✓ Routes installation complete');
    }

    protected function installHelpers(): void
    {
        $this->info('🔧 Installing helpers...');

        $source = $this->packagePath.'src/helpers.php';
        $destination = $this->basePath.'/app/helpers.php';

        if (File::exists($source)) {
            File::copy($source, $destination);

            // Add to composer.json autoload if not exists
            $composerPath = $this->basePath.'/composer.json';
            if (File::exists($composerPath)) {
                $composer = json_decode(File::get($composerPath), true);

                if (! isset($composer['autoload']['files'])) {
                    $composer['autoload']['files'] = [];
                }

                if (! in_array('app/helpers.php', $composer['autoload']['files'])) {
                    $composer['autoload']['files'][] = 'app/helpers.php';
                    File::put($composerPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                    $this->info('   ✓ Added helpers.php to composer.json autoload');
                    $this->warn('   ⚠️  Run: composer dump-autoload');
                }
            }
        }

        $this->info('   ✓ Helpers installed');
    }

    protected function installAssets(): void
    {
        $this->info('🎨 Installing assets (CSS & JS)...');

        $sourceCss = $this->packagePath.'resources/css/app.css';
        $destinationCss = $this->basePath.'/resources/css/app.css';

        $sourceJs = $this->packagePath.'resources/js/app.js';
        $destinationJs = $this->basePath.'/resources/js/app.js';

        // Install CSS (replace if exists)
        if (File::exists($sourceCss)) {
            if (File::exists($destinationCss)) {
                $this->warn('   ⚠️  resources/css/app.css already exists. Replacing with BladeCN version.');
            }
            File::ensureDirectoryExists(dirname($destinationCss));
            File::copy($sourceCss, $destinationCss);
            $this->info('   ✓ CSS installed/replaced at resources/css/app.css');
        } else {
            $this->warn('   ⚠️  CSS file not found: '.$sourceCss);
        }

        // Install JS (replace if exists)
        if (File::exists($sourceJs)) {
            if (File::exists($destinationJs)) {
                $this->warn('   ⚠️  resources/js/app.js already exists. Replacing with BladeCN version.');
            }
            File::ensureDirectoryExists(dirname($destinationJs));
            File::copy($sourceJs, $destinationJs);
            $this->info('   ✓ JS installed/replaced at resources/js/app.js');
        } else {
            $this->warn('   ⚠️  JS file not found: '.$sourceJs);
        }

        $this->info('   ✓ Assets installation complete');

        // Check if package.json exists and check for required packages
        $packageJsonPath = $this->basePath.'/package.json';
        if (File::exists($packageJsonPath)) {
            $packageJson = json_decode(File::get($packageJsonPath), true);
            $dependencies = array_merge(
                $packageJson['dependencies'] ?? [],
                $packageJson['devDependencies'] ?? []
            );

            $requiredPackages = [
                'tailwindcss' => 'tailwindcss',
                '@tailwindcss/vite' => '@tailwindcss/vite',
                'tailwindcss-animate' => 'tailwindcss-animate',
                'alpinejs' => 'alpinejs',
                '@alpinejs/focus' => '@alpinejs/focus',
            ];

            $missingPackages = [];
            foreach ($requiredPackages as $key => $package) {
                if (! isset($dependencies[$package])) {
                    $missingPackages[] = $package;
                }
            }

            if (! empty($missingPackages)) {
                $this->newLine();
                $this->warn('   ⚠️  Missing npm packages detected!');
                $this->line('   📦 Required packages:');
                foreach ($requiredPackages as $package) {
                    $status = isset($dependencies[$package]) ? '✓' : '✗';
                    $this->line("      {$status} {$package}");
                }
                $this->newLine();
                $this->line('   💡 Install missing packages:');
                $this->line('      <fg=cyan>npm install '.implode(' ', $missingPackages).'</>');
            } else {
                $this->info('   ✓ All required npm packages are installed');
            }
        } else {
            $this->newLine();
            $this->warn('   ⚠️  package.json not found!');
            $this->line('   📦 Required npm packages:');
            $this->line('      - tailwindcss');
            $this->line('      - @tailwindcss/vite');
            $this->line('      - tailwindcss-animate');
            $this->line('      - alpinejs');
            $this->line('      - @alpinejs/focus');
            $this->newLine();
            $this->line('   💡 Install packages:');
            $this->line('      <fg=cyan>npm install tailwindcss @tailwindcss/vite tailwindcss-animate alpinejs @alpinejs/focus</>');
        }
    }

    protected function copyDirectory(string $source, string $destination, array $exclude = []): void
    {
        if (! File::isDirectory($source)) {
            return;
        }

        File::ensureDirectoryExists($destination);

        $files = File::allFiles($source);

        foreach ($files as $file) {
            $relativePath = str_replace($source.'/', '', $file->getPathname());

            // Check if file should be excluded
            $shouldExclude = false;
            foreach ($exclude as $excludePath) {
                if (str_starts_with($relativePath, $excludePath)) {
                    $shouldExclude = true;
                    break;
                }
            }

            if ($shouldExclude) {
                continue;
            }

            $destPath = $destination.'/'.$relativePath;
            $destDir = dirname($destPath);

            File::ensureDirectoryExists($destDir);
            File::copy($file->getPathname(), $destPath);
        }
    }
}
