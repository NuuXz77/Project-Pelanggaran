<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;

class Beranda extends Component
{
    public $myDate;
    public $filterType = 'day'; // 'day', 'week', or 'month'
    public $filterDate;

    public function mount()
    {
        $this->filterDate = Carbon::now()->format('Y-m-d');
    }

    public function updatedFilterType()
    {
        $this->dispatch(
            'filter-updated',
            type: $this->filterType,
            date: $this->filterDate
        );
    }

    public function updatedFilterDate()
    {
        $this->dispatch(
            'filter-updated',
            type: $this->filterType,
            date: $this->filterDate
        );
    }

    public function render()
    {
        return view('livewire.beranda', [
            'myDate' => $this->myDate,
            'filterType' => $this->filterType,
            'filterDate' => $this->filterDate,
        ]);
    }
}
