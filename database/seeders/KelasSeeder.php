<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kelas::insert([
            ['kelas' => 10, 'jurusan' => 'PPLG',    'wali_kelas' => 'Budi Santosa',     'jumlah_siswa' => 30],
            ['kelas' => 10, 'jurusan' => 'AKL',     'wali_kelas' => 'Rina Marwati',     'jumlah_siswa' => 28],
            ['kelas' => 10, 'jurusan' => 'MPLB',    'wali_kelas' => 'Dedi Haryanto',    'jumlah_siswa' => 29],
            ['kelas' => 10, 'jurusan' => 'PM',      'wali_kelas' => 'Sri Wahyuni',      'jumlah_siswa' => 27],
            ['kelas' => 10, 'jurusan' => 'Kuliner', 'wali_kelas' => 'Anisa Putri',      'jumlah_siswa' => 26],
            ['kelas' => 10, 'jurusan' => 'Hotel',   'wali_kelas' => 'Andi Setiawan',    'jumlah_siswa' => 25],
            ['kelas' => 10, 'jurusan' => 'DKV',     'wali_kelas' => 'Robby Firmansyah', 'jumlah_siswa' => 31],

            ['kelas' => 11, 'jurusan' => 'PPLG',    'wali_kelas' => 'Siti Aminah',      'jumlah_siswa' => 30],
            ['kelas' => 11, 'jurusan' => 'AKL',     'wali_kelas' => 'Rahmat Hidayat',   'jumlah_siswa' => 28],
            ['kelas' => 11, 'jurusan' => 'MPLB',    'wali_kelas' => 'Nani Lestari',     'jumlah_siswa' => 29],
            ['kelas' => 11, 'jurusan' => 'PM',      'wali_kelas' => 'Bambang Irawan',   'jumlah_siswa' => 27],
            ['kelas' => 11, 'jurusan' => 'Kuliner', 'wali_kelas' => 'Wulan Sari',       'jumlah_siswa' => 26],
            ['kelas' => 11, 'jurusan' => 'Hotel',   'wali_kelas' => 'Teguh Prasetyo',   'jumlah_siswa' => 25],
            ['kelas' => 11, 'jurusan' => 'DKV',     'wali_kelas' => 'Desi Kurniawati',  'jumlah_siswa' => 31],
        ]);
    }
}
