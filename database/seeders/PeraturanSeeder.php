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
            ['kode_peraturan' => 'TT005', 'larangan' => 'Membawa buku bacaan, majalah, film, CD, dll yang berbau pornografi', 'tindakan_ringan' => 'R-1', 'tindakan_berat' => 'B-1'],
            ['kode_peraturan' => 'TT006', 'larangan' => 'Memiliki, memakai, menjual, membeli, mengedarkan, menyewa, menyimpan, membawa, dan mengadakan : Rokok,Vave/sejenisnya, miras, psikotropika, baik didalam maupun luar sekolah', 'tindakan_ringan' => 'R-1', 'tindakan_berat' => 'B-1'],
            ['kode_peraturan' => 'TT007', 'larangan' => 'Membawa senjata api dan senjata tajam', 'tindakan_ringan' => 'R-2', 'tindakan_berat' => 'B-1'],
            ['kode_peraturan' => 'TT008', 'larangan' => 'Tindakan asusila di dalam atau luar sekolah', 'tindakan_ringan' => 'R-2', 'tindakan_berat' => 'B-1'],
            ['kode_peraturan' => 'TT009', 'larangan' => 'Memiliki, memakai, menjual, membeli, mengedarkan, menyewa, menyimpan, membawa, dan mengadakan : Rokok,Vave/sejenisnya, miras, psikotropika, baik didalam maupun luar sekolah. ', 'tindakan_ringan' => 'R-2', 'tindakan_berat' => 'B-1'],
            ['kode_peraturan' => 'TT010', 'larangan' => 'Bermain game atau media sosial pada saat jam pelajaran', 'tindakan_ringan' => 'R-2', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT011', 'larangan' => 'Berkelahi baik perorangan atau kelompok di dalam sekolah ataupun di luar sekolah', 'tindakan_ringan' => 'R-1', 'tindakan_berat' => 'B-1'],
            ['kode_peraturan' => 'TT012', 'larangan' => 'Membuat sampah tidak pada tempatnya', 'tindakan_ringan' => 'R-4', 'tindakan_berat' => 'B-3'],
            ['kode_peraturan' => 'TT013', 'larangan' => 'Mencoret dinding bangunan, pagar sekolah, meja, kursi, dan peralatan milik sekolah lainnya', 'tindakan_ringan' => 'R-5', 'tindakan_berat' => 'B-4'],
            ['kode_peraturan' => 'TT014', 'larangan' => 'Berbicara kotor, mengumpat, bergunjing, menghina(perundungan) atau menyapa antar sesama siswa atau warga sekolah dengan kata sapaan atau panggilan tidak senonoh', 'tindakan_ringan' => 'R-3', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT015', 'larangan' => 'Membawa kartu dan bermain judi di dalam/luar lingkungan sekolah', 'tindakan_ringan' => 'R-1', 'tindakan_berat' => 'B-1'],
            ['kode_peraturan' => 'TT016', 'larangan' => 'Keluar sekolah sebelum proses belajar selesai tanpa ada izin dari guru piket', 'tindakan_ringan' => 'R-1', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT017', 'larangan' => 'Masuk ke lingkungan sekolah dengan cara melompat benteng atau pagar pembatas sekolah', 'tindakan_ringan' => 'R-1', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT018', 'larangan' => 'Melakukan Tindakan yang mengakibatkan kerugian dan kerusakan material milik sekolah ataupun milik perorangan', 'tindakan_ringan' => 'R-1', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT019', 'larangan' => 'Membentuk organisasi selain OSIS', 'tindakan_ringan' => 'R-3', 'tindakan_berat' => 'B-5'],
            ['kode_peraturan' => 'TT020', 'larangan' => 'Parkir motor tidak pada tempatnya, mengganggu knalpot bising dan tidak menggunakan helm berstandar SNI', 'tindakan_ringan' => 'R-3', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT021', 'larangan' => 'Rambut melebihi aturan yang ditentukan', 'tindakan_ringan' => 'R-6', 'tindakan_berat' => 'B-6'],
            ['kode_peraturan' => 'TT022', 'larangan' => 'Siswi muslim menggunakan kerudung segi empat selain VOAL', 'tindakan_ringan' => 'R-3', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT023', 'larangan' => 'Siswi yang tidak berkerudung memakai rok di atas lutut', 'tindakan_ringan' => 'R-3', 'tindakan_berat' => 'B-2'],
            ['kode_peraturan' => 'TT024', 'larangan' => 'Siswa tidak menggunakan sepatu dan tas sekolah sesuai aturan yang telah ditentukan', 'tindakan_ringan' => 'R-2', 'tindakan_berat' => 'B-2'],
        ]);
    }
}
