<?php

namespace App\Livewire;

use Livewire\Volt\Component;
use App\Models\Guru;
use App\Models\Kelas;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public $deleteModal = false;
    public $guru_id;
    public $nama_guru;
    public $nip;
    public $kelas_wali;

    // Listener untuk event dari parent
    protected $listeners = ['showDeleteModal' => 'openModal'];

    // Buka modal dan load data
    public function openModal($id)
    {
        $id = is_array($id) ? $id['id'] : $id;
        $guru = Guru::with('kelas')->find($id);

        if ($guru) {
            $this->guru_id = $guru->ID_Guru;
            $this->nama_guru = $guru->nama_guru;
            $this->nip = $guru->nip;
            // $this->kelas_wali = $guru->kelas->kelas;
            $this->deleteModal = true;
        } else {
            $this->toast(type: 'error', title: 'Error!', description: 'Data guru tidak ditemukan.', position: 'toast-top toast-end', icon: 'o-information-circle', css: 'alert-error', timeout: 3000);
        }
    }

    // Hapus data guru
    public function deleteGuru()
    {
        try {
            $guru = Guru::find($this->guru_id);

            if ($guru) {
                // Hapus relasi kelas jika guru adalah wali kelas
                if ($guru->kelas) {
                    $guru->kelas()->update(['wali_kelas' => null]);
                }

                // Hapus guru
                $guru->delete();

                $this->toast(type: 'success', title: 'Berhasil!', description: 'Data guru berhasil dihapus.', position: 'toast-top toast-end', icon: 'o-check-circle', css: 'alert-success', timeout: 3000);

                $this->dispatch('guru-dihapus');
                $this->dispatch('refresh');
                $this->deleteModal = false;
            } else {
                $this->toast(type: 'error', title: 'Error!', description: 'Data guru tidak ditemukan.', position: 'toast-top toast-end', icon: 'o-x-circle', css: 'alert-error', timeout: 3000);
            }
        } catch (\Exception $e) {
            $this->toast(type: 'error', title: 'Error!', description: 'Terjadi kesalahan: ' . $e->getMessage(), position: 'toast-top toast-end', icon: 'o-x-circle', css: 'alert-error', timeout: 5000);
        }
    }

    // Tutup modal
    public function closeModal()
    {
        $this->deleteModal = false;
        $this->resetForm();
    }

    // Reset form
    public function resetForm()
    {
        $this->reset(['guru_id', 'nama_guru', 'nip', 'kelas_wali']);
    }
};
?>

<div>
    <!-- Modal Delete Guru -->
    <x-mary-modal wire:model="deleteModal" title="Konfirmasi Hapus Guru" persistent class="backdrop-blur">
        <div class="mb-5 space-y-2">
            <p>Apakah Anda yakin ingin menghapus data guru berikut?</p>
            <div class="bg-base-200 p-4 rounded-lg">
                <p><strong>Nama Guru:</strong> {{ $nama_guru }}</p>
                <p><strong>NIP:</strong> {{ $nip }}</p>
                <p><strong>Kelas Wali:</strong> {{ $kelas_wali }}</p>
            </div>
            <p class="text-red-500 font-medium">Perhatian: Jika guru ini wali kelas, kelas tersebut akan kehilangan wali
                kelasnya!</p>
        </div>

        <x-slot:actions>
            <x-mary-button label="Batal" @click="$wire.closeModal()" />
            <x-mary-button label="Hapus" class="btn-error" wire:click="deleteGuru" spinner="deleteGuru" />
        </x-slot:actions>
    </x-mary-modal>
</div>
