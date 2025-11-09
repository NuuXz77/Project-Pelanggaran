<?php

use Livewire\Volt\Component;

new class extends Component {
    public function print()
    {
        // Simpan semua parameter filter ke session
        $this->dispatch('save-filters-for-print');
        return redirect()->route('siswa.print-all');
    }
}; ?>

<div>
    <x-button icon="o-printer" label="Cetak Data Siswa" wire:click="print" spinner class="btn-secondary" />
</div>
