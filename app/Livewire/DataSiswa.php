<?php

namespace App\Livewire;

use App\Models\Siswa;
use Livewire\Component;
use Livewire\WithPagination;

class DataSiswa extends Component
{
    use WithPagination;

    // Filter properties
    public $nama_siswa = '';
    public $kelas_id = '';
    public $tanggal_awal = '';
    public $tanggal_akhir = '';

    protected $listeners = [
        'refresh' => 'refreshTable',
        'update-filter' => 'applyFilter',
        'reset-filter' => 'resetFilter'
    ];

    public function refreshTable()
    {
        $this->resetPage();
    }

    public function applyFilter($filter)
    {
        $this->nama_siswa = $filter['nama_siswa'] ?? '';
        $this->kelas_id = $filter['kelas_id'] ?? '';
        $this->tanggal_awal = $filter['tanggal_awal'] ?? '';
        $this->tanggal_akhir = $filter['tanggal_akhir'] ?? '';
        $this->resetPage();
    }

    public function resetFilter()
    {
        $this->reset(['nama_siswa', 'kelas_id', 'tanggal_awal', 'tanggal_akhir']);
        $this->resetPage();
    }

    // Pagination
    public $perPage = 5;

    // Table Headers
    public $headers = [
        ['key' => 'number', 'label' => '#', 'class' => 'text-center', 'sortable' => false],
        ['key' => 'nis', 'label' => 'NIS'],
        ['key' => 'nama_siswa', 'label' => 'Nama Siswa'],
        ['key' => 'kelas', 'label' => 'Kelas'],
        ['key' => 'total_pelanggaran', 'label' => 'Total Pelanggaran', 'class' => 'text-center'],
        ['key' => 'actions', 'label' => 'Aksi', 'class' => 'w-32', 'sortable' => false],
    ];

    public $sortBy = ['column' => 'nama_siswa', 'direction' => 'asc'];
    public $search = '';

    public function delete($id)
    {
        Siswa::find($id)?->delete();
        $this->dispatch('toast', type: 'success', title: 'Berhasil', message: 'Data siswa berhasil dihapus', position: 'toast-top toast-end', timeout: 3000);
        $this->dispatch('refresh');
    }

    public function render()
    {
        $siswa = Siswa::with('kelas')
            // Search global
            ->when($this->search, function ($query) {
                $query->where('nama_siswa', 'like', '%' . $this->search . '%')
                    ->orWhere('nis', 'like', '%' . $this->search . '%')
                    ->orWhereHas('kelas', function ($q) {
                        $q->where('kelas', 'like', '%' . $this->search . '%');
                    });
            })

            // Filter nama siswa
            ->when($this->nama_siswa, function ($query) {
                $query->where('nama_siswa', 'like', '%' . $this->nama_siswa . '%');
            })

            // Filter berdasarkan kelas_id
            ->when($this->kelas_id, function ($query) {
                $query->where('kelas_id', $this->kelas_id);
            })

            // Filter tanggal awal & akhir
            ->when($this->tanggal_awal, function ($query) {
                $query->whereDate('created_at', '>=', $this->tanggal_awal);
            })
            ->when($this->tanggal_akhir, function ($query) {
                $query->whereDate('created_at', '<=', $this->tanggal_akhir);
            })

            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);

        // Tambahkan nomor dan format kelas
        $siswa->getCollection()->transform(function ($item, $index) use ($siswa) {
            $item->number = ($siswa->currentPage() - 1) * $siswa->perPage() + $index + 1;
            $item->kelas = $item->kelas ? $item->kelas->kelas . ' ' . $item->kelas->jurusan : '-';
            return $item;
        });

        return view('livewire.data-siswa', [
            'siswa' => $siswa,
            'headers' => $this->headers,
            'sortBy' => $this->sortBy
        ]);
    }
}
