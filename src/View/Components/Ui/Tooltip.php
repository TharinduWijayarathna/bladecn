<?php

namespace BladeCN\BladeCN\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tooltip extends Component
{
    public ?string $side;

    public ?int $sideOffset;

    public string $class;

    /**
     * Create a new component instance.
     */
    public function __construct(?string $side = 'top', ?int $sideOffset = 4, string $class = '')
    {
        $this->side = $side;
        $this->sideOffset = $sideOffset ?? 4;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('bladecn::components.ui.tooltip');
    }

    public function tooltipClasses(): string
    {
        return cn(
            'fixed left-0 top-0 z-50 w-max max-w-xs overflow-hidden rounded-md bg-primary px-3 py-1.5 text-xs text-balance text-primary-foreground',
            'pointer-events-none transition-opacity duration-150 ease-in-out',
            'data-[state=closed]:invisible data-[state=closed]:opacity-0 data-[state=open]:opacity-100',
            $this->class
        );
    }
}
