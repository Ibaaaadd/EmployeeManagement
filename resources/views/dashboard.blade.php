@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Welcome Header -->
<div class="row mb-4 animate__animated animate__fadeInDown">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1" style="font-family: 'Montserrat', sans-serif; font-weight: 700; color: var(--primary-color);">
                    <i class="fas fa-chart-line me-2"></i> Dashboard Overview
                </h2>
                <p class="text-muted mb-0">
                    <i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </p>
            </div>
            <div class="bg-primary text-white px-4 py-3 rounded-3 shadow-sm">
                <i class="far fa-clock me-2"></i>
                <span id="currentTime" class="fw-bold"></span>
            </div>
        </div>
    </div>
</div>

<!-- Main Statistics Cards -->
<div class="row g-4 mb-4 animate__animated animate__zoomIn">
    <!-- Total Pegawai -->
    <div class="col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #4f46e5, #3b82f6); color: white; border-radius: 20px;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center position-relative overflow-hidden py-4">
                <i class="fa-solid fa-users fa-3x mb-3" style="opacity: 0.9;"></i>
                <h6 class="card-title fw-light text-uppercase mb-2" style="letter-spacing: 1px;">Total Pegawai</h6>
                <h1 class="display-4 fw-bold mb-0">{{ $totalPegawai ?? 0 }}</h1>
                <div class="position-absolute" style="right: -20px; bottom: -20px; opacity: 0.1;">
                    <i class="fa-solid fa-users" style="font-size: 6rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Hadir Hari Ini -->
    <div class="col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 20px;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center position-relative overflow-hidden py-4">
                <i class="fa-solid fa-user-check fa-3x mb-3" style="opacity: 0.9;"></i>
                <h6 class="card-title fw-light text-uppercase mb-2" style="letter-spacing: 1px;">Hadir Hari Ini</h6>
                <h1 class="display-4 fw-bold mb-0">{{ $hadirHariIni ?? 0 }}</h1>
                <small class="mt-2 opacity-75">dari {{ $totalPegawai }} pegawai</small>
                <div class="position-absolute" style="right: -20px; bottom: -20px; opacity: 0.1;">
                    <i class="fa-solid fa-calendar-check" style="font-size: 6rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Gaji Bulan Ini -->
    <div class="col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; border-radius: 20px;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center position-relative overflow-hidden py-4">
                <i class="fa-solid fa-money-bill-wave fa-3x mb-3" style="opacity: 0.9;"></i>
                <h6 class="card-title fw-light text-uppercase mb-2" style="letter-spacing: 1px;">Total Gaji Bulan Ini</h6>
                <h3 class="fw-bold mb-0" style="font-size: 1.3rem;">Rp {{ number_format($totalGajiBulanIni ?? 0, 0, ',', '.') }}</h3>
                <div class="position-absolute" style="right: -20px; bottom: -20px; opacity: 0.1;">
                    <i class="fa-solid fa-money-bill-trend-up" style="font-size: 6rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tanggal Merah -->
    <div class="col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; border-radius: 20px;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center position-relative overflow-hidden py-4">
                <i class="fa-solid fa-calendar-xmark fa-3x mb-3" style="opacity: 0.9;"></i>
                <h6 class="card-title fw-light text-uppercase mb-2" style="letter-spacing: 1px;">Libur Bulan Ini</h6>
                <h1 class="display-4 fw-bold mb-0">{{ $jumlahTanggalMerah }}</h1>
                <small class="mt-2 opacity-75">hari libur nasional</small>
                <div class="position-absolute" style="right: -20px; bottom: -20px; opacity: 0.1;">
                    <i class="fa-solid fa-umbrella-beach" style="font-size: 6rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Statistics -->
<div class="row g-3 mb-4 animate__animated animate__fadeInUp animate__delay-1s">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 15px; border-left: 4px solid #10b981;">
            <div class="card-body py-3 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Hadir Bulan Ini</p>
                        <h4 class="mb-0 fw-bold text-success">{{ $hadirBulanIniTotal ?? 0 }}</h4>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-circle p-3">
                        <i class="fas fa-check-circle text-success fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 15px; border-left: 4px solid #f59e0b;">
            <div class="card-body py-3 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Izin Hari Ini</p>
                        <h4 class="mb-0 fw-bold text-warning">{{ $izinHariIni ?? 0 }}</h4>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                        <i class="fas fa-file-medical text-warning fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 15px; border-left: 4px solid #ef4444;">
            <div class="card-body py-3 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Alpha Hari Ini</p>
                        <h4 class="mb-0 fw-bold text-danger">{{ $alphaHariIni ?? 0 }}</h4>
                    </div>
                    <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                        <i class="fas fa-user-times text-danger fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 15px; border-left: 4px solid #6366f1;">
            <div class="card-body py-3 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Terlambat Hari Ini</p>
                        <h4 class="mb-0 fw-bold text-primary">{{ $telatHariIni ?? 0 }}</h4>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                        <i class="fas fa-clock text-primary fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts & Quick Actions -->
<div class="row g-4 mb-4 animate__animated animate__fadeInUp animate__delay-1s">
    <div class="col-lg-8 mb-4 mb-lg-0">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 20px;">
            <div class="card-header bg-white py-4 d-flex align-items-center" style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #f1f5f9;">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-chart-area text-white"></i>
                </div>
                <h5 class="mb-0 text-dark fw-bold">Statistik Kehadiran (7 Hari Terakhir)</h5>
            </div>
            <div class="card-body p-4">
                <div id="attendanceChart"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 20px;">
            <div class="card-header bg-white py-4 d-flex align-items-center" style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #f1f5f9;">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-bolt text-white"></i>
                </div>
                <h5 class="mb-0 text-dark fw-bold">Aksi Cepat</h5>
            </div>
            <div class="card-body p-4">
                <div class="d-flex flex-column gap-3">
                    <a href="{{ url('/absensi') }}" class="btn btn-primary px-4 py-3 rounded-pill shadow-sm d-flex align-items-center hover-lift" style="transition: all 0.3s;">
                        <i class="fa-solid fa-calendar-check me-3 fs-5"></i> 
                        <span>Input Absensi</span>
                    </a>
                    <a href="{{ url('/histori-gaji') }}" class="btn btn-success px-4 py-3 rounded-pill shadow-sm d-flex align-items-center hover-lift text-white" style="transition: all 0.3s;">
                        <i class="fa-solid fa-money-bill-wave me-3 fs-5"></i> 
                        <span>Histori Gaji</span>
                    </a>
                    <a href="{{ url('/pegawai') }}" class="btn btn-warning px-4 py-3 rounded-pill shadow-sm d-flex align-items-center hover-lift text-white" style="transition: all 0.3s;">
                        <i class="fa-solid fa-users me-3 fs-5"></i> 
                        <span>Data Pegawai</span>
                    </a>
                    <a href="{{ url('/setting-libur') }}" class="btn btn-danger px-4 py-3 rounded-pill shadow-sm d-flex align-items-center hover-lift text-white" style="transition: all 0.3s;">
                        <i class="fa-solid fa-calendar-xmark me-3 fs-5"></i> 
                        <span>Setting Libur</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities & Top Earners -->
<div class="row g-4 mb-4 animate__animated animate__fadeInUp animate__delay-2s">
    <!-- Recent Activities -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0" style="border-radius: 20px;">
            <div class="card-header bg-white py-4 d-flex align-items-center" style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #f1f5f9;">
                <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-history text-white"></i>
                </div>
                <h5 class="mb-0 text-dark fw-bold">Aktivitas Terbaru Hari Ini</h5>
            </div>
            <div class="card-body p-0">
                @if($recentActivities && $recentActivities->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentActivities as $activity)
                            <div class="list-group-item border-0 py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($activity->status == 'Hadir')
                                            <div class="bg-success bg-opacity-10 rounded-circle p-2">
                                                <i class="fas fa-check-circle text-success"></i>
                                            </div>
                                        @elseif($activity->status == 'Izin')
                                            <div class="bg-warning bg-opacity-10 rounded-circle p-2">
                                                <i class="fas fa-file-medical text-warning"></i>
                                            </div>
                                        @else
                                            <div class="bg-danger bg-opacity-10 rounded-circle p-2">
                                                <i class="fas fa-user-times text-danger"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-bold">{{ $activity->pegawai->name ?? 'N/A' }}</h6>
                                        <small class="text-muted">
                                            <span class="badge 
                                                @if($activity->status == 'Hadir') bg-success 
                                                @elseif($activity->status == 'Izin') bg-warning 
                                                @else bg-danger 
                                                @endif rounded-pill">{{ $activity->status }}</span>
                                            @if($activity->status == 'Hadir' && \Carbon\Carbon::parse($activity->attendance_time)->format('H:i:s') > '08:00:00')
                                                <span class="badge bg-danger rounded-pill ms-1">Terlambat</span>
                                            @endif
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($activity->attendance_time)->format('H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-5 text-center text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                        <p class="mb-0">Belum ada aktivitas hari ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Top Earners -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0" style="border-radius: 20px;">
            <div class="card-header bg-white py-4 d-flex align-items-center" style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #f1f5f9;">
                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-trophy text-white"></i>
                </div>
                <h5 class="mb-0 text-dark fw-bold">Top 5 Gaji Tertinggi Bulan Ini</h5>
            </div>
            <div class="card-body p-0">
                @if($topEarners && $topEarners->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($topEarners as $index => $earner)
                            <div class="list-group-item border-0 py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($index == 0)
                                            <div class="bg-warning bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-crown text-warning fs-5"></i>
                                            </div>
                                        @else
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <span class="fw-bold text-muted">{{ $index + 1 }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-bold">{{ $earner->pegawai->name ?? 'N/A' }}</h6>
                                        <small class="text-muted">{{ $earner->pegawai->jabatan ?? '-' }}</small>
                                    </div>
                                    <div class="text-end">
                                        <h6 class="mb-0 fw-bold text-success">Rp {{ number_format($earner->total_gaji, 0, ',', '.') }}</h6>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-5 text-center text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                        <p class="mb-0">Belum ada data gaji bulan ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ApexCharts Script -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Real-time Clock
    function updateTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('currentTime').textContent = `${hours}:${minutes}:${seconds}`;
    }
    updateTime();
    setInterval(updateTime, 1000);

    // Attendance Chart
    document.addEventListener("DOMContentLoaded", function() {
        var options = {
            series: [
                {
                    name: 'Hadir',
                    data: {!! json_encode($chartDataHadir ?? [0,0,0,0,0,0,0]) !!}
                },
                {
                    name: 'Izin',
                    data: {!! json_encode($chartDataIzin ?? [0,0,0,0,0,0,0]) !!}
                },
                {
                    name: 'Alpha',
                    data: {!! json_encode($chartDataAlpha ?? [0,0,0,0,0,0,0]) !!}
                }
            ],
            chart: {
                type: 'area',
                height: 350,
                toolbar: { show: false },
                fontFamily: 'Montserrat, sans-serif',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            colors: ['#10b981', '#f59e0b', '#ef4444'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: { 
                curve: 'smooth', 
                width: 3 
            },
            xaxis: {
                categories: {!! json_encode($chartLabels ?? ['','','','','','','']) !!},
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { 
                    style: { 
                        colors: '#64748b',
                        fontSize: '12px',
                        fontWeight: 500
                    } 
                }
            },
            yaxis: {
                min: 0,
                forceNiceScale: true,
                decimalsInFloat: 0,
                labels: { 
                    style: { 
                        colors: '#64748b',
                        fontSize: '12px',
                        fontWeight: 500
                    } 
                }
            },
            tooltip: { 
                theme: 'light',
                y: {
                    formatter: function(value) {
                        return value + ' orang';
                    }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                xaxis: { lines: { show: true } },
                yaxis: { lines: { show: true } }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                fontSize: '13px',
                fontWeight: 600,
                markers: {
                    width: 12,
                    height: 12,
                    radius: 12
                },
                itemMargin: {
                    horizontal: 10
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#attendanceChart"), options);
        chart.render();
    });
</script>

@endsection
