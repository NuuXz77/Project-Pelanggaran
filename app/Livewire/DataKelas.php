<?php

namespace App\Livewire;

use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;

class DataKelas extends Component
{
    use WithPagination;

    protected $listeners = ['refresh' => 'refreshTable'];

    public function refreshTable()
    {
        $this->resetPage();
    }

    // Pagination
    public $perPage = 5;

    // Header table
    public $headers = [
        ['key' => 'number', 'label' => '#', 'class' => 'text-center', 'sortable' => false],
        ['key' => 'kelas', 'label' => 'Kelas', 'class' => 'w-32'],
        ['key' => 'jurusan', 'label' => 'Jurusan'],
        ['key' => 'jumlah_siswa', 'label' => 'Jumlah Siswa', 'class' => 'text-center'],
        ['key' => 'total_pelanggaran', 'label' => 'Total Pelanggaran', 'class' => 'text-center'], // ✅ tambahkan ini
        // ['key' => 'actions', 'label' => 'Aksi', 'class' => 'w-32', 'sortable' => false],
    ];


    // Sorting
    public $sortBy = ['column' => 'kelas', 'direction' => 'asc'];

    public $sortableColumns = [
        'kelas' => 'kelas',
        'jurusan' => 'jurusan',
        'jumlah_siswa' => 'siswa_count',
        'total_pelanggaran' => 'pelanggaran_count',
    ];

    // Search
    public $search = '';

    // Method untuk hapus data
    public function delete($id)
    {
        Kelas::find($id)->delete();
        $this->dispatch(
            'toast',
            type: 'success',
            title: 'Berhasil',
            message: 'Data kelas berhasil dihapus',
            position: 'toast-top toast-end',
            timeout: 3000
        );
        $this->dispatch('refresh');
    }

    // Render view
    public function render()
    {
        $sortColumn = $this->sortableColumns[$this->sortBy['column']] ?? 'kelas';

        $kelas = Kelas::withCount(['siswa', 'pelanggaran'])
            ->when($this->search, function ($query) {
                $query->where('kelas', 'like', '%' . $this->search . '%')
                    ->orWhere('jurusan', 'like', '%' . $this->search . '%');
            })
            ->orderBy($sortColumn, $this->sortBy['direction']) // ✅ pakai kolom hasil mapping
            ->paginate($this->perPage);

        $kelas->getCollection()->transform(function ($item, $index) use ($kelas) {
            $item->number = ($kelas->currentPage() - 1) * $kelas->perPage() + $index + 1;
            $item->jumlah_siswa = $item->siswa_count;
            $item->total_pelanggaran = $item->pelanggaran_count;
            return $item;
        });

        return view('livewire.data-kelas', [
            'kelas' => $kelas,
            'headers' => $this->headers,
            'sortBy' => $this->sortBy
        ]);
    }
}
