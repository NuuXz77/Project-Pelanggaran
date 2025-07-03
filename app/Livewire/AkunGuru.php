<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AkunGuru extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public $headers = [
        ['key' => 'number', 'label' => '#', 'class' => 'text-center', 'sortable' => false],
        ['key' => 'name', 'label' => 'Nama'],
        ['key' => 'email', 'label' => 'Email'],
        ['key' => 'status', 'label' => 'Status', 'class' => 'text-center'],
        ['key' => 'actions', 'label' => 'Aksi', 'class' => 'w-32', 'sortable' => false],
    ];


    protected $listeners = ['refresh' => 'refreshTable'];

    public function refreshTable()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        User::find($id)?->delete();
        $this->dispatch('toast', type: 'success', title: 'Berhasil', message: 'Data akun BK berhasil dihapus', position: 'toast-top toast-end', timeout: 3000);
        $this->dispatch('refresh');
    }

    public function render()
    {
        $users = User::where('role', 'guru')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);

        $users->getCollection()->transform(function ($item, $index) use ($users) {
            $item->number = ($users->currentPage() - 1) * $users->perPage() + $index + 1;
            return $item;
        });

        return view('livewire.akun-guru', [
            'users' => $users,
            'headers' => $this->headers,
            'sortBy' => $this->sortBy,
        ]);
    }
}
