<?php

use App\Models\Pelanggaran;
use Livewire\Volt\Component;

new class extends Component {
    public int $total = 0;
    public int $ringan = 0;
    public int $sedang = 0;
    public int $berat = 0;

    public function mount()
    {
        $this->total = Pelanggaran::count();
        $this->ringan = Pelanggaran::where('tingkat_pelanggaran', 'Ringan')->count();
        $this->sedang = Pelanggaran::where('tingkat_pelanggaran', 'Sedang')->count();
        $this->berat = Pelanggaran::where('tingkat_pelanggaran', 'Berat')->count();
    }
};
?>


<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
    <div class="stats shadow bg-white p-4 rounded-lg">
        <div class="stat-figure text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                class="inline-block h-8 w-8 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-title">Total Pelanggaran</div>
        <div class="stat-value">{{ $total }}</div>
        <div class="stat-desc">Semua tingkat</div>
    </div>

    <div class="stats shadow bg-white p-4 rounded-lg">
        <div class="stat-figure text-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                class="inline-block h-8 w-8 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-title">Pelanggaran Ringan</div>
        <div class="stat-value">{{ $ringan }}</div>
        <div class="stat-desc">Data terkini</div>
    </div>

    <div class="stats shadow bg-white p-4 rounded-lg">
        <div class="stat-figure text-warning">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                class="inline-block h-8 w-8 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-title">Pelanggaran Sedang</div>
        <div class="stat-value">{{ $sedang }}</div>
        <div class="stat-desc">Data terkini</div>
    </div>

    <div class="stats shadow bg-white p-4 rounded-lg">
        <div class="stat-figure text-error">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                class="inline-block h-8 w-8 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-title">Pelanggaran Berat</div>
        <div class="stat-value">{{ $berat }}</div>
        <div class="stat-desc">Perlu perhatian khusus</div>
    </div>
</div>
