<?php

namespace BladeCN\BladeCN\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Badge extends Component
{
    public string $variant;

    public ?string $class;

    // Predefined variants
    public array $variants = [
        'default' => 'border-transparent bg-primary text-primary-foreground hover:bg-primary/80',
        'secondary' => 'border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80',
        'destructive' => 'border-transparent bg-destructive text-white hover:bg-destructive/90 dark:bg-destructive/60',
        'outline' => 'text-foreground',
    ];

    /**
     * Create a new component instance.
     */
    public function __construct(string $variant = 'default', ?string $class = null)
    {
        $this->variant = $variant;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('bladecn::components.ui.badge');
    }

    public function badgeClasses(): string
    {
        return cn(
            'inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2',
            $this->variants[$this->variant] ?? '',
            $this->class
        );
    }
}
