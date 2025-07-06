<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Livewire\DetailSiswa;
use App\Models\Pelanggaran;
use Livewire\Volt\Volt;
// use App\Livewire\Pages\SiswaDetail; // Sesuaikan dengan path Anda
use App\Models\Siswa;

// Route login
// Route::get('/login', fn() => Volt::mount('auth.loginform'))->middleware('guest')->name('login');
Route::get('/login', [LoginController::class, 'showLogin'])->name('login')->middleware('guest');

// Group route hanya untuk yang sudah login
Route::middleware('auth')->group(function () {

    // Semua role bisa akses beranda
    
    // Akses KESISWAAN saja
    Route::middleware('role:kesiswaan')->group(function () {
        Volt::route('/', 'beranda')->name('beranda');
        Volt::route('/tata-tertib', 'tata-tertib');
        Volt::route('/tindakan', 'tindakan');
        Volt::route('/pelanggaran', 'pelanggaran');
        Volt::route('/data-guru', 'data-guru');
        Volt::route('/data-kelas', 'data-kelas');
        Volt::route('/data-siswa', 'data-siswa');
        Volt::route('/akun-bk', 'akun-bk');
        Volt::route('/akun-pks', 'akun-pks');
        Volt::route('/akun-guru', 'akun-guru');
        Volt::route('/log-aktivitas', 'aktivitas');

        Route::get('/detail-siswa/{siswa}', \App\Livewire\Siswa\DetailSiswa::class)->name('detail-siswa');
    });

    // Akses BK dan KESISWAAN
    Route::middleware('role:bk,pks,guru')->group(function () {
        Volt::route('/input-pelanggar', 'pks.input_pelanggar')->name('input_pelanggar');
    });

    // Akses PKS dan KESISWAAN
    Route::middleware('role:pks')->group(function () {
        // Volt::route('/input-pelanggar', 'pks.input_pelanggar')->name('input_pelanggar');
    });

    // Akses GURU dan KESISWAAN
    Route::middleware('role:guru')->group(function () {
        // Volt::route('/data-siswa', 'data-siswa');
        // Volt::route('/pelanggaran', 'pelanggaran');
    });

    Route::get('/pelanggaran/print', function () {
        // Validasi dan format parameter
        $filters = [
            'search' => session('pelanggaran_search', ''),
            'nama' => session('pelanggaran_filter_nama', ''),
            'kelas_id' => session('pelanggaran_filter_kelas_id', ''),
            'tanggal_awal' => session('pelanggaran_filter_tanggal_awal', ''),
            'tanggal_akhir' => session('pelanggaran_filter_tanggal_akhir', ''),
            'sort_column' => session('pelanggaran_sort_column', 'ID_Pelanggaran'),
            'sort_direction' => in_array(strtolower(session('pelanggaran_sort_direction', 'asc')), ['asc', 'desc'])
                ? session('pelanggaran_sort_direction', 'asc')
                : 'asc'
        ];

        // Query dengan filter
        $query = Pelanggaran::query()
            ->when($filters['nama'], function ($query) use ($filters) {
                $query->where('nama_siswa', 'like', '%' . $filters['nama'] . '%');
            })
            ->when($filters['kelas_id'], function ($query) use ($filters) {
                $query->where('kelas_id', $filters['kelas_id']);
            })
            ->when($filters['tanggal_awal'] || $filters['tanggal_akhir'], function ($query) use ($filters) {
                if ($filters['tanggal_awal'] && $filters['tanggal_akhir']) {
                    // Jika kedua tanggal ada, gunakan between
                    $query->whereBetween('created_at', [
                        $filters['tanggal_awal'] . ' 00:00:00',
                        $filters['tanggal_akhir'] . ' 23:59:59'
                    ]);
                } elseif ($filters['tanggal_awal']) {
                    // Jika hanya tanggal awal, filter dari tanggal itu saja
                    $query->whereDate('created_at', $filters['tanggal_awal']);
                } else {
                    // Jika hanya tanggal akhir, filter sampai tanggal itu saja
                    $query->whereDate('created_at', $filters['tanggal_akhir']);
                }
            })
            ->when($filters['search'], function ($query) use ($filters) {
                $query->where(function ($q) use ($filters) {
                    $q->where('nis', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('nama_siswa', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('kelas', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('pelanggaran', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('tingkat_pelanggaran', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('tindakan', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('deskripsi_pelanggaran', 'like', '%' . $filters['search'] . '%');
                });
            })
            ->orderBy($filters['sort_column'], $filters['sort_direction']);

        $pelanggaran = $query->get();

        // Format data untuk print (split datetime columns)
        $pelanggaran->transform(function ($item) {
            $createdAt = \Carbon\Carbon::parse($item->created_at);
            $updatedAt = \Carbon\Carbon::parse($item->updated_at);

            $item->tanggal_melanggar = $createdAt->format('Y-m-d');
            $item->waktu_melanggar = $createdAt->format('H:i:s');
            $item->tanggal_update = $updatedAt->format('Y-m-d');
            $item->waktu_update = $updatedAt->format('H:i:s');

            return $item;
        });

        // Format data kelas untuk ditampilkan
        $kelas = null;
        if ($filters['kelas_id']) {
            $kelasModel = \App\Models\Kelas::find($filters['kelas_id']);
            $kelas = $kelasModel ? $kelasModel->kelas . ' ' . $kelasModel->jurusan : null;
        }

        return view('print.pelanggaran', [
            'pelanggaran' => $pelanggaran,
            'filter' => [
                'nama' => $filters['nama'],
                'kelas' => $kelas,
                'tanggal_awal' => $filters['tanggal_awal'],
                'tanggal_akhir' => $filters['tanggal_akhir'],
                'search' => $filters['search'],
                'sort_column' => $filters['sort_column'],
                'sort_direction' => $filters['sort_direction']
            ]
        ]);
    })->name('pelanggaran.print');
});
