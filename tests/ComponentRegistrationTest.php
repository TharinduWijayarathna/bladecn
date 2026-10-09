<?php

use BladeCN\BladeCN\BladeCNServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

/*
 * Package components must resolve as <x-ui.*> straight from the package,
 * without running bladecn:install, while published copies still win.
 */

it('registers class components under kebab-case ui and layout tags', function () {
    $aliases = Blade::getClassComponentAliases();

    expect($aliases)->toHaveKey('ui.button')
        ->and($aliases)->toHaveKey('ui.input-otp-slot')
        ->and($aliases)->toHaveKey('ui.empty-state')
        ->and($aliases)->toHaveKey('layout.app-sidebar')
        ->and($aliases)->not->toHaveKey('ui.Button');
});

it('renders class, anonymous and icon components without publishing', function () {
    expect(Blade::render('<x-ui.button variant="outline">Save</x-ui.button>'))->toContain('<button', 'Save', 'border')
        ->and(Blade::render('<x-ui.item><x-ui.item-footer>F</x-ui.item-footer></x-ui.item>'))->toContain('data-slot="item-footer"')
        ->and(Blade::render('<x-ui.alert><x-ui.alert-title>Heads up</x-ui.alert-title></x-ui.alert>'))->toContain('role="alert"', 'Heads up')
        ->and(Blade::render('<x-ui.payment-card title="T" description="D" card-holder="Jane" card-number="4242424242424242" expiry-date="2030-01-01" />'))->toContain('**** **** **** 4242', '<a')
        ->and(Blade::render('<x-icons.check class="size-4" />'))->toContain('<svg');
});

it('lets a view published to resources/views/components override a package component', function () {
    $dir = sys_get_temp_dir().'/bladecn-views-'.uniqid();
    File::ensureDirectoryExists($dir.'/components/ui');
    File::put($dir.'/components/ui/skeleton.blade.php', '<div>published skeleton</div>');
    View::addLocation($dir);

    expect(Blade::render('<x-ui.skeleton />'))->toContain('published skeleton');

    File::deleteDirectory($dir);
});

it('does not alias a component the app has published a class for', function () {
    $base = sys_get_temp_dir().'/bladecn-app-'.uniqid();
    File::ensureDirectoryExists($base.'/app/View/Components/Ui');
    File::put($base.'/app/View/Components/Ui/Button.php', '<?php // published');
    $original = $this->app->basePath();
    $this->app->setBasePath($base);

    // Fresh Blade compiler so only the provider's registrations are present.
    $this->app->forgetInstance('blade.compiler');
    Blade::clearResolvedInstances();

    $provider = new BladeCNServiceProvider($this->app);
    (fn () => $this->registerPackageComponents())->call($provider);

    $aliases = Blade::getClassComponentAliases();
    expect($aliases)->not->toHaveKey('ui.button')
        ->and($aliases)->toHaveKey('ui.badge');

    $this->app->setBasePath($original);
    File::deleteDirectory($base);
});
