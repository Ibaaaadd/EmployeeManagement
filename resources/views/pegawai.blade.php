@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
            <h2 style="border-left: 5px solid var(--accent-color); padding-left: 15px; font-family: 'Montserrat', sans-serif; font-weight: 700; color: var(--primary-color);">
                Tambah Pegawai Baru
            </h2>
            <a href="{{ url('/daftar-pegawai') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm fw-bold">
                <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Daftar
            </a>
        </div>

        @if(session('success'))
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                });
            </script>
        @endif

        <div class="card border-0 shadow-sm animate__animated animate__fadeInUp" style="border-radius: 20px;">
            <div class="card-header bg-white py-3 px-4" style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #f1f5f9;">
                <h6 class="mb-0 text-dark fw-bold"><i class="fa-solid fa-address-card text-primary me-2"></i> Form Input Data Pegawai</h6>
            </div>
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('pegawai.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label text-muted small fw-bold text-uppercase">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-secondary"></i></span>
                                <input type="text" class="form-control border-start-0 @error('name') is-invalid @enderror" id="name" name="name" placeholder="Masukkan nama lengkap pegawai" value="{{ old('name') }}" required>
                            </div>
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label text-muted small fw-bold text-uppercase">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-secondary"></i></span>
                                <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" id="email" name="email" placeholder="example@email.com" value="{{ old('email') }}" required>
                            </div>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label for="alamat" class="form-label text-muted small fw-bold text-uppercase">Alamat Tempat Tinggal</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-location-dot text-secondary"></i></span>
                                <input type="text" class="form-control border-start-0 @error('alamat') is-invalid @enderror" id="alamat" name="alamat" placeholder="Masukkan alamat lengkap rumah" value="{{ old('alamat') }}" required>
                            </div>
                            @error('alamat')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label for="nomor" class="form-label text-muted small fw-bold text-uppercase">Nomor Telepon/WA</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-phone text-secondary"></i></span>
                                <input type="text" class="form-control border-start-0 @error('nomor') is-invalid @enderror" id="nomor" name="nomor" placeholder="08xxxxxxxxxx" value="{{ old('nomor') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                            </div>
                            @error('nomor')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label for="jabatan" class="form-label text-muted small fw-bold text-uppercase">Jabatan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-briefcase text-secondary"></i></span>
                                <select class="form-select border-start-0 @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" required>
                                    <option value="" disabled selected>Pilih jabatan</option>
                                    <option value="Manager" {{ old('jabatan') == 'Manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="Accounting" {{ old('jabatan') == 'Accounting' ? 'selected' : '' }}>Accounting</option>
                                    <option value="Sales / Marketing" {{ old('jabatan') == 'Sales / Marketing' ? 'selected' : '' }}>Sales / Marketing</option>
                                    <option value="Teknisi" {{ old('jabatan') == 'Teknisi' ? 'selected' : '' }}>Teknisi</option>
                                    <option value="Operasional" {{ old('jabatan') == 'Operasional' ? 'selected' : '' }}>Operasional</option>
                                </select>
                            </div>
                            @error('jabatan')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label for="gaji" class="form-label text-muted small fw-bold text-uppercase">Gaji Pokok</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 fw-bold text-secondary">Rp</span>
                                <input type="text" class="form-control border-start-0 @error('gaji') is-invalid @enderror" id="gaji" name="gaji" placeholder="0" value="{{ old('gaji') }}" oninput="formatRupiah(this)" required>
                            </div>
                            @error('gaji')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mt-4">
                            <label for="password" class="form-label text-muted small fw-bold text-uppercase">Password Akun (Bisa buat Login)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-secondary"></i></span>
                                <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" id="password" name="password" placeholder="Masukkan password kuat" required>
                            </div>
                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-5 text-end border-top pt-4">
                        <button type="reset" class="btn btn-light rounded-pill px-4 fw-bold text-secondary me-2 hover-scale">
                            <i class="fa-solid fa-rotate-left me-2"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 shadow-sm d-inline-flex align-items-center hover-scale fw-bold">
                            <i class="fa-solid fa-paper-plane me-2"></i> Terapkan & Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function formatRupiah(el) {
    let value = el.value.replace(/\./g, '').replace(/[^\d]/g, '');
    if (!value) {
        el.value = '';
        return;
    }
    el.value = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}
</script>

<style>
.form-control, .form-select {
    border-radius: 10px;
    padding: 12px 15px;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    transition: all 0.3s;
}
.form-control:focus, .form-select:focus {
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    border-color: var(--primary-color) !important;
    background-color: #ffffff;
}
.input-group .form-control, .input-group .form-select {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
.input-group-text {
    border-radius: 10px;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border: 1px solid #e2e8f0;
    background-color: #f8fafc;
}
.input-group:focus-within .input-group-text, 
.input-group:focus-within .form-control,
.input-group:focus-within .form-select {
    border-color: var(--primary-color) !important;
    background-color: #ffffff;
}
.input-group:focus-within .input-group-text i {
    color: var(--primary-color) !important;
}
.hover-scale { transition: transform 0.2s ease; }
.hover-scale:hover { transform: scale(1.03); }
</style>
@endsection

