<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Component;

class PusatBantuan extends Component
{
    #[Layout('layouts.public')]
    public function render()
    {
        return view('livewire.front.pusat-bantuan');
    }
}
