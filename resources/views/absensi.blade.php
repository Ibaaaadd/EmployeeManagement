@extends('layouts.app')

@section('content')
        <div class="container mt-3">
        <h2 style="border-bottom: 2px solid #198754; padding-bottom: 10px; display: inline-block; font-family: 'Montserrat', sans-serif; font-weight: 600;">
            ABSENSI PEGAWAI
        </h2>

        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('absensi.store') }}" method="POST" enctype="multipart/form-data" id="absensiForm">
                    @csrf
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Nama Pegawai</label>
                        <select class="form-select" id="employee_id" name="employee_id">
                            <option selected>Pilih Pegawai</option>
                            @foreach($pegawaiList as $pegawai)
                                <option value="{{ $pegawai->id }}">{{ $pegawai->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status Kehadiran</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="hadir" name="status" value="Hadir" onclick="toggleCamera()">
                            <label class="form-check-label" for="hadir">Hadir</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="izin" name="status" value="Izin" onclick="toggleCamera()">
                            <label class="form-check-label" for="izin">Izin</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="tidak_hadir" name="status" value="Tidak Hadir" onclick="toggleCamera()">
                            <label class="form-check-label" for="tidak_hadir">Tidak Hadir</label>
                        </div>
                    </div>

                    <div class="mb-3" id="attendance_photo_div" style="display: none;">
                        <label for="attendance_photo" class="form-label">Foto Absensi</label>
                        <video id="webcam" width="320" height="240" autoplay style="display:none;"></video>
                        <canvas id="canvas" style="display:none;"></canvas>
                        <br>
                        <button type="button" class="btn btn-primary" id="takePhotoBtn">Ambil Foto</button>
                        <br><br>
                        <img id="photoPreview" src="" style="display:none; width: 320px; height: 240px; border: 1px solid #ddd;">
                        <input type="hidden" name="attendance_photo" id="attendance_photo">
                    </div>

                    <input type="hidden" id="attendance_time" name="attendance_time">

                    <button type="submit" class="btn btn-success shadow-sm rounded-pill px-4 py-2" id="absenButton" style="background-color: #28a745; border: none; font-weight: 600; transition: 0.3s;">
                        <i class="fa fa-check-circle me-2"></i> Absen
                    </button>
                </form>
            </div>

            <!-- Tabel Pegawai Belum Absen -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-dark fw-bold">
                        Belum Absen Hari Ini
                    </div>
                    <div class="card-body p-2">
                        @if($pegawaiBelumAbsen->isEmpty())
                            <p class="text-muted">Semua pegawai sudah absen hari ini.</p>
                        @else
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pegawaiBelumAbsen as $pegawai)
                                        <tr>
                                            <td>{{ $pegawai->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
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

            if (status === "Hadir") {
                attendancePhotoDiv.style.display = "block"; 
                takePhotoBtn.style.display = "block";        
                startCamera();  
            } else {
                attendancePhotoDiv.style.display = "none";  
                takePhotoBtn.style.display = "none";        
                webcam.style.display = "none";              
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
            canvas.width = webcam.videoWidth;
            canvas.height = webcam.videoHeight;
            context.drawImage(webcam, 0, 0, canvas.width, canvas.height);

        
            var photo = canvas.toDataURL('image/png');
            document.getElementById('attendance_photo').value = photo;

            
            var photoPreview = document.getElementById('photoPreview');
            photoPreview.src = photo;
            photoPreview.style.display = 'block';  
        }

        
        document.getElementById("takePhotoBtn").addEventListener("click", function() {
            takePhoto();
        });

        document.getElementById("absensiForm").addEventListener("submit", function(event) {
            var employeeId = document.getElementById("employee_id").value;  
            var status = document.querySelector('input[name="status"]:checked');  
            var attendanceTime = document.getElementById("attendance_time").value;  

            if (employeeId === "" || !status || attendanceTime === "") {
                event.preventDefault();  
                alert("Semua field wajib diisi: Nama Pegawai, Status Kehadiran, dan Waktu Absensi.");
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const attendanceInput = document.getElementById('attendance_time');
            const absenButton = document.getElementById('absenButton');

            absenButton.addEventListener('click', function () {
                const now = new Date();

                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');

                const formatted = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

                attendanceInput.value = formatted;
            });
        });

    </script>
    <style>
    .btn-success:hover {
    background-color: #218838;
    transform: scale(1.02);
    }
    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }
    .form-check-input:hover {
        cursor: pointer;
    }
    .form-check-label {
        margin-right: 12px;
    }
</style>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
@endsection
