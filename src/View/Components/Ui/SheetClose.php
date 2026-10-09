<?php

namespace BladeCN\BladeCN\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SheetClose extends Component
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
        return view('bladecn::components.ui.sheet-close');
    }

    /**
     * Returns Tailwind classes for the close button.
     */
    public function sheetCloseClasses(): string
    {
        return cn('inline-flex h-9 items-center justify-center gap-2 whitespace-nowrap rounded-md border bg-background px-4 text-sm font-medium shadow-xs outline-none transition-all hover:bg-accent hover:text-accent-foreground focus-visible:ring-[3px] focus-visible:ring-ring/50 dark:border-input dark:bg-input/30 dark:hover:bg-input/50', $this->class);
    }
}
