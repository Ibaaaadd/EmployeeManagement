@extends('layoutS.app')

@section('title', 'Pengaturan')

@section('content')
<div class="row">
    <div class="col-12 col-md-10 col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
            <h2 style="border-left: 5px solid var(--accent-color); padding-left: 15px; font-family: 'Montserrat', sans-serif; font-weight: 700; color: var(--primary-color);">
                Pengaturan Profil
            </h2>
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
                <h6 class="mb-0 text-dark fw-bold"><i class="fa-solid fa-user-edit text-primary me-2"></i> Edit Profil & Password</h6>
            </div>
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-12">
                            <label for="name" class="form-label text-muted small fw-bold text-uppercase">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-secondary"></i></span>
                                <input type="text" class="form-control border-start-0 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                            </div>
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label for="email" class="form-label text-muted small fw-bold text-uppercase">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-secondary"></i></span>
                                <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                            </div>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 mt-5">
                            <h6 class="fw-bold mb-3" style="color: var(--primary-color); border-bottom: 1px solid #eee; padding-bottom: 10px;">
                                <i class="fa-solid fa-lock me-2"></i> Ubah Password <span class="text-muted fw-normal" style="font-size: 0.8rem;">(Opsional)</span>
                            </h6>
                        </div>

                        <div class="col-md-12">
                            <label for="current_password" class="form-label text-muted small fw-bold text-uppercase">Password Saat Ini</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-key text-secondary"></i></span>
                                <input type="password" class="form-control border-start-0 @error('current_password') is-invalid @enderror" id="current_password" name="current_password" placeholder="Masukkan jika ingin mengganti password">
                            </div>
                            @error('current_password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label text-muted small fw-bold text-uppercase">Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-unlock-keyhole text-secondary"></i></span>
                                <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" id="password" name="password" placeholder="Minimal 8 karakter">
                            </div>
                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label text-muted small fw-bold text-uppercase">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-check-double text-secondary"></i></span>
                                <input type="password" class="form-control border-start-0" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                            </div>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                <i class="fa-solid fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
