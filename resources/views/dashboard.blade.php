@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4 animate__animated animate__zoomIn">
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #4f46e5, #3b82f6); color: white; border-radius: 20px;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center position-relative overflow-hidden py-5">
                <i class="fa-solid fa-users fa-4x mb-3" style="opacity: 0.8;"></i>
                <h4 class="card-title fw-light">Total Pegawai</h4>
                <h1 class="display-3 fw-bold mb-0">{{ $totalPegawai ?? 0 }}</h1>
                <div class="position-absolute" style="right: -20px; bottom: -20px; opacity: 0.1;">
                    <i class="fa-solid fa-users" style="font-size: 8rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 20px;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center position-relative overflow-hidden py-5">
                <i class="fa-solid fa-user-check fa-4x mb-3" style="opacity: 0.8;"></i>
                <h4 class="card-title fw-light">Hadir Hari Ini</h4>
                <h1 class="display-3 fw-bold mb-0">{{ $hadirHariIni ?? 0 }}</h1>
                <div class="position-absolute" style="right: -20px; bottom: -20px; opacity: 0.1;">
                    <i class="fa-solid fa-calendar-check" style="font-size: 8rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; border-radius: 20px;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center position-relative overflow-hidden py-5">
                <i class="fa-solid fa-file-invoice-dollar fa-4x mb-3" style="opacity: 0.8;"></i>
                <h4 class="card-title fw-light">Histori Gaji</h4>
                <a href="{{ url('/riwayat-gaji') }}" class="btn btn-light rounded-pill mt-3 px-4 fw-bold text-dark shadow-sm hover-scale">Lihat Detail <i class="fa-solid fa-arrow-right ms-2"></i></a>
                <div class="position-absolute" style="right: -20px; bottom: -20px; opacity: 0.1;">
                    <i class="fa-solid fa-money-bill-trend-up" style="font-size: 8rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 animate__animated animate__fadeInUp animate__delay-1s">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 20px;">
            <div class="card-header bg-white py-4 d-flex align-items-center" style="border-radius: 20px 20px 0 0;">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-bolt text-white"></i>
                </div>
                <h5 class="mb-0 text-dark fw-bold">Aksi Cepat</h5>
            </div>
            <div class="card-body p-4">
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ url('/absensi') }}" class="btn btn-primary px-4 py-3 rounded-pill shadow-sm d-flex align-items-center">
                        <i class="fa-solid fa-calendar-check me-2 fs-5"></i> Input Absensi
                    </a>
                    <a href="{{ url('/penggajian') }}" class="btn btn-success px-4 py-3 rounded-pill shadow-sm d-flex align-items-center">
                        <i class="fa-solid fa-money-bill-wave me-2 fs-5"></i> Proses Penggajian
                    </a>
                    <a href="{{ url('/pegawai') }}" class="btn btn-warning px-4 py-3 rounded-pill shadow-sm d-flex align-items-center text-white">
                        <i class="fa-solid fa-user-plus me-2 fs-5"></i> Tambah Pegawai
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-scale { transition: transform 0.3s ease; }
.hover-scale:hover { transform: scale(1.05); }
.btn { transition: all 0.3s ease; }
.btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important; }
</style>
@endsection
