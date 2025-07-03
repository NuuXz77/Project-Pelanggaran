<?php

use App\Models\Pelanggaran;
use App\Models\Siswa;
use Livewire\Volt\Component;

new class extends Component {
    public int $total = 0;
    public int $ringan = 0;
    public int $berat = 0;
    public int $siswa = 0;

    public function mount()
    {
        $this->total = Pelanggaran::count();
        $this->ringan = Pelanggaran::where('tingkat_pelanggaran', 'LIKE', '%Ringan%')->count();
        $this->berat = Pelanggaran::where('tingkat_pelanggaran', 'LIKE', '%Berat%')->count();
        $this->siswa = Siswa::count();
    }
};
?>


<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
    <!-- Total Pelanggaran -->
    <div class="stats shadow bg-base-100 p-4 rounded-lg relative overflow-hidden group transition-all duration-300 hover:shadow-lg">
        <div class="absolute inset-0 bg-gradient-to-br from-primary/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-lg"></div>
        <div class="stat-figure text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block h-8 w-8 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-title">Total Pelanggaran</div>
        <div class="stat-value">
            <count-up>{{ $total }}</count-up>
        </div>
        <div class="stat-desc">Semua tingkat</div>
    </div>

    <!-- Pelanggaran Ringan -->
    <div class="stats shadow bg-base-100 p-4 rounded-lg relative overflow-hidden group transition-all duration-300 hover:shadow-lg">
        <div class="absolute inset-0 bg-gradient-to-br from-success/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-lg"></div>
        <div class="stat-figure text-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block h-8 w-8 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-title">Pelanggaran Ringan</div>
        <div class="stat-value">
            <count-up>{{ $ringan }}</count-up>
        </div>
        <div class="stat-desc">Data terkini</div>
    </div>

    <!-- Pelanggaran Berat -->
    <div class="stats shadow bg-base-100 p-4 rounded-lg relative overflow-hidden group transition-all duration-300 hover:shadow-lg">
        <div class="absolute inset-0 bg-gradient-to-br from-error/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-lg"></div>
        <div class="stat-figure text-error">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block h-8 w-8 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-title">Pelanggaran Berat</div>
        <div class="stat-value">
            <count-up>{{ $berat }}</count-up>
        </div>
        <div class="stat-desc">Perlu perhatian khusus</div>
    </div>

    <!-- Siswa Aktif -->
    <div class="stats shadow bg-base-100 p-4 rounded-lg relative overflow-hidden group transition-all duration-300 hover:shadow-lg">
        <div class="absolute inset-0 bg-gradient-to-br from-warning/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-lg"></div>
        <div class="stat-figure text-warning">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block h-8 w-8 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-title">Siswa Aktif</div>
        <div class="stat-value">
            <count-up>{{ $siswa }}</count-up>
        </div>
        <div class="stat-desc">Data terkini</div>
    </div>
</div>
