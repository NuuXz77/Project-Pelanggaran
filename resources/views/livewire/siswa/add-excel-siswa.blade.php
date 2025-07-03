<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\Kelas;
use PhpOffice\PhpSpreadsheet\IOFactory;

new class extends Component {
    use WithFileUploads, Toast;

    public $file;
    public $importProgress = 0;
    public $importTotal = 0;
    public $importSuccess = 0;
    public $importErrors = [];
    public $isImporting = false;
    public $showModal = false;
    public $batchSize = 200;

    public function rules()
    {
        return [
            'file' => 'required|mimes:xls,xlsx|max:51200',
        ];
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['file', 'importErrors', 'importSuccess', 'importProgress']);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function import()
    {
        $this->validate();
        $this->isImporting = true;
        $this->importErrors = [];
        $this->importSuccess = 0;

        try {
            $spreadsheet = IOFactory::load($this->file->getRealPath());
            $rows = array_filter(
                $spreadsheet->getActiveSheet()->toArray(),
                fn($row) => array_filter(array_slice($row, 0, 4)), // hanya baris yg kolom 1-4 berisi
            );

            array_shift($rows); // Hapus header

            $this->importTotal = count($rows);
            $batches = array_chunk($rows, $this->batchSize);

            foreach ($batches as $batch) {
                DB::transaction(function () use ($batch) {
                    foreach ($batch as $index => $row) {
                        $this->processRow($index, $row);
                    }
                });

                $this->importProgress = min(100, round((($this->importSuccess + count($this->importErrors)) / $this->importTotal) * 100));
            }

            $this->dispatch('refresh');
            $this->success('Import selesai', "{$this->importSuccess} siswa berhasil diimport" . (count($this->importErrors) ? ' dengan ' . count($this->importErrors) . ' error' : ''));
        } catch (\Exception $e) {
            $this->importErrors[] = 'Gagal: ' . $e->getMessage();
            $this->error('Import gagal', $e->getMessage());
        } finally {
            $this->isImporting = false;
            $this->showModal = false;
        }
    }

    public function processRow($index, $row)
    {
        try {
            [$nis, $namaSiswa, $kelas, $jurusan] = array_map('trim', array_slice($row, 0, 4));

            $this->validateRowData($nis, $namaSiswa, $kelas, $jurusan);

            $kelasModel = Kelas::firstOrCreate(['kelas' => $kelas, 'jurusan' => $jurusan]);

            Siswa::updateOrCreate(
                ['nis' => $nis],
                [
                    'kelas_id' => $kelasModel->ID_Kelas,
                    'nama_siswa' => $namaSiswa,
                    'total_pelanggaran' => 0,
                ],
            );

            $this->importSuccess++;
        } catch (\Exception $e) {
            $this->importErrors[] = 'Baris ' . ($index + 2) . ': ' . $e->getMessage();
            throw $e;
        }
    }

    public function validateRowData($nis, $namaSiswa, $kelas, $jurusan)
    {
        if (!$nis || !$namaSiswa || !$kelas || !$jurusan) {
            throw new \Exception('Semua kolom (NIS, Nama, Kelas, Jurusan) wajib diisi.');
        }

        if (!is_numeric($nis)) {
            throw new \Exception('NIS harus berupa angka.');
        }
    }

    public function success($title, $message)
    {
        $this->toast(type: 'success', title: $title, description: $message, position: 'toast-top toast-end', icon: 'o-check-circle', css: 'alert-success', timeout: 5000);
    }

    public function error($title, $message)
    {
        $this->toast(type: 'error', title: $title, description: $message, position: 'toast-top toast-end', icon: 'o-exclamation-circle', css: 'alert-error', timeout: 5000);
    }
};
?>

<div>
    <x-button label="Import" class="btn btn-primary" wire:click="openModal" />

    <x-modal wire:model="showModal" title="Import Data Siswa" separator persistent>
        <div class="space-y-4">
            <div>
                <p class="text-sm text-gray-600 mb-1">Format file Excel:</p>
                <ul class="list-disc pl-5 text-sm text-gray-600">
                    <li>Kolom 1: NIS</li>
                    <li>Kolom 2: Nama Siswa</li>
                    <li>Kolom 3: Kelas</li>
                    <li>Kolom 4: Jurusan</li>
                </ul>
            </div>

            <x-file wire:model="file"
                accept=".xls,.xlsx,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                label="Pilih File Excel" />

            @error('file')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror

            @if ($isImporting)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>{{ $importProgress }}%</span>
                        <span>{{ $importSuccess }} dari {{ $importTotal }} berhasil</span>
                    </div>
                    <progress class="progress progress-primary w-full" value="{{ $importProgress }}"
                        max="100"></progress>
                </div>
            @endif

            @if (count($importErrors) > 0)
                <div class="bg-red-100 p-3 rounded-md text-sm text-red-600">
                    <p><strong>Error:</strong></p>
                    <ul class="list-disc pl-5 mt-2">
                        @foreach ($importErrors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <x-slot:actions>
            <x-button label="Batal" wire:click="closeModal" />
            <x-button label="Import" class="btn-primary" wire:click="import" wire:loading.attr="disabled" spinner />
        </x-slot:actions>
    </x-modal>

    {{-- Toast otomatis ditampilkan oleh x-toast --}}
    <x-toast />
</div>
