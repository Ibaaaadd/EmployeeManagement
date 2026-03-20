@extends('layouts.app')

@section('title', 'Riwayat Absensi Pegawai')

@section('content')
<div class="container mt-4">
    <h4 style="border-bottom: 2px solid #198754; padding-bottom: 10px; display: inline-block; font-family: 'Montserrat', sans-serif; font-weight: 600;">
            RIWAYAT ABSENSI
    </h4>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('absensi.riwayat') }}" class="row mb-3">
        <div class="col-md-3">
            <input type="text" name="nama" class="form-control" placeholder="Nama Pegawai" value="{{ request('nama') }}">
        </div>
        <div class="col-md-2">
            <input type="month" name="bulan" class="form-control" value="{{ request('bulan', \Carbon\Carbon::now()->format('Y-m')) }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>

    <ul class="nav nav-tabs mb-3" id="absensiTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tabel-tab" data-bs-toggle="tab" data-bs-target="#tabel" type="button" role="tab">Tabel Umum</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="rekap-tab" data-bs-toggle="tab" data-bs-target="#rekap" type="button" role="tab">Rekap Per Pegawai</button>
        </li>
    </ul>


   <div class="tab-content" id="absensiTabContent">
    {{-- Tab: Tabel Umum --}}
    <div class="tab-pane fade show active" id="tabel" role="tabpanel" aria-labelledby="tabel-tab">
        @if($absensis->isEmpty())
            <div class="alert alert-warning text-center">
                Data riwayat absensi yang Anda input tidak tersedia.
            </div>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Jam</th>
                        <th>Tanggal</th>
                        <th>Nama Pegawai</th>
                        <th>Status Kehadiran</th>
                        <th>Bukti Foto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($absensis as $absensi)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($absensi->attendance_time)->format('H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($absensi->attendance_time)->format('d-m-Y') }}</td>
                            <td>{{ $absensi->pegawai->name }}</td>
                            <td>{{ $absensi->status }}</td>
                            <td>
                                @if($absensi->status === 'Hadir' && $absensi->attendance_photo)
                                    <a href="{{ asset('storage/' . $absensi->attendance_photo) }}" target="_blank">Lihat Foto</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="col-md-2">
            <a href="{{ route('absensi.riwayat.download', request()->query()) }}" class="btn btn-danger">Download PDF</a>
        </div>
    </div>

    {{-- Tab: Rekap Per Pegawai --}}
    <div class="tab-pane fade" id="rekap" role="tabpanel" aria-labelledby="rekap-tab">
       <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Pegawai</th>
                    <th>Jumlah Hadir</th>
                    <th>Jumlah Izin</th>
                    <th>Jumlah Tidak Hadir</th>
                    <th>Detail Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekapPegawai as $pegawai)
                    <tr>
                        <td>{{ $pegawai['nama'] }}</td>
                        <td>{{ $pegawai['hadir'] }}</td>
                        <td>{{ $pegawai['izin'] }}</td>
                        <td>{{ $pegawai['tidak_hadir'] }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="collapse" data-bs-target="#detail-{{ $loop->index }}">
                                Lihat Tanggal
                            </button>
                            <div class="collapse mt-2" id="detail-{{ $loop->index }}">
                                <ul class="list-unstyled">
                                    @foreach($pegawai['tanggal'] as $status => $tanggalList)
                                        <li><strong>{{ $status }}:</strong>
                                            <ul>
                                                @foreach($tanggalList as $tgl)
                                                    <li>{{ $tgl }}</li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
