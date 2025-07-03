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
        // Siswa::insert([
        //     // Kelas 10 AKL 1 (kelas_id: 1) - 36 siswa
        //     ['kelas_id' => 1, 'nis' => '10001', 'nama_siswa' => 'Ahmad Fauzi', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10002', 'nama_siswa' => 'Budi Santoso', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10003', 'nama_siswa' => 'Citra Dewi', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10004', 'nama_siswa' => 'Dewi Lestari', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10005', 'nama_siswa' => 'Eko Prasetyo', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10006', 'nama_siswa' => 'Fitri Handayani', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10007', 'nama_siswa' => 'Gunawan Setiawan', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10008', 'nama_siswa' => 'Hana Saraswati', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10009', 'nama_siswa' => 'Indra Kurniawan', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10010', 'nama_siswa' => 'Joko Widodo', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10011', 'nama_siswa' => 'Kartika Sari', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10012', 'nama_siswa' => 'Luki Hermawan', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10013', 'nama_siswa' => 'Maya Indah', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10014', 'nama_siswa' => 'Nina Rahmawati', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10015', 'nama_siswa' => 'Oki Setiawan', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10016', 'nama_siswa' => 'Putri Ayu', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10017', 'nama_siswa' => 'Rudi Hartono', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10018', 'nama_siswa' => 'Sinta Dewi', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10019', 'nama_siswa' => 'Tono Wijaya', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10020', 'nama_siswa' => 'Umi Kulsum', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10021', 'nama_siswa' => 'Vina Melati', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10022', 'nama_siswa' => 'Wawan Setiawan', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10023', 'nama_siswa' => 'Yuni Astuti', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10024', 'nama_siswa' => 'Zaki Ahmad', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10025', 'nama_siswa' => 'Aditya Nugroho', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10026', 'nama_siswa' => 'Bella Nurul', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10027', 'nama_siswa' => 'Candra Wijaya', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10028', 'nama_siswa' => 'Dian Permata', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10029', 'nama_siswa' => 'Eka Putra', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10030', 'nama_siswa' => 'Fajar Nugroho', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10031', 'nama_siswa' => 'Gita Ayu', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10032', 'nama_siswa' => 'Hendra Saputra', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10033', 'nama_siswa' => 'Intan Permata', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10034', 'nama_siswa' => 'Johan Setiawan', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10035', 'nama_siswa' => 'Kiki Amalia', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 1, 'nis' => '10036', 'nama_siswa' => 'Lala Septiani', 'total_pelanggaran' => rand(0, 3)],

        //     // Kelas 10 AKL 2 (kelas_id: 2) - 36 siswa
        //     ['kelas_id' => 2, 'nis' => '10037', 'nama_siswa' => 'Maman Suherman', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 2, 'nis' => '10038', 'nama_siswa' => 'Nia Kurnia', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 2, 'nis' => '10039', 'nama_siswa' => 'Oki Pratama', 'total_pelanggaran' => rand(0, 3)],
        //     // ... (lanjutkan pola yang sama sampai kelas 31)

        //     // Kelas 11 PPLG (kelas_id: 31) - 36 siswa terakhir
        //     ['kelas_id' => 31, 'nis' => '11101', 'nama_siswa' => 'Agus Supriyanto', 'total_pelanggaran' => rand(0, 3)],
        //     ['kelas_id' => 31, 'nis' => '11102', 'nama_siswa' => 'Bayu Saputra', 'total_pelanggaran' => rand(0, 3)],
        //     // ... (sampai 36 siswa)
        //     ['kelas_id' => 31, 'nis' => '11136', 'nama_siswa' => 'Zahra Fitriani', 'total_pelanggaran' => rand(0, 3)],
        // ]);
    }
}
