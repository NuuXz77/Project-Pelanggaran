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

    // Modal state
    public bool $modalEdit = false;
    public $pelanggaranId;

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

    // Listener untuk event dari parent
    protected $listeners = ['showEditModal' => 'openModal'];

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
                    'name' => $tindakan->kode_tindakan . ' - ' . $tindakan->keterangan,
                    'keterangan' => $tindakan->keterangan,
                ];
            })
            ->toArray();
    }

    public function search(string $value = '')
    {
        $query = Siswa::with('kelas')
            ->when($value, function ($q) use ($value) {
                $q->where('nama_siswa', 'like', "%$value%")->orWhere('nis', 'like', "%$value%");
            })
            ->orderBy('nama_siswa');

        // Jika ada siswa yang dipilih, pastikan termasuk dalam hasil
        if ($this->selectedSiswaId) {
            $query->orWhere('ID_Siswa', $this->selectedSiswaId);
        }

        $this->siswaResults = $query
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
            })
            ->unique('ID_Siswa'); // Pastikan tidak ada duplikat
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
            if ($tindakan) {
                $this->tindakan = $tindakan->keterangan ?: 'Tindakan ' . $tindakan->kode_tindakan;
            } else {
                $this->tindakan = '';
            }
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

        $kodeTindakan = array_map(function ($kode) {
            if (strpos($kode, ' - ') !== false) {
                return explode(' - ', $kode)[0];
            }
            return $kode;
        }, $kodeTindakan);

        $tindakanList = Tindakan::whereIn('kode_tindakan', $kodeTindakan)->get();

        return $tindakanList
            ->map(function ($tindakan) {
                return [
                    'id' => $tindakan->ID_Tindakan,
                    'name' => $tindakan->kode_tindakan . ' - ' . ($tindakan->keterangan ?: 'Tindakan ' . $tindakan->kode_tindakan),
                    'kode' => $tindakan->kode_tindakan,
                    'keterangan' => $tindakan->keterangan ?: 'Tindakan ' . $tindakan->kode_tindakan,
                ];
            })
            ->toArray();
    }

    // Buka modal edit ketika menerima event
    public function openModal($id)
    {
        $this->pelanggaranId = $id;
        $this->loadPelanggaranData($this->pelanggaranId);
        $this->modalEdit = true;
    }

    // Load data pelanggaran untuk edit
    protected function loadPelanggaranData($id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);

        $this->selectedSiswaId = $pelanggaran->siswa_id;
        $this->selectedPeraturanId = $pelanggaran->peraturan_id;
        $this->selectedTingkat = $pelanggaran->tindakan_id;
        $this->kelas_id = $pelanggaran->kelas_id;
        $this->nis = $pelanggaran->nis;
        $this->nama = $pelanggaran->nama_siswa;
        $this->kelas = $pelanggaran->kelas;
        $this->pelanggaran = $pelanggaran->pelanggaran;
        $this->tindakan = $pelanggaran->tindakan;
        $this->deskripsi = $pelanggaran->deskripsi_pelanggaran;

        // Trigger the search to include the selected student
        $this->search();
    }

    public function update()
    {
        try {
            $this->validate([
                'selectedSiswaId' => 'required|exists:tb_siswa,ID_Siswa',
                'selectedPeraturanId' => 'required|exists:tb_peraturan,ID_Peraturan',
                'selectedTingkat' => 'required|exists:tb_tindakan,ID_Tindakan',
                'tindakan' => 'required|string',
            ]);

            // Update data pelanggaran
            Pelanggaran::where('ID_Pelanggaran', $this->pelanggaranId)->update([
                'siswa_id' => $this->selectedSiswaId,
                'peraturan_id' => $this->selectedPeraturanId,
                'pelanggaran' => $this->pelanggaran,
                'tingkat_pelanggaran' => collect($this->tingkatPelanggaranList)->firstWhere('id', $this->selectedTingkat)['name'] ?? '',
                'kelas_id' => $this->kelas_id,
                'nis' => $this->nis,
                'nama_siswa' => $this->nama,
                'kelas' => $this->kelas,
                'tindakan_id' => $this->selectedTingkat,
                'tindakan' => $this->tindakan,
                'deskripsi_pelanggaran' => $this->deskripsi,
                'updated_at' => now(),
            ]);

            $this->resetForm();
            $this->modalEdit = false;
            $this->dispatch('refresh');

            $this->toast(type: 'success', title: 'Berhasil!', description: 'Data pelanggaran berhasil diperbarui!', position: 'toast-top toast-end', icon: 'o-check-circle', css: 'alert-success', timeout: 3000);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->toast(type: 'error', title: 'Validasi Gagal', description: implode(' ', $e->validator->errors()->all()), position: 'toast-top toast-end', icon: 'o-x-circle', css: 'alert-error', timeout: 5000);
        }
    }

    public function resetForm()
    {
        $this->reset(['selectedSiswaId', 'selectedPeraturanId', 'selectedTingkat', 'nis', 'kelas_id', 'nama', 'kelas', 'pelanggaran', 'tindakan', 'deskripsi', 'pelanggaranId']);
    }
};
?>

<div>
    <!-- Modal Edit Pelanggaran -->
    <x-modal wire:model="modalEdit" box-class="w-11/12 max-w-5xl" title="Edit Data Pelanggaran"
        subtitle="Perbarui data pelanggaran siswa" separator persistent>
        <x-form wire:submit="update">
            <!-- Baris 1 - Pencarian Siswa -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-choices label="Cari Siswa (Nama)" wire:model.live="selectedSiswaId" :options="$siswaResults"
                    option-label="display_text" option-value="ID_Siswa" searchable @search="search" single clearable
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

            <x-slot:actions>
                <x-button label="Batal" @click="$wire.modalEdit = false" />
                <x-button label="Simpan Perubahan" icon="o-check" class="btn-primary" type="submit" spinner="update" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
