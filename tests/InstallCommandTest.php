<?php

use Illuminate\Support\Facades\File;

/*
 * bladecn:install publishes every component and only overwrites files with --force.
 * It runs against a throwaway base path so the Testbench skeleton is untouched.
 */

beforeEach(function () {
    $this->base = sys_get_temp_dir().'/bladecn-install-'.uniqid();
    File::ensureDirectoryExists($this->base.'/routes');
    File::ensureDirectoryExists($this->base.'/resources/css');
    File::ensureDirectoryExists($this->base.'/resources/js');
    File::put($this->base.'/routes/web.php', "<?php // laravel default\n");
    File::put($this->base.'/resources/css/app.css', "/* laravel default */\n");
    File::put($this->base.'/resources/js/app.js', "// laravel default\n");
    File::put($this->base.'/composer.json', json_encode(['autoload' => ['psr-4' => ['App\\' => 'app/']]]));

    $this->original = $this->app->basePath();
    $this->app->setBasePath($this->base);
});

/** Runs a first install, answering yes to replacing the Laravel default entry points. */
function installFresh($test): void
{
    $test->artisan('bladecn:install')
        ->expectsConfirmation('routes/web.php already exists. Replace it with the BladeCN version?', 'yes')
        ->expectsConfirmation('resources/css/app.css already exists. Replace it with the BladeCN version?', 'yes')
        ->expectsConfirmation('resources/js/app.js already exists. Replace it with the BladeCN version?', 'yes')
        ->assertSuccessful();
}

afterEach(function () {
    $this->app->setBasePath($this->original);
    File::deleteDirectory($this->base);
});

it('publishes every ui view and component class', function () {
    installFresh($this);

    $package = dirname(__DIR__);

    foreach (glob($package.'/resources/views/components/ui/*.blade.php') as $file) {
        expect(File::exists($this->base.'/resources/views/components/ui/'.basename($file)))->toBeTrue(basename($file).' was not published');
    }

    foreach (glob($package.'/src/View/Components/Ui/*.php') as $file) {
        $published = $this->base.'/app/View/Components/Ui/'.basename($file);
        expect(File::exists($published))->toBeTrue(basename($file).' class was not published');
        expect(File::get($published))
            ->toContain('namespace App\View\Components\Ui;')
            ->not->toContain('bladecn::components.ui.');
    }

    expect(File::exists($this->base.'/resources/views/components/icons/check.blade.php'))->toBeTrue()
        ->and(File::exists($this->base.'/resources/views/components/layout/settings.blade.php'))->toBeTrue()
        ->and(File::get($this->base.'/app/Http/Controllers/Auth/NewPasswordController.php'))->toContain("view('auth.reset-password'")
        ->and(File::get($this->base.'/routes/web.php'))->not->toContain('laravel default')
        ->and(json_decode(File::get($this->base.'/composer.json'), true)['autoload']['files'])->toContain('app/helpers.php');
});

it('keeps customised files unless --force is passed', function () {
    installFresh($this);

    $button = $this->base.'/resources/views/components/ui/button.blade.php';
    File::put($button, 'customised');

    $this->artisan('bladecn:install')
        ->expectsOutputToContain('Re-run with --force')
        ->assertSuccessful();
    expect(File::get($button))->toBe('customised');

    $this->artisan('bladecn:install', ['--force' => true])->assertSuccessful();
    expect(File::get($button))->not->toBe('customised');
});

it('asks before replacing the app entry points', function () {
    $this->artisan('bladecn:install')
        ->expectsConfirmation('routes/web.php already exists. Replace it with the BladeCN version?', 'no')
        ->expectsConfirmation('resources/css/app.css already exists. Replace it with the BladeCN version?', 'yes')
        ->expectsConfirmation('resources/js/app.js already exists. Replace it with the BladeCN version?', 'no')
        ->assertSuccessful();

    expect(File::get($this->base.'/routes/web.php'))->toContain('laravel default')
        ->and(File::get($this->base.'/resources/css/app.css'))->toContain('@import')
        ->and(File::get($this->base.'/resources/js/app.js'))->toContain('laravel default');
});
