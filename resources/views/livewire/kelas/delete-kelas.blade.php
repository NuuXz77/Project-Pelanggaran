<?php

namespace App\Livewire;

use Livewire\Volt\Component;
use App\Models\Kelas;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public $deleteModal = false;
    public $kelas_id;
    public $kelas;
    public $jurusan;
    public $wali_kelas;

    // Listener untuk event dari parent
    protected $listeners = ['showDeleteModal' => 'openModal'];

    // Buka modal dan load data
    public function openModal($id)
    {
        $id = is_array($id) ? $id : $id;
        $kelas = Kelas::with('guru')->find($id);

        if ($kelas) {
            $this->kelas_id = $kelas->ID_Kelas;
            $this->kelas = $kelas->kelas;
            $this->jurusan = $kelas->jurusan;
            $this->wali_kelas = $kelas->guru ? $kelas->guru->nama_guru : '-';
            $this->deleteModal = true;
        } else {
            $this->toast(
                type: 'error',
                title: 'Error!',
                description: 'Data kelas tidak ditemukan.',
                position: 'toast-top toast-end',
                icon: 'o-information-circle',
                css: 'alert-error',
                timeout: 3000
            );
        }
    }

    // Hapus data kelas
    public function deleteKelas()
    {
        try {
            $kelas = Kelas::find($this->kelas_id);

            if ($kelas) {
                // Hapus relasi siswa dan pelanggaran terlebih dahulu
                $kelas->siswa()->update(['kelas_id' => null]);
                $kelas->pelanggaran()->delete();
                
                // Hapus kelas
                $kelas->delete();
                
                $this->toast(
                    type: 'success',
                    title: 'Berhasil!',
                    description: 'Data kelas berhasil dihapus.',
                    position: 'toast-top toast-end',
                    icon: 'o-check-circle',
                    css: 'alert-success',
                    timeout: 3000
                );

                $this->dispatch('kelas-dihapus');
                $this->dispatch('refresh');
                $this->deleteModal = false;
            } else {
                $this->toast(
                    type: 'error',
                    title: 'Error!',
                    description: 'Data kelas tidak ditemukan.',
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

    // Tutup modal
    public function closeModal()
    {
        $this->deleteModal = false;
        $this->resetForm();
    }

    // Reset form
    public function resetForm()
    {
        $this->reset(['kelas_id', 'kelas', 'jurusan', 'wali_kelas']);
    }
};
?>

<div>
    <!-- Modal Delete Kelas -->
    <x-mary-modal wire:model="deleteModal" title="Konfirmasi Hapus Kelas" persistent class="backdrop-blur">
        <div class="mb-5 space-y-2">
            <p>Apakah Anda yakin ingin menghapus data kelas berikut?</p>
            <div class="bg-base-200 p-4 rounded-lg">
                <p><strong>Kelas:</strong> {{ $kelas }} {{ $jurusan }}</p>
                <p><strong>Wali Kelas:</strong> {{ $wali_kelas }}</p>
            </div>
            <p class="text-red-500 font-medium">Perhatian: Semua data siswa dan pelanggaran terkait akan diupdate!</p>
        </div>

        <x-slot:actions>
            <x-mary-button 
                label="Batal" 
                @click="$wire.closeModal()" 
            />
            <x-mary-button 
                label="Hapus" 
                class="btn-error" 
                wire:click="deleteKelas" 
                spinner="deleteKelas" 
            />
        </x-slot:actions>
    </x-mary-modal>
</div>
