<?php

namespace App\Livewire;

use Livewire\Volt\Component;
use App\Models\Peraturan;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public $deleteModal = false; // Modal untuk menghapus data
    public $peraturan_id; // ID peraturan yang akan dihapus
    public $kode_peraturan; // Kode peraturan untuk ditampilkan di modal

    // Menerima event dari $dispatch
    protected $listeners = ['showDeleteModal' => 'openModal'];

    // Method untuk membuka modal dan mengambil data berdasarkan peraturan_id
    public function openModal($id)
    {
        // Ambil data peraturan berdasarkan ID
        $peraturan = Peraturan::where('ID_Peraturan', $id)->first();

        if ($peraturan) {
            // Isi properti dengan data yang ditemukan
            $this->peraturan_id = $peraturan->ID_Peraturan;
            $this->kode_peraturan = $peraturan->kode_peraturan;

            // Buka modal
            $this->deleteModal = true;
        } else {
            // Beri feedback jika data tidak ditemukan
            $this->toast(type: 'error', title: 'Error!', description: 'Peraturan tidak ditemukan.', position: 'toast-top toast-end', icon: 'o-information-circle', css: 'alert-error', timeout: 3000);
        }
    }

    // Method untuk menghapus data peraturan
    public function deletePeraturan()
    {
        try {
            // Cari peraturan berdasarkan ID
            $peraturan = Peraturan::where('ID_Peraturan', $this->peraturan_id)->first();

            if ($peraturan) {
                // Hapus data peraturan
                $peraturan->delete();
                $this->dispatch('refresh');

                // Toast sukses
                $this->toast(type: 'success', title: 'Berhasil!', description: 'Peraturan berhasil dihapus.', position: 'toast-top toast-end', icon: 'o-check-circle', css: 'alert-success', timeout: 3000);

                // $this->dispatch('peraturan-dihapus');
                $this->deleteModal = false; // Tutup modal
            } else {
                // Toast error jika peraturan tidak ditemukan
                $this->toast(type: 'error', title: 'Error!', description: 'Peraturan tidak ditemukan.', position: 'toast-top toast-end', icon: 'o-x-circle', css: 'alert-error', timeout: 3000);
            }
        } catch (\Exception $e) {
            // Toast error jika terjadi exception
            $this->toast(type: 'error', title: 'Error!', description: 'Terjadi kesalahan: ' . $e->getMessage(), position: 'toast-top toast-end', icon: 'o-x-circle', css: 'alert-error', timeout: 5000);
        }
    }

    // Method untuk menutup modal
    public function closeModal()
    {
        $this->deleteModal = false;
        $this->resetForm();
    }

    // Method untuk reset form
    public function resetForm()
    {
        $this->reset(['peraturan_id', 'kode_peraturan']);
    }
};
?>

<div>
    <!-- Modal untuk menghapus data peraturan -->
    <x-mary-modal wire:model="deleteModal" title="Konfirmasi Hapus Peraturan" persistent class="backdrop-blur">
        <div class="mb-5">
            Apakah Anda yakin ingin menghapus peraturan dengan kode: <strong>{{ $kode_peraturan }}</strong>?
        </div>

        <x-slot:actions>
            <!-- Tombol Hapus -->
            <x-mary-button label="Hapus" class="btn-error" wire:click="deletePeraturan" spinner="deletePeraturan" />
            <!-- Tombol Batal -->
            <x-mary-button label="Batal" @click="$wire.closeModal()" />
        </x-slot:actions>
    </x-mary-modal>
</div>
