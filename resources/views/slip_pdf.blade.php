<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - PT Jaya Abadi</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1f2937;
            margin: 0;
            padding: 0;
            font-size: 14px;
        }
        .container {
            width: 100%;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f1f5f9;
        }
        .header h1 {
            color: #1e3a8a;
            font-size: 26px;
            font-weight: bold;
            margin: 0 0 5px 0;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .header p {
            margin: 3px 0;
            color: #475569;
            font-size: 14px;
        }
        .title-section {
            text-align: center;
            margin-bottom: 20px;
        }
        .title-section h2 {
            display: inline-block;
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 1.5px;
            padding: 6px 20px;
            background-color: #f8fafc;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
            text-transform: uppercase;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 20px;
            background-color: #f8fafc;
            padding: 10px 15px;
            border: 1px solid #f1f5f9;
            border-radius: 8px;
        }
        .info-grid table {
            width: 100%;
            border: none;
            margin: 0;
            background-color: transparent;
            box-shadow: none;
        }
        .info-grid table td {
            border: none;
            padding: 4px 0;
            font-size: 14px;
        }
        .info-label {
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            width: 100px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }
        .data-table th, .data-table td {
            padding: 10px 15px;
            border-bottom: 1px solid #e2e8f0;
        }
        .data-table th {
            background-color: #f8fafc;
            font-weight: bold;
            color: #475569;
            text-align: left;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }
        .data-table td {
            font-size: 14px;
            color: #334155;
        }
        .amount {
            text-align: right;
        }
        .section-title td {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #0f172a;
            font-size: 13px;
        }
        .subtotal-row td {
            font-weight: bold;
            color: #0f172a;
            background-color: #f8fafc;
        }
        .grand-total td {
            background-color: #1e3a8a !important;
            color: #ffffff !important;
            font-weight: bold !important;
            font-size: 16px;
            padding: 15px;
        }
        /* PDF specific override for background colors */
        .header h1 { color: #1e3a8a; }
        .data-table th { background-color: #f8fafc !important; }
        .section-title td { background-color: #f1f5f9 !important; }
        .subtotal-row td { background-color: #f8fafc !important; }
        .grand-total td { background-color: #1e3a8a !important; color: #ffffff !important; }
        
        .footer {
            width: 100%;
            margin-top: 40px;
        }
        .signature-box {
            float: right;
            text-align: center;
            width: 250px;
        }
        .signature-date {
            margin-bottom: 6px;
            color: #64748b;
            font-size: 14px;
        }
        .signature-title {
            font-weight: bold;
            margin-bottom: 80px;
            color: #0f172a;
        }
        .signature-line {
            border-top: 1px solid #0f172a;
            margin-top: 8px;
        }
        .signature-name {
            font-weight: bold;
            color: #0f172a;
            font-size: 14px;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PT. JAYA ABADI</h1>
            <p>Jl. Jend Sudirman No.41/49 A Palembang</p>
            <p>Telp (0711) 313414 / 310562 &bull; Fax (0711) 312226</p>
        </div>

        <div class="title-section">
            <h2>SLIP GAJI PEGAWAI</h2>
        </div>

        <div class="info-grid">
            <table>
                <tr>
                    <td class="info-label">Nama</td>
                    <td style="width: 15px;">:</td>
                    <td><b>{{ $pegawai->name }}</b></td>
                    <td class="info-label">Periode</td>
                    <td style="width: 15px;">:</td>
                    <td><b>{{ $periode }} {{ date('F Y', strtotime($bulanTahun)) }}</b></td>
                </tr>
                <tr>
                    <td class="info-label">Jabatan</td>
                    <td>:</td>
                    <td><b>{{ $pegawai->jabatan ?? '-' }}</b></td>
                    <td colspan="3"></td>
                </tr>
            </table>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Deskripsi Komponen</th>
                    <th class="amount">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Gaji Pokok</td>
                    <td class="amount">{{ number_format($gajiPokok, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Insentif</td>
                    <td class="amount">{{ number_format($insentif, 0, ',', '.') }}</td>
                </tr>
                <tr class="subtotal-row">
                    <td>Total Penerimaan</td>
                    <td class="amount">{{ number_format($gajiPokok + $insentif, 0, ',', '.') }}</td>
                </tr>
                
                <tr class="section-title">
                    <td colspan="2">PENGURANGAN (POTONGAN)</td>
                </tr>
                <tr>
                    <td>Izin ({{ $jumlahIzin }} Hari x Rp {{ number_format($gajiPerHari ?? 30000, 0, ',', '.') }})</td>
                    <td class="amount">{{ number_format($jumlahIzin * ($gajiPerHari ?? 30000), 0, ',', '.') }}</td>
                </tr>
                <tr>
<td>Tidak Hadir ({{ $jumlahTidakHadir }} Hari x Rp {{ number_format($gajiPerHari ?? 30000, 0, ',', '.') }})</td>
                    <td class="amount">{{ number_format($jumlahTidakHadir * ($gajiPerHari ?? 30000), 0, ',', '.') }}</td>
                </tr>
                <tr class="subtotal-row">
                    <td>Total Potongan</td>
                    <td class="amount">{{ number_format($totalPengurangan, 0, ',', '.') }}</td>
                </tr>
                
                <tr class="grand-total">
                    <td>TOTAL GAJI BERSIH (TAKE HOME PAY)</td>
                    <td class="amount">Rp {{ number_format($totalGaji, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <div class="signature-box">
                <div class="signature-date">Palembang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                <div class="signature-title">Pegawai yang bersangkutan,</div>
                <div class="signature-name">{{ $pegawai->name }}</div>
                <div class="signature-line"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>
