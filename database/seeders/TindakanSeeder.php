<?php

namespace Database\Seeders;

use App\Models\Tindakan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TindakanSeeder extends Seeder
{
    public function run()
    {
        Tindakan::insert([
            ['kode_tindakan' => 'R-1', 'jenis' => 'ringan', 'keterangan' => 'Dipotong rambut dan kebersihan'],
            ['kode_tindakan' => 'R-2', 'jenis' => 'ringan', 'keterangan' => 'Diamankan dan kebersihan'],
            ['kode_tindakan' => 'R-3', 'jenis' => 'ringan', 'keterangan' => 'Teguran'],
            ['kode_tindakan' => 'R-4', 'jenis' => 'ringan', 'keterangan' => 'Membersihkan tempat tertentu'],
            ['kode_tindakan' => 'R-5', 'jenis' => 'ringan', 'keterangan' => 'Membersihkan kembali'],
            ['kode_tindakan' => 'R-6', 'jenis' => 'ringan', 'keterangan' => 'Potong Rambut Mandiri'],

            ['kode_tindakan' => 'B-1', 'jenis' => 'berat', 'keterangan' => 'Dikembalikan kepada orang tua'],
            ['kode_tindakan' => 'B-2', 'jenis' => 'berat', 'keterangan' => 'Pemanggilan orang tua'],
            ['kode_tindakan' => 'B-3', 'jenis' => 'berat', 'keterangan' => 'Membersihkan seluruh area kampus'],
            ['kode_tindakan' => 'B-4', 'jenis' => 'berat', 'keterangan' => 'Mengecat ulang oleh sendiri dengan biaya sendiri'],
            ['kode_tindakan' => 'B-5', 'jenis' => 'berat', 'keterangan' => 'Dibubarkan'],
            ['kode_tindakan' => 'B-6', 'jenis' => 'berat', 'keterangan' => 'Potong Rambut Di Sekolah'],
        ]);
    }
}
