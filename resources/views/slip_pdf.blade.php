<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - PT Jaya Abadi</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            color: #1e293b;
            background: #ffffff;
            font-size: 13px;
            line-height: 1.6;
        }
        .container {
            width: 100%;
            padding: 0;
        }
        
        /* Modern Header */
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
        }
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        .header p {
            font-size: 12px;
            opacity: 0.95;
            margin: 2px 0;
        }
        
        /* Badge Title */
        .title-badge {
            display: inline-block;
            background: #f1f5f9;
            color: #1e40af;
            font-weight: 700;
            font-size: 16px;
            padding: 10px 30px;
            border-radius: 25px;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 2px solid #e2e8f0;
        }
        
        /* Info Cards */
        .info-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .info-card {
            background: #f8fafc;
            padding: 15px 20px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }
        .info-card-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }
        .info-card-value {
            font-size: 16px;
            color: #0f172a;
            font-weight: 700;
        }
        
        /* Section Cards */
        .section-card {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
        }
        .section-header {
            background: #f1f5f9;
            color: #1e40af;
            font-weight: 700;
            font-size: 13px;
            padding: 10px 15px;
            margin: -20px -20px 15px -20px;
            border-radius: 10px 10px 0 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Item Rows */
        .item-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .item-row:last-child {
            border-bottom: none;
        }
        .item-label {
            color: #475569;
            font-size: 13px;
        }
        .item-value {
            color: #0f172a;
            font-weight: 600;
            font-size: 13px;
        }
        
        /* Badge Pills */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 8px;
        }
        .badge-primary {
            background: #dbeafe;
            color: #1e40af;
        }
        .badge-success {
            background: #d1fae5;
            color: #047857;
        }
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }
        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }
        
        /* Subtotal */
        .subtotal-row {
            background: #f8fafc;
            padding: 12px 15px;
            margin: 10px -20px -10px -20px;
            border-radius: 0 0 10px 10px;
            display: flex;
            justify-content: space-between;
            font-weight: 700;
            color: #0f172a;
        }
        
        /* Grand Total Card */
        .grand-total-card {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            border-radius: 12px;
            padding: 25px 30px;
            margin: 20px 0;
            text-align: center;
        }
        .grand-total-label {
            font-size: 13px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .grand-total-value {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 20px;
            color: #10b981;
            font-style: italic;
            background: #d1fae5;
            border-radius: 8px;
            margin: 10px 0;
        }
        
        /* Signature */
        .signature-section {
            margin-top: 40px;
            text-align: right;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
            width: 250px;
        }
        .signature-date {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 8px;
        }
        .signature-title {
            font-weight: 600;
            color: #475569;
            margin-bottom: 70px;
        }
        .signature-name {
            font-weight: 700;
            color: #0f172a;
            font-size: 14px;
            padding-top: 10px;
            border-top: 2px solid #1e40af;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Modern Header -->
        <div class="header">
            <h1>PT. JAYA ABADI</h1>
            <p>Jl. Jend Sudirman No.41/49 A Palembang</p>
            <p>Telp (0711) 313414 / 310562 &bull; Fax (0711) 312226</p>
        </div>

        <!-- Title Badge -->
        <div style="text-align: center;">
            <span class="title-badge">💰 Slip Gaji Pegawai</span>
        </div>

        <!-- Info Cards Grid -->
        <div class="info-cards">
            <div class="info-card">
                <div class="info-card-label">👤 Nama Pegawai</div>
                <div class="info-card-value">{{ $pegawai->name }}</div>
            </div>
            <div class="info-card">
                <div class="info-card-label">📅 Periode</div>
                <div class="info-card-value">{{ $periode }} {{ date('F Y', strtotime($bulanTahun)) }}</div>
            </div>
            <div class="info-card">
                <div class="info-card-label">💼 Jabatan</div>
                <div class="info-card-value">{{ $pegawai->jabatan ?? '-' }}</div>
            </div>
            <div class="info-card">
                <div class="info-card-label">📊 Total Hari Kerja</div>
                <div class="info-card-value">{{ $totalHariKerja ?? 0 }} Hari</div>
            </div>
        </div>

        <!-- Penerimaan Card -->
        <div class="section-card">
            <div class="section-header">💵 Penerimaan Gaji</div>
            
            <div class="item-row">
                <span class="item-label">Gaji Pokok</span>
                <span class="item-value">Rp {{ number_format($gajiPokok, 0, ',', '.') }}</span>
            </div>
            <div class="item-row">
                <span class="item-label">Insentif</span>
                <span class="item-value">Rp {{ number_format($insentif, 0, ',', '.') }}</span>
            </div>
            
            <div class="subtotal-row">
                <span>Total Penerimaan</span>
                <span>Rp {{ number_format($gajiPokok + $insentif, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Potongan Card -->
        <div class="section-card">
            <div class="section-header">✂️ Pengurangan (Potongan)</div>
            
            @php
                $hasPotongan = $jumlahIzin > 0 || $jumlahTidakHadir > 0 || $jumlahTerlambat > 0;
            @endphp
            
            @if($hasPotongan)
                @if($jumlahIzin > 0)
                <div class="item-row">
                    <span class="item-label">
                        Potongan Izin 
                        <span class="badge badge-warning">{{ $jumlahIzin }} Hari</span>
                    </span>
                    <span class="item-value">Rp {{ number_format($potonganIzin ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="item-row" style="font-size: 11px; color: #94a3b8; margin-top: -8px; padding-top: 0;">
                    <span style="padding-left: 15px;">{{ $jumlahIzin }} Hari × Rp {{ number_format($gajiPerHari ?? 0, 0, ',', '.') }}</span>
                    <span></span>
                </div>
                @endif
                
                @if($jumlahTidakHadir > 0)
                <div class="item-row">
                    <span class="item-label">
                        Potongan Alpha / Tidak Hadir
                        <span class="badge badge-danger">{{ $jumlahTidakHadir }} Hari</span>
                    </span>
                    <span class="item-value">Rp {{ number_format($potonganAlpha ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="item-row" style="font-size: 11px; color: #94a3b8; margin-top: -8px; padding-top: 0;">
                    <span style="padding-left: 15px;">{{ $jumlahTidakHadir }} Hari × Rp {{ number_format($gajiPerHari ?? 0, 0, ',', '.') }}</span>
                    <span></span>
                </div>
                @endif
                
                @if($jumlahTerlambat > 0)
                <div class="item-row">
                    <span class="item-label">
                        Potongan Keterlambatan
                        <span class="badge badge-warning">{{ $jumlahTerlambat }} Kali</span>
                    </span>
                    <span class="item-value">Rp {{ number_format($potonganTelat ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="item-row" style="font-size: 11px; color: #94a3b8; margin-top: -8px; padding-top: 0;">
                    <span style="padding-left: 15px;">{{ $jumlahTerlambat }} Kali × Rp 30.000</span>
                    <span></span>
                </div>
                @endif
                
                <div class="subtotal-row">
                    <span>Total Potongan</span>
                    <span>Rp {{ number_format($totalPengurangan, 0, ',', '.') }}</span>
                </div>
            @else
                <div class="empty-state">
                    ✨ Tidak ada potongan - Kehadiran sempurna! ✨
                </div>
            @endif
        </div>

        <!-- Grand Total Card -->
        <div class="grand-total-card">
            <div class="grand-total-label">Total Gaji Bersih (Take Home Pay)</div>
            <div class="grand-total-value">Rp {{ number_format($totalGaji, 0, ',', '.') }}</div>
        </div>

        <!-- Signature -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-date">Palembang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                <div class="signature-title">Pegawai yang bersangkutan,</div>
                <div class="signature-name">{{ $pegawai->name }}</div>
            </div>
        </div>
    </div>
</body>
</html>
