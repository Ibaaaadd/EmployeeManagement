<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - PT Jaya Abadi</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 40px 20px;
            color: #1f2937;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            padding: 50px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 25px;
        }
        .header h1 {
            color: #1e3a8a;
            font-size: 32px;
            font-weight: 800;
            margin: 0 0 8px 0;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .header p {
            margin: 4px 0;
            color: #64748b;
            font-size: 15px;
        }
        .title-section {
            text-align: center;
            margin-bottom: 35px;
        }
        .title-section h2 {
            display: inline-block;
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 2px;
            padding: 8px 25px;
            background-color: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 35px;
            background: #f8fafc;
            padding: 25px;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
        }
        .info-item {
            display: flex;
            align-items: center;
        }
        .info-label {
            min-width: 100px;
            font-weight: 600;
            color: #64748b;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-separator {
            margin: 0 10px;
            color: #cbd5e1;
        }
        .info-value {
            font-weight: 600;
            color: #0f172a;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 40px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }
        th, td {
            padding: 16px 20px;
            border-bottom: 1px solid #e2e8f0;
        }
        th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #475569;
            text-align: left;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
        }
        td {
            font-size: 15px;
            color: #334155;
        }
        table tr:last-child td {
            border-bottom: none;
        }
        .amount {
            text-align: right;
            font-variant-numeric: tabular-nums;
            font-weight: 500;
        }
        .section-title td {
            background-color: #f1f5f9;
            font-weight: 700;
            color: #0f172a;
            font-size: 14px;
        }
        .subtotal-row td {
            font-weight: 600;
            color: #0f172a;
            background-color: #f8fafc;
        }
        .grand-total td {
            background-color: #1e3a8a !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 18px;
            padding: 20px;
        }
        .footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 60px;
            padding-right: 20px;
        }
        .signature-box {
            text-align: center;
            width: 250px;
        }
        .signature-date {
            margin-bottom: 8px;
            color: #64748b;
            font-size: 15px;
        }
        .signature-title {
            font-weight: 600;
            margin-bottom: 90px;
            color: #0f172a;
            font-size: 16px;
        }
        .signature-line {
            border-top: 2px solid #0f172a;
            margin-top: 10px;
        }
        .signature-name {
            font-weight: 700;
            color: #0f172a;
            margin-top: 8px;
            font-size: 16px;
        }

        /* Action buttons for web view */
        .actions {
            text-align: center;
            margin-top: 30px;
        }
        .btn-print {
            background-color: #1e3a8a;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.2s;
            font-family: 'Inter', sans-serif;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-print:hover {
            background-color: #1e40af;
        }
        .btn-print svg {
            width: 18px;
            height: 18px;
        }
        
        @media print {
            @page {
                size: A4;
                margin: 10mm;
            }
            body {
                background-color: white;
                padding: 0;
            }
            .container {
                box-shadow: none;
                padding: 0;
                margin: 0;
                max-width: 100%;
                width: 100%;
            }
            /* Reduce spacing for print so it fits on one page */
            .header {
                margin-bottom: 20px;
                padding-bottom: 15px;
            }
            .header h1 {
                font-size: 24px;
            }
            .title-section {
                margin-bottom: 20px;
            }
            .title-section h2 {
                font-size: 18px;
                padding: 5px 15px;
            }
            .info-grid {
                margin-bottom: 20px;
                padding: 15px;
            }
            table {
                margin-bottom: 20px;
            }
            th, td {
                padding: 10px 15px;
            }
            .grand-total td {
                padding: 15px;
            }
            .footer {
                margin-top: 30px;
            }
            .signature-title {
                margin-bottom: 60px;
            }
            .actions {
                display: none;
            }
            .grand-total td {
                background-color: #1e3a8a !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
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
            <div class="info-item">
                <span class="info-label">Nama</span>
                <span class="info-separator">:</span>
                <span class="info-value">{{ $pegawai->name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Jabatan</span>
                <span class="info-separator">:</span>
                <span class="info-value">{{ $pegawai->jabatan ?? '-' }}</span>
            </div>
            <div class="info-item" style="grid-column: span 2;">
                <span class="info-label">Periode</span>
                <span class="info-separator">:</span>
                <span class="info-value">{{ $periode }} {{ date('F Y', strtotime($bulanTahun)) }}</span>
            </div>
        </div>

        <table>
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
                <div style="height: 60px;"></div>
                <div class="signature-name">{{ $pegawai->name }}</div>
                <div class="signature-line"></div>
            </div>
        </div>
        
        <div class="actions">
            <button class="btn-print" onclick="window.print()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Cetak Slip Gaji
            </button>
        </div>
    </div>
</body>
</html>
