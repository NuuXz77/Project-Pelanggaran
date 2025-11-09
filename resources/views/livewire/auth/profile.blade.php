<?php
// app/Livewire/Auth/Profile.php

namespace App\Livewire\Auth;

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Rule;
use Mary\Traits\Toast; // Tambahkan ini

new class extends Component {
    use Toast; // Gunakan trait Toast

    #[Rule('required|min:3')]
    public string $name = '';

    #[Rule('required|email')]
    public string $email = '';

    #[Rule('nullable|min:6')]
    public string $password = '';

    #[Rule('nullable|min:6|same:password')]
    public string $password_confirmation = '';

    public function mount()
    {
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;
    }

    public function save()
    {
        $validated = $this->validate();

        $user = auth()->user();
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $this->toast('success', 'Sukses!', 'Berhasil Diubah!');
        $this->dispatch('refresh');
    }
};
?>

<div>
    <x-form wire:submit="save" class="mt-6">
        <x-input label="Nama" wire:model="name" icon="o-user" />
        <x-input label="Email" wire:model="email" icon="o-envelope" />
        <x-password label="Password Baru" wire:model="password" right />
        <x-password label="Konfirmasi Password" wire:model="password_confirmation" right />

        <div class="flex items-center gap-4 mt-4">
            <x-button label="Simpan Perubahan" type="submit" icon="o-check" class="btn-primary" spinner="save" />
        </div>
    </x-form>
</div>
