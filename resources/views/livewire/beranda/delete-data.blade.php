<?php

use Livewire\Volt\Component;
use App\Models\Siswa;
use App\Models\Pelanggaran;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $modalPeringatan1 = false;
    public bool $modalPeringatan2 = false;
    public int $countdown = 10;

    public function showModalPeringatan1()
    {
        $this->modalPeringatan1 = true;
    }

    public function konfirmasiPertama()
    {
        $this->modalPeringatan1 = false;
        $this->modalPeringatan2 = true;
        $this->countdown = 10;
        
        // Trigger countdown di frontend
        $this->dispatch('start-countdown');
    }

    public function hapusSemuaData()
    {
        try {
            // Hitung jumlah data sebelum dihapus
            $jumlahSiswa = Siswa::count();
            $jumlahPelanggaran = Pelanggaran::count();
            $jumlahKelas = Kelas::count();

            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Hapus semua data - pakai delete() bukan truncate() karena truncate auto-commit
            Pelanggaran::query()->delete();
            Siswa::query()->delete();
            Kelas::query()->delete();

            // Enable foreign key checks lagi
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->modalPeringatan2 = false;
            
            $this->success(
                'Semua Data Berhasil Dihapus!',
                "Dihapus: {$jumlahSiswa} Siswa, {$jumlahPelanggaran} Pelanggaran, {$jumlahKelas} Kelas",
                position: 'toast-top toast-right',
                timeout: 5000,
                redirectTo: '/'
            );

            // Refresh semua component
            $this->dispatch('refresh');
            
        } catch (\Exception $e) {
            // Pastikan foreign key checks di-enable kembali kalau error
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            $this->error(
                'Gagal Menghapus Data!',
                $e->getMessage(),
                position: 'toast-top toast-right',
                timeout: 5000
            );
        }
    }

    public function batalHapus()
    {
        $this->modalPeringatan1 = false;
        $this->modalPeringatan2 = false;
        $this->info(
            'Dibatalkan',
            'Penghapusan data dibatalkan',
            position: 'toast-top toast-end',
            timeout: 3000
        );
    }
};
?>

<div>
    <x-button 
        icon="o-trash" 
        class="btn-sm btn-error" 
        @click="$wire.showModalPeringatan1()" 
        label="Hapus Semua Data"
        {{-- tooltip="Hapus semua data siswa, pelanggaran, dan kelas" --}}
    />

    <!-- Modal Peringatan 1 -->
    <x-modal wire:model="modalPeringatan1" title="⚠️ PERINGATAN KRITIS!" persistent class="backdrop-blur">
        <div class="space-y-4">
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-bold">Anda akan menghapus SEMUA DATA!</span>
            </div>

            <div class="bg-base-200 p-4 rounded-lg">
                <p class="font-bold text-lg mb-2">Data yang akan dihapus:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li class="text-error">✗ Semua Data Siswa</li>
                    <li class="text-error">✗ Semua Data Pelanggaran</li>
                    <li class="text-error">✗ Semua Data Kelas</li>
                </ul>
            </div>

            <div class="bg-warning/20 p-4 rounded-lg border-l-4 border-warning">
                <p class="font-bold text-warning">⚠️ PERHATIAN:</p>
                <p class="text-sm mt-2">
                    Data yang dihapus TIDAK DAPAT DIKEMBALIKAN! 
                    Pastikan Anda telah membuat backup sebelum melanjutkan.
                </p>
            </div>
        </div>

        <x-slot:actions>
            <x-button label="Batal" @click="$wire.batalHapus()" class="btn-ghost" />
            <x-button 
                label="Saya Mengerti, Lanjutkan" 
                @click="$wire.konfirmasiPertama()" 
                class="btn-error"
                icon="o-arrow-right"
            />
        </x-slot:actions>
    </x-modal>

    <!-- Modal Peringatan 2 (Final Confirmation) -->
    <x-modal wire:model="modalPeringatan2" title="🚨 KONFIRMASI TERAKHIR!" persistent class="backdrop-blur">
        <div class="space-y-4">
            <div class="alert alert-error shadow-lg">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current flex-shrink-0 w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <h3 class="font-bold text-lg">INI ADALAH KESEMPATAN TERAKHIR!</h3>
                        <div class="text-sm">Data akan terhapus permanen setelah Anda menekan tombol HAPUS!</div>
                    </div>
                </div>
            </div>

            <div class="card bg-error/10 border-2 border-error">
                <div class="card-body">
                    <h2 class="card-title text-error">⛔ Aksi Tidak Dapat Dibatalkan</h2>
                    <p class="text-sm">
                        Dengan menekan tombol "HAPUS SEMUA DATA" di bawah, 
                        Anda menyetujui untuk menghapus PERMANEN semua data dari sistem.
                    </p>
                </div>
            </div>

            <x-slot:actions>
                <x-button 
                    label="Batal" 
                    @click="$wire.batalHapus()" 
                    class="btn-ghost"
                />
                <x-button 
                    label="HAPUS SEMUA DATA" 
                    @click="$wire.hapusSemuaData()" 
                    class="btn-error"
                    icon="o-trash"
                    spinner="hapusSemuaData"
                />
            </x-slot:actions>
        </div>
    </x-modal>
</div>
