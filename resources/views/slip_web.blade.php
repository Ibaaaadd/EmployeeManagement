<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji Web View</title>
    <style>
        /* Tetap gunakan styling sama */
        body { font-family: 'Times New Roman', Times, serif; font-size: 14px; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        table, th, td { border: 1px solid black; padding: 8px; }
        th { background-color: #f2f2f2; text-align: left; }
        .text-end { text-align: right; }
        .header { text-align: center; margin-bottom: 10px; }
        .header h1 { color: orange; font-weight: bold; font-style: italic; margin: 0; }
        .header p { margin: 0; }
        .signature { text-align: right; margin-top: 50px; }
        .signature p { margin: 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PT. Sarina Indika Makmur</h1>
        <p>JL. Jend Sudirman No.41/49 A Palembang</p>
        <p>Telp (0711) 313414/310562 Fax (0711) 312226</p>
        <hr style="border: 1px solid black; margin-top: 8px;">
    </div>

    <h3 style="margin-top: 10px;">SLIP GAJI PEGAWAI</h3>

    <p><strong>Nama</strong> : {{ $pegawai->name }}</p>
    <p><strong>Jabatan</strong> : {{ $pegawai->jabatan }}</p>
    <p><strong>Periode</strong> : {{ $periode == 1 ? '1 - 15' : '16 - Akhir Bulan' }} {{ date('F Y', strtotime($bulanTahun)) }}</p>

    <table>
        <thead>
            <tr>
                <th>Komponen</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Gaji Pokok</td>
                <td>Rp {{ number_format($gajiPokok, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Insentif</td>
                <td>Rp {{ number_format($insentif, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-end"><strong>Total</strong></td>
                <td><strong>Rp {{ number_format($gajiPokok + $insentif, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <th colspan="2">Pengurangan</th>
            </tr>
            <tr>
                <td>Izin</td>
                <td>{{ $jumlahIzin }} x Rp 30.000</td>
            </tr>
            <tr>
                <td>Tidak Hadir</td>
                <td>{{ $jumlahTidakHadir }} x Rp 30.000</td>
            </tr>
            <tr>
                <td class="text-end"><strong>Total Pengurangan</strong></td>
                <td><strong>Rp {{ number_format($totalPengurangan, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td class="text-end"><strong>TOTAL GAJI</strong></td>
                <td><strong>Rp {{ number_format($totalGaji, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="signature">
        <p>Palembang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p><strong>Pegawai yang bersangkutan,</strong></p>
        <br><br><br>
        <p><strong>______________________</strong></p>
    </div>
</body>
</html>
