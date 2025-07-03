<?php

use Livewire\Volt\Component;
use App\Models\Kelas;

new class extends Component {
    public bool $filter = false;
    public $nama_siswa = '';
    public $kelas_id = '';
    public $tanggal_awal = '';
    public $tanggal_akhir = '';
    
    public $kelasOptions = [];

    public function mount()
    {
        $this->kelasOptions = Kelas::select('ID_Kelas', 'kelas', 'jurusan')
            ->get()
            ->map(function ($kelas) {
                return [
                    'id' => $kelas->ID_Kelas,
                    'name' => $kelas->kelas . ' ' . $kelas->jurusan
                ];
            })
            ->toArray();
    }

    // Update saat filter berubah
    public function updated($property, $value)
    {
        if (in_array($property, ['nama_siswa', 'kelas_id', 'tanggal_awal', 'tanggal_akhir'])) {
            $this->dispatch('update-filter', [
                'nama_siswa' => $this->nama_siswa,
                'kelas_id' => $this->kelas_id,
                'tanggal_awal' => $this->tanggal_awal,
                'tanggal_akhir' => $this->tanggal_akhir
            ]);
        }
    }

    public function resetFilter()
    {
        $this->reset(['nama_siswa', 'kelas_id', 'tanggal_awal', 'tanggal_akhir']);
        $this->dispatch('reset-filter');
    }
};
?>

<div>
    <x-drawer 
        wire:model="filter" 
        title="Filter Data Siswa" 
        subtitle="Filter akan diterapkan otomatis" 
        separator 
        with-close-button 
        close-on-escape 
        class="w-11/12 lg:w-1/3" 
        right
    >
        <div class="space-y-4">
            <x-input 
                label="Nama Siswa" 
                wire:model.live="nama_siswa" 
                placeholder="Cari nama siswa..." 
                icon="o-user" 
            />

            <x-select 
                label="Kelas" 
                wire:model.live="kelas_id" 
                :options="$kelasOptions" 
                placeholder="Pilih Kelas" 
                icon="o-academic-cap" 
                option-label="name" 
                option-value="id" 
            />

            <x-datetime 
                label="Tanggal Awal (opsional)" 
                wire:model.live="tanggal_awal" 
                icon="o-calendar" 
            />

            <x-datetime 
                label="Tanggal Akhir (opsional)" 
                wire:model.live="tanggal_akhir" 
                icon="o-calendar" 
            />
        </div>

        <x-slot:actions>
            <x-button 
                label="Reset Filter" 
                class="btn-error" 
                @click="$wire.resetFilter" 
            />
            <x-button 
                label="Tutup" 
                @click="$wire.filter = false" 
            />
        </x-slot:actions>
    </x-drawer>

    <!-- Tombol Buka Filter -->
    <x-button icon="o-funnel" label="Filter" @click="$wire.filter = true" />
</div>
