<?php

namespace App\Livewire;

use Livewire\Volt\Component;
use App\Models\Tindakan;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public $deleteModal = false; // Modal untuk menghapus data
    public $tindakan_id; // ID tindakan yang akan dihapus
    public $kode_tindakan; // Kode tindakan untuk ditampilkan di modal

    // Menerima event dari $dispatch
    protected $listeners = ['showDeleteModal' => 'openModal'];

    // Method untuk membuka modal dan mengambil data berdasarkan tindakan_id
    public function openModal($id)
    {
        // Ambil data tindakan berdasarkan ID
        $tindakan = Tindakan::where('ID_Tindakan', $id)->first();

        if ($tindakan) {
            // Isi properti dengan data yang ditemukan
            $this->tindakan_id = $tindakan->ID_Tindakan;
            $this->kode_tindakan = $tindakan->kode_tindakan;

            // Buka modal
            $this->deleteModal = true;
        } else {
            // Beri feedback jika data tidak ditemukan
            $this->toast(
                type: 'error',
                title: 'Error!',
                description: 'Tindakan tidak ditemukan.',
                position: 'toast-top toast-end',
                icon: 'o-information-circle',
                css: 'alert-error',
                timeout: 3000
            );
        }
    }

    // Method untuk menghapus data tindakan
    public function deleteTindakan()
    {
        try {
            // Cari tindakan berdasarkan ID
            $tindakan = Tindakan::where('ID_Tindakan', $this->tindakan_id)->first();

            if ($tindakan) {
                // Hapus data tindakan
                $tindakan->delete();
                
                // Toast sukses
                $this->toast(
                    type: 'success',
                    title: 'Berhasil!',
                    description: 'Tindakan berhasil dihapus.',
                    position: 'toast-top toast-end',
                    icon: 'o-check-circle',
                    css: 'alert-success',
                    timeout: 3000
                );

                $this->dispatch('tindakan-dihapus');
                $this->dispatch('refresh');
                $this->deleteModal = false; // Tutup modal
            } else {
                // Toast error jika tindakan tidak ditemukan
                $this->toast(
                    type: 'error',
                    title: 'Error!',
                    description: 'Tindakan tidak ditemukan.',
                    position: 'toast-top toast-end',
                    icon: 'o-x-circle',
                    css: 'alert-error',
                    timeout: 3000
                );
            }
        } catch (\Exception $e) {
            // Toast error jika terjadi exception
            $this->toast(
                type: 'error',
                title: 'Error!',
                description: 'Terjadi kesalahan: ' . $e->getMessage(),
                position: 'toast-top toast-end',
                icon: 'o-x-circle',
                css: 'alert-error',
                timeout: 5000
            );
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
        $this->reset(['tindakan_id', 'kode_tindakan']);
    }
};
?>

<div>
    <!-- Modal untuk menghapus data tindakan -->
    <x-mary-modal wire:model="deleteModal" title="Konfirmasi Hapus Tindakan" persistent class="backdrop-blur">
        <div class="mb-5">
            Apakah Anda yakin ingin menghapus tindakan dengan kode: <strong>{{ $kode_tindakan }}</strong>?
        </div>

        <x-slot:actions>
            <!-- Tombol Hapus -->
            <x-mary-button 
                label="Hapus" 
                class="btn-error" 
                wire:click="deleteTindakan" 
                spinner="deleteTindakan" 
            />
            <!-- Tombol Batal -->
            <x-mary-button 
                label="Batal" 
                @click="$wire.closeModal()" 
            />
        </x-slot:actions>
    </x-mary-modal>
</div>