<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            margin: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid black;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .text-end {
            text-align: right;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            color: orange;
            font-weight: bold;
            font-style: italic;
            margin: 0;
        }

        .header p {
            margin: 0;
        }

        .signature {
            text-align: right;
            margin-top: 50px;
        }

        .signature p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PT. Sarina Indika Makmur</h1>
        <br>
        <p>JL. Jend Sudirman No.41/49 A Palembang</p>
        <p>Telp (0711) 313414/310562 Fax (0711) 312226</p>
        <hr style="border: 1px solid black; margin-top: 8px;">
    </div>
    <h3 style="margin-top: 10px;">RIWAYAT ABSENSI PEGAWAI</h3>

     @if ($nama || $bulan)
        <p style="text-align: left;">
            Filter By:
            @if ($nama) '{{ strtoupper($nama) }}'@endif
            @if ($nama && $bulan) & @endif
            @if ($bulan) '{{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}'@endif
        </p>
    @endif
    <table>
        <thead>
            <tr>
                <th>Jam</th>
                <th>Tanggal</th>
                <th>Nama Pegawai</th>
                <th>Status Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absensis as $absensi)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($absensi->attendance_time)->format('H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($absensi->attendance_time)->format('d-m-Y') }}</td>
                    <td>{{ $absensi->pegawai->name }}</td>
                    <td>{{ $absensi->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>