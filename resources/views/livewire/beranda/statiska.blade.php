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
        $this->ringan = Pelanggaran::where('tingkat_pelanggaran', 'LIKE', 'R%')->count();
        $this->berat = Pelanggaran::where('tingkat_pelanggaran', 'LIKE', 'B%')->count();
        $this->siswa = Siswa::count();
    }
};
?>


<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
    <!-- Total Pelanggaran -->
    <div
        class="stats shadow bg-base-100 p-4 rounded-lg relative overflow-hidden group transition-all duration-300 hover:shadow-lg">
        <div
            class="absolute inset-0 bg-gradient-to-br from-primary/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-lg">
        </div>
        <div class="stat-figure text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
        </div>
        <div class="stat-title">Total Pelanggaran</div>
        <div class="stat-value">
            <count-up>{{ $total }}</count-up>
        </div>
        <div class="stat-desc">Semua tingkat</div>
    </div>

    <!-- Pelanggaran Ringan -->
    <div
        class="stats shadow bg-base-100 p-4 rounded-lg relative overflow-hidden group transition-all duration-300 hover:shadow-lg">
        <div
            class="absolute inset-0 bg-gradient-to-br from-success/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-lg">
        </div>
        <div class="stat-figure text-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
        </div>
        <div class="stat-title">Pelanggaran Ringan</div>
        <div class="stat-value">
            <count-up>{{ $ringan }}</count-up>
        </div>
        <div class="stat-desc">Data terkini</div>
    </div>

    <!-- Pelanggaran Berat -->
    <div
        class="stats shadow bg-base-100 p-4 rounded-lg relative overflow-hidden group transition-all duration-300 hover:shadow-lg">
        <div
            class="absolute inset-0 bg-gradient-to-br from-error/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-lg">
        </div>
        <div class="stat-figure text-error">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
        </div>
        <div class="stat-title">Pelanggaran Berat</div>
        <div class="stat-value">
            <count-up>{{ $berat }}</count-up>
        </div>
        <div class="stat-desc">Perlu perhatian khusus</div>
    </div>

    <!-- Siswa Aktif -->
    <div
        class="stats shadow bg-base-100 p-4 rounded-lg relative overflow-hidden group transition-all duration-300 hover:shadow-lg">
        <div
            class="absolute inset-0 bg-gradient-to-br from-warning/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-lg">
        </div>
        <div class="stat-figure text-warning">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
        </div>
        <div class="stat-title">Siswa Aktif</div>
        <div class="stat-value">
            <count-up>{{ $siswa }}</count-up>
        </div>
        <div class="stat-desc">Data terkini</div>
    </div>
</div>
