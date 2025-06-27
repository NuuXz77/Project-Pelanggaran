<?php

namespace Database\Seeders;

use App\Models\Peraturan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeraturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Peraturan::insert([
            ['kode_peraturan' => 'TT001', 'larangan' => 'Siswa yang terlambat datang ke sekolah', 'tindakan_ringan' => 'R-3', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT002', 'larangan' => 'Memakai perhiasan yang berlebihan/Tidak sesuai norma', 'tindakan_ringan' => 'R-3', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT003', 'larangan' => 'Memakai kosmetik berlebihan untuk perempuan', 'tindakan_ringan' => 'R-3', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT004', 'larangan' => 'Memakai jaket/sweater di lingkungan sekolah', 'tindakan_ringan' => 'R-3', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT005', 'larangan' => 'Membawa buku bacaan, majalah, film, CD, dll yang berbau pornografi', 'tindakan_ringan' => 'R-3', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT006', 'larangan' => 'Merokok, vape, miras, psikotropika', 'tindakan_ringan' => 'R-1', 'tindakan_berat' => 'B-1'],
            ['kode_peraturan' => 'TT007', 'larangan' => 'Membawa senjata api dan senjata tajam', 'tindakan_ringan' => 'R-1', 'tindakan_berat' => 'B-1'],
            ['kode_peraturan' => 'TT008', 'larangan' => 'Tindakan asusila di dalam atau luar sekolah', 'tindakan_ringan' => 'R-2', 'tindakan_berat' => 'B-1'],
            ['kode_peraturan' => 'TT009', 'larangan' => 'Membawa handphone/kamera', 'tindakan_ringan' => 'R-2', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT010', 'larangan' => 'Main game/sosial media saat jam pelajaran', 'tindakan_ringan' => 'R-2', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT011', 'larangan' => 'Berteriak dan gaduh di kelas atau lorong sekolah', 'tindakan_ringan' => 'R-2', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT012', 'larangan' => 'Buang sampah tidak pada tempatnya', 'tindakan_ringan' => 'R-2', 'tindakan_berat' => 'B-3'],
            ['kode_peraturan' => 'TT013', 'larangan' => 'Mencoret dinding/bangunan sekolah', 'tindakan_ringan' => 'R-2', 'tindakan_berat' => 'B-3'],
            ['kode_peraturan' => 'TT014', 'larangan' => 'Berjudi/berkelahi/menggertak', 'tindakan_ringan' => 'R-2', 'tindakan_berat' => 'B-1'],
            ['kode_peraturan' => 'TT015', 'larangan' => 'Keluar sekolah saat jam pelajaran tanpa izin', 'tindakan_ringan' => 'R-2', 'tindakan_berat' => 'B-1'],
            ['kode_peraturan' => 'TT016', 'larangan' => 'Melompat benteng sekolah', 'tindakan_ringan' => 'R-2', 'tindakan_berat' => 'B-1'],
            ['kode_peraturan' => 'TT017', 'larangan' => 'Mengakibatkan kerusakan dan kehilangan fasilitas', 'tindakan_ringan' => 'R-1', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT018', 'larangan' => 'Menjual/beli atribut sekolah', 'tindakan_ringan' => 'R-3', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT019', 'larangan' => 'Parkir motor tidak tertib', 'tindakan_ringan' => 'R-2', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT020', 'larangan' => 'Tidak memakai helm berstandar SNI', 'tindakan_ringan' => 'R-2', 'tindakan_berat' => 'B-3'],
            ['kode_peraturan' => 'TT021', 'larangan' => 'Tidak memakai atribut lengkap OSIS', 'tindakan_ringan' => 'R-2', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT022', 'larangan' => 'Siswa tidak menggunakan kerudung segi empat', 'tindakan_ringan' => 'R-2', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT023', 'larangan' => 'Tidak memakai sepatu/tas sesuai aturan', 'tindakan_ringan' => 'R-2', 'tindakan_berat' => 'B-2'],
        ]);
    }
}
