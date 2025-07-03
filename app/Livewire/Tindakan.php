<?php

namespace App\Livewire;

use App\Models\Tindakan as ModelsTindakan;
use Livewire\Component;
use Livewire\WithPagination;

class Tindakan extends Component
{
    use WithPagination;

    // Pagination
    public $perPage = 5;

    public $headers = [
        ['key' => 'number', 'label' => '#', 'class' => 'text-center', 'sortable' => false],
        ['key' => 'kode_tindakan', 'label' => 'Kode'],
        ['key' => 'jenis', 'label' => 'Jenis'],
        ['key' => 'keterangan', 'label' => 'Keterangan'],
        ['key' => 'actions', 'label' => 'Aksi', 'sortable' => false],
    ];

    // Sorting
    public $sortBy = ['column' => 'ID_Tindakan', 'direction' => 'asc'];

    // Search
    public $search = '';

    // Listener untuk refresh
    protected $listeners = ['refresh' => 'refreshTable'];

    public function refreshTable()
    {
        $this->resetPage();
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
        ModelsTindakan::find($id)->delete();
        $this->dispatch(
            'toast',
            type: 'success',
            title: 'Berhasil',
            message: 'Tindakan berhasil dihapus',
            position: 'toast-top toast-end',
            timeout: 3000
        );
        $this->dispatch('refresh');
    }

    // Render view
    public function render()
    {
        // Query dengan search dan sorting
        $tindakan = ModelsTindakan::when($this->search, function ($query) {
            $query->where('kode_tindakan', 'like', '%' . $this->search . '%')
                ->orWhere('jenis', 'like', '%' . $this->search . '%')
                ->orWhere('keterangan', 'like', '%' . $this->search . '%');
        })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);

        // Tambahkan nomor urut dinamis
        collect($tindakan->items())->transform(function ($item, $index) use ($tindakan) {
            $item->number = ($tindakan->currentPage() - 1) * $tindakan->perPage() + $index + 1;
            return $item;
        });
        return view('livewire.tindakan', [
            'tindakan' => $tindakan,
            'headers' => $this->headers,
            'sortBy' => $this->sortBy
        ]);
    }
}
