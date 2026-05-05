<?php

namespace App\Livewire\Pencari;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

class UlasanSaya extends Component
{
    #[Layout('layouts.public')]
    public function render()
    {
        return view('livewire.pencari.ulasan-saya');
    }
}
