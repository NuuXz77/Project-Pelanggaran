<?php

use Livewire\Volt\Component;
use App\Models\Peraturan;
use App\Models\Tindakan;
use Illuminate\Support\Facades\DB;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $modalEdit = false;
    public $peraturanId;
    public $kode_peraturan;
    public $larangan;
    public $selectedTindakanRingan;
    public $selectedTindakanBerat;
    public $tindakanRinganOptions = [];
    public $tindakanBeratOptions = [];

    // Listener untuk membuka modal edit
    protected $listeners = ['showEditModal' => 'openModal'];

    public function mount()
    {
        $this->loadTindakanOptions();
    }

    // Buka modal edit
    public function openModal($id)
    {
        if (isset($id)) {
            // dd($id);
            $this->peraturanId = $id;
            $this->loadPeraturanData($this->peraturanId);
            $this->modalEdit = true;
        }
    }

    // Load data peraturan untuk edit
    protected function loadPeraturanData($id)
    {
        $peraturan = Peraturan::findOrFail($id);
        
        $this->kode_peraturan = $peraturan->kode_peraturan;
        $this->larangan = $peraturan->larangan;
        
        // Cari ID tindakan berdasarkan keterangan yang disimpan
        $this->selectedTindakanRingan = $this->findTindakanId($peraturan->tindakan_ringan);
        $this->selectedTindakanBerat = $this->findTindakanId($peraturan->tindakan_berat);
    }

    // Helper untuk mencari ID tindakan berdasarkan string keterangan
    protected function findTindakanId($keterangan)
    {
        $parts = explode(' - ', $keterangan);
        $kode = $parts[0] ?? '';
        
        $tindakan = Tindakan::where('kode_tindakan', $kode)->first();
        return $tindakan ? $tindakan->ID_Tindakan : null;
    }

    protected function loadTindakanOptions()
    {
        // Tindakan Ringan (jenis = 'ringan')
        $this->tindakanRinganOptions = Tindakan::where('jenis', 'ringan')
            ->get()
            ->map(function ($tindakan) {
                return [
                    'id' => $tindakan->ID_Tindakan,
                    'name' => $tindakan->kode_tindakan . ' - ' . $tindakan->keterangan,
                ];
            })
            ->toArray();

        // Tindakan Berat (jenis = 'berat')
        $this->tindakanBeratOptions = Tindakan::where('jenis', 'berat')
            ->get()
            ->map(function ($tindakan) {
                return [
                    'id' => $tindakan->ID_Tindakan,
                    'name' => $tindakan->kode_tindakan . ' - ' . $tindakan->keterangan,
                ];
            })
            ->toArray();
    }

    public function updatePeraturan()
    {
        try {
            // Validasi dengan pesan custom
            $rules = [
                // 'kode_peraturan' => 'required|unique:tb_peraturan,kode_peraturan',
                'larangan' => 'required',
                'selectedTindakanRingan' => 'required',
                'selectedTindakanBerat' => 'required',
            ];
            // dd($rules);

            $messages = [
                'kode_peraturan.required' => 'Kode peraturan harus diisi',
                // 'kode_peraturan.unique' => 'Kode peraturan sudah digunakan',
                'larangan.required' => 'Deskripsi larangan harus diisi',
                'selectedTindakanRingan.required' => 'Tindakan ringan harus dipilih',
                'selectedTindakanBerat.required' => 'Tindakan berat harus dipilih',
            ];

            $this->validate($rules, $messages);

            DB::beginTransaction();

            // Ambil data tindakan yang dipilih
            $tindakanRingan = Tindakan::findOrFail($this->selectedTindakanRingan);
            $tindakanBerat = Tindakan::findOrFail($this->selectedTindakanBerat);

            // Format data untuk disimpan
            $dataTindakanRingan = $tindakanRingan->kode_tindakan . ' - ' . $tindakanRingan->keterangan;
            $dataTindakanBerat = $tindakanBerat->kode_tindakan . ' - ' . $tindakanBerat->keterangan;

            $data = [
                'kode_peraturan' => $this->kode_peraturan,
                'larangan' => $this->larangan,
                'tindakan_ringan' => $dataTindakanRingan,
                'tindakan_berat' => $dataTindakanBerat,
            ];

            // Update data existing
            // dd($data);
            Peraturan::findOrFail($this->peraturanId)->update($data);
            // Peraturan::where('ID_Peraturan', $this->peraturanId)->update($data);

            DB::commit();

            // Reset form
            $this->resetForm();

            // Notifikasi sukses
            $this->showSuccessToast('Peraturan berhasil diperbarui');

            // Emit event untuk refresh data
            $this->dispatch('peraturan-diperbarui');
            $this->dispatch('refresh');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Notifikasi validasi gagal
            $this->showErrorToast('Validasi gagal: ' . implode(' ', $e->validator->errors()->all()));
        } catch (\Exception $e) {
            DB::rollBack();
            // Notifikasi error umum
            $this->showErrorToast('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Reset form setelah update
    public function resetForm()
    {
        $this->reset(['larangan', 'selectedTindakanRingan', 'selectedTindakanBerat', 'peraturanId']);
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
    <!-- Modal Edit Peraturan -->
    <x-modal wire:model="modalEdit" title="Edit Peraturan" subtitle="Perbarui data berikut">
        <x-form wire:submit="updatePeraturan" no-separator>
            <x-input label="Kode Peraturan" wire:model="kode_peraturan" icon="o-user" readonly />
            <x-textarea label="Larangan" wire:model="larangan" icon="o-exclamation-circle" placeholder="Deskripsi larangan"
                rows="3" />

            <x-select label="Tindakan Ringan" wire:model="selectedTindakanRingan" :options="$tindakanRinganOptions" option-label="name"
                option-value="id" icon="o-arrow-down" placeholder="Pilih Tindakan Ringan" />

            <x-select label="Tindakan Berat" wire:model="selectedTindakanBerat" :options="$tindakanBeratOptions" option-label="name"
                option-value="id" icon="o-arrow-down" placeholder="Pilih Tindakan Berat" />

            <x-slot:actions>
                <x-button label="Batal" @click="$wire.resetForm()" />
                <x-button label="Simpan Perubahan" type="submit" class="btn-primary" spinner="updatePeraturan"/>
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>