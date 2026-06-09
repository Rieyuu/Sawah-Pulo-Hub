<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AdminLayout extends Component
{
    public $activeRoute;
    public $header;

    /**
     * Create a new component instance.
     */
    public function __construct($activeRoute = 'dashboard', $header = 'Panel Admin')
    {
        $this->activeRoute = $activeRoute;
        $this->header = $header;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.admin');
    }
}
