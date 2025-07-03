<?php

use Livewire\Volt\Component;
use App\Models\Siswa;
use App\Models\Kelas;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $modalTambah = false;
    public $nis;
    public $nama_siswa;
    public $kelas_id;
    public $total_pelanggaran = 0;
    public $kelasOptions = [];

    public function mount()
    {
        $this->loadKelasOptions();
    }

    protected function loadKelasOptions()
    {
        $this->kelasOptions = Kelas::all()
            ->map(function ($kelas) {
                return [
                    'id' => $kelas->ID_Kelas,
                    'name' => $kelas->kelas . ' - ' . $kelas->jurusan,
                ];
            })
            ->toArray();
    }

    public function simpanSiswa()
    {
        try {
            $this->validate([
                'nis' => 'required|string|max:20|unique:tb_siswa,nis',
                'nama_siswa' => 'required|string|max:100',
                'kelas_id' => 'required|exists:tb_kelas,ID_Kelas',
                'total_pelanggaran' => 'nullable|integer|min:0',
            ], [
                'nis.required' => 'NIS harus diisi',
                'nis.unique' => 'NIS sudah digunakan',
                'nama_siswa.required' => 'Nama siswa harus diisi',
                'kelas_id.required' => 'Kelas harus dipilih',
            ]);

            Siswa::create([
                'nis' => $this->nis,
                'nama_siswa' => $this->nama_siswa,
                'kelas_id' => $this->kelas_id,
                'total_pelanggaran' => $this->total_pelanggaran ?? 0,
            ]);

            $this->reset(['nis', 'nama_siswa', 'kelas_id', 'total_pelanggaran']);
            $this->modalTambah = false;

            $this->showSuccessToast('Data siswa berhasil ditambahkan');

            $this->dispatch('siswa-ditambahkan');
            $this->dispatch('refresh');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->showErrorToast('Validasi gagal: ' . implode(' ', $e->validator->errors()->all()));
        } catch (\Exception $e) {
            $this->showErrorToast('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    protected function showSuccessToast($message, $title = 'Sukses!')
    {
        $this->toast(
            type: 'success',
            title: $title,
            description: $message,
            position: 'toast-top toast-end',
            icon: 'o-check-circle',
            css: 'alert-success',
            timeout: 3000
        );
    }

    protected function showErrorToast($message, $title = 'Error!')
    {
        $this->toast(
            type: 'error',
            title: $title,
            description: $message,
            position: 'toast-top toast-end',
            icon: 'o-x-circle',
            css: 'alert-error',
            timeout: 5000
        );
    }
};
?>

<div>
    <!-- Modal Tambah Siswa -->
    <x-modal wire:model="modalTambah" title="Tambah Siswa Baru" subtitle="Isi data siswa berikut" separator persistent>
        <x-form wire:submit="simpanSiswa">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="NIS" wire:model="nis" icon="o-identification" placeholder="Masukkan NIS" />
                <x-input label="Nama Siswa" wire:model="nama_siswa" icon="o-user" placeholder="Masukkan nama siswa" />
            </div>

            <x-select 
                label="Kelas" 
                wire:model="kelas_id" 
                :options="$kelasOptions" 
                option-label="name" 
                option-value="id"
                icon="o-academic-cap" 
                placeholder="Pilih Kelas"
                class="mt-4"
            />

            <x-slot:actions>
                <x-button label="Batal" @click="$wire.modalTambah = false" />
                <x-button label="Simpan" type="submit" class="btn-primary" spinner="simpanSiswa" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <!-- Tombol Buka Modal -->
    <x-button icon="o-plus" class="btn-primary" @click="$wire.modalTambah = true" label="Tambah Siswa" />
</div>
