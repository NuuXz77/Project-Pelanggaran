<?php

namespace App\Livewire;

use App\Models\Guru;
use Livewire\Component;
use Livewire\WithPagination;

class DataGuru extends Component
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
        ['key' => 'nama_guru', 'label' => 'Nama Guru', 'class' => 'w-32'],
        ['key' => 'nip', 'label' => 'NIP'],
        ['key' => 'kelas', 'label' => 'Wali Kelas'],
        ['key' => 'actions', 'label' => 'Aksi', 'class' => 'w-32', 'sortable' => false],
    ];

    // Sorting
    public $sortBy = ['column' => 'nama_guru', 'direction' => 'asc'];

    // Search
    public $search = '';

    // Method untuk hapus data
    public function delete($id)
    {
        Guru::find($id)->delete();
        $this->dispatch(
            'toast',
            type: 'success',
            title: 'Berhasil',
            message: 'Data guru berhasil dihapus',
            position: 'toast-top toast-end',
            timeout: 3000
        );
        $this->dispatch('refresh');
    }

    // Render view
    public function render()
    {
        // Query dengan search dan sorting
        $gurus = Guru::with('kelas')
            ->when($this->search, function ($query) {
                $query->where('nama_guru', 'like', '%' . $this->search . '%')
                    ->orWhere('nip', 'like', '%' . $this->search . '%')
                    ->orWhereHas('kelas', function ($q) {
                        $q->where('kelas', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);

        // Tambahkan nomor urut dinamis
        $gurus->getCollection()->transform(function ($item, $index) use ($gurus) {
            $item->number = ($gurus->currentPage() - 1) * $gurus->perPage() + $index + 1;
            $item->kelas_wali = $item->kelas ;
            return $item;
        });

        return view('livewire.data-guru', [
            'gurus' => $gurus,
            'headers' => $this->headers,
            'sortBy' => $this->sortBy
        ]);
    }
}
