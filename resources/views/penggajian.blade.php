@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
            <h2 style="border-left: 5px solid var(--accent-color); padding-left: 15px; font-family: 'Montserrat', sans-serif; font-weight: 700; color: var(--primary-color);">
                Proses Penggajian
            </h2>
        </div>

        <div class="card border-0 shadow-sm animate__animated animate__fadeInUp" style="border-radius: 20px;">
            <div class="card-header bg-white py-4 d-flex align-items-center" style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #f1f5f9;">
                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                    <i class="fa-solid fa-calculator text-success fs-5"></i>
                </div>
                <h5 class="mb-0 text-dark fw-bold">Hitung Gaji Pegawai</h5>
            </div>

            <div class="card-body p-4 p-md-5">
                <form action="{{ route('gaji.hitung') }}" method="POST" id="formPenggajian">
                    @csrf

                    <div class="row g-4">
                        <div class="col-md-12">
                            <label for="pegawai" class="form-label fw-bold text-secondary small text-uppercase">Pilih Pegawai</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user-tie text-muted"></i></span>
                                <select id="pegawai" name="pegawai_id" class="form-select border-start-0 ps-0" required>
                                    <option value="" disabled selected>-- Pilih Pegawai --</option>
                                    @foreach($pegawaiList as $pegawaiItem)
                                        <option value="{{ $pegawaiItem->id }}" 
                                                data-gaji="{{ $pegawaiItem->gaji }}" 
                                                {{ isset($pegawaiId) && $pegawaiId == $pegawaiItem->id ? 'selected' : '' }}>
                                            {{ $pegawaiItem->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="gaji_pokok" class="form-label fw-bold text-secondary small text-uppercase">Gaji Pokok</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 fw-bold">Rp</span>
                                <input type="text" id="gaji_pokok" class="form-control border-start-0 ps-0 bg-light" readonly>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="bulan" class="form-label fw-bold text-secondary small text-uppercase">Bulan & Tahun</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-calendar-days text-muted"></i></span>
                                <input type="month" id="bulan" name="bulan" class="form-control border-start-0 ps-0 @error('bulan') is-invalid @enderror" value="{{ old('bulan') }}" required>
                            </div>
                            @error('bulan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="periode" class="form-label fw-bold text-secondary small text-uppercase">Periode Gaji</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-clock text-muted"></i></span>
                                <select id="periode" name="periode" class="form-select border-start-0 ps-0 @error('periode') is-invalid @enderror" required>
                                    <option value="1" {{ old('periode') == '1' ? 'selected' : '' }}>Periode 1 (Tgl 1 - 15)</option>
                                    <option value="2" {{ old('periode') == '2' ? 'selected' : '' }}>Periode 2 (Tgl 16 - Akhir Bulan)</option>
                                </select>
                            </div>
                            @error('periode')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-5 text-end">
                        <button type="submit" class="btn btn-success rounded-pill px-5 py-2 shadow-sm d-inline-flex align-items-center">
                            <i class="fa-solid fa-money-check-dollar me-2"></i> Hitung Gaji
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Note: Output / Hasil Perhitungan Biasanya di-render di bawah sini atau via modal / halaman baru (tergantung implementasi backend) --}}
        @if(isset($hasilHitung) || session('hasil'))
             <div class="alert alert-info mt-4 animate__animated animate__fadeInUp">Sistem telah memproses gaji. Silahkan cek Histori Gaji.</div>
        @endif
    </div>
</div>

<style>
.form-control:focus, .form-select:focus {
    box-shadow: none;
    border-color: #dee2e6;
}
.input-group:focus-within .input-group-text, .input-group:focus-within .form-control, .input-group:focus-within .form-select {
    border-color: var(--success-color);
}
.input-group .form-control, .input-group .form-select, .input-group .input-group-text {
    border-radius: 12px;
    padding: 12px 15px;
}
.input-group-text {
    background-color: #f8fafc;
}
input[readonly] { cursor: not-allowed; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pegawaiSelect = document.getElementById('pegawai');
    const gajiPokokInput = document.getElementById('gaji_pokok');

    function formatRupiahJs(angka, prefix){
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split   = number_string.split(','),
        sisa     = split[0].length % 3,
        rupiah     = split[0].substr(0, sisa),
        ribuan     = split[0].substr(sisa).match(/\d{3}/gi);

        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
    }

    pegawaiSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const gaji = selectedOption.getAttribute('data-gaji');

        if(gaji) {
            // Remove existing dots before re-formatting (just in case)
            let cleanGaji = gaji.replace(/\./g, '');
            gajiPokokInput.value = formatRupiahJs(cleanGaji, '');
        } else {
            gajiPokokInput.value = '';
        }
    });

    // Trigger change on load if an option is already selected
    if(pegawaiSelect.value) {
        pegawaiSelect.dispatchEvent(new Event('change'));
    }

    document.getElementById('formPenggajian').addEventListener('submit', function(e) {
        if(this.checkValidity()) {
            Swal.fire({
                title: 'Menghitung Penggajian...',
                html: 'Tunggu sebentar, sedang memproses data kehadiran',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
    });
});
</script>
@endsection

