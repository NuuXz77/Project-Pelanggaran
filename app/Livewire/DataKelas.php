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
        ['key' => 'wali_kelas', 'label' => 'Wali Kelas'],
        ['key' => 'jumlah_siswa', 'label' => 'Jumlah Siswa', 'class' => 'text-center'],
        ['key' => 'actions', 'label' => 'Aksi', 'class' => 'w-32', 'sortable' => false],
    ];

    // Sorting
    public $sortBy = ['column' => 'kelas', 'direction' => 'asc'];

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
        // Query dengan search dan sorting
        $kelas = Kelas::withCount('siswa')
            ->with(['guru' => function ($query) {
                $query->select('ID_Guru', 'kelas_id', 'nama_guru');
            }])
            ->when($this->search, function ($query) {
                $query->where('kelas', 'like', '%' . $this->search . '%')
                    ->orWhere('jurusan', 'like', '%' . $this->search . '%')
                    ->orWhereHas('guru', function ($q) {
                        $q->where('nama_guru', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);

        // Tambahkan nomor urut dinamis
        $kelas->getCollection()->transform(function ($item, $index) use ($kelas) {
            $item->number = ($kelas->currentPage() - 1) * $kelas->perPage() + $index + 1;
            $item->wali_kelas = $item->guru ? $item->guru->nama_guru : '-';
            $item->jumlah_siswa = $item->siswa_count;
            return $item;
        });

        return view('livewire.data-kelas', [
            'kelas' => $kelas,
            'headers' => $this->headers,
            'sortBy' => $this->sortBy
        ]);
    }
}
