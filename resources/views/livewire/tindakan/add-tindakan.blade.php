<?php

use Livewire\Volt\Component;
use App\Models\Tindakan;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $modalTambah = false;
    public $kode_tindakan;
    public $jenis;
    public $keterangan;
    public $jenisOptions = [['id' => 'ringan', 'name' => 'Ringan'], ['id' => 'berat', 'name' => 'Berat']];

    public function mount()
    {
        $this->generateKodeTindakan();
    }

    protected function generateKodeTindakan()
    {
        $count = Tindakan::count() + 1;
        $prefix = $this->jenis == 'berat' ? 'B-' : 'R-';
        $this->kode_tindakan = $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function updatedJenis($value)
    {
        $this->generateKodeTindakan();
    }

    public function simpanTindakan()
    {
        try {
            // Validasi dengan pesan custom
            $this->validate(
                [
                    'kode_tindakan' => 'required|unique:tb_tindakan,kode_tindakan',
                    'jenis' => 'required|in:ringan,berat',
                    'keterangan' => 'required|min:3',
                ],
                [
                    'kode_tindakan.required' => 'Kode tindakan harus diisi',
                    'kode_tindakan.unique' => 'Kode tindakan sudah digunakan',
                    'jenis.required' => 'Jenis tindakan harus dipilih',
                    'jenis.in' => 'Jenis tindakan tidak valid',
                    'keterangan.required' => 'Keterangan harus diisi',
                    'keterangan.min' => 'Keterangan minimal 3 karakter',
                ],
            );

            Tindakan::create([
                'kode_tindakan' => $this->kode_tindakan,
                'jenis' => $this->jenis,
                'keterangan' => $this->keterangan,
            ]);

            // Reset form
            $this->reset(['jenis', 'keterangan']);
            $this->generateKodeTindakan();
            $this->modalTambah = false;

            // Notifikasi sukses
            $this->showSuccessToast('Tindakan baru berhasil ditambahkan');

            // Emit event untuk refresh data
            $this->dispatch('tindakan-ditambahkan');
            $this->dispatch('refresh');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Notifikasi validasi gagal
            $this->showErrorToast('Validasi gagal: ' . implode(' ', $e->validator->errors()->all()));
        } catch (\Exception $e) {
            // Notifikasi error umum
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

    protected function showWarningToast($message, $title = 'Peringatan!')
    {
        $this->toast(type: 'warning', title: $title, description: $message, position: 'toast-top toast-end', icon: 'o-exclamation-triangle', css: 'alert-warning', timeout: 4000);
    }
}; ?>

<div>
    <!-- Modal Tambah Tindakan -->
    <x-modal wire:model="modalTambah" title="Tambah Tindakan Baru" subtitle="Isi form berikut">
        <x-form wire:submit="simpanTindakan" no-separator>
            <x-input label="Kode Tindakan" wire:model="kode_tindakan" icon="o-hashtag" readonly />

            <x-select label="Jenis Tindakan" wire:model.live="jenis" :options="$jenisOptions" option-label="name"
                option-value="id" icon="o-scale" placeholder="Pilih Jenis Tindakan" />

            <x-textarea label="Keterangan" wire:model="keterangan" icon="o-document-text"
                placeholder="Deskripsi tindakan" rows="3" />

            <x-slot:actions>
                <x-button label="Batal" @click="$wire.modalTambah = false" />
                <x-button label="Simpan" type="submit" class="btn-primary" spinner="simpanTindakan" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <!-- Tombol Buka Modal -->
    <x-button icon="o-plus" class="btn-primary" @click="$wire.modalTambah = true" />
</div>
