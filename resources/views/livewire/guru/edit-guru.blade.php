<?php

use Livewire\Volt\Component;
use App\Models\Guru;
use App\Models\Kelas;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $modalEdit = false;
    public $guruId;
    public $nama_guru;
    public $nip;
    public $password;
    public $kelas_id;
    public $kelasOptions = [];

    // Listener untuk event dari parent
    protected $listeners = ['showEditModal' => 'openModal'];

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
                    'name' => $kelas->kelas . ' ' . $kelas->jurusan, // Format: "10 AKL"
                ];
            })
            ->toArray();
    }

    // Buka modal edit
    public function openModal($id)
    {
        $this->guruId = is_array($id) ? $id['id'] : $id;
        $this->loadGuruData($this->guruId);
        $this->modalEdit = true;
    }

    // Load data guru untuk edit
    protected function loadGuruData($id)
    {
        $guru = Guru::findOrFail($id);
        $this->nama_guru = $guru->nama_guru;
        $this->nip = $guru->nip;
        $this->kelas_id = $guru->kelas_id;
        // Password tidak di-load untuk keamanan
    }

    // Update data guru
    public function updateGuru()
    {
        try {
            $rules = [
                'nama_guru' => 'required|string|max:100',
                'nip' => 'required|string|max:18|unique:tb_guru,nip,'.$this->guruId.',ID_Guru',
                // 'kelas_id' => 'nullable|exists:tb_kelas,ID_Kelas',
            ];

            // Hanya validasi password jika diisi
            if ($this->password) {
                $rules['password'] = 'string|min:6';
            }

            $this->validate($rules, [
                'nama_guru.required' => 'Nama guru harus diisi',
                'nama_guru.max' => 'Nama maksimal 100 karakter',
                'nip.required' => 'NIP harus diisi',
                'nip.max' => 'NIP maksimal 18 karakter',
                'nip.unique' => 'NIP sudah digunakan',
                'password.min' => 'Password minimal 6 karakter',
                'kelas_id.exists' => 'Kelas tidak valid',
            ]);

            $updateData = [
                'nama_guru' => $this->nama_guru,
                'nip' => $this->nip,
                'kelas_id' => $this->kelas_id,
                'kelas' => $this->kelas_id ? Kelas::find($this->kelas_id)->kelas . ' ' . Kelas::find($this->kelas_id)->jurusan : null,
            ];

            // Update password hanya jika diisi
            if ($this->password) {
                $updateData['password'] = bcrypt($this->password);
            }

            Guru::where('ID_Guru', $this->guruId)->update($updateData);

            $this->resetForm();
            $this->showSuccessToast('Data guru berhasil diperbarui');
            $this->dispatch('guru-diperbarui');
            $this->dispatch('refresh');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->showErrorToast('Validasi gagal: ' . implode(' ', $e->validator->errors()->all()));
        } catch (\Exception $e) {
            $this->showErrorToast('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Reset form
    public function resetForm()
    {
        $this->reset(['nama_guru', 'nip', 'password', 'kelas_id', 'guruId']);
        $this->modalEdit = false;
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
    <!-- Modal Edit Guru -->
    <x-modal wire:model="modalEdit" title="Edit Data Guru" subtitle="Perbarui data guru berikut" separator persistent>
        <x-form wire:submit="updateGuru">
            <div class="grid grid-cols-1 gap-4">
                <x-input label="Nama Guru" wire:model="nama_guru" icon="o-user" placeholder="Nama lengkap guru" />
                <x-input label="NIP" wire:model="nip" icon="o-identification" placeholder="Nomor Induk Pegawai" />
                <x-input label="Password Baru (Kosongkan jika tidak diubah)" 
                    wire:model="password" 
                    type="password" 
                    icon="o-lock-closed" 
                    placeholder="Minimal 6 karakter" />
            </div>

            <x-select 
                label="Kelas Wali (Opsional)" 
                wire:model="kelas_id" 
                :options="$kelasOptions" 
                option-label="name" 
                option-value="id"
                icon="o-academic-cap" 
                placeholder="Pilih Kelas Wali"
                class="mt-4"
            />

            <x-slot:actions>
                <x-button label="Batal" @click="$wire.resetForm()" />
                <x-button label="Simpan Perubahan" type="submit" class="btn-primary" spinner="updateGuru" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>