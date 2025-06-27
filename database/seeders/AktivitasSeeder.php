<?php

namespace Database\Seeders;

use App\Models\Aktivitas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AktivitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Aktivitas::insert([
            [
                'ID_Akun' => 1,
                'keterangan' => 'Mengakses halaman dashboard',
                'tanggal' => now()->toDateString(),
                'time' => now()->format('H:i:s'),
            ],
        ]);
    }
}
