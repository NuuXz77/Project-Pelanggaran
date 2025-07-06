<?php

namespace App\Livewire;

use App\Models\Aktivitas as ModelsAktivitas;
use Livewire\Component;
use Livewire\WithPagination;

class Aktivitas extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $sortBy = ['column' => 'tanggal', 'direction' => 'desc'];

    protected $listeners = ['refresh' => 'refreshTable'];

    public function refreshTable()
    {
        $this->resetPage();
    }

    public $headers = [
        ['key' => 'number', 'label' => '#', 'class' => 'text-center', 'sortable' => false],
        ['key' => 'user_name', 'label' => 'User', 'class' => 'w-54'],
        ['key' => 'keterangan', 'label' => 'Keterangan' ,'class' => 'w-54'],
        ['key' => 'tanggal', 'label' => 'Tanggal'],
        ['key' => 'time', 'label' => 'Waktu'],
        // ['key' => 'actions', 'label' => 'Aksi', 'class' => 'w-32', 'sortable' => false],
    ];

    public function render()
    {
        $aktivitas = ModelsAktivitas::with('akun')
            ->when($this->search, function ($query) {
                $query->where('keterangan', 'like', '%' . $this->search . '%')
                    ->orWhereHas('akun', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);

        $aktivitas->getCollection()->transform(function ($item, $index) use ($aktivitas) {
            $item->number = ($aktivitas->currentPage() - 1) * $aktivitas->perPage() + $index + 1;
            $item->user_name = $item->akun ? $item->akun->name : '-';
            return $item;
        });

        return view('livewire.aktivitas', [
            'aktivitas' => $aktivitas,
            'headers' => $this->headers,
            'sortBy' => $this->sortBy
        ]);
    }
}
