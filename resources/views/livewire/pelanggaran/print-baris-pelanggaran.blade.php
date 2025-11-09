<?php

use Livewire\Volt\Component;

new class extends Component {
    public function print()
    {
        // Simpan semua parameter filter ke session
        $this->dispatch('save-filters-for-print');
        
        // Redirect ke route print per baris
        return redirect()->route('pelanggaran.print-baris');
    }
}; ?>

<div>
    <x-button 
        icon="o-printer" 
        label="Cetak Per Baris" 
        wire:click="print" 
        spinner
        class="btn-secondary"
    />
</div>
