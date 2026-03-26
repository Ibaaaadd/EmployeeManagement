@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
            <h2 style="border-left: 5px solid var(--accent-color); padding-left: 15px; font-family: 'Montserrat', sans-serif; font-weight: 700; color: var(--primary-color);">
                Absensi Pegawai
            </h2>
        </div>

        <div class="row g-4">
            <!-- Input Form Absensi -->
            <div class="col-md-12">
                <div class="card border-0 shadow-sm animate__animated animate__fadeInLeft" style="border-radius: 20px;">
                    <div class="card-header bg-white py-3 px-4" style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #f1f5f9;">
                        <h6 class="mb-0 text-dark fw-bold"><i class="fa-solid fa-clipboard-user text-primary me-2"></i> Form Absensi</h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('absensi.store') }}" method="POST" enctype="multipart/form-data" id="absensiForm">
                            @csrf
                            <div class="mb-3">
                                <label for="employee_id" class="form-label fw-bold text-secondary small text-uppercase">Nama Pegawai</label>
                                <select class="form-select w-100 select-search" id="employee_id" name="employee_id" required>
                                    <option value="" disabled selected>-- Pilih Pegawai --</option>
                                    @foreach($pegawaiList as $pegawai)
                                        <option value="{{ $pegawai->id }}">{{ $pegawai->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary small text-uppercase d-block mb-2">Status Kehadiran</label>
                                <div class="d-flex gap-2 flex-wrap">
                                    <input type="radio" class="btn-check" name="status" id="hadir" value="Hadir" autocomplete="off" onclick="toggleCamera()" required>
                                    <label class="btn btn-outline-success rounded-pill px-4 py-2 fw-bold custom-btn-check" for="hadir"><i class="fa-solid fa-check-circle me-1"></i> Hadir</label>

                                    <input type="radio" class="btn-check" name="status" id="izin" value="Izin" autocomplete="off" onclick="toggleCamera()">
                                    <label class="btn btn-outline-warning rounded-pill px-4 py-2 fw-bold custom-btn-check" for="izin"><i class="fa-solid fa-envelope-open-text me-1"></i> Izin</label>

                                    <input type="radio" class="btn-check" name="status" id="tidak_hadir" value="Tidak Hadir" autocomplete="off" onclick="toggleCamera()">
                                    <label class="btn btn-outline-danger rounded-pill px-4 py-2 fw-bold custom-btn-check" for="tidak_hadir"><i class="fa-solid fa-circle-xmark me-1"></i> Tidak Hadir</label>
                                </div>
                            </div>

                            <div class="mb-3" id="tipe_absen_div" style="display: none;">
                                <label class="form-label fw-bold text-secondary small text-uppercase d-block mb-2">Tipe Absen</label>
                                <div class="d-flex gap-2 flex-wrap">
                                    <input type="radio" class="btn-check" name="tipe_absen" id="masuk" value="masuk" autocomplete="off">
                                    <label class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold custom-btn-check" for="masuk"><i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Masuk (Max 08:00 WIB)</label>
                                    
                                    <input type="radio" class="btn-check" name="tipe_absen" id="pulang" value="pulang" autocomplete="off">
                                    <label class="btn btn-outline-info rounded-pill px-4 py-2 fw-bold custom-btn-check" for="pulang"><i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Pulang</label>
                                </div>
                            </div>

                            <div class="mb-3 text-center p-3 bg-light rounded-3" id="attendance_photo_div" style="display: none; border: 2px dashed #cbd5e1;">
                                <label class="form-label fw-bold text-secondary small text-uppercase mb-2">Ambil Foto Kehadiran</label>
                                <div class="webcam-container mx-auto mb-2" style="width: 320px; height: 240px; overflow: hidden; border-radius: 12px; background: #000; position: relative;">
                                    <video id="webcam" width="320" height="240" autoplay style="object-fit: cover;"></video>
                                </div>
                                <canvas id="canvas" style="display:none;"></canvas>
                                
                                <button type="button" class="btn btn-primary rounded-pill px-4 mb-2" id="takePhotoBtn">
                                    <i class="fa-solid fa-camera me-2"></i> Ambil Foto
                                </button>
                                
                                <div class="mt-2">
                                    <img id="photoPreview" src="" class="rounded-3 shadow-sm mx-auto" style="display:none; width: 320px; height: 240px; border: 3px solid #fff; object-fit: cover;">
                                </div>
                                <input type="hidden" name="attendance_photo" id="attendance_photo" required disabled>
                            </div>

                            <input type="hidden" id="attendance_time" name="attendance_time">

                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-success fw-bold text-white rounded-pill px-5 py-2 shadow-sm d-inline-flex align-items-center" id="absenButton" style="background-color: var(--success-color);">
                                    <i class="fa-solid fa-paper-plane me-2"></i> Kirim Absensi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Tabel Pegawai Belum Absen -->
            <div class="col-md-12 mt-4">
                @component('components.datatable', [
                    'id' => 'tabelBelumAbsen',
                    'title' => 'Daftar Pegawai Belum Absen Hari Ini',
                    'icon' => 'fa-solid fa-clock-rotate-left',
                    'empty' => $pegawaiBelumAbsen->isEmpty()
                ])
                    @slot('head')
                        <th class="ps-4">Profil</th>
                        <th>Nama Pegawai</th>
                        <th>Jabatan</th>
                        <th class="text-center">Status</th>
                    @endslot

                    @slot('body')
                        @foreach($pegawaiBelumAbsen as $pegawai)
                        <tr>
                            <td class="ps-4">
                                <div class="bg-light text-secondary rounded-circle d-flex justify-content-center align-items-center shadow-sm" style="width: 40px; height: 40px; font-weight: bold;">
                                    {{ strtoupper(substr($pegawai->name, 0, 1)) }}
                                </div>
                            </td>
                            <td class="fw-bold text-dark">{{ $pegawai->name }}</td>
                            <td class="text-muted">{{ $pegawai->jabatan ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-2">
                                    <i class="fa-solid fa-hourglass-half me-1"></i> Pending
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    @endslot

                    @if(!$pegawaiBelumAbsen->isEmpty())
                        @slot('pagination')
                            {{ $pegawaiBelumAbsen->links() }}
                        @endslot
                    @endif
                @endcomponent
            </div>
        </div> 
    </div>
</div>

<script>
    function toggleCamera() {
        var status = document.querySelector('input[name="status"]:checked').value;  
        var attendancePhotoDiv = document.getElementById("attendance_photo_div");  
        var tipeAbsenDiv = document.getElementById("tipe_absen_div");
        var webcam = document.getElementById("webcam");  
        var takePhotoBtn = document.getElementById("takePhotoBtn");  
        var photoInput = document.getElementById("attendance_photo");
        var tipeAbsenInputs = document.querySelectorAll('input[name="tipe_absen"]');

        if (status === "Hadir") {
            attendancePhotoDiv.style.display = "block"; 
            tipeAbsenDiv.style.display = "block";
            takePhotoBtn.style.display = "inline-block";
            photoInput.disabled = false;
            tipeAbsenInputs.forEach(input => input.required = true);
            startCamera();  
        } else {
            attendancePhotoDiv.style.display = "none";  
            tipeAbsenDiv.style.display = "none";
            takePhotoBtn.style.display = "none";        
            webcam.style.display = "none";              
            photoInput.disabled = true; 
            photoInput.value = "";
            tipeAbsenInputs.forEach(input => {
                input.required = false;
                input.checked = false;
            });
            stopCamera(); 
        }
    }

    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })  
            .then(function(stream) {
                var webcam = document.getElementById('webcam');
                webcam.srcObject = stream;  
                webcam.style.display = 'block';  
                window.stream = stream;  
            })
            .catch(function(err) {
                Swal.fire('Oops...', 'Tidak dapat mengakses kamera! Pastikan izin kamera diaktifkan.', 'error');
                console.log("Error accessing webcam: " + err);  
            });
    }

    function stopCamera() {
        var webcam = document.getElementById('webcam');
        if (window.stream) {
            window.stream.getTracks().forEach(track => track.stop());  
            webcam.style.display = 'none';  
        }
    }

    function takePhoto() {
        var canvas = document.getElementById('canvas');
        var webcam = document.getElementById('webcam');
        var context = canvas.getContext('2d');
        canvas.width = webcam.videoWidth || 320;
        canvas.height = webcam.videoHeight || 240;
        context.drawImage(webcam, 0, 0, canvas.width, canvas.height);

        var photo = canvas.toDataURL('image/png');
        document.getElementById('attendance_photo').value = photo;
        
        var photoPreview = document.getElementById('photoPreview');
        photoPreview.src = photo;
        photoPreview.style.display = 'block';
        
        webcam.style.display = 'none';
        takePhotoBtn.innerHTML = '<i class="fa-solid fa-camera-rotate me-2"></i> Foto Ulang';
        
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Foto berhasil diambil!',
            showConfirmButton: false,
            timer: 2000
        });
    }

    document.getElementById("takePhotoBtn").addEventListener("click", function() {
        var webcam = document.getElementById('webcam');
        if (webcam.style.display === 'none') {
            // User click "Foto Ulang"
             webcam.style.display = 'block';
             document.getElementById('photoPreview').style.display = 'none';
             this.innerHTML = '<i class="fa-solid fa-camera me-2"></i> Ambil Foto';
             document.getElementById('attendance_photo').value = '';
        } else {
             takePhoto();
        }
    });

    document.getElementById("absensiForm").addEventListener("submit", function(event) {
        var employeeId = document.getElementById("employee_id").value;  
        var status = document.querySelector('input[name="status"]:checked');  
        var photo = document.getElementById('attendance_photo').value;
        var now = new Date();

        var year = now.getFullYear();
        var month = String(now.getMonth() + 1).padStart(2, '0');
        var day = String(now.getDate()).padStart(2, '0');
        var hours = String(now.getHours()).padStart(2, '0');
        var minutes = String(now.getMinutes()).padStart(2, '0');
        var seconds = String(now.getSeconds()).padStart(2, '0');

        document.getElementById('attendance_time').value = year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;

        if (!employeeId || !status) {
            event.preventDefault();  
            Swal.fire('Peringatan', 'Silakan pilih Nama Pegawai dan Status Kehadiran!', 'warning');
            return;
        }
        
        if (status.value === 'Hadir' && !photo) {
            event.preventDefault();
            Swal.fire('Peringatan', 'Foto kehadiran wajib diambil untuk status Hadir!', 'warning');
            return;
        }

        Swal.fire({
            title: 'Memproses Absensi...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    });
</script>
<style>
/* Override default bootstrap outline button issues to strictly enforce colors */
.btn-outline-success.custom-btn-check {
    color: var(--success-color);
    border-color: var(--success-color);
}
.btn-outline-success.custom-btn-check:hover, .btn-check:checked + .btn-outline-success.custom-btn-check {
    background-color: var(--success-color) !important;
    color: #ffffff !important;
    border-color: var(--success-color) !important;
}

.btn-outline-warning.custom-btn-check {
    color: var(--accent-color);
    border-color: var(--accent-color);
}
.btn-outline-warning.custom-btn-check:hover, .btn-check:checked + .btn-outline-warning.custom-btn-check {
    background-color: var(--accent-color) !important;
    color: #ffffff !important;
    border-color: var(--accent-color) !important;
}

.btn-outline-danger.custom-btn-check {
    color: var(--danger-color);
    border-color: var(--danger-color);
}
.btn-outline-danger.custom-btn-check:hover, .btn-check:checked + .btn-outline-danger.custom-btn-check {
    background-color: var(--danger-color) !important;
    color: #ffffff !important;
    border-color: var(--danger-color) !important;
}

.custom-btn-check {
    transition: all 0.2s ease;
}
.btn-check:checked + .custom-btn-check {
    transform: scale(1.05);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.list-group-item { transition: background-color 0.2s; }
.list-group-item:hover { background-color: #f8fafc; }
</style>

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if(document.querySelector('.select-search')) {
            new TomSelect('.select-search', {
                create: false,
                sortField: { field: "text", direction: "asc" }
            });
        }
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
@endsection

