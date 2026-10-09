<?php

namespace BladeCN\BladeCN\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SheetFooter extends Component
{
    public string $class;

    /**
     * Create a new component instance.
     */
    public function __construct(string $class = '')
    {
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('bladecn::components.ui.sheet-footer');
    }

    public function sheetFooterClasses(): string
    {
        return cn('mt-auto flex flex-col-reverse gap-2 sm:flex-row sm:justify-end', $this->class);
    }
}
