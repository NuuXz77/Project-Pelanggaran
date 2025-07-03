<?php

use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $resetModal = false;
    public $akun_id;
    public $nama;
    public string $defaultPassword = 'gurusmkn1ciamis'; // ← bisa kamu ubah kapan saja

    protected $listeners = ['showResetModal' => 'openModal'];

    public function openModal($id)
    {
        $akun = User::find($id);

        if ($akun && $akun->role === 'guru') {
            $this->akun_id = $akun->ID_Akun;
            $this->nama = $akun->name;
            $this->resetModal = true;
        } else {
            $this->toast(type: 'error', title: 'Error', description: 'Akun GURU tidak ditemukan.', icon: 'o-x-circle', css: 'alert-error');
        }
    }

    public function resetPassword()
    {
        try {
            $user = User::find($this->akun_id);

            if ($user) {
                $user->update([
                    'password' => Hash::make($this->defaultPassword),
                ]);

                $this->toast(type: 'success', title: 'Berhasil', description: 'Password berhasil direset ke default.', icon: 'o-check-circle', css: 'alert-success');

                $this->dispatch('refresh');
                $this->resetModal = false;
            } else {
                $this->toast(type: 'error', title: 'Error', description: 'Akun tidak ditemukan.', icon: 'o-x-circle', css: 'alert-error');
            }
        } catch (\Exception $e) {
            $this->toast(type: 'error', title: 'Error', description: 'Terjadi kesalahan: ' . $e->getMessage(), icon: 'o-x-circle', css: 'alert-error');
        }
    }

    public function closeModal()
    {
        $this->resetModal = false;
        $this->reset(['akun_id', 'nama']);
    }
};
?>
<div>
    <!-- Modal Reset -->
    <x-mary-modal wire:model="resetModal" title="Reset Password Akun" class="backdrop-blur" persistent>
        <div class="space-y-3">
            <p>Yakin ingin mereset password akun berikut ke default?</p>
            <div class="bg-base-200 p-4 rounded-lg">
                <p><strong>Nama Akun:</strong> {{ $nama }}</p>
                <p><strong>Password Baru:</strong> {{ $defaultPassword }}</p>
            </div>
        </div>

        <x-slot:actions>
            <x-mary-button label="Batal" @click="$wire.closeModal()" />
            <x-mary-button label="Reset Password" class="btn-warning" wire:click="resetPassword" spinner="resetPassword" />
        </x-slot:actions>
    </x-mary-modal>
</div>
