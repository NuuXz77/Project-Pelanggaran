<?php

use Livewire\Volt\Component;
use App\Models\Siswa;
use App\Models\Kelas;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $modalEdit = false;
    public $siswaId;
    public $nis;
    public $nama_siswa;
    public $kelas_id;
    public $total_pelanggaran = 0;
    public $kelasOptions = [];

    protected $listeners = ['showEditModal' => 'openModal'];

    public function mount()
    {
        $this->loadKelasOptions();
    }

    protected function loadKelasOptions()
    {
        $this->kelasOptions = Kelas::all()
            ->map(fn ($kelas) => [
                'id' => $kelas->ID_Kelas,
                'name' => $kelas->kelas . ' - ' . $kelas->jurusan,
            ])
            ->toArray();
    }

    public function openModal($id)
    {
        $this->siswaId = is_array($id) ? $id : $id;
        $this->loadSiswaData($this->siswaId);
        $this->modalEdit = true;
    }

    protected function loadSiswaData($id)
    {
        $siswa = Siswa::findOrFail($id);
        $this->nis = $siswa->nis;
        $this->nama_siswa = $siswa->nama_siswa;
        $this->kelas_id = $siswa->kelas_id;
        $this->total_pelanggaran = $siswa->total_pelanggaran;
    }

    public function updateSiswa()
    {
        try {
            $this->validate([
                'nis' => 'required|string|max:20|unique:tb_siswa,nis,' . $this->siswaId . ',ID_Siswa',
                'nama_siswa' => 'required|string|max:100',
                'kelas_id' => 'required|exists:tb_kelas,ID_Kelas',
                'total_pelanggaran' => 'nullable|integer|min:0',
            ]);

            Siswa::where('ID_Siswa', $this->siswaId)->update([
                'nis' => $this->nis,
                'nama_siswa' => $this->nama_siswa,
                'kelas_id' => $this->kelas_id,
                'total_pelanggaran' => $this->total_pelanggaran ?? 0,
            ]);

            $this->resetForm();
            $this->showSuccessToast('Data siswa berhasil diperbarui');
            $this->dispatch('siswa-diperbarui');
            $this->dispatch('refresh');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->showErrorToast('Validasi gagal: ' . implode(' ', $e->validator->errors()->all()));
        } catch (\Exception $e) {
            $this->showErrorToast('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset(['nis', 'nama_siswa', 'kelas_id', 'total_pelanggaran', 'siswaId']);
        $this->modalEdit = false;
    }

    protected function showSuccessToast($message, $title = 'Sukses!')
    {
        $this->toast(type: 'success', title: $title, description: $message, position: 'toast-top toast-end', icon: 'o-check-circle', css: 'alert-success', timeout: 3000);
    }

    protected function showErrorToast($message, $title = 'Error!')
    {
        $this->toast(type: 'error', title: $title, description: $message, position: 'toast-top toast-end', icon: 'o-x-circle', css: 'alert-error', timeout: 5000);
    }
};
?>

<div>
    <!-- Modal Edit Siswa -->
    <x-modal wire:model="modalEdit" title="Edit Data Siswa" subtitle="Perbarui data siswa berikut" separator persistent>
        <x-form wire:submit="updateSiswa">
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
                <x-button label="Batal" @click="$wire.resetForm()" />
                <x-button label="Simpan Perubahan" type="submit" class="btn-primary" spinner="updateSiswa" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
