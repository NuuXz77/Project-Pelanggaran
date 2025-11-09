<?php

use Livewire\Volt\Component;

new class extends Component {
    public $siswa;

    public function mount($siswa)
    {
        $this->siswa = $siswa;
    }

    public function print()
    {
        // Gunakan ID siswa sebagai parameter
        return redirect()->route('siswa.print', ['siswa' => $this->siswa->ID_Siswa]);
    }
}; ?>

<div>
    <x-button 
        icon="o-printer" 
        label="Cetak Detail Siswa" 
        wire:click="print" 
        spinner
        class="btn-primary"
    />
</div>
