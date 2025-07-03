<?php

use Livewire\Volt\Component;
use App\Models\User;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $deleteModal = false;
    public $akunId, $name, $email;
    public $listeners = ['showDeleteModal' => 'openModal'];

    public function openModal($id)
    {
        $akun = User::findOrFail($id);
        $this->akunId = $akun->ID_Akun;
        $this->name = $akun->name;
        $this->email = $akun->email;
        $this->deleteModal = true;
    }

    public function deletePKS()
    {
        User::find($this->akunId)?->delete();
        $this->reset(['akunId', 'name', 'email']);
        $this->deleteModal = false;
        $this->toast('success', 'Sukses!', 'Data akun PKS berhasil dihapus');
        $this->dispatch('refresh');
    }
};
?>

<div>
    <x-mary-modal wire:model="deleteModal" title="Hapus Akun PKS" persistent class="backdrop-blur">
        <div class="mb-4 space-y-2">
            <p>Yakin ingin menghapus akun berikut?</p>
            <div class="bg-base-200 p-3 rounded">
                <p><strong>Nama:</strong> {{ $name }}</p>
                <p><strong>Email:</strong> {{ $email }}</p>
            </div>
        </div>

        <x-slot:actions>
            <x-button label="Batal" @click="$wire.deleteModal = false" />
            <x-button label="Hapus" wire:click="deletePKS" class="btn-error" spinner="deletePKS" />
        </x-slot:actions>
    </x-mary-modal>
</div>
