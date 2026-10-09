<?php

namespace BladeCN\BladeCN;

use BladeCN\BladeCN\Commands\BladeCNCommand;
use BladeCN\BladeCN\Commands\InstallBladeCNCommand;
use BladeCN\BladeCN\View\Components\Layout\App;
use BladeCN\BladeCN\View\Components\Layout\AppHeader;
use BladeCN\BladeCN\View\Components\Layout\AppSidebar;
use BladeCN\BladeCN\View\Components\Layout\Auth;
use BladeCN\BladeCN\View\Components\Layout\Head;
use BladeCN\BladeCN\View\Components\Ui\Avatar;
use BladeCN\BladeCN\View\Components\Ui\AvatarFallback;
use BladeCN\BladeCN\View\Components\Ui\AvatarImage;
use BladeCN\BladeCN\View\Components\Ui\Badge;
use BladeCN\BladeCN\View\Components\Ui\Breadcrumb;
use BladeCN\BladeCN\View\Components\Ui\Button;
use BladeCN\BladeCN\View\Components\Ui\Card;
use BladeCN\BladeCN\View\Components\Ui\CardContent;
use BladeCN\BladeCN\View\Components\Ui\CardDescription;
use BladeCN\BladeCN\View\Components\Ui\CardFooter;
use BladeCN\BladeCN\View\Components\Ui\CardHeader;
use BladeCN\BladeCN\View\Components\Ui\CardTitle;
use BladeCN\BladeCN\View\Components\Ui\Checkbox;
use BladeCN\BladeCN\View\Components\Ui\Dialog;
use BladeCN\BladeCN\View\Components\Ui\DialogClose;
use BladeCN\BladeCN\View\Components\Ui\DialogContent;
use BladeCN\BladeCN\View\Components\Ui\DialogDescription;
use BladeCN\BladeCN\View\Components\Ui\DialogFooter;
use BladeCN\BladeCN\View\Components\Ui\DialogHeader;
use BladeCN\BladeCN\View\Components\Ui\DialogOverlay;
use BladeCN\BladeCN\View\Components\Ui\DialogTitle;
use BladeCN\BladeCN\View\Components\Ui\DialogTrigger;
use BladeCN\BladeCN\View\Components\Ui\Dropdown;
use BladeCN\BladeCN\View\Components\Ui\DropdownCheckboxItem;
use BladeCN\BladeCN\View\Components\Ui\DropdownContent;
use BladeCN\BladeCN\View\Components\Ui\DropdownItem;
use BladeCN\BladeCN\View\Components\Ui\DropdownLabel;
use BladeCN\BladeCN\View\Components\Ui\DropdownRadioItem;
use BladeCN\BladeCN\View\Components\Ui\DropdownSeparator;
use BladeCN\BladeCN\View\Components\Ui\DropdownShortcut;
use BladeCN\BladeCN\View\Components\Ui\DropdownSub;
use BladeCN\BladeCN\View\Components\Ui\DropdownSubContent;
use BladeCN\BladeCN\View\Components\Ui\DropdownSubTrigger;
use BladeCN\BladeCN\View\Components\Ui\DropdownTrigger;
use BladeCN\BladeCN\View\Components\Ui\Input;
use BladeCN\BladeCN\View\Components\Ui\InputError;
use BladeCN\BladeCN\View\Components\Ui\InputGroup;
use BladeCN\BladeCN\View\Components\Ui\InputGroupAddon;
use BladeCN\BladeCN\View\Components\Ui\InputGroupInput;
use BladeCN\BladeCN\View\Components\Ui\Label;
use BladeCN\BladeCN\View\Components\Ui\NativeSelect;
use BladeCN\BladeCN\View\Components\Ui\Progress;
use BladeCN\BladeCN\View\Components\Ui\RadioGroup;
use BladeCN\BladeCN\View\Components\Ui\Select;
use BladeCN\BladeCN\View\Components\Ui\Separator;
use BladeCN\BladeCN\View\Components\Ui\Sheet;
use BladeCN\BladeCN\View\Components\Ui\SheetClose;
use BladeCN\BladeCN\View\Components\Ui\SheetDescription;
use BladeCN\BladeCN\View\Components\Ui\SheetFooter;
use BladeCN\BladeCN\View\Components\Ui\SheetHeader;
use BladeCN\BladeCN\View\Components\Ui\SheetTitle;
use BladeCN\BladeCN\View\Components\Ui\SheetTrigger;
use BladeCN\BladeCN\View\Components\Ui\Spinner;
use BladeCN\BladeCN\View\Components\Ui\Table;
use BladeCN\BladeCN\View\Components\Ui\TableBody;
use BladeCN\BladeCN\View\Components\Ui\TableCell;
use BladeCN\BladeCN\View\Components\Ui\TableHead;
use BladeCN\BladeCN\View\Components\Ui\TableHeader;
use BladeCN\BladeCN\View\Components\Ui\TableRow;
use BladeCN\BladeCN\View\Components\Ui\Textarea;
use BladeCN\BladeCN\View\Components\Ui\TextLink;
use BladeCN\BladeCN\View\Components\Ui\Tooltip;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
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
        // Check if components are installed in app directory
        // If installed, Laravel will auto-discover them from App\View\Components
        // If not installed, register package components as fallback
        if (! $this->componentsInstalled()) {
            $this->registerPackageComponents();
        }
    }

    protected function componentsInstalled(): bool
    {
        return File::exists(app_path('View/Components/Ui/Button.php')) ||
               File::exists(app_path('View/Components/Layout/App.php'));
    }

    protected function registerPackageComponents(): void
    {
        // Register UI components individually with 'ui' prefix (fallback)
        $this->registerUiComponents();

        // Register Layout components individually with 'layout' prefix (fallback)
        $this->registerLayoutComponents();
    }

    protected function registerUiComponents(): void
    {
        // Essential shadcn/ui components only
        $components = [
            // Core Components
            'Avatar' => Avatar::class,
            'AvatarFallback' => AvatarFallback::class,
            'AvatarImage' => AvatarImage::class,
            'Badge' => Badge::class,
            'Button' => Button::class,
            'Card' => Card::class,
            'CardContent' => CardContent::class,
            'CardDescription' => CardDescription::class,
            'CardFooter' => CardFooter::class,
            'CardHeader' => CardHeader::class,
            'CardTitle' => CardTitle::class,
            'Input' => Input::class,
            'InputError' => InputError::class,
            'InputGroup' => InputGroup::class,
            'InputGroupAddon' => InputGroupAddon::class,
            'InputGroupInput' => InputGroupInput::class,
            'Label' => Label::class,
            'Separator' => Separator::class,
            'Textarea' => Textarea::class,
            'TextLink' => TextLink::class,
            'Breadcrumb' => Breadcrumb::class,

            // Dialog Components
            'Dialog' => Dialog::class,
            'DialogClose' => DialogClose::class,
            'DialogContent' => DialogContent::class,
            'DialogDescription' => DialogDescription::class,
            'DialogFooter' => DialogFooter::class,
            'DialogHeader' => DialogHeader::class,
            'DialogOverlay' => DialogOverlay::class,
            'DialogTitle' => DialogTitle::class,
            'DialogTrigger' => DialogTrigger::class,

            // Dropdown Components
            'Dropdown' => Dropdown::class,
            'DropdownCheckboxItem' => DropdownCheckboxItem::class,
            'DropdownContent' => DropdownContent::class,
            'DropdownItem' => DropdownItem::class,
            'DropdownLabel' => DropdownLabel::class,
            'DropdownRadioItem' => DropdownRadioItem::class,
            'DropdownSeparator' => DropdownSeparator::class,
            'DropdownShortcut' => DropdownShortcut::class,
            'DropdownSub' => DropdownSub::class,
            'DropdownSubContent' => DropdownSubContent::class,
            'DropdownSubTrigger' => DropdownSubTrigger::class,
            'DropdownTrigger' => DropdownTrigger::class,

            // Sheet Components
            'Sheet' => Sheet::class,
            'SheetClose' => SheetClose::class,
            'SheetDescription' => SheetDescription::class,
            'SheetFooter' => SheetFooter::class,
            'SheetHeader' => SheetHeader::class,
            'SheetTitle' => SheetTitle::class,
            'SheetTrigger' => SheetTrigger::class,

            // Form Components
            'Checkbox' => Checkbox::class,
            'NativeSelect' => NativeSelect::class,
            'Select' => Select::class,
            'RadioGroup' => RadioGroup::class,

            // Table Components
            'Table' => Table::class,
            'TableHeader' => TableHeader::class,
            'TableBody' => TableBody::class,
            'TableRow' => TableRow::class,
            'TableHead' => TableHead::class,
            'TableCell' => TableCell::class,

            // Utility Components
            'Progress' => Progress::class,
            'Spinner' => Spinner::class,
            'Tooltip' => Tooltip::class,
        ];

        foreach ($components as $name => $class) {
            if (class_exists($class)) {
                Blade::component($class, "ui.{$name}");
            }
        }
    }

    protected function registerLayoutComponents(): void
    {
        $components = [
            'App' => App::class,
            'AppHeader' => AppHeader::class,
            'AppSidebar' => AppSidebar::class,
            'Auth' => Auth::class,
            'Head' => Head::class,
        ];

        foreach ($components as $name => $class) {
            Blade::component($class, "layout.{$name}");
        }
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
