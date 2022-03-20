<?php

namespace App\View\Components;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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
     * @return Application|Htmlable|Factory|View
     */
    public function render()
    {
        return view('components.same-client');
    }
}
