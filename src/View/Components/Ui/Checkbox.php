<?php

namespace BladeCN\BladeCN\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Checkbox extends Component
{
    public bool $checked;

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

    public function __construct(?bool $checked = false, mixed $model = null, ?bool $bind = null, string $errorBag = 'default')
    {
        $this->checked = $checked ?? false;
        $this->model = $model;
        $this->bind = $bind;
        $this->errorBag = $errorBag;
    }

    public function render(): View|Closure|string
    {
        return view('bladecn::components.ui.checkbox');
    }
}
