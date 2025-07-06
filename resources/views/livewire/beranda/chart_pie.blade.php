<?php

use Livewire\Volt\Component;
use App\Models\Pelanggaran;
use App\Models\Peraturan;
use Carbon\Carbon;

new class extends Component {
    public $filter = 'monthly';
    public $chartData = [];
    public $categories = [];
    public $topPeraturan = [];
    public $peraturanHeaders = [];

    public function mount()
    {
        // ... existing siswa & kelas

        $this->topPeraturan = Peraturan::withCount('pelanggaran')
            ->orderByDesc('pelanggaran_count')
            ->take(5)
            ->get()
            ->map(function ($item, $index) {
                return [
                    'number' => $index + 1,
                    'kode' => $item->kode_peraturan,
                    'larangan' => $item->larangan,
                    'total_pelanggaran' => $item->pelanggaran_count,
                ];
            });

        $this->peraturanHeaders = [['key' => 'number', 'label' => '#', 'class' => 'text-center', 'sortable' => false], ['key' => 'kode', 'label' => 'Kode'], ['key' => 'larangan', 'label' => 'Larangan'], ['key' => 'total_pelanggaran', 'label' => 'Total Pelanggaran', 'class' => 'text-center']];
    }
}; ?>

<div class="shadow bg-base-100 p-4 mt-5 rounded-xl h-auto flex flex-col sm:col-span-2">
    <x-header title="Peraturan Terlanggar Terbanyak" separator>
        <x-slot:actions>
            <x-button class="btn btn-primary btn-soft" link="/tata-tertib">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
                </svg>
            </x-button>
        </x-slot:actions>
    </x-header>
    <div class="overflow-x-auto">
        <x-table :headers="$peraturanHeaders" :rows="$topPeraturan">
            @scope('cell_total_pelanggaran', $peraturan)
                <x-badge value="{{ $peraturan['total_pelanggaran'] }}" class="badge-error badge-soft" />
            @endscope
        </x-table>
    </div>
</div>
