<?php

namespace Database\Seeders;

use App\Models\Guru;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Guru::insert([
            ['kelas_id' => 1, 'nama_guru' => 'Budi Santosa', 'nip' => '198506052021001', 'password' => bcrypt('guru123'), 'kelas' => 'X RPL'],
            ['kelas_id' => 2, 'nama_guru' => 'Siti Aminah', 'nip' => '198507102021002', 'password' => bcrypt('guru456'), 'kelas' => 'XI TKJ'],
        ]);
    }
}
