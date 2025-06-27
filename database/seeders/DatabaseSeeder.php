<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KelasSeeder::class,
            SiswaSeeder::class,
            GuruSeeder::class,
            PeraturanSeeder::class,
            TindakanSeeder::class,
            PelanggaranSeeder::class,
            LogSeeder::class,
            AktivitasSeeder::class,
        ]);
    }
}
