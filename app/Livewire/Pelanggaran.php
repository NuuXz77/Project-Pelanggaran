<?php

namespace App\Livewire;

use App\Models\Pelanggaran as ModelsPelanggaran;
use Livewire\Component;
use Livewire\WithPagination;

class Pelanggaran extends Component
{
    use WithPagination;

    public $filterNama = '';
    public $filterKelasId = '';
    public $filterTanggalAwal = '';
    public $filterTanggalAkhir = '';
    // Pagination
    public $perPage = 5;

    public $headers = [
        ['key' => 'number', 'label' => '#', 'class' => 'text-center', 'sortable' => false],
        ['key' => 'nis', 'label' => 'NIS'],
        ['key' => 'nama_siswa', 'label' => 'Nama Siswa'],
        ['key' => 'kelas', 'label' => 'Kelas'],
        ['key' => 'pelanggaran', 'label' => 'Pelanggaran'],
        ['key' => 'tingkat_pelanggaran', 'label' => 'Tingkat Pelanggaran'],
        ['key' => 'tindakan', 'label' => 'Tindakan'],
        ['key' => 'deskripsi_pelanggaran', 'label' => 'Deskripsi'],
        ['key' => 'created_at', 'label' => 'Waktu Melanggar'],
        ['key' => 'updated_at', 'label' => 'Terakhir DiUpdate', 'class' => 'text-center'],
        ['key' => 'actions', 'label' => 'Aksi', 'class' => 'w-32', 'sortable' => false],

    ];

    // Sorting
    public $sortBy = ['column' => 'ID_Pelanggaran', 'direction' => 'asc'];

    // Search
    public $search = '';

    // Listener untuk refresh
    protected $listeners = [
        'refresh' => 'refreshTable',
        'update-filter' => 'applyFilter',
        'reset-filter' => 'resetFilter',
        'save-filters-for-print' => 'saveFiltersForPrint'
    ];

    public function refreshTable()
    {
        $this->resetPage();
    }

    public function applyFilter($params)
    {
        $this->filterNama = $params['nama_siswa'];
        $this->filterKelasId = $params['kelas_id'];
        $this->filterTanggalAwal = $params['tanggal_awal'];
        $this->filterTanggalAkhir = $params['tanggal_akhir'];
        $this->resetPage();
    }

    public function resetFilter()
    {
        $this->reset([
            'filterNama',
            'filterKelasId',
            'filterTanggalAwal',
            'filterTanggalAkhir'
        ]);
        $this->resetPage();
    }

    public function saveFiltersForPrint()
    {
        session()->put([
            'pelanggaran_search' => $this->search,
            'pelanggaran_filter_nama' => $this->filterNama,
            'pelanggaran_filter_kelas_id' => $this->filterKelasId,
            'pelanggaran_filter_tanggal_awal' => $this->filterTanggalAwal,
            'pelanggaran_filter_tanggal_akhir' => $this->filterTanggalAkhir,
            'pelanggaran_sort_column' => $this->sortBy['column'],
            'pelanggaran_sort_direction' => $this->sortBy['direction']
        ]);
    }

    // Method untuk sorting
    public function sort($column)
    {
        if ($this->sortBy['column'] == $column) {
            $this->sortBy['direction'] = $this->sortBy['direction'] == 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy['column'] = $column;
            $this->sortBy['direction'] = 'asc';
        }
    }

    // Method untuk hapus data
    public function delete($id)
    {
        ModelsPelanggaran::find($id)->delete();
        $this->dispatch(
            'toast',
            type: 'success',
            title: 'Berhasil',
            message: 'Data pelanggaran berhasil dihapus',
            position: 'toast-top toast-end',
            timeout: 3000
        );
        $this->dispatch('refresh');
    }

    // Render view
    public function render()
    {
        // Query dengan search dan sorting
        $pelanggaran = ModelsPelanggaran::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nis', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_siswa', 'like', '%' . $this->search . '%')
                        ->orWhere('kelas', 'like', '%' . $this->search . '%')
                        ->orWhere('pelanggaran', 'like', '%' . $this->search . '%')
                        ->orWhere('tingkat_pelanggaran', 'like', '%' . $this->search . '%')
                        ->orWhere('tindakan', 'like', '%' . $this->search . '%')
                        ->orWhere('deskripsi_pelanggaran', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterNama, function ($query) {
                $query->where('nama_siswa', 'like', '%' . $this->filterNama . '%');
            })
            ->when($this->filterKelasId, function ($query) {
                $query->where('kelas_id', $this->filterKelasId);
            })
            ->when($this->filterTanggalAwal || $this->filterTanggalAkhir, function ($query) {
                if ($this->filterTanggalAwal && $this->filterTanggalAkhir) {
                    // Jika kedua tanggal ada, gunakan between
                    $query->whereBetween('created_at', [
                        $this->filterTanggalAwal,
                        $this->filterTanggalAkhir
                    ]);
                } elseif ($this->filterTanggalAwal) {
                    // Jika hanya tanggal awal, filter dari tanggal itu saja
                    $query->whereDate('created_at', $this->filterTanggalAwal);
                } else {
                    // Jika hanya tanggal akhir, filter sampai tanggal itu saja
                    $query->whereDate('created_at', $this->filterTanggalAkhir);
                }
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);

        // Transform data to split datetime into separate date and time columns
        collect($pelanggaran->items())->transform(function ($item, $index) use ($pelanggaran) {
            $item->number = ($pelanggaran->currentPage() - 1) * $pelanggaran->perPage() + $index + 1;

            // Split created_at into date and time
            $createdAt = \Carbon\Carbon::parse($item->created_at);
            $item->tanggal_melanggar = $createdAt->format('Y-m-d');
            $item->waktu_melanggar = $createdAt->format('H:i:s');

            // Split updated_at into date and time
            $updatedAt = \Carbon\Carbon::parse($item->updated_at);
            $item->tanggal_update = $updatedAt->format('Y-m-d');
            $item->waktu_update = $updatedAt->format('H:i:s');

            return $item;
        });

        return view('livewire.pelanggaran', [
            'pelanggaran' => $pelanggaran,
            'headers' => $this->headers,
            'sortBy' => $this->sortBy,
            'search' => $this->search,
            'filterNama' => $this->filterNama,
            'filterKelasId' => $this->filterKelasId,
            'filterTanggalAwal' => $this->filterTanggalAwal,
            'filterTanggalAkhir' => $this->filterTanggalAkhir
        ]);
    }
}
