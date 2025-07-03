<?php

namespace App\Livewire;

use Livewire\Volt\Component;
use App\Models\Siswa;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public $deleteModal = false;
    public $siswa_id;
    public $nis;
    public $nama_siswa;
    public $kelas;

    protected $listeners = ['showDeleteModal' => 'openModal'];

    public function openModal($id)
    {
        $id = is_array($id) ? $id : $id;
        $siswa = Siswa::with('kelas')->find($id);

        if ($siswa) {
            $this->siswa_id = $siswa->ID_Siswa;
            $this->nis = $siswa->nis;
            $this->nama_siswa = $siswa->nama_siswa;
            $this->kelas = $siswa->kelas ? $siswa->kelas->kelas . ' - ' . $siswa->kelas->jurusan : '-';
            $this->deleteModal = true;
        } else {
            $this->toast(
                type: 'error',
                title: 'Error!',
                description: 'Data siswa tidak ditemukan.',
                position: 'toast-top toast-end',
                icon: 'o-information-circle',
                css: 'alert-error',
                timeout: 3000
            );
        }
    }

    public function deleteSiswa()
    {
        try {
            $siswa = Siswa::find($this->siswa_id);

            if ($siswa) {
                $siswa->delete();

                $this->toast(
                    type: 'success',
                    title: 'Berhasil!',
                    description: 'Data siswa berhasil dihapus.',
                    position: 'toast-top toast-end',
                    icon: 'o-check-circle',
                    css: 'alert-success',
                    timeout: 3000
                );

                $this->dispatch('siswa-dihapus');
                $this->dispatch('refresh');
                $this->deleteModal = false;
            } else {
                $this->toast(
                    type: 'error',
                    title: 'Error!',
                    description: 'Data siswa tidak ditemukan.',
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

    public function closeModal()
    {
        $this->deleteModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['siswa_id', 'nis', 'nama_siswa', 'kelas']);
    }
};
?>

<div>
    <!-- Modal Delete Siswa -->
    <x-mary-modal wire:model="deleteModal" title="Konfirmasi Hapus Siswa" persistent class="backdrop-blur">
        <div class="mb-5 space-y-2">
            <p>Apakah Anda yakin ingin menghapus data siswa berikut?</p>
            <div class="bg-base-200 p-4 rounded-lg">
                <p><strong>NIS:</strong> {{ $nis }}</p>
                <p><strong>Nama:</strong> {{ $nama_siswa }}</p>
                <p><strong>Kelas:</strong> {{ $kelas }}</p>
            </div>
            <p class="text-red-500 font-medium">Perhatian: Data pelanggaran siswa ini juga dapat terpengaruh!</p>
        </div>

        <x-slot:actions>
            <x-mary-button label="Batal" @click="$wire.closeModal()" />
            <x-mary-button label="Hapus" class="btn-error" wire:click="deleteSiswa" spinner="deleteSiswa" />
        </x-slot:actions>
    </x-mary-modal>
</div>
