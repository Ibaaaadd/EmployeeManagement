<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - PT Jaya Abadi</title>
    <style>
        @page {
            margin: 30px 40px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333333;
            line-height: 1.5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        /* Header styling */
        .header-table {
            border-bottom: 2px solid #1e40af;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            margin: 0;
            padding: 0;
            text-transform: uppercase;
        }
        .company-address {
            font-size: 10px;
            color: #555;
            margin-top: 5px;
        }
        .slip-title {
            text-align: right;
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            text-transform: uppercase;
        }

        /* Employee Info */
        .info-table {
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }
        .info-table td {
            padding: 8px 12px;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            color: #64748b;
            width: 120px;
            font-size: 11px;
            text-transform: uppercase;
        }
        .info-value {
            font-weight: bold;
            color: #0f172a;
        }

        /* Rincian table */
        .details-wrapper {
            width: 100%;
            margin-bottom: 20px;
        }
        .details-table {
            border: 1px solid #e2e8f0;
            margin-bottom: 15px;
        }
        .details-table th {
            background-color: #f1f5f9;
            color: #1e40af;
            padding: 10px 12px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
        }
        .details-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        .details-table .amount {
            text-align: right;
            font-weight: bold;
            width: 150px;
        }
        .details-table .sub-text {
            font-size: 10px;
            color: #64748b;
            display: block;
            margin-top: 2px;
        }
        .subtotal-row td {
            background-color: #f8fafc;
            font-weight: bold;
            border-top: 1px solid #e2e8f0;
            color: #0f172a;
        }

        /* Take Home Pay */
        .total-table {
            width: 100%;
            border: 2px solid #1e40af;
            background-color: #eff6ff;
            margin-top: 10px;
        }
        .total-table td {
            padding: 15px 20px;
        }
        .total-label {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            text-transform: uppercase;
        }
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            text-align: right;
        }

        /* Signatures */
        .signature-table {
            margin-top: 40px;
            width: 100%;
        }
        .signature-table td {
            text-align: center;
            width: 50%;
            vertical-align: bottom;
            padding: 0 30px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 70px;
            padding-top: 5px;
            font-weight: bold;
        }
        .signature-date {
            margin-bottom: 10px;
            color: #555;
        }
        .no-data {
            color: #10b981;
            font-style: italic;
            text-align: center;
            padding: 15px;
            font-size: 11px;
            background: #f0fdf4;
        }
    </style>
</head>
<body>
    
    <table class="header-table">
        <tr>
            <td width="60%">
                <h1 class="company-name">PT. JAYA ABADI</h1>
                <div class="company-address">
                    Jl. Jend Sudirman No.41/49 A Palembang<br>
                    Telp (0711) 313414 / 310562 &bull; Fax (0711) 312226
                </div>
            </td>
            <td width="40%" class="slip-title">
                SLIP GAJI
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td class="info-label">Nama Pegawai</td>
            <td class="info-value">: {{ $pegawai->name }}</td>
            <td class="info-label">Periode</td>
            <td class="info-value">: {{ $periode }} {{ date('F Y', strtotime($bulanTahun)) }}</td>
        </tr>
        <tr>
            <td class="info-label">Jabatan</td>
            <td class="info-value">: {{ $pegawai->jabatan ?? '-' }}</td>
            <td class="info-label">Total Hari Kerja</td>
            <td class="info-value">: {{ $totalHariKerja ?? 0 }} Hari</td>
        </tr>
    </table>

    <table class="details-wrapper">
        <tr>
            <!-- PENERIMAAN -->
            <td width="48%" valign="top" style="padding-right: 2%;">
                <table class="details-table">
                    <tr>
                        <th colspan="2">Penerimaan</th>
                    </tr>
                    <tr>
                        <td>Gaji Pokok</td>
                        <td class="amount">Rp {{ number_format($gajiPokok, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Insentif</td>
                        <td class="amount">Rp {{ number_format($insentif, 0, ',', '.') }}</td>
                    </tr>
                    <!-- Extra spacing to align heights if needed -->
                    <tr><td colspan="2" style="border:none; padding: 15px;"></td></tr>
                    <tr class="subtotal-row">
                        <td>Total Penerimaan</td>
                        <td class="amount">Rp {{ number_format($gajiPokok + $insentif, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </td>

            <!-- POTONGAN -->
            <td width="48%" valign="top">
                <table class="details-table">
                    <tr>
                        <th colspan="2">Potongan</th>
                    </tr>
                    @php
                        $hasPotongan = $jumlahIzin > 0 || $jumlahTidakHadir > 0 || $jumlahTerlambat > 0;
                    @endphp
                    
                    @if($hasPotongan)
                        @if($jumlahIzin > 0)
                        <tr>
                            <td>
                                Izin ({{ $jumlahIzin }} Hari)
                                <span class="sub-text">{{ $jumlahIzin }} Hari &times; Rp {{ number_format($gajiPerHari ?? 0, 0, ',', '.') }}</span>
                            </td>
                            <td class="amount">Rp {{ number_format($potonganIzin ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        
                        @if($jumlahTidakHadir > 0)
                        <tr>
                            <td>
                                Alpha / Tidak Hadir ({{ $jumlahTidakHadir }} Hari)
                                <span class="sub-text">{{ $jumlahTidakHadir }} Hari &times; Rp {{ number_format($gajiPerHari ?? 0, 0, ',', '.') }}</span>
                            </td>
                            <td class="amount">Rp {{ number_format($potonganAlpha ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        
                        @if($jumlahTerlambat > 0)
                        <tr>
                            <td>
                                Keterlambatan ({{ $jumlahTerlambat }} Kali)
                                <span class="sub-text">{{ $jumlahTerlambat }} Kali &times; Rp 30.000</span>
                            </td>
                            <td class="amount">Rp {{ number_format($potonganTelat ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr class="subtotal-row">
                            <td>Total Potongan</td>
                            <td class="amount">Rp {{ number_format($totalPengurangan, 0, ',', '.') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="2" class="no-data">Tidak ada potongan - Kehadiran sempurna!</td>
                        </tr>
                        <tr class="subtotal-row">
                            <td>Total Potongan</td>
                            <td class="amount">Rp 0</td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    <table class="total-table">
        <tr>
            <td class="total-label">Total Gaji Bersih (Take Home Pay)</td>
            <td class="total-amount">Rp {{ number_format($totalGaji, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                <!-- Reserved for Employer Signature if needed, or left blank -->
            </td>
            <td>
                <div class="signature-date">Palembang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                <div>Penerima,</div>
                <div class="signature-line">{{ $pegawai->name }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
