<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class DashboardPagesTitle extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $text
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard-pages-title');
    }
}
