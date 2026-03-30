@extends('layouts.app')

@section('title', 'Setting Tanggal Merah')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
            <h2 style="border-left: 5px solid var(--accent-color); padding-left: 15px; font-family: 'Montserrat', sans-serif; font-weight: 700; color: var(--primary-color);">
                Setting Tanggal Merah
            </h2>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Form Input Tanggal Merah -->
        <div class="card border-0 shadow-sm mb-4 animate__animated animate__fadeInUp" style="border-radius: 20px;">
            <div class="card-header bg-white py-4" style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #f1f5f9;">
                <h5 class="mb-0 text-dark fw-bold">
                    <i class="fas fa-plus-circle text-primary me-2"></i> Tambah/Update Tanggal Merah
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('setting-libur.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="bulan" class="form-label fw-semibold">Pilih Bulan <span class="text-danger">*</span></label>
                            <input type="month" class="form-control" id="bulan" name="bulan" 
                                   value="{{ sprintf('%04d-%02d', $currentYear, $currentMonth) }}" required>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Format: Tahun-Bulan (contoh: 2026-03)
                            </small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_merah" class="form-label fw-semibold">Tanggal Merah (Libur Nasional)</label>
                            <input type="text" class="form-control" id="tanggal_merah" name="tanggal_merah" 
                                   placeholder="Contoh: 1,17,25" value="{{ old('tanggal_merah') }}">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Pisahkan dengan koma. Contoh: 1,17,25
                            </small>
                        </div>
                        <div class="col-md-2 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary rounded-pill w-100 shadow-sm">
                                <i class="fas fa-save me-2"></i>Simpan
                            </button>
                        </div>
                    </div>
                </form>

                <div class="alert alert-info border-0 mt-4" style="border-radius: 15px; background-color: #e0f2fe;">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-lightbulb fs-3 text-info"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <strong class="text-info">Informasi Penting:</strong>
                            <ul class="mb-0 mt-2 text-dark">
                                <li>Tanggal merah adalah hari libur nasional yang tetap dihitung sebagai <strong>hari kerja aktif</strong></li>
                                <li>Pegawai <strong>tidak perlu absen</strong> di tanggal merah dan <strong>tidak akan kena potongan</strong></li>
                                <li>Weekend (Sabtu-Minggu) otomatis tidak dihitung sebagai hari kerja</li>
                                <li>Kosongkan field tanggal merah jika tidak ada libur nasional di bulan tersebut</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Tanggal Merah - Modern Cards -->
        <div class="card border-0 shadow-sm animate__animated animate__fadeInUp" style="border-radius: 20px;">
            <div class="card-header bg-white py-4" style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #f1f5f9;">
                <h5 class="mb-0 text-dark fw-bold">
                    <i class="fas fa-list text-secondary me-2"></i> Data Tanggal Merah Tahun {{ $currentYear }}
                </h5>
            </div>
            <div class="card-body p-4">
                @if($holidays->isEmpty())
                    <div class="alert alert-warning text-center rounded-3 border-0 py-4">
                        <i class="fa-solid fa-circle-exclamation fs-1 text-warning mb-3 d-block"></i>
                        <h5 class="text-warning">Belum ada data tanggal merah untuk tahun {{ $currentYear }}</h5>
                        <p class="mb-0 text-muted">Silakan tambahkan tanggal merah menggunakan form di atas</p>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach($holidays as $index => $holiday)
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="card border-0 shadow-sm h-100 hover-lift" style="border-radius: 15px; transition: all 0.3s ease;">
                                    <!-- Card Header with Month -->
                                    <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 15px 15px 0 0; border: none;">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 45px; height: 45px;">
                                                    <i class="fa-regular fa-calendar fs-5"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($holiday->bulan . '-01')->locale('id')->isoFormat('MMMM YYYY') }}</h6>
                                                    <small class="opacity-75">{{ $holiday->bulan }}</small>
                                                </div>
                                            </div>
                                            <span class="badge bg-white text-primary fw-bold px-3 py-2">
                                                #{{ $index + 1 }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Card Body with Holidays -->
                                    <div class="card-body p-4">
                                        <div class="mb-3">
                                            <label class="text-muted small text-uppercase fw-semibold mb-2">
                                                <i class="fas fa-calendar-times me-1"></i> Tanggal Libur Nasional
                                            </label>
                                            @if($holiday->tanggal_merah)
                                                @php
                                                    $dates = explode(',', $holiday->tanggal_merah);
                                                @endphp
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($dates as $date)
                                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-2 fw-semibold">
                                                            <i class="fas fa-calendar-day me-1"></i>{{ trim($date) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                                <div class="mt-3 pt-3 border-top">
                                                    <small class="text-muted">
                                                        <i class="fas fa-check-circle text-success me-1"></i>
                                                        <strong>{{ count($dates) }} hari</strong> libur nasional
                                                    </small>
                                                </div>
                                            @else
                                                <div class="alert alert-light border-0 mb-0 py-3" style="background-color: #f8fafc;">
                                                    <div class="text-center">
                                                        <i class="fas fa-calendar-check fs-4 text-success mb-2 d-block"></i>
                                                        <span class="text-muted small">Tidak ada libur nasional</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Card Footer with Actions -->
                                    <div class="card-footer bg-light border-0 py-3" style="border-radius: 0 0 15px 15px;">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button class="btn btn-sm btn-warning rounded-pill px-4 shadow-sm flex-fill" 
                                                    onclick="editHoliday('{{ $holiday->bulan }}', '{{ $holiday->tanggal_merah }}')"
                                                    style="transition: all 0.3s ease;"
                                                    onmouseover="this.style.transform='translateY(-2px)'"
                                                    onmouseout="this.style.transform='translateY(0)'">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </button>
                                            <form action="{{ route('setting-libur.destroy', $holiday->bulan) }}" method="POST" class="form-delete flex-fill">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger rounded-pill px-4 shadow-sm w-100"
                                                        style="transition: all 0.3s ease;"
                                                        onmouseover="this.style.transform='translateY(-2px)'"
                                                        onmouseout="this.style.transform='translateY(0)'">
                                                    <i class="fas fa-trash me-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function editHoliday(bulan, tanggalMerah) {
        document.getElementById('bulan').value = bulan;
        document.getElementById('tanggal_merah').value = tanggalMerah || '';
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Add highlight effect to form
        const formCard = document.querySelector('.card');
        formCard.classList.add('animate__animated', 'animate__pulse');
        setTimeout(() => {
            formCard.classList.remove('animate__animated', 'animate__pulse');
        }, 1000);
    }
</script>
@endsection
