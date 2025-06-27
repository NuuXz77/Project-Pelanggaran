<?php

use Livewire\Volt\Component;
use App\Models\Peraturan;
use App\Models\Tindakan;
use Illuminate\Support\Facades\DB;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $modalTambah = false;
    public $kode_peraturan;
    public $larangan;
    public $selectedTindakanRingan;
    public $selectedTindakanBerat;
    public $tindakanRinganOptions = [];
    public $tindakanBeratOptions = [];
    
    public function mount()
    {
        $this->generateKodePeraturan();
        $this->loadTindakanOptions();
    }

    protected function generateKodePeraturan()
    {
        $count = Peraturan::count() + 1;
        $this->kode_peraturan = 'TT' . str_pad($count, 3, '0', STR_PAD_LEFT);
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

    public function simpanPeraturan()
    {
        try {
            // Validasi dengan pesan custom
            $this->validate(
                [
                    'kode_peraturan' => 'required|unique:tb_peraturan,kode_peraturan',
                    'larangan' => 'required',
                    'selectedTindakanRingan' => 'required',
                    'selectedTindakanBerat' => 'required',
                ],
                [
                    'kode_peraturan.required' => 'Kode peraturan harus diisi',
                    'kode_peraturan.unique' => 'Kode peraturan sudah digunakan',
                    'larangan.required' => 'Deskripsi larangan harus diisi',
                    'selectedTindakanRingan.required' => 'Tindakan ringan harus dipilih',
                    'selectedTindakanBerat.required' => 'Tindakan berat harus dipilih',
                ],
            );

            DB::beginTransaction();

            // Ambil data tindakan yang dipilih
            $tindakanRingan = Tindakan::findOrFail($this->selectedTindakanRingan);
            $tindakanBerat = Tindakan::findOrFail($this->selectedTindakanBerat);

            // Format data untuk disimpan
            $dataTindakanRingan = $tindakanRingan->kode_tindakan . ' - ' . $tindakanRingan->keterangan;
            $dataTindakanBerat = $tindakanBerat->kode_tindakan . ' - ' . $tindakanBerat->keterangan;

            Peraturan::create([
                'kode_peraturan' => $this->kode_peraturan,
                'larangan' => $this->larangan,
                'tindakan_ringan' => $dataTindakanRingan,
                'tindakan_berat' => $dataTindakanBerat,
            ]);

            DB::commit();

            // Reset form
            $this->reset(['larangan', 'selectedTindakanRingan', 'selectedTindakanBerat']);
            $this->generateKodePeraturan();
            $this->modalTambah = false;

            // Notifikasi sukses
            $this->showSuccessToast('Peraturan baru berhasil ditambahkan');

            // Emit event untuk refresh data parent component
            $this->dispatch('peraturan-ditambahkan');
            
            // Trigger Livewire refresh
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
    <!-- Modal Tambah Peraturan -->
    <x-modal wire:model="modalTambah" title="Tambah Peraturan Baru" subtitle="Isi form berikut">
        <x-form wire:submit="simpanPeraturan" no-separator>
            <x-input label="Kode Peraturan" wire:model="kode_peraturan" icon="o-user" readonly />
            <x-textarea label="Larangan" wire:model="larangan" icon="o-exclamation-circle" placeholder="Deskripsi larangan"
                rows="3" />

            <x-select label="Tindakan Ringan" wire:model="selectedTindakanRingan" :options="$tindakanRinganOptions" option-label="name"
                option-value="id" icon="o-arrow-down" placeholder="Pilih Tindakan Ringan" />

            <x-select label="Tindakan Berat" wire:model="selectedTindakanBerat" :options="$tindakanBeratOptions" option-label="name"
                option-value="id" icon="o-arrow-down" placeholder="Pilih Tindakan Berat" />

            <x-slot:actions>
                <x-button label="Batal" @click="$wire.modalTambah = false" />
                <x-button label="Simpan" type="submit" class="btn-primary" spinner="simpanPeraturan"/>
            </x-slot:actions>
        </x-form>
    </x-modal>

    <!-- Tombol Buka Modal -->
    <x-button icon="o-plus" class="btn-primary" @click="$wire.modalTambah = true"/>
</div>