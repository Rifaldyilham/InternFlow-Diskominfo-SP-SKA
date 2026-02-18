@extends('layouts.peserta')

@section('title', 'Absensi Magang')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/peserta/peserta.css') }}">
@endsection

@section('content')
<div class="form-card">
    <!-- Alert Notification -->
    <div id="absensiAlert" class="@if(isset($infoMessage)) absensi-info-box success @else hidden @endif">
        @if(isset($infoMessage))
            <div class="flex items-center gap-3">
                <i class='bx bx-check-circle text-success text-xl'></i>
                <div>
                    <div class="font-semibold">
                        @if(!empty($finishedMagang))
                            Magang Telah Selesai
                        @else
                            Absensi Terkirim
                        @endif
                    </div>
                    <div>{{ $infoMessage }}</div>
                </div>
            </div>
        @endif
    </div>

    @if(!empty($notStarted))
    <div class="info-alert">
        <i class='bx bx-info-circle'></i>
        <div>
            <strong>Magang Belum Dimulai</strong>
            <p>Absensi baru bisa diisi setelah tanggal mulai magang.</p>
        </div>
    </div>
    @endif
    
<!-- Statistik Absensi -->
<div class="absensi-stats-grid">
    <div class="absensi-stat-card border-primary">
        <div class="absensi-stat-icon primary">
            <i class='bx bx-calendar'></i>
        </div>
        <div class="absensi-stat-value">{{ $total }}</div>
        <div class="absensi-stat-label">Total Hari</div>
    </div>
    <div class="absensi-stat-card border-success">
        <div class="absensi-stat-icon success">
            <i class='bx bx-check-circle'></i>
        </div>
        <div class="absensi-stat-value">{{ $hadir }}</div>
        <div class="absensi-stat-label">Hadir</div>
    </div>
    <div class="absensi-stat-card border-warning">
        <div class="absensi-stat-icon warning">
            <i class='bx bx-alarm-exclamation'></i>
        </div>
        <div class="absensi-stat-value">{{ $izin }}</div>
        <div class="absensi-stat-label">Izin</div>
    </div>
    <div class="absensi-stat-card border-danger">
        <div class="absensi-stat-icon danger">
            <i class='bx bx-x-circle'></i>
        </div>
        <div class="absensi-stat-value">{{ $alpha }}</div>
        <div class="absensi-stat-label">Alpha</div>
    </div>
    <div class="absensi-stat-card border-info">
        <div class="absensi-stat-icon info">
            <i class='bx bx-plus-medical'></i>
        </div>
        <div class="absensi-stat-value">{{ $sakit ?? 0 }}</div>
        <div class="absensi-stat-label">Sakit</div>
    </div>
</div>
    
    <!-- Form Absensi Hari Ini -->
    @if(!isset($infoMessage) && empty($notStarted))
    <div id="absensiForm" class="absensi-form-container">
        <h3 class="section-title">
            <i class='bx bx-edit'></i> Absensi Hari Ini
        </h3>
        
        <div class="p-6 bg-white rounded-xl shadow-sm mb-6">
            <!-- Date and Time Display -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                <div>
                    <div class="font-semibold text-lg text-primary" id="currentDate">Sabtu, 16 Maret 2024</div>
                    <div class="text-sm text-gray-600">Tanggal absensi hari ini</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-primary" id="clock">00:00:00</div>
                    <div class="text-sm text-gray-600">Waktu saat ini (WIB)</div>
                </div>
            </div>
            
            <!-- Status Absensi -->
            <div id="absensiStatus" class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-4">Status Kehadiran *</label>
                <div class="status-btn-group">
                    <button type="button" onclick="setAbsensiStatus('hadir')" id="btnHadir" class="status-btn hadir">
                        <i class='bx bx-check-circle'></i> Hadir
                    </button>
                    <button type="button" onclick="setAbsensiStatus('izin')" id="btnIzin" class="status-btn izin">
                        <i class='bx bx-alarm-exclamation'></i> Izin
                    </button>
                    <button type="button" onclick="setAbsensiStatus('sakit')" id="btnSakit" class="status-btn sakit">
                        <i class='bx bx-plus-medical'></i> Sakit
                    </button>
                </div>
                
                <div class="text-center mt-4">
                    <div id="statusText" class="text-lg font-semibold text-primary">Silakan pilih status absensi</div>
                </div>
            </div>
            
            <!-- GPS Location -->
            <div id="gpsSection" class="hidden">
                <div style="background: #e6f2ff; padding: 20px; border-radius: 12px; margin-bottom: 20px; border-left: 4px solid #3498db;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <i class='bx bx-map' style="font-size: 2rem; color: #3498db;"></i>
                        <div>
                            <div style="font-weight: 600; color: var(--primary);">Lokasi Anda</div>
                            <div id="locationText" style="color: #666;">Mendeteksi lokasi...</div>
                        </div>
                        <button onclick="getLocation()" style="margin-left: auto; background: #3498db; color: white; border: none; padding: 8px 15px; border-radius: 8px; font-size: 0.9rem; cursor: pointer;">
                            <i class='bx bx-refresh'></i> Refresh
                        </button>
                    </div>
                    
                    <div class="flex items-center gap-4 mb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-600">Lokasi Kantor</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <span class="text-sm text-gray-600">Lokasi Anda</span>
                        </div>
                    </div>
                    
                    <!-- Map Container -->
                    <div class="map-visual">
                        <!-- Office Marker -->
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                            <div style="width: 50px; height: 50px; background: #2ecc71; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; box-shadow: 0 5px 15px rgba(46, 204, 113, 0.4);">
                                <i class='bx bx-building'></i>
                            </div>
                            <div style="position: absolute; top: 55px; left: 50%; transform: translateX(-50%); white-space: nowrap; background: white; padding: 5px 10px; border-radius: 6px; font-size: 0.8rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                Diskominfo SP
                            </div>
                        </div>
                        
                        <!-- User Marker -->
                        <div id="userMarker" style="position: absolute; top: 30%; left: 60%; display: none;">
                            <div style="width: 40px; height: 40px; background: #3498db; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem; box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);">
                                <i class='bx bx-user'></i>
                            </div>
                            <div style="position: absolute; top: 45px; left: 50%; transform: translateX(-50%); white-space: nowrap; background: white; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                Anda
                            </div>
                        </div>
                    </div>
                    
                    <!-- Distance Info -->
                    <div id="distanceInfo" class="mt-4 p-3 bg-white rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-700">Jarak dari Kantor:</span>
                            <span id="distanceValue" class="font-bold text-primary">-</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Upload Bukti -->
            <div id="uploadSection" class="hidden">
                <div class="mb-6">
                    <label id="uploadLabel" class="block text-sm font-medium text-gray-700 mb-3">Upload Bukti *</label>
                    <div class="upload-area-custom" onclick="document.getElementById('buktiFile').click()" id="uploadZone">
                        <div class="upload-icon-custom">
                            <i class='bx bx-cloud-upload'></i>
                        </div>
                        <div class="font-semibold mb-2">Klik atau drag file ke sini</div>
                        <div class="text-sm text-gray-500 mb-1">Format: JPG, JPEG, PNG (maks. 2MB)</div>
                        <div class="text-xs text-gray-400">Ukuran file tidak boleh lebih dari 2MB</div>
                        <input type="file" id="buktiFile" accept=".jpg,.jpeg,.png" style="display:none;" onchange="previewBukti(this)">
                    </div>
                    <div id="buktiPreview" class="mt-4"></div>
                </div>

                <!-- Izin Notes -->
                <div id="izinNotes" class="hidden">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Alasan Izin/Sakit *</label>
                        <textarea id="alasanText" rows="4" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                  placeholder="Jelaskan alasan izin/sakit secara detail... (minimal 10 karakter)"></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="absensi-action-buttons">
                <button onclick="submitAbsensi()" id="submitBtn" class="btn btn-primary" disabled>
                    <i class='bx bx-send'></i> Submit Absensi
                </button>
                <button onclick="resetAbsensi()" class="btn btn-secondary">
                    <i class='bx bx-reset'></i> Reset Form
                </button>
            </div>
        </div>
        
<!-- Jam Kerja Info -->
        <div style="background: #e6fff3; padding: 20px; border-radius: 12px; border-left: 4px solid #2ecc71;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <i class='bx bx-time-five' style="font-size: 2rem; color: #2ecc71;"></i>
                <div>
                    <div style="font-weight: 600; color: var(--primary);">Jam Kerja Diskominfo SP Surakarta</div>
                    <div style="color: #666; margin-top: 5px;">
                        <span style="font-weight: 600;">Senin - Kamis:</span> 07:30 - 16:30 WIB<br>
                        <span style="font-weight: 600;">Jumat:</span> 07:30 - 14:30 WIB<br>
                    </div>
                    <div style="color: #e74c3c; font-size: 0.9rem; margin-top: 10px; font-weight: 600;">
                        <i class='bx bx-alarm'></i> Absensi diluar jam kerja akan tercatat terlambat dan jika tidak submit absensi dianggap alpha.
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Success Notification Modal -->
    <div id="successModal" class="modal-notification">
        <div class="modal-notification-content">
            <div class="mb-6">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class='bx bx-check-circle text-green-600 text-4xl'></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Absensi Berhasil!</h3>
                <p id="successMessage" class="text-gray-600"></p>
            </div>
            <button onclick="closeSuccessModal()" class="btn btn-primary w-full">
                <i class='bx bx-check'></i> OK
            </button>
        </div>
    </div>
</div>

<script>
let currentAbsensiStatus = null;
let userLocation = null;
let uploadedFile = null;
let isLocationValid = false;
    
// Office coordinates (Diskominfo SP Surakarta)
const OFFICE_COORDS = {
    lat: -7.565962,
    lng: 110.826141,
    address: "Jl. Jend. Sudirman No. 2, Kp. Baru, Kec. Ps. Kliwon, Kota Surakarta"
};
    
// Update clock
function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('id-ID');
    document.getElementById('clock').textContent = timeString;
    
    // Update date
    const dateString = now.toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    document.getElementById('currentDate').textContent = dateString;
}
    
// Set absensi status
function setAbsensiStatus(status) {
    currentAbsensiStatus = status;
    
    // Reset all buttons
    document.querySelectorAll('.status-btn').forEach(btn => {
        btn.classList.remove('active');
        const statusClass = btn.classList.contains('hadir') ? 'hadir' : 
                          btn.classList.contains('izin') ? 'izin' : 'sakit';
        btn.style.background = 'white';
        btn.style.color = statusClass === 'hadir' ? '#2ecc71' : 
                         statusClass === 'izin' ? '#f1c40f' : '#e74c3c';
        btn.style.borderColor = statusClass === 'hadir' ? '#2ecc71' : 
                               statusClass === 'izin' ? '#f1c40f' : '#e74c3c';
    });
    
    // Set active button
    if (status === 'hadir') {
        document.getElementById('btnHadir').classList.add('active');
        document.getElementById('btnHadir').style.background = '#2ecc71';
        document.getElementById('btnHadir').style.color = 'white';
        document.getElementById('statusText').innerHTML = '<span class="text-success">Status: HADIR</span>';
        document.getElementById('gpsSection').classList.remove('hidden');
        
        // Reset location state
        isLocationValid = false;
        userLocation = null;
        document.getElementById('izinNotes').classList.add('hidden');
        
        // Auto-get location for "hadir"
        setTimeout(() => {
            getLocation();
        }, 500);
        
    } else if (status === 'izin') {
        document.getElementById('btnIzin').classList.add('active');
        document.getElementById('btnIzin').style.background = '#f1c40f';
        document.getElementById('btnIzin').style.color = 'white';
        document.getElementById('statusText').innerHTML = '<span class="text-warning">Status: IZIN</span>';
        document.getElementById('gpsSection').classList.add('hidden');
        document.getElementById('izinNotes').classList.remove('hidden');
        isLocationValid = true; // Skip location validation for izin/sakit
    } else {
        document.getElementById('btnSakit').classList.add('active');
        document.getElementById('btnSakit').style.background = '#e74c3c';
        document.getElementById('btnSakit').style.color = 'white';
        document.getElementById('statusText').innerHTML = '<span class="text-danger">Status: SAKIT</span>';
        document.getElementById('gpsSection').classList.add('hidden');
        document.getElementById('izinNotes').classList.remove('hidden');
        isLocationValid = true; // Skip location validation for izin/sakit
    }
    
    document.getElementById('uploadSection').classList.remove('hidden');
    
    // Update upload label based on status
    const uploadLabel = document.getElementById('uploadLabel');
    if (status === 'hadir') {
        uploadLabel.textContent = 'Upload Foto Bukti Kehadiran *';
    } else {
        uploadLabel.textContent = 'Upload Surat Izin/Sakit *';
    }
    
    checkSubmitEligibility();
}
    
function getLocation() {
    if (!navigator.geolocation) {
        document.getElementById('locationText').innerHTML = 'Geolocation tidak didukung browser';
        return;
    }
    
    document.getElementById('locationText').innerHTML = 'Mendeteksi lokasi...';
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            userLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            
            // Hitung jarak
            const distance = calculateDistance(
                userLocation.lat,
                userLocation.lng,
                OFFICE_COORDS.lat,
                OFFICE_COORDS.lng
            );
            
            // Update location text
            document.getElementById('locationText').innerHTML = `
                <div>Jarak: <strong>${Math.round(distance)} meter</strong> dari Diskominfo</div>
                <div class="text-xs text-gray-500">
                    Koordinat: ${userLocation.lat.toFixed(6)}, ${userLocation.lng.toFixed(6)}
                </div>
            `;
            
            // Update distance display
            document.getElementById('distanceValue').textContent = Math.round(distance) + ' meter';
            
            // Show user marker on map
            const userMarker = document.getElementById('userMarker');
            userMarker.style.display = 'block';
            
            // SELALU valid untuk testing
            isLocationValid = true;
            
            // Check eligibility
            checkSubmitEligibility();
        },
        function(error) {
            let errorMessage = '';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage = 'Izin lokasi ditolak. Silakan aktifkan GPS.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage = 'Informasi lokasi tidak tersedia.';
                    break;
                case error.TIMEOUT:
                    errorMessage = 'Permintaan lokasi timeout.';
                    break;
                default:
                    errorMessage = 'Error tidak diketahui.';
            }
            document.getElementById('locationText').innerHTML = errorMessage;
            isLocationValid = false;
            userLocation = null;
            checkSubmitEligibility();
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}
    
// Calculate distance between two coordinates in meters
function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371e3; // Earth radius in meters
    const phi1 = lat1 * Math.PI / 180;
    const phi2 = lat2 * Math.PI / 180;
    const deltaPhi = (lat2 - lat1) * Math.PI / 180;
    const deltaLambda = (lon2 - lon1) * Math.PI / 180;

    const a = Math.sin(deltaPhi / 2) * Math.sin(deltaPhi / 2) +
             Math.cos(phi1) * Math.cos(phi2) *
             Math.sin(deltaLambda / 2) * Math.sin(deltaLambda / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    return R * c;
}
    
// Preview uploaded file
function previewBukti(input) {
    const preview = document.getElementById('buktiPreview');
    const uploadZone = document.getElementById('uploadZone');
    
    if (input.files && input.files[0]) {
        uploadedFile = input.files[0];
        const fileName = uploadedFile.name;
        const fileSize = (uploadedFile.size / 1024 / 1024).toFixed(2);
        
        if (fileSize > 2) {
            showAlert('File terlalu besar! Maksimal 2MB.', 'danger');
            input.value = '';
            preview.innerHTML = '';
            uploadedFile = null;
            checkSubmitEligibility();
            return;
        }
        
        // Check file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(uploadedFile.type)) {
            showAlert('Format file tidak valid! Hanya JPG/PNG/JPEG yang diperbolehkan.', 'danger');
            input.value = '';
            preview.innerHTML = '';
            uploadedFile = null;
            checkSubmitEligibility();
            return;
        }
        
        // Update preview
        preview.innerHTML = `
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class='bx bx-image text-green-600 text-xl'></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-800">${fileName}</div>
                        <div class="text-sm text-gray-600">${fileSize} MB • ${uploadedFile.type}</div>
                    </div>
                    <button onclick="removeFile()" class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600 hover:bg-red-200 transition-colors">
                        <i class='bx bx-trash'></i>
                    </button>
                </div>
            </div>
        `;
        
        // Update upload zone
        uploadZone.style.background = 'rgba(46, 204, 113, 0.1)';
        uploadZone.style.borderColor = '#2ecc71';
    }
    
    checkSubmitEligibility();
}
    
// Remove uploaded file
function removeFile() {
    document.getElementById('buktiFile').value = '';
    document.getElementById('buktiPreview').innerHTML = '';
    uploadedFile = null;
    const uploadZone = document.getElementById('uploadZone');
    uploadZone.style.background = '';
    uploadZone.style.borderColor = '';
    checkSubmitEligibility();
}
    
// Check if submit is eligible
function checkSubmitEligibility() {
    const submitBtn = document.getElementById('submitBtn');
    
    if (!currentAbsensiStatus) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bx bx-send"></i> Submit Absensi';
        return;
    }
    
    // Reset button text first
    submitBtn.innerHTML = '<i class="bx bx-send"></i> Submit Absensi';
    
    if (currentAbsensiStatus === 'hadir') {
        // For hadir: need location AND file upload
        const hasLocation = userLocation !== null;
        
        if (hasLocation && uploadedFile) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
            
            // Show helpful message
            if (!hasLocation) {
                submitBtn.innerHTML = '<i class="bx bx-map"></i> Ambil Lokasi Dulu';
            } else if (!uploadedFile) {
                submitBtn.innerHTML = '<i class="bx bx-cloud-upload"></i> Upload Bukti';
            }
        }
    } else {
        // For izin/sakit: need reason AND file upload
        const alasan = document.getElementById('alasanText').value;
        
        if (alasan.trim().length > 10 && uploadedFile) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
            
            // Show helpful message
            if (alasan.trim().length <= 10) {
                submitBtn.innerHTML = '<i class="bx bx-edit"></i> Isi Alasan (min. 10 karakter)';
            } else if (!uploadedFile) {
                submitBtn.innerHTML = '<i class="bx bx-cloud-upload"></i> Upload Surat Izin/Sakit';
            }
        }
    }
}
    
// Submit absensi
function submitAbsensi() {
    if (!currentAbsensiStatus) {
        showAlert('Pilih status absensi dulu', 'warning');
        return;
    }

    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bx bx-loader-circle bx-spin"></i> Mengirim...';
    submitBtn.disabled = true;

    const formData = new FormData();
    formData.append('status', currentAbsensiStatus);

    // HADIR -> butuh lokasi dan bukti
    if (currentAbsensiStatus === 'hadir') {
        if (!uploadedFile) {
            showAlert('Upload bukti wajib untuk absensi hadir', 'warning');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            return;
        }

        // Wajib ada lokasi (tapi tidak perlu validasi jarak)
        if (!userLocation) {
            showAlert('Lokasi belum terdeteksi. Klik "Refresh" untuk mengambil lokasi', 'warning');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            return;
        }

        formData.append('bukti_kegiatan', uploadedFile);
        formData.append('lokasi', JSON.stringify(userLocation));
    }

    // IZIN / SAKIT -> butuh alasan dan bukti
    if (currentAbsensiStatus === 'izin' || currentAbsensiStatus === 'sakit') {
        const alasan = document.getElementById('alasanText').value;
        if (alasan.trim().length < 10) {
            showAlert('Alasan minimal 10 karakter', 'warning');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            return;
        }

        if (!uploadedFile) {
            showAlert('Upload surat izin / sakit wajib', 'warning');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            return;
        }

        formData.append('alasan', alasan);
        formData.append('bukti_kegiatan', uploadedFile);
    }

    fetch('/peserta/absensi', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'same-origin',
        body: formData
    })
    .then(async res => {
        let payload = null;
        try {
            payload = await res.json();
        } catch (e) {
            payload = null;
        }

        if (!res.ok) {
            const message = payload?.message || 'Gagal menyimpan absensi';
            if (res.status === 409 || res.status === 422) {
                const alertBox = document.getElementById('absensiAlert');
                if (alertBox) {
                    alertBox.innerHTML = `
                        <div class="flex items-center gap-3">
                            <i class='bx bx-error-circle text-danger text-xl'></i>
                            <div>
                                <div class="font-semibold">Perhatian</div>
                                <div>${message}</div>
                            </div>
                        </div>
                    `;
                    alertBox.classList.remove('hidden');
                    alertBox.classList.add('absensi-info-box', 'danger');
                }
            } else {
                showAlert(message, 'danger');
            }
            throw new Error(message);
        }

        return payload;
    })
    .then(data => {
        // Show success modal
        showSuccessModal(data?.message || 'Absen berhasil');
        
        // Auto reload after 3 seconds
        setTimeout(() => {
            window.location.reload();
        }, 3000);
    })
    .catch(err => {
        console.error(err);
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}
    
// Reset absensi form
function resetAbsensi() {
    currentAbsensiStatus = null;
    userLocation = null;
    uploadedFile = null;
    isLocationValid = false;
    
    document.getElementById('gpsSection').classList.add('hidden');
    document.getElementById('uploadSection').classList.add('hidden');
    document.getElementById('izinNotes').classList.add('hidden');
    document.getElementById('buktiPreview').innerHTML = '';
    document.getElementById('alasanText').value = '';
    document.getElementById('submitBtn').disabled = true;
    
    document.getElementById('statusText').innerHTML = 'Silakan pilih status absensi';
    
    // Reset buttons
    document.querySelectorAll('.status-btn').forEach(btn => {
        btn.classList.remove('active');
        const statusClass = btn.classList.contains('hadir') ? 'hadir' : 
                          btn.classList.contains('izin') ? 'izin' : 'sakit';
        btn.style.background = 'white';
        btn.style.color = statusClass === 'hadir' ? '#2ecc71' : 
                         statusClass === 'izin' ? '#f1c40f' : '#e74c3c';
        btn.style.borderColor = statusClass === 'hadir' ? '#2ecc71' : 
                               statusClass === 'izin' ? '#f1c40f' : '#e74c3c';
    });
}
    
// Show success modal
function showSuccessModal(message) {
    document.getElementById('successMessage').textContent = message;
    document.getElementById('successModal').style.display = 'flex';
}

// Close success modal
function closeSuccessModal() {
    document.getElementById('successModal').style.display = 'none';
    window.location.reload();
}

// Show alert notification
function showAlert(message, type = 'info') {
    const alertBox = document.getElementById('absensiAlert');
    if (!alertBox) return;
    
    const icons = {
        'success': 'bx-check-circle',
        'warning': 'bx-error-circle',
        'danger': 'bx-error-circle',
        'info': 'bx-info-circle'
    };
    
    const colors = {
        'success': 'success',
        'warning': 'warning',
        'danger': 'danger',
        'info': ''
    };
    
    alertBox.innerHTML = `
        <div class="flex items-center gap-3">
            <i class='bx ${icons[type] || 'bx-info-circle'} text-${colors[type] || 'primary'} text-xl'></i>
            <div>
                <div class="font-semibold">${type === 'success' ? 'Sukses' : type === 'warning' ? 'Perhatian' : type === 'danger' ? 'Error' : 'Info'}</div>
                <div>${message}</div>
            </div>
        </div>
    `;
    
    alertBox.className = `absensi-info-box ${colors[type]}`;
    alertBox.classList.remove('hidden');
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        alertBox.classList.add('hidden');
    }, 5000);
}
    
// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Start clock
    updateClock();
    setInterval(updateClock, 1000);

    // Setup drag and drop for file upload
    const uploadZone = document.getElementById('uploadZone');
    if (uploadZone) {
        uploadZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.background = 'rgba(46, 204, 113, 0.2)';
            this.style.borderColor = '#2ecc71';
        });
        
        uploadZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            if (!uploadedFile) {
                this.style.background = '';
                this.style.borderColor = '';
            }
        });
        
        uploadZone.addEventListener('drop', function(e) {
            e.preventDefault();
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('buktiFile').files = files;
                previewBukti(document.getElementById('buktiFile'));
            }
        });
    }
    
    // Listen for reason text changes
    const alasanText = document.getElementById('alasanText');
    if (alasanText) {
        alasanText.addEventListener('input', checkSubmitEligibility);
    }
    
    // Listen for file input changes
    const buktiFile = document.getElementById('buktiFile');
    if (buktiFile) {
        buktiFile.addEventListener('change', function() {
            previewBukti(this);
            checkSubmitEligibility();
        });
    }
});
</script>
@endsection
