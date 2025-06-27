<?php

use Livewire\Volt\Component;
use App\Models\Peraturan;

new class extends Component {
    public $peraturan;
    public $headers = [];

    public function mount()
    {
        $this->peraturan = Peraturan::latest()->get();

        $this->headers = 
        [
            ['key' => 'id', 'label' => '#'], 
            ['key' => 'kode_peraturan', 'label' => 'Kode'], 
            ['key' => 'larangan', 'label' => 'Tingkat'],
            ['key' => 'tindakan_ringan', 'label' => 'Tindakan Ringan'],
            ['key' => 'tindakan_berat', 'label' => 'Tindakan Berat']
        ];
    }
};
?>


<div>
    <x-table :headers="$headers" :rows="$peraturan" striped />
</div>
