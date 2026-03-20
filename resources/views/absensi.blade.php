@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-11">
        <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
            <h2 style="border-left: 5px solid var(--accent-color); padding-left: 15px; font-family: 'Montserrat', sans-serif; font-weight: 700; color: var(--primary-color);">
                Absensi Pegawai
            </h2>
        </div>

        <div class="row g-4">
            <!-- Input Form Absensi -->
            <div class="col-md-7">
                <div class="card border-0 shadow-sm animate__animated animate__fadeInLeft" style="border-radius: 20px;">
                    <div class="card-header bg-white py-3 px-4" style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #f1f5f9;">
                        <h6 class="mb-0 text-dark fw-bold"><i class="fa-solid fa-clipboard-user text-primary me-2"></i> Form Absensi</h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('absensi.store') }}" method="POST" enctype="multipart/form-data" id="absensiForm">
                            @csrf
                            <div class="mb-3">
                                <label for="employee_id" class="form-label fw-bold text-secondary small text-uppercase">Nama Pegawai</label>
                                <select class="form-select w-100" id="employee_id" name="employee_id" required>
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
            <div class="col-md-5">
                <div class="card border-0 shadow-sm animate__animated animate__fadeInRight animate__delay-1s" style="border-radius: 20px;">
                    <div class="card-header bg-warning bg-opacity-10 py-3 px-4 d-flex align-items-center" style="border-radius: 20px 20px 0 0; border-bottom: 2px solid #fef3c7;">
                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 35px; height: 35px;">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <h6 class="mb-0 text-dark fw-bold">Belum Absen Hari Ini</h6>
                    </div>
                    <div class="card-body p-0">
                        @if($pegawaiBelumAbsen->isEmpty())
                            <div class="p-5 text-center">
                                <i class="fa-solid fa-circle-check text-success mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                                <h6 class="text-muted fw-bold">Semua pegawai sudah absen!</h6>
                                <p class="small text-muted mb-0">Kerja bagus semuanya hadir.</p>
                            </div>
                        @else
                            <div class="list-group list-group-flush" style="max-height: 420px; overflow-y: auto;">
                                @foreach($pegawaiBelumAbsen as $pegawai)
                                    <div class="list-group-item d-flex align-items-center py-3 px-4 border-bottom">
                                        <div class="bg-light text-secondary rounded-circle d-flex justify-content-center align-items-center me-3 shadow-sm" style="width: 35px; height: 35px; font-weight: bold;">
                                            {{ strtoupper(substr($pegawai->name, 0, 1)) }}
                                        </div>
                                        <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $pegawai->name }}</div>
                                        <span class="badge bg-danger ms-auto rounded-pill px-3">Pending</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>

<script>
    function toggleCamera() {
        var status = document.querySelector('input[name="status"]:checked').value;  
        var attendancePhotoDiv = document.getElementById("attendance_photo_div");  
        var webcam = document.getElementById("webcam");  
        var takePhotoBtn = document.getElementById("takePhotoBtn");  
        var photoInput = document.getElementById("attendance_photo");

        if (status === "Hadir") {
            attendancePhotoDiv.style.display = "block"; 
            takePhotoBtn.style.display = "inline-block";
            photoInput.disabled = false;
            startCamera();  
        } else {
            attendancePhotoDiv.style.display = "none";  
            takePhotoBtn.style.display = "none";        
            webcam.style.display = "none";              
            photoInput.disabled = true; 
            photoInput.value = "";
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
@endsection
