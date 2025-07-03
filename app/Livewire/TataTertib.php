<?php

namespace App\Livewire;

use App\Models\Peraturan;
use Livewire\Component;
use Livewire\WithPagination;

class TataTertib extends Component
{

    protected $listeners = ['refresh' => '$refreshTable'];

    public function refreshTable()
    {
        $this->resetPage();
    }
    use WithPagination;
    public $perPage = 5;    // Header table
    public $headers = [
        ['key' => 'number', 'label' => '#', 'class' => 'text-center', 'sortable' => false],
        ['key' => 'kode_peraturan', 'label' => 'Kode', 'class' => 'w-32'],
        ['key' => 'larangan', 'label' => 'Larangan'],
        ['key' => 'tindakan_ringan', 'label' => 'Tindakan Ringan'],
        ['key' => 'tindakan_berat', 'label' => 'Tindakan Berat'],
        ['key' => 'actions', 'label' => 'Aksi', 'class' => 'w-32', 'sortable' => false],
    ];

    // Untuk sorting
    public $sortBy = ['column' => 'kode_peraturan', 'direction' => 'asc'];

    // Untuk search
    public $search = '';

    // Method untuk hapus data
    public function delete($id)
    {
        Peraturan::find($id)->delete();
        $this->dispatch('toast', type: 'success', message: 'Data berhasil dihapus');
    }

    // Render view
    public function render()
    {
        // Ambil data dengan search dan sorting
        $peraturan = Peraturan::when($this->search, function ($query) {
            $query->where('kode_peraturan', 'like', '%' . $this->search . '%')
                ->orWhere('larangan', 'like', '%' . $this->search . '%');
        })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);

        // Tambahkan nomor urut dinamis
        collect($peraturan->items())->transform(function ($item, $index) use ($peraturan) {
            $item->number = ($peraturan->currentPage() - 1) * $peraturan->perPage() + $index + 1;
            return $item;
        });

        // Kirim data ke view
        return view('livewire.tata-tertib', [
            'peraturan' => $peraturan,
            'headers' => $this->headers,
            'sortBy' => $this->sortBy
        ]);
    }
}
