<?php

use App\Models\Siswa;
use App\Models\Peraturan;
use App\Models\Tindakan;
use App\Models\Pelanggaran;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public $groupedSiswa = [];
    public $peraturanList = [];
    public $tingkatPelanggaranList = [];

    // Properti untuk data pelanggaran
    public bool $modalEdit = false;
    public $pelanggaranId;
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

    // Listener untuk event dari parent
    protected $listeners = ['showEditModal' => 'openModal'];

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

    // Buka modal edit ketika menerima event
    public function openModal($id)
    {
        $this->pelanggaranId = is_array($id) ? $id : $id;
        $this->loadPelanggaranData($this->pelanggaranId);
        $this->modalEdit = true;
    }

    // Load data pelanggaran untuk edit
    protected function loadPelanggaranData($id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);
        
        $this->selectedSiswa = $pelanggaran->siswa_id;
        $this->selectedPeraturan = $pelanggaran->peraturan_id;
        $this->selectedTingkat = $pelanggaran->tindakan_id;
        $this->kelas_id = $pelanggaran->kelas_id;
        $this->nis = $pelanggaran->nis;
        $this->nama = $pelanggaran->nama_siswa;
        $this->kelas = $pelanggaran->kelas;
        $this->pelanggaran = $pelanggaran->pelanggaran;
        $this->tindakan = $pelanggaran->tindakan;
        $this->deskripsi = $pelanggaran->deskripsi_pelanggaran;
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

    public function update()
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

            // Update data pelanggaran
            Pelanggaran::where('ID_Pelanggaran', $this->pelanggaranId)->update([
                'siswa_id' => $this->selectedSiswa,
                'peraturan_id' => $this->selectedPeraturan,
                'pelanggaran' => $selectedPeraturanName,
                'tingkat_pelanggaran' => $selectedTingkatName,
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
            $this->showSuccessToast('Data pelanggaran berhasil diperbarui!');
        } catch (\Exception $e) {
            $this->showErrorToast('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    protected function resetForm()
    {
        $this->reset([
            'selectedSiswa', 'selectedPeraturan', 'selectedTingkat',
            'nis', 'kelas', 'tindakan', 'deskripsi', 'pelanggaranId'
        ]);
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
};
?>

<div>
    <!-- Modal Edit Pelanggaran -->
    <x-modal wire:model="modalEdit" title="Edit Pelanggaran" subtitle="Perbarui data pelanggaran siswa" separator persistent>
        <x-form wire:submit="update">
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

            <x-slot:actions>
                <x-button label="Batal" @click="$wire.modalEdit = false" />
                <x-button label="Simpan Perubahan" icon="o-check" class="btn-primary" type="submit" spinner="update" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>