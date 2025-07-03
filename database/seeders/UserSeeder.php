<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Akun Kesiswaan (hanya satu)
        User::create([
            'name' => 'Admin Kesiswaan',
            'email' => 'kesiswaan@smkn1ciamis.com',
            'password' => Hash::make('kesiswaansmkn1ciamis'),
            'role' => 'kesiswaan',
            'status' => 'Aktif'
        ]);

        // Akun BK (bisa lebih dari satu)
        User::create([
            'name' => 'Etin',
            'email' => 'etin@smk1ciamis.com',
            'password' => Hash::make('bk123'),
            'role' => 'bk',
            'status' => 'Aktif'
        ]);

        // Akun Guru (bisa lebih dari satu)
        User::create([
            'name' => 'Udin',
            'email' => 'Udin@mail.com',
            'password' => Hash::make('udinudin'),
            'role' => 'guru',
            'status' => 'Aktif'
        ]);

        // Akun PKS (bisa lebih dari satu)
        User::create([
            'name' => 'User PKS',
            'email' => 'pks@smkn1ciamis.com',
            'password' => Hash::make('pks123'),
            'role' => 'pks',
            'status' => 'Aktif'
        ]);
    }
}
