<?php

namespace BladeCN\BladeCN\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NativeSelect extends Component
{
    public $class;

    /**
     * Model (or array) the field reads its value from, by `name`; also turns on inline errors.
     */
    public mixed $model;

    /**
     * Render the field's validation error and aria-invalid. Defaults to true when a `model` is passed.
     */
    public ?bool $bind;

    /**
     * Error bag to read validation messages from.
     */
    public string $errorBag;

    /**
     * Create a new component instance.
     */
    public function __construct(string $class = '', mixed $model = null, ?bool $bind = null, string $errorBag = 'default')
    {
        $this->class = $class;
        $this->model = $model;
        $this->bind = $bind;
        $this->errorBag = $errorBag;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('bladecn::components.ui.native-select');
    }

    public function nativeSelectClasses(): string
    {
        return cn(
            'border-input placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 dark:hover:bg-input/50 h-9 w-full min-w-0 appearance-none rounded-md border bg-transparent px-3 py-2 pr-9 text-sm shadow-xs transition-[color,box-shadow] outline-none disabled:pointer-events-none disabled:cursor-not-allowed focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive',
            $this->class
        );
    }
}
