<?php

namespace Database\Seeders;

use App\Models\Siswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Siswa::insert([
            ['kelas_id' => 1, 'nis' => '10001', 'nama_siswa' => 'Andi Pratama',      'total_pelanggaran' => 1],
            ['kelas_id' => 1, 'nis' => '10002', 'nama_siswa' => 'Budi Santosa',      'total_pelanggaran' => 0],

            ['kelas_id' => 2, 'nis' => '10003', 'nama_siswa' => 'Rina Maulida',      'total_pelanggaran' => 0],
            ['kelas_id' => 2, 'nis' => '10004', 'nama_siswa' => 'Siti Komariah',     'total_pelanggaran' => 1],

            ['kelas_id' => 3, 'nis' => '10005', 'nama_siswa' => 'Udin Pratama',      'total_pelanggaran' => 1],
            ['kelas_id' => 3, 'nis' => '10006', 'nama_siswa' => 'Joko Firmansyah',   'total_pelanggaran' => 2],

            ['kelas_id' => 4, 'nis' => '10007', 'nama_siswa' => 'Dodo Maulida',      'total_pelanggaran' => 0],
            ['kelas_id' => 4, 'nis' => '10008', 'nama_siswa' => 'Dian Nuraini',      'total_pelanggaran' => 1],

            ['kelas_id' => 5, 'nis' => '10009', 'nama_siswa' => 'Siti Aminah',       'total_pelanggaran' => 3],
            ['kelas_id' => 5, 'nis' => '10010', 'nama_siswa' => 'Wulan Kencana',     'total_pelanggaran' => 0],

            ['kelas_id' => 6, 'nis' => '10011', 'nama_siswa' => 'Andi Wijaya',       'total_pelanggaran' => 0],
            ['kelas_id' => 6, 'nis' => '10012', 'nama_siswa' => 'Rudi Hartono',      'total_pelanggaran' => 1],

            ['kelas_id' => 7, 'nis' => '10013', 'nama_siswa' => 'Intan Permata',     'total_pelanggaran' => 2],
            ['kelas_id' => 7, 'nis' => '10014', 'nama_siswa' => 'Dewi Sartika',      'total_pelanggaran' => 0],

            ['kelas_id' => 8, 'nis' => '10015', 'nama_siswa' => 'Fajar Nugraha',     'total_pelanggaran' => 1],
            ['kelas_id' => 8, 'nis' => '10016', 'nama_siswa' => 'Tono Wijaya',       'total_pelanggaran' => 2],

            ['kelas_id' => 9, 'nis' => '10017', 'nama_siswa' => 'Rahmawati',         'total_pelanggaran' => 0],
            ['kelas_id' => 9, 'nis' => '10018', 'nama_siswa' => 'Ahmad Ridho',       'total_pelanggaran' => 1],

            ['kelas_id' => 10, 'nis' => '10019', 'nama_siswa' => 'Nina Apriani',     'total_pelanggaran' => 0],
            ['kelas_id' => 10, 'nis' => '10020', 'nama_siswa' => 'Dina Marlia',      'total_pelanggaran' => 1],

            ['kelas_id' => 11, 'nis' => '10021', 'nama_siswa' => 'Bayu Saputra',     'total_pelanggaran' => 2],
            ['kelas_id' => 11, 'nis' => '10022', 'nama_siswa' => 'Yudi Hidayat',     'total_pelanggaran' => 0],

            ['kelas_id' => 12, 'nis' => '10023', 'nama_siswa' => 'Tiara Kusuma',     'total_pelanggaran' => 1],
            ['kelas_id' => 12, 'nis' => '10024', 'nama_siswa' => 'Mega Pertiwi',     'total_pelanggaran' => 0],

            ['kelas_id' => 13, 'nis' => '10025', 'nama_siswa' => 'Rangga Pratama',   'total_pelanggaran' => 3],
            ['kelas_id' => 13, 'nis' => '10026', 'nama_siswa' => 'Cindy Yuliana',    'total_pelanggaran' => 0],

            ['kelas_id' => 14, 'nis' => '10027', 'nama_siswa' => 'Aditya Nugroho',   'total_pelanggaran' => 2],
            ['kelas_id' => 14, 'nis' => '10028', 'nama_siswa' => 'Salsa Azzahra',    'total_pelanggaran' => 0],
        ]);
    }
}
