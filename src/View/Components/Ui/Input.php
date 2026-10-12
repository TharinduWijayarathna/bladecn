<?php

namespace BladeCN\BladeCN\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public ?string $type;

    public ?string $class;

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
    public function __construct(?string $type = 'text', ?string $class = null, mixed $model = null, ?bool $bind = null, string $errorBag = 'default')
    {
        $this->type = $type;
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
        return view('bladecn::components.ui.input');
    }

    public function inputClasses(): string
    {
        return cn(
            'file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
            'focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]',
            'aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive',
            $this->class
        );
    }
}
