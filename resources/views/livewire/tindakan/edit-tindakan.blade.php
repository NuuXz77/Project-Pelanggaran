<?php

use Livewire\Volt\Component;
use App\Models\Tindakan;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $modalEdit = false;
    public $tindakanId;
    public $kode_tindakan;
    public $jenis;
    public $keterangan;
    public $jenisOptions = [['id' => 'ringan', 'name' => 'Ringan'], ['id' => 'berat', 'name' => 'Berat']];

    // Listener untuk membuka modal edit
    protected $listeners = ['showEditModal' => 'openModal'];

    public function mount()
    {
        // Tidak perlu load data awal di sini
    }

    // Buka modal edit
    public function openModal($id)
    {
        if (isset($id)) {
            $this->tindakanId = $id;
            $this->loadTindakanData($this->tindakanId);
            $this->modalEdit = true;
        }
    }

    // Load data tindakan untuk edit
    protected function loadTindakanData($id)
    {
        $tindakan = Tindakan::findOrFail($id);

        $this->kode_tindakan = $tindakan->kode_tindakan;
        $this->jenis = $tindakan->jenis;
        $this->keterangan = $tindakan->keterangan;
    }

    public function updateTindakan()
    {
        try {
            // Validasi dengan pesan custom
            $rules = [
                'kode_tindakan' => 'required|unique:tb_tindakan,kode_tindakan,' . $this->tindakanId . ',ID_Tindakan',
                'jenis' => 'required|in:ringan,berat',
                'keterangan' => 'required|min:3',
            ];

            $messages = [
                'kode_tindakan.required' => 'Kode tindakan harus diisi',
                'kode_tindakan.unique' => 'Kode tindakan sudah digunakan',
                'jenis.required' => 'Jenis tindakan harus dipilih',
                'jenis.in' => 'Jenis tindakan tidak valid',
                'keterangan.required' => 'Keterangan harus diisi',
                'keterangan.min' => 'Keterangan minimal 3 karakter',
            ];

            $this->validate($rules, $messages);

            $data = [
                'kode_tindakan' => $this->kode_tindakan,
                'jenis' => $this->jenis,
                'keterangan' => $this->keterangan,
            ];

            // Update data existing
            Tindakan::where('ID_Tindakan', $this->tindakanId)->update($data);

            // Reset form
            $this->resetForm();

            // Notifikasi sukses
            $this->showSuccessToast('Tindakan berhasil diperbarui');

            // Emit event untuk refresh data
            $this->dispatch('tindakan-diperbarui');
            $this->dispatch('refresh');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Notifikasi validasi gagal
            $this->showErrorToast('Validasi gagal: ' . implode(' ', $e->validator->errors()->all()));
        } catch (\Exception $e) {
            // Notifikasi error umum
            $this->showErrorToast('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Reset form setelah update
    public function resetForm()
    {
        $this->reset(['kode_tindakan', 'jenis', 'keterangan', 'tindakanId']);
        $this->modalEdit = false;
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
    <!-- Modal Edit Tindakan -->
    <x-modal wire:model="modalEdit" title="Edit Tindakan" subtitle="Perbarui data berikut">
        <x-form wire:submit="updateTindakan" no-separator>
            <x-input label="Kode Tindakan" wire:model="kode_tindakan" icon="o-hashtag" />

            <x-select label="Jenis Tindakan" wire:model="jenis" :options="$jenisOptions" option-label="name" option-value="id"
                icon="o-scale" placeholder="Pilih Jenis Tindakan" />

            <x-textarea label="Keterangan" wire:model="keterangan" icon="o-document-text"
                placeholder="Deskripsi tindakan" rows="3" />

            <x-slot:actions>
                <x-button label="Batal" @click="$wire.resetForm()" />
                <x-button label="Simpan Perubahan" type="submit" class="btn-primary" spinner="updateTindakan" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
