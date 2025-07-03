<?php

use Livewire\Volt\Component;
use App\Models\User;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $modalEdit = false;
    public $akunId, $name, $email, $status;
    public $listeners = ['showEditModal' => 'openModal'];

    public function openModal($id)
    {
        $akun = User::findOrFail($id);
        $this->akunId = $akun->ID_Akun;
        $this->name = $akun->name;
        $this->email = $akun->email;
        $this->status = $akun->status;
        $this->modalEdit = true;
    }

    public function updatePKS()
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:tb_akun,email,' . $this->akunId . ',ID_Akun',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        User::where('ID_Akun', $this->akunId)->update([
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
        ]);

        $this->reset(['akunId', 'name', 'email', 'status']);
        $this->modalEdit = false;

        $this->toast('success', 'Berhasil!', 'Data akun PKS berhasil diperbarui');
        $this->dispatch('refresh');
    }
};
?>

<div>
    <x-modal wire:model="modalEdit" title="Edit Akun PKS" subtitle="Perbarui informasi akun PKS" separator persistent>
        <x-form wire:submit="updatePKS">
            <x-input label="Nama" wire:model="name" icon="o-user" />
            <x-input label="Email" wire:model="email" icon="o-envelope" />
            <x-select label="Status" wire:model="status" :options="[['id'=>'Aktif','name'=>'Aktif'],['id'=>'Tidak Aktif','name'=>'Tidak Aktif']]" option-label="name" option-value="id" />

            <x-slot:actions>
                <x-button label="Batal" @click="$wire.modalEdit = false" />
                <x-button label="Simpan Perubahan" type="submit" class="btn-primary" spinner="updatePKS" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
