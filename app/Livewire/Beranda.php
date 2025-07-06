<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;

class Beranda extends Component
{
    public $myDate;

    public function mount()
    {
        $this->myDate = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.beranda', [
            'myDate' => $this->myDate,
        ]);
    }
}
