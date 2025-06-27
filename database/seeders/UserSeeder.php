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
            'email' => 'kesiswaan@example.com',
            'password' => Hash::make('password'),
            'role' => 'kesiswaan',
            'status' => 'Aktif'
        ]);

        // Akun BK (bisa lebih dari satu)
        User::create([
            'name' => 'Guru BK 1',
            'email' => 'bk1@example.com',
            'password' => Hash::make('password'),
            'role' => 'bk',
            'status' => 'Aktif'
        ]);

        // Akun Guru (bisa lebih dari satu)
        User::create([
            'name' => 'Guru Matematika',
            'email' => 'guru1@example.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'status' => 'Aktif'
        ]);

        // Akun PKS (bisa lebih dari satu)
        User::create([
            'name' => 'PKS Kurikulum',
            'email' => 'pks1@example.com',
            'password' => Hash::make('password'),
            'role' => 'pks',
            'status' => 'Aktif'
        ]);
    }
}
