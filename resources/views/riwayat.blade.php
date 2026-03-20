@extends('layouts.app') 

@section('title', 'Riwayat Penggajian')

@section('content')
<div class="container mt-4">
    <h4>Riwayat Penggajian</h4>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('gaji.riwayat') }}" class="row mb-4">
        <div class="col-md-3">
            <input type="text" name="nama" class="form-control" placeholder="Nama Pegawai" value="{{ request('nama') }}">
        </div>
        <div class="col-md-2">
            <input type="month" name="bulan" class="form-control" value="{{ request('bulan') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>
    @if ($riwayat->isEmpty())
    <div class="alert alert-warning">
        Data riwayat penggajian yang Anda input tidak tersedia.
    </div>
    @else
    {{-- Riwayat List --}}
    <table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Pegawai</th>
            <th>Periode Gaji</th>
            <th>Slip Gaji</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($riwayat as $riwayat)
            <tr>
                <td>{{ $riwayat->pegawai->name }}</td>
                <td>{{ \Carbon\Carbon::parse($riwayat->tanggal_gaji)->translatedFormat('F Y') }}</td>
                <td>
                    <div class="d-flex gap-2">
                        <a href="{{ route('riwayat.preview', $riwayat->id) }}" class="btn btn-sm btn-secondary" target="_blank">View</a>
                        <a href="{{ route('riwayat.download', $riwayat->id) }}" class="btn btn-sm btn-danger">Download PDF</a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
    </table>
    @endif
</div>
@endsection
