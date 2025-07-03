<?php

use Livewire\Volt\Component;
use App\Models\Kelas;
use App\Models\Guru;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $modalTambah = false;
    public $kelas;
    public $jurusan;
    public $wali_kelas_id;
    public $guruOptions = [];

    public function mount()
    {
        $this->loadGuruOptions();
    }

    // Load data guru untuk dropdown wali kelas
    protected function loadGuruOptions()
    {
        $this->guruOptions = Guru::all()
            ->map(function ($guru) {
                return [
                    'id' => $guru->ID_Guru,
                    'name' => $guru->nama_guru . ' - ' . $guru->nip,
                ];
            })
            ->toArray();
    }

    public function simpanKelas()
    {
        try {
            $this->validate([
                'kelas' => 'required|string|max:10',
                'jurusan' => 'required|string|max:50',
                'wali_kelas_id' => 'required|exists:tb_guru,ID_Guru',
            ], [
                'kelas.required' => 'Nama kelas harus diisi',
                'kelas.max' => 'Nama kelas maksimal 10 karakter',
                'jurusan.required' => 'Jurusan harus diisi',
                'jurusan.max' => 'Jurusan maksimal 50 karakter',
                'wali_kelas_id.required' => 'Wali kelas harus dipilih',
                'wali_kelas_id.exists' => 'Wali kelas tidak valid',
            ]);

            // Buat kelas baru
            Kelas::create([
                'kelas' => $this->kelas,
                'jurusan' => $this->jurusan,
                'wali_kelas' => $this->wali_kelas_id,
            ]);

            // Reset form
            $this->reset(['kelas', 'jurusan', 'wali_kelas_id']);
            $this->modalTambah = false;

            // Notifikasi sukses
            $this->showSuccessToast('Data kelas berhasil ditambahkan');

            // Emit event untuk refresh data
            $this->dispatch('kelas-ditambahkan');
            $this->dispatch('refresh');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->showErrorToast('Validasi gagal: ' . implode(' ', $e->validator->errors()->all()));
        } catch (\Exception $e) {
            $this->showErrorToast('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Toast helper methods
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
}; ?>

<div>
    <!-- Modal Tambah Kelas -->
    <x-modal wire:model="modalTambah" title="Tambah Kelas Baru" subtitle="Isi data kelas berikut" separator persistent>
        <x-form wire:submit="simpanKelas">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Kelas" wire:model="kelas" icon="o-academic-cap" placeholder="Contoh: X, XI, XII" />
                <x-input label="Jurusan" wire:model="jurusan" icon="o-bookmark" placeholder="Contoh: IPA, IPS, Bahasa" />
            </div>

            <x-select 
                label="Wali Kelas" 
                wire:model="wali_kelas_id" 
                :options="$guruOptions" 
                option-label="name" 
                option-value="id"
                icon="o-user-circle" 
                placeholder="Pilih Wali Kelas"
                class="mt-4"
            />

            <x-slot:actions>
                <x-button label="Batal" @click="$wire.modalTambah = false" />
                <x-button label="Simpan" type="submit" class="btn-primary" spinner="simpanKelas" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <!-- Tombol Buka Modal -->
    <x-button icon="o-plus" class="btn-primary" @click="$wire.modalTambah = true" label="Tambah Kelas" />
</div>
