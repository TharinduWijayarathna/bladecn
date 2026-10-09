<?php

namespace BladeCN\BladeCN\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputGroupAddon extends Component
{
    public ?string $class;

    public ?string $align;

    /**
     * Create a new component instance.
     *
     * @param  string|null  $align  `inline-start` / `inline-end` pin the addon before / after the
     *                              control regardless of source order; null keeps source order.
     */
    public function __construct(?string $class = null, ?string $align = null)
    {
        $this->class = $class;
        $this->align = $align;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('bladecn::components.ui.input-group-addon');
    }

    public function addonClasses(): string
    {
        return cn(
            'flex items-center justify-center px-3 text-muted-foreground [&>svg]:size-4',
            match ($this->align) {
                'inline-start' => 'order-first',
                'inline-end' => 'order-last',
                default => null,
            },
            $this->class
        );
    }
}
