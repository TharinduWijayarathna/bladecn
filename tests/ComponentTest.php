<?php

namespace BladeCN\BladeCN\Tests;

use BladeCN\BladeCN\View\Components\Layout\App;
use BladeCN\BladeCN\View\Components\Layout\Auth;
use BladeCN\BladeCN\View\Components\Ui\Button;
use Illuminate\Contracts\View\View;

it('can render button component', function () {
    $component = new Button;
    $view = $component->render();

    expect($view)->toBeInstanceOf(View::class);
});

it('can render auth layout component', function () {
    $component = new Auth;
    $view = $component->render();

    expect($view)->toBeInstanceOf(View::class);
});

it('can render app layout component', function () {
    $component = new App;
    $view = $component->render();

    expect($view)->toBeInstanceOf(View::class);
});

it('button component has correct default classes', function () {
    $component = new Button;
    $classes = $component->buttonClasses();

    expect($classes)->toContain('inline-flex');
    expect($classes)->toContain('items-center');
    expect($classes)->toContain('justify-center');
});
