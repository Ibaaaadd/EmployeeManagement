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

        <!-- Tabel Data Tanggal Merah -->
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
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%" class="ps-4">No</th>
                                    <th width="20%">Bulan</th>
                                    <th width="50%">Tanggal Merah</th>
                                    <th width="25%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($holidays as $index => $holiday)
                                    <tr>
                                        <td class="ps-4 fw-bold">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fa-regular fa-calendar text-info"></i>
                                                </div>
                                                <div>
                                                    <strong class="text-dark">{{ \Carbon\Carbon::parse($holiday->bulan . '-01')->locale('id')->isoFormat('MMMM YYYY') }}</strong>
                                                    <div class="text-muted small">{{ $holiday->bulan }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($holiday->tanggal_merah)
                                                @php
                                                    $dates = explode(',', $holiday->tanggal_merah);
                                                @endphp
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($dates as $date)
                                                        <span class="badge bg-danger rounded-pill px-3 py-2">
                                                            <i class="fas fa-calendar-times me-1"></i>{{ trim($date) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted fst-italic">
                                                    <i class="fas fa-info-circle me-1"></i>Tidak ada libur
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button class="btn btn-sm btn-warning rounded-pill px-3 shadow-sm" 
                                                        onclick="editHoliday('{{ $holiday->bulan }}', '{{ $holiday->tanggal_merah }}')"
                                                        title="Edit">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </button>
                                                <form action="{{ route('setting-libur.destroy', $holiday->bulan) }}" method="POST" class="d-inline form-delete">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm" title="Hapus">
                                                        <i class="fas fa-trash me-1"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
