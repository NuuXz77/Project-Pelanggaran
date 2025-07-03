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
        // Guru::insert([
        //     // Wali Kelas 10
        //     ['kelas_id' => 1, 'nama_guru' => 'Rina Marwati', 'nip' => '198001012021001', 'password' => bcrypt('198001012021001'), 'kelas' => '10 AKL 1'],
        //     ['kelas_id' => 2, 'nama_guru' => 'Dewi Kurniasih', 'nip' => '198002022021002', 'password' => bcrypt('198002022021002'), 'kelas' => '10 AKL 2'],
        //     ['kelas_id' => 3, 'nama_guru' => 'Agus Supriyanto', 'nip' => '198003032021003', 'password' => bcrypt('198003032021003'), 'kelas' => '10 AKL 3'],
        //     ['kelas_id' => 4, 'nama_guru' => 'Siti Rahayu', 'nip' => '198004042021004', 'password' => bcrypt('198004042021004'), 'kelas' => '10 AKL 4'],
        //     ['kelas_id' => 5, 'nama_guru' => 'Bambang Suryadi', 'nip' => '198005052021005', 'password' => bcrypt('198005052021005'), 'kelas' => '10 AKL 5'],
        //     ['kelas_id' => 6, 'nama_guru' => 'Dedi Haryanto', 'nip' => '198006062021006', 'password' => bcrypt('198006062021006'), 'kelas' => '10 MPLB 1'],
        //     ['kelas_id' => 7, 'nama_guru' => 'Eko Prasetyo', 'nip' => '198007072021007', 'password' => bcrypt('198007072021007'), 'kelas' => '10 MPLB 2'],
        //     ['kelas_id' => 8, 'nama_guru' => 'Fitri Handayani', 'nip' => '198008082021008', 'password' => bcrypt('198008082021008'), 'kelas' => '10 MPLB 3'],
        //     ['kelas_id' => 9, 'nama_guru' => 'Sri Wahyuni', 'nip' => '198009092021009', 'password' => bcrypt('198009092021009'), 'kelas' => '10 PM 1'],
        //     ['kelas_id' => 10, 'nama_guru' => 'Yuni Astuti', 'nip' => '198010102021010', 'password' => bcrypt('198010102021010'), 'kelas' => '10 PM 2'],
        //     ['kelas_id' => 11, 'nama_guru' => 'Anisa Putri', 'nip' => '198011112021011', 'password' => bcrypt('198011112021011'), 'kelas' => '10 Kuliner 1'],
        //     ['kelas_id' => 12, 'nama_guru' => 'Rizki Amelia', 'nip' => '198012122021012', 'password' => bcrypt('198012122021012'), 'kelas' => '10 Kuliner 2'],
        //     ['kelas_id' => 13, 'nama_guru' => 'Andi Setiawan', 'nip' => '198101012021013', 'password' => bcrypt('198101012021013'), 'kelas' => '10 Hotel 1'],
        //     ['kelas_id' => 14, 'nama_guru' => 'Hendra Gunawan', 'nip' => '198102022021014', 'password' => bcrypt('198102022021014'), 'kelas' => '10 Hotel 2'],
        //     ['kelas_id' => 15, 'nama_guru' => 'Robby Firmansyah', 'nip' => '198103032021015', 'password' => bcrypt('198103032021015'), 'kelas' => '10 DKV'],
        //     ['kelas_id' => 16, 'nama_guru' => 'Budi Santosa', 'nip' => '198104042021016', 'password' => bcrypt('198104042021016'), 'kelas' => '10 PPLG'],

        //     // Wali Kelas 11
        //     ['kelas_id' => 17, 'nama_guru' => 'Rahmat Hidayat', 'nip' => '198105052021017', 'password' => bcrypt('198105052021017'), 'kelas' => '11 AKL 1'],
        //     ['kelas_id' => 18, 'nama_guru' => 'Linda Susanti', 'nip' => '198106062021018', 'password' => bcrypt('198106062021018'), 'kelas' => '11 AKL 2'],
        //     ['kelas_id' => 19, 'nama_guru' => 'Fajar Nugroho', 'nip' => '198107072021019', 'password' => bcrypt('198107072021019'), 'kelas' => '11 AKL 3'],
        //     ['kelas_id' => 20, 'nama_guru' => 'Maya Indah Sari', 'nip' => '198108082021020', 'password' => bcrypt('198108082021020'), 'kelas' => '11 AKL 4'],
        //     ['kelas_id' => 21, 'nama_guru' => 'Nani Lestari', 'nip' => '198109092021021', 'password' => bcrypt('198109092021021'), 'kelas' => '11 MPLB 1'],
        //     ['kelas_id' => 22, 'nama_guru' => 'Rudi Hermawan', 'nip' => '198110102021022', 'password' => bcrypt('198110102021022'), 'kelas' => '11 MPLB 2'],
        //     ['kelas_id' => 23, 'nama_guru' => 'Sari Dewi', 'nip' => '198111112021023', 'password' => bcrypt('198111112021023'), 'kelas' => '11 MPLB 3'],
        //     ['kelas_id' => 24, 'nama_guru' => 'Bambang Irawan', 'nip' => '198112122021024', 'password' => bcrypt('198112122021024'), 'kelas' => '11 PM 1'],
        //     ['kelas_id' => 25, 'nama_guru' => 'Dian Permatasari', 'nip' => '198201012021025', 'password' => bcrypt('198201012021025'), 'kelas' => '11 PM 2'],
        //     ['kelas_id' => 26, 'nama_guru' => 'Wulan Sari', 'nip' => '198202022021026', 'password' => bcrypt('198202022021026'), 'kelas' => '11 Kuliner 1'],
        //     ['kelas_id' => 27, 'nama_guru' => 'Aditya Pratama', 'nip' => '198203032021027', 'password' => bcrypt('198203032021027'), 'kelas' => '11 Kuliner 2'],
        //     ['kelas_id' => 28, 'nama_guru' => 'Teguh Prasetyo', 'nip' => '198204042021028', 'password' => bcrypt('198204042021028'), 'kelas' => '11 Hotel 1'],
        //     ['kelas_id' => 29, 'nama_guru' => 'Ratna Dewi', 'nip' => '198205052021029', 'password' => bcrypt('198205052021029'), 'kelas' => '11 Hotel 2'],
        //     ['kelas_id' => 30, 'nama_guru' => 'Desi Kurniawati', 'nip' => '198206062021030', 'password' => bcrypt('198206062021030'), 'kelas' => '11 DKV'],
        //     ['kelas_id' => 31, 'nama_guru' => 'Siti Aminah', 'nip' => '198207072021031', 'password' => bcrypt('198207072021031'), 'kelas' => '11 PPLG'],
        // ]);
    }
}
