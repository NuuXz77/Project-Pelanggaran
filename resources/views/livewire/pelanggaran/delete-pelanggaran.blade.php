<?php

namespace App\Livewire;

use Livewire\Volt\Component;
use App\Models\Pelanggaran;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public $deleteModal = false;
    public $pelanggaran_id;
    public $nama_siswa;
    public $pelanggaran;

    // Menerima event dari $dispatch
    protected $listeners = ['showDeleteModal' => 'openModal'];

    // Method untuk membuka modal dan mengambil data
    public function openModal($id)
    {
        $id = is_array($id) ? $id : $id;
        $pelanggaran = Pelanggaran::find($id);

        if ($pelanggaran) {
            $this->pelanggaran_id = $pelanggaran->ID_Pelanggaran;
            $this->nama_siswa = $pelanggaran->nama_siswa;
            $this->pelanggaran = $pelanggaran->pelanggaran;
            $this->deleteModal = true;
        } else {
            $this->toast(
                type: 'error',
                title: 'Error!',
                description: 'Data pelanggaran tidak ditemukan.',
                position: 'toast-top toast-end',
                icon: 'o-information-circle',
                css: 'alert-error',
                timeout: 3000
            );
        }
    }

    // Method untuk menghapus data
    public function deletePelanggaran()
    {
        try {
            $pelanggaran = Pelanggaran::find($this->pelanggaran_id);

            if ($pelanggaran) {
                // Update counter pelanggaran siswa sebelum menghapus
                $pelanggaran->siswa()->decrement('total_pelanggaran');
                
                // Hapus data pelanggaran
                $pelanggaran->delete();
                
                $this->toast(
                    type: 'success',
                    title: 'Berhasil!',
                    description: 'Data pelanggaran berhasil dihapus.',
                    position: 'toast-top toast-end',
                    icon: 'o-check-circle',
                    css: 'alert-success',
                    timeout: 3000
                );

                $this->dispatch('pelanggaran-dihapus');
                $this->dispatch('refresh');
                $this->deleteModal = false;
            } else {
                $this->toast(
                    type: 'error',
                    title: 'Error!',
                    description: 'Data pelanggaran tidak ditemukan.',
                    position: 'toast-top toast-end',
                    icon: 'o-x-circle',
                    css: 'alert-error',
                    timeout: 3000
                );
            }
        } catch (\Exception $e) {
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
        $this->reset(['pelanggaran_id', 'nama_siswa', 'pelanggaran']);
    }
};
?>

<div>
    <!-- Modal untuk menghapus data pelanggaran -->
    <x-mary-modal wire:model="deleteModal" title="Konfirmasi Hapus Pelanggaran" persistent class="backdrop-blur">
        <div class="mb-5">
            Apakah Anda yakin ingin menghapus pelanggaran siswa: 
            <strong>{{ $nama_siswa }}</strong> untuk pelanggaran: 
            <strong>{{ $pelanggaran }}</strong>?
        </div>

        <x-slot:actions>
            <!-- Tombol Hapus -->
            <x-mary-button 
                label="Hapus" 
                class="btn-error" 
                wire:click="deletePelanggaran" 
                spinner="deletePelanggaran" 
            />
            <!-- Tombol Batal -->
            <x-mary-button 
                label="Batal" 
                @click="$wire.closeModal()" 
            />
        </x-slot:actions>
    </x-mary-modal>
</div>