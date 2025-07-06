<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
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
            $this->importTotal = 0;

            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                $rows = array_filter(
                    $worksheet->toArray(),
                    fn($row) => array_filter(array_slice($row, 0, 5)),
                );
                array_shift($rows);
                $this->importTotal += count($rows);
            }

            $processedCount = 0;
            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                $rows = array_filter(
                    $worksheet->toArray(),
                    fn($row) => array_filter(array_slice($row, 0, 5)),
                );
                array_shift($rows);
                $batches = array_chunk($rows, $this->batchSize);

                foreach ($batches as $batch) {
                    DB::transaction(function () use ($batch, &$processedCount) {
                        foreach ($batch as $index => $row) {
                            $this->processRow($index, $row);
                            $processedCount++;
                            $this->importProgress = min(100, round(($processedCount / $this->importTotal) * 100));
                        }
                    });
                }
            }

            $this->dispatch('refresh');
            $this->success('Import selesai', "{$this->importSuccess} akun PKS berhasil diimport" . (count($this->importErrors) ? ' dengan ' . count($this->importErrors) . ' error' : ''));
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
            [$name, $email, $role, $status, $password] = array_map('trim', array_slice($row, 0, 5));

            $this->validateRowData($name, $email, $role, $status, $password);

            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'role' => $role,
                    'status' => $status,
                    'password' => Hash::make($password),
                ],
            );

            $this->importSuccess++;
        } catch (\Exception $e) {
            $this->importErrors[] = 'Baris ' . ($index + 2) . ': ' . $e->getMessage();
            throw $e;
        }
    }

    public function validateRowData($name, $email, $role, $status, $password)
    {
        if (!$name || !$email || !$role || !$status || !$password) {
            throw new \Exception('Semua kolom (Name, Email, Role, Status, Password) wajib diisi.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Format email tidak valid.');
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
    <x-button label="Import Akun PKS" class="btn btn-primary" wire:click="openModal" />

    <x-modal wire:model="showModal" title="Import Akun PKS dari Excel" separator persistent>
        <div class="space-y-4">
            <div>
                <p class="text-sm text-gray-600 mb-1">Format file Excel:</p>
                <ul class="list-disc pl-5 text-sm text-gray-600">
                    <li>Kolom 1: Nama</li>
                    <li>Kolom 2: Email</li>
                    <li>Kolom 3: Role (contoh: <code>pks</code>)</li>
                    <li>Kolom 4: Status (contoh: <code>Aktif</code> / <code>Nonaktif</code>)</li>
                    <li>Kolom 5: Password (plaintext, akan di-hash)</li>
                    <li>File dapat memiliki multiple sheet dengan format yang sama</li>
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
                    <progress class="progress progress-primary w-full" value="{{ $importProgress }}" max="100"></progress>
                </div>
            @endif

            @if (count($importErrors) > 0)
                <div class="bg-red-100 p-3 rounded-md text-sm text-red-600">
                    <p><strong>Error:</strong></p>
                    <ul class="list-disc pl-5 mt-2 max-h-40 overflow-y-auto">
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

    <x-toast />
</div>
