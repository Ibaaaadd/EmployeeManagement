@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #3498db, #2980b9); color: white;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <i class="fa-solid fa-users fa-3x mb-3"></i>
                <h3 class="card-title">Total Pegawai</h3>
                <h1 class="display-4 fw-bold">{{ $totalPegawai ?? 0 }}</h1>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #2ecc71, #27ae60); color: white;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <i class="fa-solid fa-user-check fa-3x mb-3"></i>
                <h3 class="card-title">Hadir Hari Ini</h3>
                <h1 class="display-4 fw-bold">{{ $hadirHariIni ?? 0 }}</h1>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #e67e22, #d35400); color: white;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <i class="fa-solid fa-file-invoice-dollar fa-3x mb-3"></i>
                <h3 class="card-title">Histori Gaji</h3>
                <a href="{{ url('/riwayat-gaji') }}" class="btn btn-light mt-3 fw-bold text-dark">Lihat Detail</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-primary fw-bold"><i class="fa-solid fa-bolt me-2"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ url('/absensi') }}" class="btn btn-primary px-4 py-2"><i class="fa-solid fa-calendar-check me-2"></i> Input Absensi</a>
                    <a href="{{ url('/penggajian') }}" class="btn btn-success px-4 py-2"><i class="fa-solid fa-money-bill-wave me-2"></i> Proses Penggajian</a>
                    <a href="{{ url('/pegawai') }}" class="btn btn-warning px-4 py-2 text-white"><i class="fa-solid fa-user-plus me-2"></i> Tambah Pegawai</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
