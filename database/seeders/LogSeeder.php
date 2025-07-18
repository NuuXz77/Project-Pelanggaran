<?php

namespace Database\Seeders;

use App\Models\Log;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Log::insert([
            [
                'ID_Akun' => 1,
                'keterangan' => 'Login berhasil',
                'tanggal' => now()->toDateString(),
                'time' => now()->format('H:i:s'),
            ],
        ]);
    }
}
