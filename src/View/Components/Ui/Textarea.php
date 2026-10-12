<?php

namespace BladeCN\BladeCN\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Textarea extends Component
{
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
    public function __construct(?string $class = null, mixed $model = null, ?bool $bind = null, string $errorBag = 'default')
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
        return view('bladecn::components.ui.textarea');
    }

    public function textareaClasses(): string
    {
        return cn(
            'border-input placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive dark:bg-input/30 flex field-sizing-content min-h-16 w-full rounded-md border bg-transparent px-3 py-2 text-base shadow-xs transition-[color,box-shadow] outline-none focus-visible:ring-[3px] disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
            $this->class
        );
    }
}
