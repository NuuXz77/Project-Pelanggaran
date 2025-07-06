<?php

use Livewire\Volt\Component;
use App\Models\Siswa;
use App\Models\Kelas;

new class extends Component {
    public $siswa;
    public $headers = [];
    public $kelasPelanggar = [];
    public $kelasHeaders = [];
    public function mount()
    {
        $this->siswa = Siswa::with(['kelas'])
            ->withCount('pelanggaran')
            ->orderByDesc('pelanggaran_count')
            ->take(5)
            ->get()
            ->map(function ($item, $index) {
                $kelasFormat = $item->kelas ? $item->kelas->kelas . ' - ' . $item->kelas->jurusan : '-';
                return [
                    'number' => $index + 1,
                    'nis' => $item->nis,
                    'nama_siswa' => $item->nama_siswa,
                    'kelas' => $kelasFormat,
                    'total_pelanggaran' => $item->pelanggaran_count,
                ];
            });

        // ✅ Data untuk kelas pelanggar terbanyak
        $this->kelasPelanggar = Kelas::withCount('pelanggaran')
            ->orderByDesc('pelanggaran_count')
            ->take(5)
            ->get()
            ->map(function ($item, $index) {
                return [
                    'kelas' => $item->kelas . ' - ' . $item->jurusan,
                    'total_pelanggaran' => $item->pelanggaran_count,
                ];
            });

        // ✅ Header untuk tabel kelas pelanggar
        $this->kelasHeaders = [['key' => 'kelas', 'label' => 'Kelas'], ['key' => 'total_pelanggaran', 'label' => 'Total Pelanggaran', 'class' => 'text-center']];

        $this->headers = [['key' => 'number', 'label' => '#', 'class' => 'text-center', 'sortable' => false], ['key' => 'nis', 'label' => 'NIS'], ['key' => 'nama_siswa', 'label' => 'Nama Siswa'], ['key' => 'kelas', 'label' => 'Kelas'], ['key' => 'total_pelanggaran', 'label' => 'Total Pelanggaran', 'class' => 'text-center']];
    }
}; ?>


<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 mt-5">
    <!-- Chart Line: Lebih besar, span 2 kolom -->
    <div class="shadow bg-base-100 p-4 rounded-xl h-auto flex flex-col sm:col-span-2">
        <x-header title="Pelanggar Terbanyak" separator>
            <x-slot:actions>
                <x-button class="btn btn-primary btn-soft" link="/pelanggaran">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
                    </svg>
                </x-button>
            </x-slot:actions>
        </x-header>
        <div class="overflow-x-auto">
            <x-table :headers="$headers" :rows="$siswa">
                @scope('cell_total_pelanggaran', $siswa)
                    <x-badge value="{{ $siswa['total_pelanggaran'] }}" class="badge-primary badge-soft" />
                @endscope
            </x-table>
        </div>
    </div>


    <div class="shadow bg-base-100 p-4 rounded-xl h-auto flex flex-col">
        <x-header title="Kelas Pelanggar Terbanyak" separator>
            <x-slot:actions>
                <x-button class="btn btn-primary btn-soft" link="/data-kelas">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
                    </svg>
                </x-button>
            </x-slot:actions>
        </x-header>
        <div class="overflow-x-auto">
            <x-table :headers="$kelasHeaders" :rows="$kelasPelanggar">
                @scope('cell_total_pelanggaran', $kelas)
                    <x-badge value="{{ $kelas['total_pelanggaran'] }}" class="badge-warning badge-soft" />
                @endscope
            </x-table>
        </div>
    </div>
</div>
