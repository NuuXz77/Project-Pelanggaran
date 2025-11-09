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

    // Data otomatis untuk kesiangan
    public $peraturanKesiangan;
    public $tindakanRingan;

    // Form fields
    public $kelas_id = '';
    public $nis = '';
    public $kelas = '';
    public $nama = '';
    public $pelanggaran = 'Siswa yang terlambat datang ke sekolah';
    public $tindakan = '';
    public $deskripsi = '';
    public $selectedPeraturanId = null;
    public $selectedTingkat = null;

    public function mount()
    {
        $this->search();
        $this->setPelanggaranOtomatis();
    }

    public function setPelanggaranOtomatis()
    {
        // Cari peraturan kesiangan (TT001)
        $this->peraturanKesiangan = Peraturan::where('kode_peraturan', 'TT001')->first();
        
        if ($this->peraturanKesiangan) {
            $this->selectedPeraturanId = $this->peraturanKesiangan->ID_Peraturan;
            $this->pelanggaran = $this->peraturanKesiangan->larangan;
            
            // Cari tindakan ringan (R-3)
            $this->tindakanRingan = Tindakan::where('kode_tindakan', 'R-3')->first();
            
            if ($this->tindakanRingan) {
                $this->selectedTingkat = $this->tindakanRingan->ID_Tindakan;
                $this->tindakan = $this->tindakanRingan->keterangan ?: 'Peringatan Lisan';
            }
        }
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

    public function updatedSelectedSiswaId($value)
    {
        $this->handleSiswaChange($value);
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
            $pelanggaran->tingkat_pelanggaran = $this->tindakan;
            $pelanggaran->kelas_id = $this->kelas_id;
            $pelanggaran->nis = $this->nis;
            $pelanggaran->nama_siswa = $this->nama;
            $pelanggaran->kelas = $this->kelas;
            $pelanggaran->tindakan_id = $this->selectedTingkat;
            $pelanggaran->tindakan = $this->tindakan;
            $pelanggaran->deskripsi_pelanggaran = $this->deskripsi;
            $pelanggaran->dicatat_oleh = auth()->user()->name;
            $pelanggaran->save();

            Siswa::where('ID_Siswa', $this->selectedSiswaId)->increment('total_pelanggaran');

            $this->reset(['selectedSiswaId', 'nis', 'kelas', 'nama', 'deskripsi']);

            $this->toast(type: 'success', title: 'Berhasil!', description: 'Data pelanggaran kesiangan berhasil disimpan!', position: 'toast-top toast-end', icon: 'o-check-circle', css: 'alert-success', timeout: 3000);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->toast(type: 'error', title: 'Validasi Gagal', description: implode(' ', $e->validator->errors()->all()), position: 'toast-top toast-end', icon: 'o-x-circle', css: 'alert-error', timeout: 5000);
        }
    }
};
?>

<div class="bg-base-100 p-6 rounded-lg shadow-sm space-y-6">
    <x-header title="PENCATATAN KESIANGAN" size="text-2xl" separator />
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

        <!-- Baris 2 - Pelanggaran Otomatis -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-input label="Pelanggaran" wire:model="pelanggaran" readonly />
            <x-input label="Tindakan" wire:model="tindakan" readonly />
        </div>

        <!-- Baris 3 - Keterangan Tambahan -->
        <div>
            <x-textarea label="Deskripsi (Opsional)" wire:model="deskripsi" placeholder="Catatan tambahan..." rows="5" />
        </div>

        <!-- Hidden fields untuk menyimpan ID peraturan dan tindakan -->
        <input type="hidden" wire:model="selectedPeraturanId" />
        <input type="hidden" wire:model="selectedTingkat" />

        <x-button label="Simpan" icon="o-plus" class="btn-primary" type="submit" spinner />
    </x-form>
</div>