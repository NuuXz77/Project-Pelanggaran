<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Livewire\DetailSiswa;
use App\Models\Pelanggaran;
use App\Models\Peraturan;
use Livewire\Volt\Volt;
use App\Models\Siswa;

// Fungsi helper untuk membuat alias pelanggaran dari database
function getAliasPelanggaran($pelanggaran) {
    // Cari di tabel peraturan berdasarkan kolom 'larangan'
    $peraturan = Peraturan::where('larangan', $pelanggaran)->first();
    
    if ($peraturan && !empty($peraturan->alias)) {
        return strtoupper($peraturan->alias);
    }
    
    // Jika tidak ada alias di database, buat otomatis dari 3 huruf pertama
    $words = explode(' ', $pelanggaran);
    if (count($words) >= 2) {
        // Ambil huruf pertama dari 2-3 kata pertama
        $alias = '';
        for ($i = 0; $i < min(3, count($words)); $i++) {
            $alias .= strtoupper(substr($words[$i], 0, 1));
        }
        return $alias;
    }
    
    // Fallback: ambil 3 huruf pertama
    return strtoupper(substr(str_replace(' ', '', $pelanggaran), 0, 3));
}

// Fungsi untuk mendapatkan semua alias mapping
function getAllAliasMapping() {
    $jenisPelanggaran = Pelanggaran::select('pelanggaran')
        ->distinct()
        ->orderBy('pelanggaran')
        ->pluck('pelanggaran');
    
    $aliasMapping = [];
    foreach ($jenisPelanggaran as $jenis) {
        $aliasMapping[$jenis] = getAliasPelanggaran($jenis);
    }
    
    return $aliasMapping;
}

// Route login
Route::get('/login', [LoginController::class, 'showLogin'])->name('login')->middleware('guest');

// Group route hanya untuk yang sudah login
Route::middleware('auth')->group(function () {

    // Akses KESISWAAN saja
    Route::middleware('role:kesiswaan')->group(function () {
        Volt::route('/', 'beranda')->name('beranda');
        Volt::route('/profile', 'profile')->name('profile');
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
        Volt::route('/siswa-kesiangan', 'pks.siswa_kesiangan')->name('siswa_kesiangan');
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
                    $query->whereBetween('created_at', [
                        $filters['tanggal_awal'] . ' 00:00:00',
                        $filters['tanggal_akhir'] . ' 23:59:59'
                    ]);
                } elseif ($filters['tanggal_awal']) {
                    $query->whereDate('created_at', $filters['tanggal_awal']);
                } else {
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

        // Format data untuk print
        $pelanggaran->transform(function ($item) {
            $createdAt = \Carbon\Carbon::parse($item->created_at);
            $updatedAt = \Carbon\Carbon::parse($item->updated_at);

            $item->tanggal_melanggar = $createdAt->format('Y-m-d');
            $item->waktu_melanggar = $createdAt->format('H:i:s');
            $item->tanggal_update = $updatedAt->format('Y-m-d');
            $item->waktu_update = $updatedAt->format('H:i:s');

            return $item;
        });

        // Format data kelas
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

    // Route print pelanggaran per baris (seperti per siswa)
    Route::get('/pelanggaran/print-baris', function () {
        // Ambil filter dari session
        $filters = [
            'search' => session('pelanggaran_search', ''),
            'nama' => session('pelanggaran_filter_nama', ''),
            'kelas_id' => session('pelanggaran_filter_kelas_id', ''),
            'tanggal_awal' => session('pelanggaran_filter_tanggal_awal', ''),
            'tanggal_akhir' => session('pelanggaran_filter_tanggal_akhir', ''),
        ];

        // Query pelanggaran dengan filter
        $pelanggaranQuery = Pelanggaran::query()
            ->when($filters['nama'], function ($query) use ($filters) {
                $query->where('nama_siswa', 'like', '%' . $filters['nama'] . '%');
            })
            ->when($filters['kelas_id'], function ($query) use ($filters) {
                $query->where('kelas_id', $filters['kelas_id']);
            })
            ->when($filters['tanggal_awal'] && $filters['tanggal_akhir'], function ($query) use ($filters) {
                // Jika kedua tanggal ada, gunakan whereBetween untuk rentang waktu
                $query->whereBetween('created_at', [
                    $filters['tanggal_awal'] . ' 00:00:00',
                    $filters['tanggal_akhir'] . ' 23:59:59'
                ]);
            })
            ->when($filters['tanggal_awal'] && !$filters['tanggal_akhir'], function ($query) use ($filters) {
                // Jika hanya tanggal awal
                $query->whereDate('created_at', '>=', $filters['tanggal_awal']);
            })
            ->when(!$filters['tanggal_awal'] && $filters['tanggal_akhir'], function ($query) use ($filters) {
                // Jika hanya tanggal akhir
                $query->whereDate('created_at', '<=', $filters['tanggal_akhir']);
            })
            ->when($filters['search'], function ($query) use ($filters) {
                $query->where(function ($q) use ($filters) {
                    $q->where('nis', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('nama_siswa', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('kelas', 'like', '%' . $filters['search'] . '%');
                });
            });

        $pelanggaranData = $pelanggaranQuery->get();

        // Ambil semua jenis pelanggaran yang unik dari data yang difilter
        $jenisPelanggaran = $pelanggaranData->pluck('pelanggaran')->unique()->sort()->values();

        // Buat mapping alias
        $aliasMapping = [];
        foreach ($jenisPelanggaran as $jenis) {
            $aliasMapping[$jenis] = getAliasPelanggaran($jenis);
        }

        // Group by siswa dan hitung pelanggaran per jenis
        $siswaGrouped = $pelanggaranData->groupBy('nis');
        
        $siswaData = $siswaGrouped->map(function ($items, $nis) use ($jenisPelanggaran) {
            $firstItem = $items->first();
            $pelanggaranCount = [];
            
            foreach ($jenisPelanggaran as $jenis) {
                $count = $items->where('pelanggaran', $jenis)->count();
                $pelanggaranCount[$jenis] = $count > 0 ? $count : '-';
            }
            
            return [
                'nis' => $nis,
                'nama_siswa' => $firstItem->nama_siswa,
                'kelas' => $firstItem->kelas,
                'pelanggaran' => $pelanggaranCount,
                'total' => $items->count()
            ];
        })->values();

        // Format kelas untuk filter info
        $kelas = null;
        if ($filters['kelas_id']) {
            $kelasModel = \App\Models\Kelas::find($filters['kelas_id']);
            $kelas = $kelasModel ? $kelasModel->kelas . ' ' . $kelasModel->jurusan : null;
        }

        return view('print.baris-pelanggaran', [
            'siswaData' => $siswaData,
            'jenisPelanggaran' => $jenisPelanggaran,
            'aliasMapping' => $aliasMapping,
            'filter' => [
                'search' => $filters['search'],
                'nama' => $filters['nama'],
                'kelas' => $kelas,
                'tanggal_awal' => $filters['tanggal_awal'],
                'tanggal_akhir' => $filters['tanggal_akhir'],
            ]
        ]);
    })->name('pelanggaran.print-baris');

    Route::get('/siswa/{siswa}/print', function (\App\Models\Siswa $siswa) {
        $pelanggarans = \App\Models\Pelanggaran::where('nis', $siswa->nis)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('print.siswa', [
            'siswa' => $siswa,
            'pelanggarans' => $pelanggarans
        ]);
    })->name('siswa.print');

    // Route print semua data siswa dengan pelanggaran + FILTER
    Route::get('/siswa/print-all', function () {
        // Ambil filter dari session
        $filters = [
            'search' => session('siswa_search', ''),
            'nama_siswa' => session('siswa_filter_nama', ''),
            'kelas_id' => session('siswa_filter_kelas_id', ''),
            'tanggal_awal' => session('siswa_filter_tanggal_awal', ''),
            'tanggal_akhir' => session('siswa_filter_tanggal_akhir', ''),
            'sort_column' => session('siswa_sort_column', 'nama_siswa'),
            'sort_direction' => in_array(strtolower(session('siswa_sort_direction', 'asc')), ['asc', 'desc'])
                ? session('siswa_sort_direction', 'asc')
                : 'asc'
        ];

        // Query siswa dengan filter
        $siswaQuery = \App\Models\Siswa::with('kelas')
            ->when($filters['search'], function ($query) use ($filters) {
                $query->where(function ($q) use ($filters) {
                    $q->where('nama_siswa', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('nis', 'like', '%' . $filters['search'] . '%')
                        ->orWhereHas('kelas', function ($q) use ($filters) {
                            $q->where('kelas', 'like', '%' . $filters['search'] . '%');
                        });
                });
            })
            ->when($filters['nama_siswa'], function ($query) use ($filters) {
                $query->where('nama_siswa', 'like', '%' . $filters['nama_siswa'] . '%');
            })
            ->when($filters['kelas_id'], function ($query) use ($filters) {
                $query->where('kelas_id', $filters['kelas_id']);
            })
            ->when($filters['tanggal_awal'], function ($query) use ($filters) {
                $query->whereDate('created_at', '>=', $filters['tanggal_awal']);
            })
            ->when($filters['tanggal_akhir'], function ($query) use ($filters) {
                $query->whereDate('created_at', '<=', $filters['tanggal_akhir']);
            })
            ->orderBy($filters['sort_column'], $filters['sort_direction']);

        $siswa = $siswaQuery->get();
        
        // Ambil semua jenis pelanggaran yang unik
        $jenisPelanggaran = \App\Models\Pelanggaran::select('pelanggaran')
            ->distinct()
            ->orderBy('pelanggaran')
            ->pluck('pelanggaran');
        
        // Buat mapping alias dari database
        $aliasMapping = [];
        foreach ($jenisPelanggaran as $jenis) {
            $aliasMapping[$jenis] = getAliasPelanggaran($jenis);
        }
        
        // Untuk setiap siswa, hitung jumlah pelanggaran per jenis
        $siswaWithPelanggaran = $siswa->map(function ($s) use ($jenisPelanggaran) {
            $pelanggaranCount = [];
            
            foreach ($jenisPelanggaran as $jenis) {
                $count = \App\Models\Pelanggaran::where('nis', $s->nis)
                    ->where('pelanggaran', $jenis)
                    ->count();
                    
                $pelanggaranCount[$jenis] = $count > 0 ? $count : '-';
            }
            
            return [
                'siswa' => $s,
                'pelanggaran' => $pelanggaranCount,
                'total' => \App\Models\Pelanggaran::where('nis', $s->nis)->count()
            ];
        });

        // Format kelas untuk filter info
        $kelas = null;
        if ($filters['kelas_id']) {
            $kelasModel = \App\Models\Kelas::find($filters['kelas_id']);
            $kelas = $kelasModel ? $kelasModel->kelas . ' ' . $kelasModel->jurusan : null;
        }
        
        return view('print.per-siswa', [
            'siswaData' => $siswaWithPelanggaran,
            'jenisPelanggaran' => $jenisPelanggaran,
            'aliasMapping' => $aliasMapping,
            'filter' => [
                'search' => $filters['search'],
                'nama_siswa' => $filters['nama_siswa'],
                'kelas' => $kelas,
                'tanggal_awal' => $filters['tanggal_awal'],
                'tanggal_akhir' => $filters['tanggal_akhir'],
                'sort_column' => $filters['sort_column'],
                'sort_direction' => $filters['sort_direction']
            ]
        ]);
    })->name('siswa.print-all');
});
