@extends('layouts.app')

@section('title', 'Riwayat Penggajian')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
                <h2
                    style="border-left: 5px solid var(--accent-color); padding-left: 15px; font-family: 'Montserrat', sans-serif; font-weight: 700; color: var(--primary-color);">
                    Riwayat Penggajian
                </h2>
            </div>

            <div class="card border-0 shadow-sm animate__animated animate__fadeInUp mb-4" style="border-radius: 20px;">
                <div class="card-header bg-white py-3 d-flex align-items-center"
                    style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #f1f5f9;">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-filter text-primary fs-6"></i>
                    </div>
                    <h6 class="mb-0 text-dark fw-bold">Filter Data</h6>
                </div>
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('gaji.riwayat') }}" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Nama Pegawai</label>
                            <select name="nama" class="form-select select-search">
                                <option value="">-- Semua Pegawai --</option>
                                @foreach ($pegawaiList as $p)
                                    <option value="{{ $p->name }}" {{ request('nama') == $p->name ? 'selected' : '' }}>
                                        {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Bulan & Tahun</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i
                                        class="fa-solid fa-calendar-days text-muted"></i></span>
                                <input type="month" name="bulan"
                                    class="form-control border-start-0 ps-0 datepicker-month"
                                    value="{{ request('bulan') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label d-none d-md-block">&nbsp;</label>
                            <button type="submit"
                                class="btn btn-primary w-100 rounded-pill shadow-sm d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-magnifying-glass me-2"></i> Tampilkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm animate__animated animate__fadeInUp mb-4" style="border-radius: 20px;">
                <div class="card-body p-4">

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4"
                            role="alert">
                            <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($riwayat->isEmpty())
                        <div class="alert alert-warning text-center rounded-3 border-0">
                            <i class="fa-solid fa-circle-exclamation me-2"></i> Data riwayat penggajian yang Anda input
                            tidak tersedia.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Nama Pegawai</th>
                                        <th>Periode Gaji</th>
                                        <th>Slip Gaji</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($riwayat as $item)
                                        <tr>
                                            <td class="fw-medium text-dark">{{ $item->pegawai->name }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-info text-dark bg-opacity-10 py-2 px-3 rounded-pill border border-info border-opacity-25">
                                                    <i class="fa-regular fa-calendar me-1"></i>
                                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('F Y') }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('riwayat.preview', $item->id) }}"
                                                        class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                                        target="_blank">
                                                        <i class="fa-solid fa-eye me-1"></i>
                                                    </a>
                                                    <a href="{{ route('riwayat.download', $item->id) }}"
                                                        class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm">
                                                        <i class="fa-solid fa-file-pdf me-1"></i>
                                                    </a>
                                                    <form action="{{ route('riwayat.destroy', $item->id) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data riwayat gaji ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm">
                                                            <i class="fa-solid fa-trash me-1"></i>
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
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dropdown dengan fitur search menggunakan Tom Select
            document.querySelectorAll('.select-search').forEach((el) => {
                new TomSelect(el, {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    dropdownParent: 'body'
                });
            });
        });
    </script>
    <style>
        .ts-control {
            border-radius: 10px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            box-shadow: none;
        }

        .ts-control.focus {
            background-color: #fff;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }
    </style>
@endsection
