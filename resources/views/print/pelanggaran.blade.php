<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pelanggaran Siswa</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 20px;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px double #333;
        }

        .logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .school-info {
            text-align: center;
            flex-grow: 1;
        }

        .school-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .school-address {
            font-size: 12px;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            margin: 15px 0;
            text-align: center;
        }

        .report-info {
            margin-bottom: 15px;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }

        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        .footer {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }

        .filter-info {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 11px;
        }

        .filter-item {
            margin-right: 15px;
            display: inline-block;
        }

        .no-print {
            display: none;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }
        }

        @media screen {
            .print-only {
                display: none;
            }

            .no-print {
                display: block;
                text-align: center;
                margin-top: 20px;
            }

            body {
                background-color: #f5f5f5;
                padding: 20px;
            }

            .print-container {
                background-color: white;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                max-width: 1000px;
                margin: 0 auto;
            }
        }
    </style>
</head>

<body>
    <div class="print-container">
        <!-- Kop Surat -->
        <div class="header">
            <div class="logo-placeholder">
                <!-- Logo akan ditempatkan di sini -->
                <div style="width:80px;height:80px;display:flex;align-items:center;justify-content:center;">
                    <img src="{{ asset('image/logo_smea.jpg') }}" alt="logo smea" width="60px">
                </div>
            </div>
            <div class="school-info">
                <div class="school-name">SMK NEGERI 1 CIAMIS</div>
                <div class="school-address">Jl.Jendral Soedirman No.269
                    Ciamis,Jawa Barat
                    46215
                    Indonesia</div>
                <div class="school-contact">Telp. (0265) 771204 | Email: surat@smkn1cms.net</div>
            </div>
        </div>

        <!-- Garis Pembatas -->
        <div style="border-bottom:1px solid #333;margin-bottom:15px;"></div>

        <!-- Judul Laporan -->
        <div class="report-title">LAPORAN DATA PELANGGARAN SISWA</div>

        <!-- Info Filter -->
        <!-- Info Filter -->
        <div class="filter-info">
            <strong>PARAMETER LAPORAN:</strong><br>
            @if ($filter['nama'])
                <span class="filter-item">• Filter Nama: "{{ $filter['nama'] }}"</span><br>
            @endif
            @if ($filter['kelas'])
                <span class="filter-item">• Filter Kelas: {{ $filter['kelas'] }}</span><br>
            @endif
            @if ($filter['tanggal_awal'])
                <span class="filter-item">• Periode:
                    {{ \Carbon\Carbon::parse($filter['tanggal_awal'])->translatedFormat('d/m/Y') }} -
                    {{ \Carbon\Carbon::parse($filter['tanggal_akhir'])->translatedFormat('d/m/Y') }}</span><br>
            @endif
            @if ($filter['search'])
                <span class="filter-item">• Pencarian: "{{ $filter['search'] }}"</span><br>
            @endif
            <span class="filter-item">• Total Data: {{ count($pelanggaran) }} record</span>
        </div>

        <!-- Info Tanggal Cetak -->
        <div class="report-info">
            Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y H:i:s') }}
        </div>

        <!-- Tabel Data -->
        <table>
            <thead>
                <tr>
                    <th style="width:5%">No</th>
                    <th style="width:8%">NIS</th>
                    <th style="width:15%">Nama Siswa</th>
                    <th style="width:10%">Kelas</th>
                    <th style="width:15%">Pelanggaran</th>
                    <th style="width:10%">Tingkat</th>
                    <th style="width:15%">Tindakan</th>
                    <th style="width:12%">Deskripsi</th>
                    <th style="width:12%">Di Catat Oleh</th>
                    <th style="width:10%">Waktu</th>
                </tr>
            </thead>
            <tbody style="text-align:center; justify-content:center;">
                @foreach ($pelanggaran as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nis }}</td>
                        <td>{{ $item->nama_siswa }}</td>
                        <td>{{ $item->kelas }}</td>
                        <td>{{ $item->pelanggaran }}</td>
                        <td>{{ $item->tingkat_pelanggaran }}</td>
                        <td>{{ $item->tindakan }}</td>
                        <td>{{ $item->deskripsi_pelanggaran }}</td>
                        <td>{{ $item->dicatat_oleh }}</td>
                        <td>{{ $item->created_at->translatedFormat('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <div class="print-date">
                Dicetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}
            </div>
            <div class="page-info">
                Halaman <span class="page-number"></span>
            </div>
        </div>
    </div>

    <!-- Tombol Kembali (hanya tampil di browser) -->
    <div class="no-print">
        <a href="/pelanggaran" onclick="window.history.back()"
            style="
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 20px auto;
            cursor: pointer;
            border-radius: 5px;
            display: block;
        ">
            Kembali
        </a>
    </div>

    <script>
        // Auto print saat dokumen siap
        window.addEventListener('load', function() {
            // Tambahkan nomor halaman
            var pages = document.querySelectorAll('.page-number');
            for (var i = 0; i < pages.length; i++) {
                pages[i].textContent = (i + 1);
            }

            // Auto print dan close
            setTimeout(function() {
                window.print();

                // Set timeout untuk close window setelah print
                setTimeout(function() {
                    window.close();
                }, 500);
            }, 500);
        });

        // Fallback jika window.close tidak bekerja
        document.querySelector('.no-print button').addEventListener('click', function() {
            window.history.back();
        });
    </script>
</body>

</html>
