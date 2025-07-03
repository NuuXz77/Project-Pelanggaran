<?php

use App\Models\Siswa;
use App\Models\Peraturan;
use App\Models\Tindakan;
use App\Models\Pelanggaran;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use Mary\Traits\Toast;
use Illuminate\Support\Collection;

new class extends Component {
    use Toast;

    // Pencarian Siswa
    public ?int $selectedSiswaId = null;
    public Collection $siswaResults;

    // Pencarian Pelanggaran
    public ?int $selectedPeraturanId = null;
    public Collection $peraturanResults;

    // Tingkat pelanggaran
    public array $tingkatPelanggaranList = [];
    public $selectedTingkat = null;

    // Form fields
    public $kelas_id = '';
    public $nis = '';
    public $kelas = '';
    public $nama = '';
    public $pelanggaran = '';
    public $tindakan = '';
    public $deskripsi = '';

    public function mount()
    {
        $this->loadTingkatPelanggaran();
        $this->search();
        $this->SelectPeraturan();
    }

    public function loadTingkatPelanggaran()
    {
        $this->tingkatPelanggaranList = Tindakan::all()
            ->map(function ($tindakan) {
                return [
                    'id' => $tindakan->ID_Tindakan,
                    'kode' => $tindakan->kode_tindakan,
                    'name' => $tindakan->kode_tindakan . ' - ' . Str::title($tindakan->jenis),
                    'keterangan' => $tindakan->keterangan,
                ];
            })
            ->toArray();
    }

    public function search(string $value = '')
    {
        $selectedOption = collect();
        if ($this->selectedSiswaId) {
            $selectedOption = Siswa::with('kelas')
                ->where('ID_Siswa', $this->selectedSiswaId)
                ->get()
                ->map(function ($siswa) {
                    return [
                        'ID_Siswa' => $siswa->ID_Siswa,
                        'nama_siswa' => $siswa->nama_siswa,
                        'nis' => $siswa->nis,
                        'kelas_nama' => $siswa->kelas->kelas . ' ' . $siswa->kelas->jurusan,
                        'display_text' => $siswa->nama_siswa . ' - ' . $siswa->kelas->kelas . ' ' . $siswa->kelas->jurusan,
                        'kelas_id' => $siswa->kelas_id,
                    ];
                });
        }

        $searchResults = Siswa::with('kelas')
            ->where('nama_siswa', 'like', "%$value%")
            ->orWhere('nis', 'like', "%$value%")
            ->orderBy('nama_siswa')
            ->get()
            ->map(function ($siswa) {
                return [
                    'ID_Siswa' => $siswa->ID_Siswa,
                    'nama_siswa' => $siswa->nama_siswa,
                    'nis' => $siswa->nis,
                    'kelas_nama' => $siswa->kelas->kelas . ' ' . $siswa->kelas->jurusan,
                    'display_text' => $siswa->nama_siswa . ' - ' . $siswa->kelas->kelas . ' ' . $siswa->kelas->jurusan,
                    'kelas_id' => $siswa->kelas_id,
                ];
            });

        $this->siswaResults = $searchResults->merge($selectedOption);
    }

    public function SelectPeraturan()
    {
        $this->peraturanResults = Peraturan::orderBy('kode_peraturan')
            ->get()
            ->map(function ($peraturan) {
                return [
                    'ID_Peraturan' => $peraturan->ID_Peraturan,
                    'kode_peraturan' => $peraturan->kode_peraturan,
                    'larangan' => $peraturan->larangan,
                    'display_text' => $peraturan->kode_peraturan . ' - ' . $peraturan->larangan,
                    'tindakan_ringan' => $peraturan->tindakan_ringan,
                    'tindakan_berat' => $peraturan->tindakan_berat,
                ];
            });
    }

    public function updatedSelectedSiswaId($value)
    {
        $this->handleSiswaChange($value);
    }

    public function updatedSelectedPeraturanId($value)
    {
        $this->handlePeraturanChange($value);
    }

    public function updatedSelectedTingkat($value)
    {
        $this->handleTingkatChange($value);
    }

    public function handleSiswaChange($siswaId)
    {
        if (!empty($siswaId)) {
            $siswa = Siswa::with('kelas')->find($siswaId);
            if ($siswa) {
                $this->nis = $siswa->nis;
                $this->kelas_id = $siswa->kelas_id;
                $this->nama = $siswa->nama_siswa;
                $this->kelas = $siswa->kelas->kelas . ' ' . $siswa->kelas->jurusan;
                return;
            }
        }
        $this->reset(['nis', 'kelas_id', 'nama', 'kelas']);
    }

    public function handlePeraturanChange($peraturanId)
    {
        if (!empty($peraturanId)) {
            $peraturan = Peraturan::find($peraturanId);
            if ($peraturan) {
                $this->pelanggaran = $peraturan->larangan;
                $this->reset('selectedTingkat');
                return;
            }
        }
        $this->reset(['pelanggaran', 'selectedTingkat']);
    }

    public function handleTingkatChange($tingkatId)
    {
        if (!empty($tingkatId)) {
            $tindakan = Tindakan::find($tingkatId);
            $this->tindakan = $tindakan ? $tindakan->keterangan : '';
        } else {
            $this->tindakan = '';
        }
    }

    public function getTingkatOptionsProperty()
    {
        if (empty($this->selectedPeraturanId)) {
            return [];
        }

        $peraturan = Peraturan::find($this->selectedPeraturanId);
        if (!$peraturan) {
            return [];
        }

        $kodeTindakan = [$peraturan->tindakan_ringan, $peraturan->tindakan_berat];

        return collect($this->tingkatPelanggaranList)->whereIn('kode', $kodeTindakan)->values()->toArray();
    }

    public function save()
    {
        try {
            $this->validate([
                'selectedSiswaId' => 'required|exists:tb_siswa,ID_Siswa',
                'selectedPeraturanId' => 'required|exists:tb_peraturan,ID_Peraturan',
                'selectedTingkat' => 'required|exists:tb_tindakan,ID_Tindakan',
                'tindakan' => 'required|string',
            ]);

            $pelanggaran = new Pelanggaran();
            $pelanggaran->siswa_id = $this->selectedSiswaId;
            $pelanggaran->peraturan_id = $this->selectedPeraturanId;
            $pelanggaran->pelanggaran = $this->pelanggaran;
            $pelanggaran->tingkat_pelanggaran = collect($this->tingkatPelanggaranList)->firstWhere('id', $this->selectedTingkat)['name'] ?? '';
            $pelanggaran->kelas_id = $this->kelas_id;
            $pelanggaran->nis = $this->nis;
            $pelanggaran->nama_siswa = $this->nama;
            $pelanggaran->kelas = $this->kelas;
            $pelanggaran->tindakan_id = $this->selectedTingkat;
            $pelanggaran->tindakan = $this->tindakan;
            $pelanggaran->deskripsi_pelanggaran = $this->deskripsi;
            $pelanggaran->save();

            Siswa::where('ID_Siswa', $this->selectedSiswaId)->increment('total_pelanggaran');

            $this->reset(['selectedSiswaId', 'selectedPeraturanId', 'selectedTingkat', 'nis', 'kelas', 'nama', 'pelanggaran', 'tindakan', 'deskripsi']);

            // $this->searchSiswa();
            // $this->searchPeraturan();

            // Toast sukses dengan style Mary UI
            $this->toast(type: 'success', title: 'Berhasil!', description: 'Data pelanggaran berhasil disimpan!', position: 'toast-top toast-end', icon: 'o-check-circle', css: 'alert-success', timeout: 3000);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Toast error untuk validasi gagal
            $this->toast(type: 'error', title: 'Validasi Gagal', description: implode(' ', $e->validator->errors()->all()), position: 'toast-top toast-end', icon: 'o-x-circle', css: 'alert-error', timeout: 5000);
        }
    }
};
?>

<div class="bg-base-100 p-6 rounded-lg shadow-sm space-y-6">
    <x-header title="MASUKAN DATA SISWA" size="text-2xl" separator />
    <x-form wire:submit="save">
        <!-- Baris 1 - Pencarian Siswa -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-choices label="Cari Siswa (Nama)" wire:model.live="selectedSiswaId" :options="$siswaResults"
                option-label="display_text" option-value="ID_Siswa" searchable @search="searchSiswa" single clearable
                no-result-text="Tidak ada siswa ditemukan" async-data>
            </x-choices>

            <x-input label="NIS" wire:model="nis" readonly />
            <x-input label="Kelas" wire:model="kelas" readonly />
        </div>

        <!-- Baris 2 - Pencarian Pelanggaran -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-select label="Cari Pelanggaran" wire:model.live="selectedPeraturanId" :options="$peraturanResults"
                option-label="display_text" option-value="ID_Peraturan" placeholder="Pilih Pelanggaran...">
            </x-select>

            <x-select label="Tingkat Pelanggaran" wire:model.live="selectedTingkat" :options="$this->tingkatOptions"
                option-label="name" option-value="id" placeholder="Pilih Tingkat Pelanggaran" :disabled="!$this->selectedPeraturanId" />
        </div>

        <!-- Baris 3 - Keterangan Tambahan -->
        <div>
            <x-textarea label="Tindakan" wire:model="tindakan" placeholder="Otomatis" rows="5" readonly />
            <x-textarea label="Deskripsi (Opsional)" wire:model="deskripsi" placeholder="Tulis tambahan..."
                rows="5" />
        </div>

        <x-button label="Simpan" icon="o-plus" class="btn-primary" type="submit" spinner />
    </x-form>
</div>
