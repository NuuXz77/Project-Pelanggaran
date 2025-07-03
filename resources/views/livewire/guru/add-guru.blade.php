<?php

use Livewire\Volt\Component;
use App\Models\Guru;
use App\Models\Kelas;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $modalTambah = false;
    public $nama_guru;
    public $nip;
    public $password;
    public $kelas_id;
    public $kelasOptions = [];

    public function mount()
    {
        $this->loadKelasOptions();
    }

    // Load data kelas untuk dropdown
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

    public function simpanGuru()
    {
        try {
            $this->validate(
                [
                    'nama_guru' => 'required|string|max:100',
                    'nip' => 'required|string|max:18|unique:tb_guru,nip',
                    'password' => 'required|string|min:6',
                    'kelas_id' => 'nullable|exists:tb_kelas,ID_Kelas|unique:tb_kelas,ID_Kelas',
                ],
                [
                    'nama_guru.required' => 'Nama guru harus diisi',
                    'nama_guru.max' => 'Nama guru maksimal 100 karakter',
                    'nip.required' => 'NIP harus diisi',
                    'nip.max' => 'NIP maksimal 18 karakter',
                    'nip.unique' => 'NIP sudah terdaftar',
                    'password.required' => 'Password harus diisi',
                    'password.min' => 'Password minimal 6 karakter',
                    'kelas_id.exists' => 'Kelas tidak valid',
                    'kelas_id.unique' => 'Wali kelas sudah ada',
                ],
            );

            // Buat guru baru
            Guru::create([
                'nama_guru' => $this->nama_guru,
                'nip' => $this->nip,
                'password' => bcrypt($this->password),
                'kelas_id' => $this->kelas_id,
                'kelas' => $this->kelas_id ? Kelas::find($this->kelas_id)->kelas . ' ' . Kelas::find($this->kelas_id)->jurusan : null,
            ]);

            // Reset form
            $this->reset(['nama_guru', 'nip', 'password', 'kelas_id']);
            $this->modalTambah = false;

            // Notifikasi sukses
            $this->showSuccessToast('Data guru berhasil ditambahkan');

            // Emit event untuk refresh data
            $this->dispatch('guru-ditambahkan');
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
        $this->toast(type: 'success', title: $title, description: $message, position: 'toast-top toast-end', icon: 'o-check-circle', css: 'alert-success', timeout: 3000);
    }

    protected function showErrorToast($message, $title = 'Error!')
    {
        $this->toast(type: 'error', title: $title, description: $message, position: 'toast-top toast-end', icon: 'o-x-circle', css: 'alert-error', timeout: 5000);
    }
}; ?>

<div>
    <!-- Modal Tambah Guru -->
    <x-modal wire:model="modalTambah" title="Tambah Guru Baru" subtitle="Isi data guru berikut" separator persistent>
        <x-form wire:submit="simpanGuru">
            <div class="grid grid-cols-1 gap-4">
                <x-input label="Nama Guru" wire:model="nama_guru" icon="o-user" placeholder="Masukkan nama lengkap" />
                <x-input label="NIP" wire:model="nip" icon="o-identification" placeholder="Masukkan NIP" />
                <x-input label="Password" wire:model="password" type="password" icon="o-lock-closed"
                    placeholder="Minimal 6 karakter" />
            </div>

            <x-select label="Kelas Wali (Opsional)" wire:model="kelas_id" :options="$kelasOptions" option-label="name"
                option-value="id" icon="o-academic-cap" placeholder="Pilih Kelas Wali" class="mt-4" />

            <x-slot:actions>
                <x-button label="Batal" @click="$wire.modalTambah = false" />
                <x-button label="Simpan" type="submit" class="btn-primary" spinner="simpanGuru" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <!-- Tombol Buka Modal -->
    <x-button icon="o-plus" class="btn-primary" @click="$wire.modalTambah = true" label="Tambah Guru" />
</div>
