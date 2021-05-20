<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SameClient extends Component
{
    public $clients;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($clients = null)
    {
        $this->clients = $clients;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.same-client');
    }
}
