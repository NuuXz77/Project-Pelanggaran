<?php

use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public $role = 'bk';
    public bool $modalTambah = false;
    public $name,
        $email,
        $password,
        $status = 'aktif';

    public function simpanBk()
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:tb_akun,email',
            'password' => 'required|min:6',
            'status' => 'required|in:aktif,nonaktif',
            'role' => 'required|in:bk,kesiswaan,guru,pks',
        ]);

        // dd($this->role);
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'status' => $this->status,
        ]);

        $this->reset(['name', 'email', 'password', 'status']);
        $this->modalTambah = false;

        $this->toast('success', 'Sukses!', 'Akun BK berhasil ditambahkan');
        $this->dispatch('refresh');
    }
};
?>

<div>
    <x-button icon="o-plus" class="btn-primary" @click="$wire.modalTambah = true" label="Tambah BK" />

    <x-modal wire:model="modalTambah" title="Tambah Akun BK" subtitle="Isi informasi akun BK" separator persistent>
        <x-form wire:submit="simpanBk">
            <x-input label="Nama" wire:model="name" icon="o-user" />
            <x-input label="Email" wire:model="email" icon="o-envelope" type="email" />
            <x-input label="Password" wire:model="password" icon="o-lock-closed" type="password" />
            <x-select label="Status" wire:model="status" :options="[['id' => 'aktif', 'name' => 'Aktif'], ['id' => 'nonaktif', 'name' => 'Nonaktif']]" option-label="name" option-value="id" />

            <x-slot:actions>
                <x-button label="Batal" @click="$wire.modalTambah = false" />
                <x-button label="Simpan" type="submit" class="btn-primary" spinner="simpanBk" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
