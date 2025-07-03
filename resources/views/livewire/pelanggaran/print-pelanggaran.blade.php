<?php

use Livewire\Volt\Component;
use App\Models\Pelanggaran;

new class extends Component {
    public function print()
    {
        // Simpan semua parameter filter ke session
        $this->dispatch('save-filters-for-print');
        
        // Redirect ke route print
        return redirect()->route('pelanggaran.print');
    }
}; ?>

<div>
    <x-button 
        icon="o-printer" 
        label="Cetak" 
        wire:click="print" 
        spinner
        class="btn-primary"
    />
</div>