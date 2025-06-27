<?php

namespace App\Livewire;

use App\Models\Tindakan as ModelsTindakan;
use Livewire\Component;

class Tindakan extends Component
{
    public $tindakan;
    public $headers = [];

    public function render()
    {
        $this->tindakan = ModelsTindakan::all();

        $this->headers =
            [
                ['key' => 'id', 'label' => '#'],
                ['key' => 'kode_tindakan', 'label' => 'Kode'],
                ['key' => 'jenis', 'label' => 'Jenis'],
                ['key' => 'keterangan', 'label' => 'Keterangan']
            ];
        return view('livewire.tindakan', ['headers' => $this->headers, 'tindakan' => $this->tindakan]);
    }
}
