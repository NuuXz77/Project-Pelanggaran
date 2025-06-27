<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use Livewire\Volt\Volt;


// Route login
// Route::get('/login', fn() => Volt::mount('auth.loginform'))->middleware('guest')->name('login');
Route::get('/login', [LoginController::class, 'showLogin'])->name('login')->middleware('guest');

// Group route hanya untuk yang sudah login
Route::middleware('auth')->group(function () {

    // Semua role bisa akses beranda
    Volt::route('/', 'beranda')->name('beranda');

    // Akses KESISWAAN saja
    Route::middleware('role:kesiswaan')->group(function () {
        Volt::route('/tata-tertib', 'tata-tertib');
        Volt::route('/tindakan', 'tindakan');
    });

    // Akses BK dan KESISWAAN
    Route::middleware('role:bk,kesiswaan')->group(function () {
        Volt::route('/tata-tertib', 'tata-tertib');
        Volt::route('/bk-area', 'bk.index'); // contoh area BK
    });

    // Akses PKS dan KESISWAAN
    Route::middleware('role:pks,kesiswaan')->group(function () {
        Volt::route('/input-pelanggar', 'rolepks.input_pelanggar');
    });

    // Akses GURU dan KESISWAAN
    Route::middleware('role:guru,kesiswaan')->group(function () {
        Volt::route('/data-kelas', 'kelas.index');
        Volt::route('/siswa-melanggar', 'guru.pelanggaran');
    });
});
