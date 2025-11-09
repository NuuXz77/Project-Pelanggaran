<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pelanggaran Per Baris</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15px;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 3px double #333;
        }

        .logo {
            width: 60px;
            height: auto;
        }

        .school-info {
            text-align: center;
            flex-grow: 1;
        }

        .school-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .school-address {
            font-size: 10px;
            margin-bottom: 3px;
        }

        .school-contact {
            font-size: 9px;
        }

        .report-title {
            font-size: 14px;
            font-weight: bold;
            text-decoration: underline;
            margin: 10px 0;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 9px;
        }

        th {
            background-color: #f2f2f2;
            border: 1px solid #333;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
        }

        td {
            border: 1px solid #333;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
        }

        td.text-left {
            text-align: left;
        }

        .siswa-col {
            min-width: 35px;
            font-size: 10px;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
        }

        .signature {
            margin-top: 30px;
            text-align: right;
        }

        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #333;
            display: inline-block;
            min-width: 150px;
        }

        .no-print {
            display: none;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }

        @media screen {
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
            }
        }

        .summary-info {
            margin-bottom: 10px;
            background-color: #f9f9f9;
            padding: 8px;
            border-radius: 5px;
            font-size: 10px;
        }

        .filter-info {
            background-color: #e3f2fd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            border-left: 4px solid #2196F3;
            font-size: 10px;
        }

        .filter-item {
            display: inline-block;
            margin-right: 15px;
            margin-bottom: 5px;
        }

        .legend {
            margin-top: 15px;
            margin-bottom: 10px;
            background-color: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
        }

        .legend-title {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 11px;
        }

        .legend-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 5px;
            font-size: 9px;
        }

        .legend-item {
            display: flex;
            gap: 5px;
        }

        .legend-alias {
            font-weight: bold;
            min-width: 40px;
        }
    </style>
</head>

<body>
    <div class="print-container">
        <!-- Kop Surat -->
        <div class="header">
            <img src="{{ asset('image/logo_smea.jpg') }}" alt="Logo SMEA" class="logo">
            <div class="school-info">
                <div class="school-name">SMK NEGERI 1 CIAMIS</div>
                <div class="school-address">Jl. Jendral Soedirman No.269, Ciamis, Jawa Barat 46215, Indonesia</div>
                <div class="school-contact">Telp. (0265) 771204 | Email: surat@smkn1cms.net</div>
            </div>
        </div>

        <!-- Judul Laporan -->
        <div class="report-title">LAPORAN PELANGGARAN PER SISWA (BARIS)</div>

        <!-- Filter Info -->
        @if($filter['search'] || $filter['nama'] || $filter['kelas'] || $filter['tanggal_awal'] || $filter['tanggal_akhir'])
            <div class="filter-info">
                <strong>FILTER YANG DITERAPKAN:</strong>
                @if($filter['search'])
                    <div class="filter-item"><strong>Pencarian:</strong> {{ $filter['search'] }}</div>
                @endif
                @if($filter['nama'])
                    <div class="filter-item"><strong>Nama Siswa:</strong> {{ $filter['nama'] }}</div>
                @endif
                @if($filter['kelas'])
                    <div class="filter-item"><strong>Kelas:</strong> {{ $filter['kelas'] }}</div>
                @endif
                @if($filter['tanggal_awal'])
                    <div class="filter-item"><strong>Dari Tanggal:</strong> {{ \Carbon\Carbon::parse($filter['tanggal_awal'])->format('d/m/Y') }}</div>
                @endif
                @if($filter['tanggal_akhir'])
                    <div class="filter-item"><strong>Sampai Tanggal:</strong> {{ \Carbon\Carbon::parse($filter['tanggal_akhir'])->format('d/m/Y') }}</div>
                @endif
            </div>
        @endif

        <!-- Summary Info -->
        <div class="summary-info">
            <strong>Total Siswa:</strong> {{ count($siswaData) }} | 
            <strong>Jenis Pelanggaran:</strong> {{ count($jenisPelanggaran) }} |
            <strong>Total Pelanggaran:</strong> {{ $siswaData->sum('total') }}
        </div>

        <!-- Legend/Keterangan Alias -->
        <div class="legend">
            <div class="legend-title">KETERANGAN KODE PELANGGARAN:</div>
            <div class="legend-grid">
                @foreach($aliasMapping as $pelanggaran => $alias)
                    <div class="legend-item">
                        <span class="legend-alias">{{ $alias }}</span>
                        <span>=</span>
                        <span>{{ $pelanggaran }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Tabel Data -->
        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width: 3%">No</th>
                    <th rowspan="2" style="width: 8%">NIS</th>
                    <th rowspan="2" style="width: 15%">Nama Siswa</th>
                    <th rowspan="2" style="width: 8%">Kelas</th>
                    <th colspan="{{ count($jenisPelanggaran) }}">Jenis Pelanggaran</th>
                    <th rowspan="2" style="width: 5%">Total</th>
                </tr>
                <tr>
                    @foreach($jenisPelanggaran as $jenis)
                        <th class="siswa-col" title="{{ $jenis }}">
                            {{ $aliasMapping[$jenis] }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($siswaData as $index => $data)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $data['nis'] }}</td>
                        <td class="text-left">{{ $data['nama_siswa'] }}</td>
                        <td>{{ $data['kelas'] }}</td>
                        @foreach($jenisPelanggaran as $jenis)
                            <td>{{ $data['pelanggaran'][$jenis] }}</td>
                        @endforeach
                        <td><strong>{{ $data['total'] }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #e9ecef; font-weight: bold;">
                    <td colspan="4" class="text-left">TOTAL KESELURUHAN</td>
                    @foreach($jenisPelanggaran as $jenis)
                        <td>
                            {{ collect($siswaData)->sum(function($data) use ($jenis) {
                                return $data['pelanggaran'][$jenis] === '-' ? 0 : $data['pelanggaran'][$jenis];
                            }) }}
                        </td>
                    @endforeach
                    <td>
                        <strong>{{ collect($siswaData)->sum('total') }}</strong>
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="footer">
            Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y H:i:s') }}
        </div>

        <!-- Tanda Tangan -->
        <div class="signature">
            <div>Ciamis, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div>Petugas Kesiswaan</div>
            <div class="signature-line"></div>
        </div>
    </div>

    <!-- Tombol Kembali (hanya tampil di browser) -->
    <div class="no-print">
        <button onclick="window.history.back()"
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
        ">
            Kembali
        </button>
    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
                setTimeout(function() {
                    window.close();
                }, 500);
            }, 500);
        });
    </script>
</body>

</html>
