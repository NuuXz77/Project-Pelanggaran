<?php

use App\Models\Siswa;
use App\Models\Peraturan;
use App\Models\Tindakan;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public $groupedSiswa = [];
    public $peraturanList = [];
    public $tingkatPelanggaranList = [];

    public $selectedSiswa = '';
    public $selectedPeraturan = '';
    public $selectedTingkat = '';

    public $kelas_id = '';
    public $nis = '';
    public $kelas = '';
    public $nama = '';
    public $pelanggaran = '';
    public $tindakan = '';
    public $deskripsi = '';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Load siswa data
        $siswaList = Siswa::with('kelas')->get();
        $this->groupedSiswa = $siswaList
            ->groupBy(fn($siswa) => 'Kelas ' . $siswa->kelas->kelas . ' ' . $siswa->kelas->jurusan)
            ->map(function ($group) {
                return $group
                    ->map(function ($siswa) {
                        return [
                            'id' => $siswa->ID_Siswa,
                            'name' => $siswa->nama_siswa,
                            'nis' => $siswa->nis,
                            'kelas' => $siswa->kelas->kelas . ' ' . $siswa->kelas->jurusan,
                        ];
                    })
                    ->toArray();
            })
            ->toArray();

        // Load peraturan data
        $this->peraturanList = Peraturan::all()
            ->map(function ($peraturan) {
                return [
                    'id' => $peraturan->ID_Peraturan,
                    'name' => $peraturan->larangan,
                ];
            })
            ->toArray();

        // Load tingkat pelanggaran data
        $this->tingkatPelanggaranList = Tindakan::all()
            ->map(function ($tindakan) {
                return [
                    'id' => $tindakan->ID_Tindakan,
                    'name' => $tindakan->kode_tindakan . ' - ' . Str::title($tindakan->jenis),
                    'keterangan' => $tindakan->keterangan,
                ];
            })
            ->toArray();
    }

    public function updated($property, $value)
    {
        if ($property === 'selectedSiswa') {
            $this->handleSiswaChange($value);
        }

        if ($property === 'selectedTingkat') {
            $this->handleTingkatChange($value);
        }
    }

    protected function handleSiswaChange($siswaId)
    {
        if (!empty($siswaId)) {
            $siswa = Siswa::with('kelas')->find($siswaId);
            if ($siswa) {
                $this->nis = $siswa->nis;
                $this->kelas_id = $siswa->kelas_id;
                $this->nama = $siswa->nama_siswa;
                $this->kelas = $siswa->kelas->kelas . ' ' . $siswa->kelas->jurusan;
            }
        } else {
            $this->nis = '';
            $this->kelas = '';
        }
    }

    protected function handleTingkatChange($tingkatId)
    {
        if (!empty($tingkatId)) {
            $tindakan = Tindakan::find($tingkatId);
            $this->tindakan = $tindakan ? $tindakan->keterangan : '';
        } else {
            $this->tindakan = '';
        }
    }

    public function save()
    {
        try {
            $this->validate([
                'selectedSiswa' => 'required|exists:tb_siswa,ID_Siswa',
                'selectedPeraturan' => 'required|exists:tb_peraturan,ID_Peraturan',
                'selectedTingkat' => 'required|exists:tb_tindakan,ID_Tindakan',
                'tindakan' => 'required|string',
            ]);

            // Get the selected peraturan and tindakan names
            $selectedPeraturanName = collect($this->peraturanList)->firstWhere('id', $this->selectedPeraturan)['name'] ?? '';

            $selectedTingkatName = collect($this->tingkatPelanggaranList)->firstWhere('id', $this->selectedTingkat)['name'] ?? '';

            // Simpan data
            $pelanggaran = new \App\Models\Pelanggaran();
            $pelanggaran->siswa_id = $this->selectedSiswa;
            $pelanggaran->peraturan_id = $this->selectedPeraturan;
            $pelanggaran->pelanggaran = $selectedPeraturanName;
            $pelanggaran->tingkat_pelanggaran = $selectedTingkatName;
            $pelanggaran->kelas_id = $this->kelas_id;
            $pelanggaran->nis = $this->nis;
            $pelanggaran->nama_siswa = $this->nama;
            $pelanggaran->kelas = $this->kelas;
            $pelanggaran->tindakan_id = $this->selectedTingkat;
            $pelanggaran->tindakan = $this->tindakan;
            $pelanggaran->deskripsi_pelanggaran = $this->deskripsi;
            $pelanggaran->save();

            // Update counter
            Siswa::where('ID_Siswa', $this->selectedSiswa)->increment('total_pelanggaran');

            // Reset form
            $this->reset(['selectedSiswa', 'selectedPeraturan', 'selectedTingkat', 'nis', 'kelas', 'tindakan', 'deskripsi']);

            $this->showSuccessToast('Data pelanggaran berhasil disimpan!');
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

    protected function showWarningToast($message, $title = 'Peringatan!')
    {
        $this->toast(type: 'warning', title: $title, description: $message, position: 'toast-top toast-end', icon: 'o-exclamation-triangle', css: 'alert-warning', timeout: 4000);
    }

    protected function showInfoToast($message, $title = 'Informasi')
    {
        $this->toast(type: 'info', title: $title, description: $message, position: 'toast-top toast-end', icon: 'o-information-circle', css: 'alert-info', timeout: 3000);
    }
};
?>
<div class="bg-white p-6 rounded-lg shadow-sm space-y-6">
    <x-header title="MASUKAN DATA SISWA" size="text-2xl" separator />
    <x-form wire:submit="save">
        {{-- Baris 1 --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-select-group label="Nama Siswa" :options="$groupedSiswa" option-label="name" option-value="id"
                wire:model.live="selectedSiswa" placeholder="Pilih Siswa" />
            <x-input label="NIS" wire:model="nis" readonly />
            <x-input label="Kelas" wire:model="kelas" readonly />
        </div>

        {{-- Baris 2 --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-select label="Pelanggaran" :options="$peraturanList" wire:model="selectedPeraturan" option-label="name"
                option-value="id" placeholder="Pilih Pelanggaran" />
            <x-select label="Tingkat Pelanggaran" :options="$tingkatPelanggaranList" wire:model.live="selectedTingkat"
                option-label="name" option-value="id" placeholder="Pilih Tingkat Pelanggaran" />
        </div>

        {{-- Baris 3 --}}
        <div>
            <x-textarea label="Tindakan" wire:model="tindakan" placeholder="Otomatis" rows="5" readonly />
            <x-textarea label="Deskripsi (Opsional)" wire:model="deskripsi" placeholder="Tulis tambahan..."
                rows="5" />
        </div>

        <x-button label="Simpan" icon="o-plus" class="btn-primary" type="submit" spinner />
    </x-form>
</div>
