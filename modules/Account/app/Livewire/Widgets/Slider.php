<?php

namespace Modules\Account\app\Livewire\Widgets;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Slider extends Component
{
    public $accounts;

    public function render()
    {
        $this->accounts = Auth::user()->accounts;

        return view('account::livewire.widgets.slider');
    }
}
