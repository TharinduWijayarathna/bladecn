<?php

namespace BladeCN\BladeCN\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sheet extends Component
{
    public string $side;

    public string $class;

    /**
     * Create a new component instance.
     */
    public function __construct(string $side = 'right', string $class = '')
    {
        $this->side = $side;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('bladecn::components.ui.sheet');
    }

    /**
     * Returns merged Tailwind classes for the sheet content.
     */
    public function contentClasses(): string
    {
        $base = 'fixed z-50 flex flex-col gap-4 bg-background text-foreground shadow-lg p-6 transition-transform duration-300 ease-in-out';

        $sides = [
            'top' => ['base' => 'inset-x-0 top-0 border-b', 'translate_in' => 'translate-y-0', 'translate_out' => '-translate-y-full'],
            'bottom' => ['base' => 'inset-x-0 bottom-0 border-t', 'translate_in' => 'translate-y-0', 'translate_out' => 'translate-y-full'],
            'left' => ['base' => 'inset-y-0 left-0 w-3/4 sm:max-w-sm border-r', 'translate_in' => 'translate-x-0', 'translate_out' => '-translate-x-full'],
            'right' => ['base' => 'inset-y-0 right-0 w-3/4 sm:max-w-sm border-l', 'translate_in' => 'translate-x-0', 'translate_out' => 'translate-x-full'],
        ];

        $side = $sides[$this->side] ?? $sides['right'];

        return cn($base, $side['base'], $side['translate_out'], $this->class);
    }

    /**
     * Returns the translation classes separately for JS.
     */
    public function translateIn(): string
    {
        $sides = [
            'top' => 'translate-y-0',
            'bottom' => 'translate-y-0',
            'left' => 'translate-x-0',
            'right' => 'translate-x-0',
        ];

        return $sides[$this->side] ?? $sides['right'];
    }

    public function translateOut(): string
    {
        $sides = [
            'top' => '-translate-y-full',
            'bottom' => 'translate-y-full',
            'left' => '-translate-x-full',
            'right' => 'translate-x-full',
        ];

        return $sides[$this->side] ?? $sides['right'];
    }
}
