@extends('layouts.peserta')

@section('title', 'Absensi Magang')

@section('content')
<div class="form-card">
    <h2 style="color: var(--primary); margin-bottom: 30px; display: flex; align-items: center; gap: 10px;">
        <i class='bx bx-calendar-check'></i> Absensi Harian Magang
    </h2>
    
    <!-- Statistik Absensi -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, rgba(33, 52, 72, 0.1) 0%, rgba(84, 119, 146, 0.1) 100%); padding: 20px; border-radius: 12px;">
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary);">15</div>
            <div style="color: #666;">Total Hari</div>
        </div>
        <div style="background: linear-gradient(135deg, rgba(46, 213, 115, 0.1) 0%, rgba(39, 174, 96, 0.1) 100%); padding: 20px; border-radius: 12px;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #2ecc71;">14</div>
            <div style="color: #666;">Hadir</div>
        </div>
        <div style="background: linear-gradient(135deg, rgba(241, 196, 15, 0.1) 0%, rgba(243, 156, 18, 0.1) 100%); padding: 20px; border-radius: 12px;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #f1c40f;">1</div>
            <div style="color: #666;">Izin</div>
        </div>
        <div style="background: linear-gradient(135deg, rgba(231, 76, 60, 0.1) 0%, rgba(192, 57, 43, 0.1) 100%); padding: 20px; border-radius: 12px;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #e74c3c;">0</div>
            <div style="color: #666;">Alfa</div>
        </div>
    </div>
    
    <!-- Form Absensi Hari Ini -->
    <div id="absensiForm" style="background: #f8fafc; padding: 30px; border-radius: 16px; margin-bottom: 30px; border: 2px solid var(--accent);">
        <h3 style="color: var(--primary); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class='bx bx-edit'></i> Absensi Hari Ini
        </h3>
        
        <div style="background: white; padding: 25px; border-radius: 12px; margin-bottom: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div>
                    <div style="font-weight: 600; color: var(--primary); font-size: 1.2rem;" id="currentDate">Sabtu, 16 Maret 2024</div>
                    <div style="color: #666; font-size: 0.9rem;">Hari ke-16 dari 90 hari magang</div>
                </div>
                <div id="clock" style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">00:00:00</div>
            </div>
            
            <!-- Status Absensi -->
            <div id="absensiStatus" style="margin-bottom: 20px;">
                <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                    <button onclick="setAbsensiStatus('hadir')" id="btnHadir" style="flex:1; padding:15px; background:#e6fff3; border:2px solid #2ecc71; color:#2ecc71; border-radius:10px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:10px;">
                        <i class='bx bx-check-circle'></i> Hadir
                    </button>
                    <button onclick="setAbsensiStatus('izin')" id="btnIzin" style="flex:1; padding:15px; background:#fff9e6; border:2px solid #f1c40f; color:#f1c40f; border-radius:10px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:10px;">
                        <i class='bx bx-alarm-exclamation'></i> Izin
                    </button>
                    <button onclick="setAbsensiStatus('sakit')" id="btnSakit" style="flex:1; padding:15px; background:#ffe6e6; border:2px solid #e74c3c; color:#e74c3c; border-radius:10px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:10px;">
                        <i class='bx bx-plus-medical'></i> Sakit
                    </button>
                </div>
                
                <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-top: 15px;">
                    <div id="statusText" style="font-weight: 600; color: var(--primary);">Silakan pilih status absensi</div>
                </div>
            </div>
            
            <!-- GPS Location -->
            <div id="gpsSection" style="display: none;">
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
                    
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 5px;">
                            <div style="width: 12px; height: 12px; background: #2ecc71; border-radius: 50%;"></div>
                            <span style="font-size: 0.85rem; color: #666;">Lokasi Kantor</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 5px;">
                            <div style="width: 12px; height: 12px; background: #3498db; border-radius: 50%;"></div>
                            <span style="font-size: 0.85rem; color: #666;">Lokasi Anda</span>
                        </div>
                    </div>
                    
                    <!-- Map Container -->
                    <div id="mapContainer" style="height: 200px; background: #f8f9fa; border-radius: 8px; margin-top: 15px; overflow: hidden; position: relative;">
                        <!-- Simplified Map Visualization -->
                        <div style="position: relative; width: 100%; height: 100%; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                            <!-- Office Marker -->
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                <div style="width: 40px; height: 40px; background: #2ecc71; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem; box-shadow: 0 5px 15px rgba(46, 204, 113, 0.4);">
                                    <i class='bx bx-building'></i>
                                </div>
                                <div style="position: absolute; top: 45px; left: 50%; transform: translateX(-50%); white-space: nowrap; background: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                    Diskominfo SP
                                </div>
                            </div>
                            
                            <!-- User Marker -->
                            <div id="userMarker" style="position: absolute; top: 30%; left: 60%; display: none;">
                                <div style="width: 30px; height: 30px; background: #3498db; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1rem; box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);">
                                    <i class='bx bx-user'></i>
                                </div>
                                <div style="position: absolute; top: 35px; left: 50%; transform: translateX(-50%); white-space: nowrap; background: white; padding: 3px 6px; border-radius: 4px; font-size: 0.7rem; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                    Anda
                                </div>
                            </div>
                            
                            <!-- Distance Line -->
                            <div id="distanceLine" style="position: absolute; display: none;"></div>
                        </div>
                        
                        <!-- Distance Info -->
                        <div id="distanceInfo" style="position: absolute; bottom: 10px; left: 10px; background: rgba(255, 255, 255, 0.9); padding: 8px 15px; border-radius: 20px; font-size: 0.9rem; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            Jarak: <span id="distanceValue">-</span> meter
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Upload Bukti -->
            <div id="uploadSection" style="display: none;">
                <div class="form-group">
                    <label>Upload Bukti *</label>
                    <div class="upload-area" onclick="document.getElementById('buktiFile').click()" id="uploadZone">
                        <div class="upload-icon">
                            <i class='bx bx-cloud-upload'></i>
                        </div>
                        <div style="font-weight: 600; margin-bottom: 5px;">Klik atau drag file ke sini</div>
                        <small>Format JPG/PNG, max 2MB. Foto wajib menunjukkan lokasi kantor</small>
                        <input type="file" id="buktiFile" accept=".jpg,.jpeg,.png" style="display:none;" onchange="previewBukti(this)">
                    </div>
                    <div id="buktiPreview" style="margin-top: 10px;"></div>
                    
                    <div style="background: #fff9e6; padding: 15px; border-radius: 8px; margin-top: 15px; border-left: 4px solid #f1c40f;">
                        <div style="font-weight: 600; color: #f1c40f; margin-bottom: 5px;">
                            <i class='bx bx-info-circle'></i> Petunjuk Foto Bukti:
                        </div>
                        <ul style="margin: 10px 0 0 20px; padding: 0; font-size: 0.9rem; color: #666;">
                            <li>Pastikan wajah dan timestamp jelas</li>
                            <li>Waktu foto maksimal 1 jam sebelum/sesudah absen</li>
                        </ul>
                    </div>
                </div>
                
                <!-- Additional Notes for Izin/Sakit -->
                <div id="izinNotes" style="display: none;">
                    <div class="form-group">
                        <label>Alasan Izin/Sakit *</label>
                        <textarea id="alasanText" rows="3" placeholder="Jelaskan alasan izin/sakit secara detail..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Upload Surat Izin/Dokter (Opsional)</label>
                        <div class="upload-area" onclick="document.getElementById('suratFile').click()">
                            <div class="upload-icon">
                                <i class='bx bx-file'></i>
                            </div>
                            <div>Unggah Surat Izin/Dokter</div>
                            <small>Format PDF/JPG, max 2MB</small>
                            <input type="file" id="suratFile" style="display:none;">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div style="text-align: center; margin-top: 25px;">
                <button onclick="submitAbsensi()" id="submitBtn" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.1rem;" disabled>
                    <i class='bx bx-send'></i> Submit Absensi
                </button>
                <button onclick="resetAbsensi()" class="btn" style="margin-left: 15px; background: #f8fafc; color: #666; padding: 15px 30px;">
                    Reset
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
                        <i class='bx bx-alarm'></i> Absensi diluar jam kerja akan tercatat terlambat
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    // Global variables
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
        document.getElementById('btnHadir').style.background = '#e6fff3';
        document.getElementById('btnHadir').style.borderColor = '#2ecc71';
        document.getElementById('btnHadir').style.color = '#2ecc71';
        
        document.getElementById('btnIzin').style.background = '#fff9e6';
        document.getElementById('btnIzin').style.borderColor = '#f1c40f';
        document.getElementById('btnIzin').style.color = '#f1c40f';
        
        document.getElementById('btnSakit').style.background = '#ffe6e6';
        document.getElementById('btnSakit').style.borderColor = '#e74c3c';
        document.getElementById('btnSakit').style.color = '#e74c3c';
        
        // Set active button
        if (status === 'hadir') {
            document.getElementById('btnHadir').style.background = '#2ecc71';
            document.getElementById('btnHadir').style.color = 'white';
            document.getElementById('statusText').innerHTML = 'Status: <span style="color:#2ecc71">HADIR</span>';
            document.getElementById('gpsSection').style.display = 'block';
            document.getElementById('izinNotes').style.display = 'none';
        } else if (status === 'izin') {
            document.getElementById('btnIzin').style.background = '#f1c40f';
            document.getElementById('btnIzin').style.color = 'white';
            document.getElementById('statusText').innerHTML = 'Status: <span style="color:#f1c40f">IZIN</span>';
            document.getElementById('gpsSection').style.display = 'none';
            document.getElementById('izinNotes').style.display = 'block';
            isLocationValid = true; // Skip location validation for izin/sakit
        } else {
            document.getElementById('btnSakit').style.background = '#e74c3c';
            document.getElementById('btnSakit').style.color = 'white';
            document.getElementById('statusText').innerHTML = 'Status: <span style="color:#e74c3c">SAKIT</span>';
            document.getElementById('gpsSection').style.display = 'none';
            document.getElementById('izinNotes').style.display = 'block';
            isLocationValid = true; // Skip location validation for izin/sakit
        }
        
        document.getElementById('uploadSection').style.display = 'block';
        checkSubmitEligibility();
    }
    
    // Get user location
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
                
                // Calculate distance from office
                const distance = calculateDistance(
                    userLocation.lat,
                    userLocation.lng,
                    OFFICE_COORDS.lat,
                    OFFICE_COORDS.lng
                );
                
                // Update location text
                document.getElementById('locationText').innerHTML = `
                    <div>Lat: ${userLocation.lat.toFixed(6)}, Lng: ${userLocation.lng.toFixed(6)}</div>
                    <div style="font-size:0.9rem; color:#666;">Akurasi: ${position.coords.accuracy.toFixed(1)} meter</div>
                `;
                
                // Update distance
                document.getElementById('distanceValue').textContent = distance.toFixed(0);
                
                // Show user marker on map
                const userMarker = document.getElementById('userMarker');
                userMarker.style.display = 'block';
                
                // Position user marker (simplified)
                const userX = 60 + (userLocation.lng - OFFICE_COORDS.lng) * 100;
                const userY = 30 + (userLocation.lat - OFFICE_COORDS.lat) * 100;
                userMarker.style.left = `${Math.min(Math.max(userX, 10), 90)}%`;
                userMarker.style.top = `${Math.min(Math.max(userY, 10), 90)}%`;
                
                // Show distance line
                const line = document.getElementById('distanceLine');
                line.style.display = 'block';
                line.style.position = 'absolute';
                line.style.top = '50%';
                line.style.left = '50%';
                line.style.width = `${Math.abs(userX - 50)}%`;
                line.style.height = '2px';
                line.style.background = '#3498db';
                line.style.transformOrigin = '0 0';
                
                // Check if location is valid (within 500 meters)
                isLocationValid = distance <= 500;
                
                if (isLocationValid) {
                    document.getElementById('distanceInfo').style.background = 'rgba(46, 204, 113, 0.9)';
                    document.getElementById('distanceInfo').style.color = 'white';
                } else {
                    document.getElementById('distanceInfo').style.background = 'rgba(231, 76, 60, 0.9)';
                    document.getElementById('distanceInfo').style.color = 'white';
                    document.getElementById('distanceInfo').innerHTML += ' <i class="bx bx-error" style="color:white;"></i>';
                }
                
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
        const φ1 = lat1 * Math.PI/180;
        const φ2 = lat2 * Math.PI/180;
        const Δφ = (lat2-lat1) * Math.PI/180;
        const Δλ = (lon2-lon1) * Math.PI/180;
        
        const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
                 Math.cos(φ1) * Math.cos(φ2) *
                 Math.sin(Δλ/2) * Math.sin(Δλ/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        
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
                alert('File terlalu besar! Maksimal 2MB.');
                input.value = '';
                preview.innerHTML = '';
                uploadedFile = null;
                checkSubmitEligibility();
                return;
            }
            
            // Check file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(uploadedFile.type)) {
                alert('Format file tidak valid! Hanya JPG/PNG yang diperbolehkan.');
                input.value = '';
                preview.innerHTML = '';
                uploadedFile = null;
                checkSubmitEligibility();
                return;
            }
            
            // Update preview
            preview.innerHTML = `
                <div style="background: #e6fff3; padding: 15px; border-radius: 12px; border: 2px solid #2ecc71;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <i class='bx bx-image' style="font-size: 2rem; color: #2ecc71;"></i>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: var(--primary);">${fileName}</div>
                            <div style="font-size: 0.9rem; color: #666;">${fileSize} MB • ${uploadedFile.type}</div>
                        </div>
                        <button onclick="removeFile()" style="background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 1.2rem;">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                </div>
            `;
            
            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML += `
                    <div style="margin-top: 15px; text-align: center;">
                        <img src="${e.target.result}" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e2e8f0;">
                    </div>
                `;
            };
            reader.readAsDataURL(uploadedFile);
            
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
        document.getElementById('uploadZone').style.background = 'rgba(148, 180, 193, 0.05)';
        document.getElementById('uploadZone').style.borderColor = 'var(--accent)';
        checkSubmitEligibility();
    }
    
    // Check if submit is eligible
    function checkSubmitEligibility() {
        const submitBtn = document.getElementById('submitBtn');
        
        if (!currentAbsensiStatus) {
            submitBtn.disabled = true;
            return;
        }
        
        if (currentAbsensiStatus === 'hadir') {
            // For hadir: need location validation and file upload
            if (isLocationValid && uploadedFile) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        } else {
            // For izin/sakit: need reason and optional file
            const alasan = document.getElementById('alasanText').value;
            if (alasan.trim().length > 10) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }
    }
    
    // Submit absensi
    function submitAbsensi() {
        const now = new Date();
        const waktu = now.toLocaleTimeString('id-ID');
        const tanggal = now.toLocaleDateString('id-ID');
        
        // Get reason if izin/sakit
        let alasan = '';
        if (currentAbsensiStatus !== 'hadir') {
            alasan = document.getElementById('alasanText').value;
            if (alasan.trim().length < 10) {
                alert('Harap isi alasan minimal 10 karakter!');
                return;
            }
        }
        
        // Show loading
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bx bx-loader-circle bx-spin"></i> Mengirim...';
        submitBtn.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            // Success message
            let statusColor = currentAbsensiStatus === 'hadir' ? '#2ecc71' : 
                             currentAbsensiStatus === 'izin' ? '#f1c40f' : '#e74c3c';
            
            const modal = `
                <div style="position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.7); display:flex; align-items:center; justify-content:center; z-index:9999;">
                    <div style="background:white; padding:40px; border-radius:20px; text-align:center; max-width:500px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.3);">
                        <div style="font-size:4rem; color:${statusColor}; margin-bottom:20px;">
                            ${currentAbsensiStatus === 'hadir' ? '✅' : 
                              currentAbsensiStatus === 'izin' ? '⚠️' : '🏥'}
                        </div>
                        <h2 style="color:var(--primary); margin-bottom:15px;">
                            Absensi Berhasil!
                        </h2>
                        <p>Absensi Anda telah tercatat dengan status:</p>
                        <div style="background:#f8fafc; padding:20px; border-radius:12px; margin:20px 0; border-left:4px solid ${statusColor};">
                            <div style="font-weight:600; color:var(--primary);">${currentAbsensiStatus.toUpperCase()}</div>
                            <div style="color:#666; margin-top:5px;">${tanggal} ${waktu}</div>
                            ${alasan ? `<div style="color:#666; margin-top:10px;">Alasan: ${alasan}</div>` : ''}
                        </div>
                        <p style="color:#666;">Status: <span style="font-weight:600; color:#f1c40f;">MENUNGGU VERIFIKASI</span></p>
                        <div style="display:flex; gap:15px; margin-top:25px;">
                            <button onclick="window.location.reload()" style="flex:1; background:var(--primary); color:white; border:none; padding:12px; border-radius:10px; font-weight:bold; cursor:pointer;">
                                OK
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modal);
            
            // Reset button
            submitBtn.innerHTML = originalText;
        }, 2000);
    }
    
    // Reset absensi form
    function resetAbsensi() {
        currentAbsensiStatus = null;
        userLocation = null;
        uploadedFile = null;
        isLocationValid = false;
        
        document.getElementById('gpsSection').style.display = 'none';
        document.getElementById('uploadSection').style.display = 'none';
        document.getElementById('izinNotes').style.display = 'none';
        document.getElementById('buktiPreview').innerHTML = '';
        document.getElementById('alasanText').value = '';
        document.getElementById('submitBtn').disabled = true;
        
        document.getElementById('statusText').innerHTML = 'Silakan pilih status absensi';
        
        // Reset buttons
        setAbsensiStatus(null);
    }
    
    // Generate calendar
    function generateCalendar() {
        const calendarEl = document.getElementById('calendar');
        // Remove existing day cells (keep headers)
        while (calendarEl.children.length > 7) {
            calendarEl.removeChild(calendarEl.lastChild);
        }
        
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth(); // 0-indexed
        
        // Get first day of month
        const firstDay = new Date(year, month, 1);
        // Get last day of month
        const lastDay = new Date(year, month + 1, 0);
        // Get starting day (0 = Monday)
        const startDay = (firstDay.getDay() + 6) % 7;
        
        // Add empty cells for days before month starts
        for (let i = 0; i < startDay; i++) {
            const emptyCell = document.createElement('div');
            emptyCell.style.padding = '15px';
            emptyCell.style.textAlign = 'center';
            emptyCell.style.color = '#ccc';
            emptyCell.textContent = '';
            calendarEl.appendChild(emptyCell);
        }
        
        // Add days of month
        for (let day = 1; day <= lastDay.getDate(); day++) {
            const dayCell = document.createElement('div');
            dayCell.style.padding = '15px';
            dayCell.style.textAlign = 'center';
            dayCell.style.borderRadius = '8px';
            dayCell.style.fontWeight = '600';
            dayCell.style.cursor = 'pointer';
            dayCell.textContent = day;
            
            // Check if today
            if (day === today.getDate() && month === today.getMonth()) {
                dayCell.style.background = '#3498db';
                dayCell.style.color = 'white';
                dayCell.style.border = '2px solid #2980b9';
            } else {
                // Assign random status for demo
                const rand = Math.random();
                if (rand < 0.6) {
                    dayCell.style.background = '#2ecc71';
                    dayCell.style.color = 'white';
                } else if (rand < 0.8) {
                    dayCell.style.background = '#f1c40f';
                    dayCell.style.color = 'white';
                } else if (rand < 0.9) {
                    dayCell.style.background = '#e74c3c';
                    dayCell.style.color = 'white';
                } else {
                    dayCell.style.background = '#95a5a6';
                    dayCell.style.color = 'white';
                }
            }
            
            // Add click event
            dayCell.onclick = function() {
                showDayDetails(day);
            };
            
            calendarEl.appendChild(dayCell);
        }
    }
    
    // Show day details
    function showDayDetails(day) {
        const now = new Date();
        const dateStr = `${day} Maret ${now.getFullYear()}`;
        
        alert(`Detail Absensi ${dateStr}:\n\nStatus: Hadir\nWaktu: 08:15 - 16:30\nLokasi: Dalam kantor\nVerifikasi: ✓ Disetujui Mentor`);
    }
    
    // Load riwayat data
    function loadRiwayat() {
        const riwayatData = [
            {
                tanggal: '15 Mar 2024',
                status: 'Hadir',
                waktu: '08:15 - 16:30',
                lokasi: 'Dalam kantor',
                bukti: '✅',
                verifikasi: 'Disetujui'
            },
            {
                tanggal: '14 Mar 2024',
                status: 'Hadir',
                waktu: '08:30 - 17:00',
                lokasi: 'Dalam kantor',
                bukti: '✅',
                verifikasi: 'Disetujui'
            },
            {
                tanggal: '13 Mar 2024',
                status: 'Sakit',
                waktu: '-',
                lokasi: '-',
                bukti: '📄',
                verifikasi: 'Disetujui'
            },
            {
                tanggal: '12 Mar 2024',
                status: 'Hadir',
                waktu: '09:00 - 16:30',
                lokasi: 'Dalam kantor',
                bukti: '✅',
                verifikasi: 'Disetujui'
            },
            {
                tanggal: '11 Mar 2024',
                status: 'Hadir',
                waktu: '08:00 - 15:00',
                lokasi: 'Dalam kantor',
                bukti: '✅',
                verifikasi: 'Disetujui'
            },
            {
                tanggal: '10 Mar 2024',
                status: 'Hadir',
                waktu: '08:20 - 16:45',
                lokasi: 'Dalam kantor',
                bukti: '✅',
                verifikasi: 'Disetujui'
            },
            {
                tanggal: '09 Mar 2024',
                status: 'Izin',
                waktu: '-',
                lokasi: '-',
                bukti: '📄',
                verifikasi: 'Disetujui'
            }
        ];
        
        const tableBody = document.getElementById('riwayatTable');
        tableBody.innerHTML = '';
        
        riwayatData.forEach(item => {
            let statusColor = item.status === 'Hadir' ? '#2ecc71' : 
                             item.status === 'Izin' ? '#f1c40f' : '#e74c3c';
            
            const row = document.createElement('tr');
            row.style.borderBottom = '1px solid #eee';
            row.innerHTML = `
                <td style="padding: 15px;">${item.tanggal}</td>
                <td style="padding: 15px;">
                    <span style="background:${statusColor}; color:white; padding:5px 12px; border-radius:20px; font-size:0.85rem;">
                        ${item.status}
                    </span>
                </td>
                <td style="padding: 15px;">${item.waktu}</td>
                <td style="padding: 15px; color:#666;">${item.lokasi}</td>
                <td style="padding: 15px; font-size:1.2rem;">${item.bukti}</td>
                <td style="padding: 15px;">
                    <span style="color:#2ecc71; font-weight:600;">${item.verifikasi}</span>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }
    
    // Export absensi
    function exportAbsensi() {
        alert('Fitur export PDF akan membuka halaman download...\n\nDalam implementasi nyata, ini akan generate PDF dengan:\n• Logo Diskominfo\n• Data absensi lengkap\n• Tanda tangan digital\n• QR code verifikasi');
    }
    
    // Navigation functions
    function prevMonth() {
        alert('Bulan sebelumnya');
    }
    
    function nextMonth() {
        alert('Bulan berikutnya');
    }
    
    function loadMoreRiwayat() {
        alert('Memuat data absensi lebih banyak...');
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Start clock
        updateClock();
        setInterval(updateClock, 1000);
        
        // Generate calendar
        generateCalendar();
        
        // Load riwayat
        loadRiwayat();
        
        // Setup drag and drop for file upload
        const uploadZone = document.getElementById('uploadZone');
        uploadZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.background = 'rgba(46, 204, 113, 0.2)';
            this.style.borderColor = '#2ecc71';
        });
        
        uploadZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            if (!uploadedFile) {
                this.style.background = 'rgba(148, 180, 193, 0.05)';
                this.style.borderColor = 'var(--accent)';
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
        
        // Listen for reason text changes
        document.getElementById('alasanText').addEventListener('input', checkSubmitEligibility);
    });
</script>

<style>
    /* Drag and drop styles */
    #uploadZone.drag-over {
        background: rgba(46, 204, 113, 0.2) !important;
        border-color: #2ecc71 !important;
        transform: scale(1.02);
    }
</style>
@endsection