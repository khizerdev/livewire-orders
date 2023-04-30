<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
 
class Select2 extends Component
{
    public function __construct(public mixed $options)
    {}
 
    public function render(): View
    {
        return view('components.select2');
    }
}
