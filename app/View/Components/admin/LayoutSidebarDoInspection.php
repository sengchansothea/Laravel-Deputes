<?php

namespace App\View\Components\admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LayoutSidebarDoInspection extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $adata)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.layout-sidebar-do-inspection');
    }
}
