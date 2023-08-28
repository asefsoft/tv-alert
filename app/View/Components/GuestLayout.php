<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Livewire\Attributes\On;

class GuestLayout extends Component
{
    public function render(): View
    {
        return view('layouts.guest');
    }
}
