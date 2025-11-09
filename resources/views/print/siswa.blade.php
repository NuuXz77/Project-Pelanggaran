<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Siswa - {{ $siswa->nama_siswa }}</title>
    <style>
        @page {
            size: A4;
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

        .logo-placeholder {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
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
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .school-address {
            font-size: 12px;
            margin-bottom: 5px;
        }

        .school-contact {
            font-size: 11px;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            margin: 15px 0;
            text-align: center;
        }

        .student-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            width: 150px;
            font-weight: bold;
        }

        .info-value {
            flex-grow: 1;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #333;
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
            text-align: left;
            font-weight: bold;
        }

        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        .summary-box {
            background-color: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #ffc107;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            display: inline-block;
            min-width: 200px;
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
                max-width: 800px;
                margin: 0 auto;
            }
        }

        .no-data {
            text-align: center;
            padding: 20px;
            background-color: #e7f3ff;
            border-radius: 5px;
            color: #0066cc;
        }
    </style>
</head>

<body>
    <div class="print-container">
        <!-- Kop Surat -->
        <div class="header">
            <div class="logo-placeholder">
                <img src="{{ asset('image/logo_smea.jpg') }}" alt="Logo SMEA" class="logo">
            </div>
            <div class="school-info">
                <div class="school-name">SMK NEGERI 1 CIAMIS</div>
                <div class="school-address">Jl. Jendral Soedirman No.269<br>
                    Ciamis, Jawa Barat 46215<br>
                    Indonesia</div>
                <div class="school-contact">Telp. (0265) 771204 | Email: surat@smkn1cms.net</div>
            </div>
        </div>

        <!-- Judul Laporan -->
        <div class="report-title">DETAIL DATA SISWA DAN PELANGGARAN</div>

        <!-- Data Siswa -->
        <div class="student-info">
            <div class="info-row">
                <div class="info-label">NIS</div>
                <div class="info-value">: {{ $siswa->nis }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nama Siswa</div>
                <div class="info-value">: {{ $siswa->nama_siswa }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Kelas</div>
                <div class="info-value">: {{ $siswa->kelas->kelas }} - {{ $siswa->kelas->jurusan }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Total Pelanggaran</div>
                <div class="info-value">: {{ $siswa->total_pelanggaran }}</div>
            </div>
        </div>

        <!-- Summary -->
        <div class="summary-box">
            <strong>RINGKASAN:</strong> Siswa ini memiliki <strong>{{ count($pelanggarans) }}</strong> catatan
            pelanggaran
        </div>

        <!-- Daftar Pelanggaran -->
        <div class="section-title">DAFTAR PELANGGARAN</div>

        @if (count($pelanggarans) > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width:5%">No</th>
                        <th style="width:12%">Tanggal</th>
                        <th style="width:10%">Tingkat</th>
                        <th style="width:25%">Pelanggaran</th>
                        <th style="width:20%">Tindakan</th>
                        <th style="width:20%">Deskripsi</th>
                        <th style="width:15%">Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pelanggarans as $index => $item)
                        <tr>
                            <td style="text-align:center">{{ $index + 1 }}</td>
                            <td>{{ $item->created_at->translatedFormat('d/m/Y H:i') }}</td>
                            <td>{{ $item->tingkat_pelanggaran }}</td>
                            <td>{{ $item->pelanggaran }}</td>
                            <td>{{ $item->tindakan }}</td>
                            <td>{{ $item->deskripsi_pelanggaran ?? '-' }}</td>
                            <td>{{ $item->dicatat_oleh }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                Siswa ini belum memiliki catatan pelanggaran
            </div>
        @endif

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