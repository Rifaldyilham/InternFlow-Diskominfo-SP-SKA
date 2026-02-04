@extends('layouts.peserta')

@section('title', 'Absensi Magang')

@section('content')
<div class="form-card">
    <h2 style="color: var(--primary); margin-bottom: 30px; display: flex; align-items: center; gap: 10px;">
        <i class='bx bx-calendar-check'></i> Absensi Harian Magang
    </h2>

    @if(isset($infoMessage))
        <div id="absensiAlert" style="background: #e6f2ff; border: 1px solid #3498db; color: #1f4e79; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px;">
            {{ $infoMessage }}
        </div>
    @else
        <div id="absensiAlert" style="display: none; background: #e6f2ff; border: 1px solid #3498db; color: #1f4e79; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px;"></div>
    @endif
    
    <!-- Statistik Absensi -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, rgba(33, 52, 72, 0.1) 0%, rgba(84, 119, 146, 0.1) 100%); padding: 20px; border-radius: 12px;">
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary);">{{ $total }}</div>
            <div style="color: #666;">Total Hari</div>
        </div>
        <div style="background: linear-gradient(135deg, rgba(46, 213, 115, 0.1) 0%, rgba(39, 174, 96, 0.1) 100%); padding: 20px; border-radius: 12px;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #2ecc71;">{{ $hadir }}</div>
            <div style="color: #666;">Hadir</div>
        </div>
        <div style="background: linear-gradient(135deg, rgba(241, 196, 15, 0.1) 0%, rgba(243, 156, 18, 0.1) 100%); padding: 20px; border-radius: 12px;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #f1c40f;">{{ $izin }}</div>
            <div style="color: #666;">Izin</div>
        </div>
        <div style="background: linear-gradient(135deg, rgba(231, 76, 60, 0.1) 0%, rgba(192, 57, 43, 0.1) 100%); padding: 20px; border-radius: 12px;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #e74c3c;">{{ $alpha }}</div>
            <div style="color: #666;">Alpha</div>
        </div>
    </div>
    
    <!-- Form Absensi Hari Ini -->
    @if(!isset($infoMessage))
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
                    <label id="uploadLabel">Upload Bukti *</label>
                    <div class="upload-area" onclick="document.getElementById('buktiFile').click()" id="uploadZone">
                        <div class="upload-icon">
                            <i class='bx bx-cloud-upload'></i>
                        </div>
                        <div style="font-weight: 600; margin-bottom: 5px;">Klik atau drag file ke sini</div>
                        <small>Format PDF/JPG/PNG, max 2MB. </small>
                        <input type="file" id="buktiFile" accept=".jpg,.jpeg,.png,.pdf" style="display:none;" onchange="previewBukti(this)">
                    </div>
                    <div id="buktiPreview" style="margin-top: 10px;"></div>
                </div>

                <!--izin notes -->
                <div id="izinNotes" style="display: none;">
                    <div class="form-group">
                        <label>Alasan Izin/Sakit *</label>
                        <textarea id="alasanText" rows="3" placeholder="Jelaskan alasan izin/sakit secara detail..."></textarea>
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
    @endif

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
            isLocationValid = false;
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
                alert('File terlalu besar! Maksimal 2MB.');
                input.value = '';
                preview.innerHTML = '';
                uploadedFile = null;
                checkSubmitEligibility();
                return;
            }
            
            // Check file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            if (!validTypes.includes(uploadedFile.type)) {
                alert('Format file tidak valid! Hanya JPG/PNG/PDF yang diperbolehkan.');
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
                            <div style="font-size: 0.9rem; color: #666;">${fileSize} MB - ${uploadedFile.type}</div>
                        </div>
                        <button onclick="removeFile()" style="background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 1.2rem;">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                </div>
            `;
            
            if (uploadedFile.type !== 'application/pdf') {
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
            }
            
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
    if (!currentAbsensiStatus) {
        alert('Pilih status absensi dulu');
        return;
    }

    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bx bx-loader-circle bx-spin"></i> Mengirim...';
    submitBtn.disabled = true;

    const formData = new FormData();
    formData.append('status', currentAbsensiStatus);

    // HADIR -> wajib lokasi + bukti
    if (currentAbsensiStatus === 'hadir') {
        if (!uploadedFile) {
            alert('Upload bukti wajib');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            return;
        }

        formData.append('bukti_kegiatan', uploadedFile);
        formData.append('lokasi', JSON.stringify(userLocation));
    }

    // IZIN / SAKIT -> wajib alasan + bukti surat
    if (currentAbsensiStatus === 'izin' || currentAbsensiStatus === 'sakit') {
        const alasan = document.getElementById('alasanText').value;
        if (alasan.trim().length < 10) {
            alert('Alasan minimal 10 karakter');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            return;
        }

        if (!uploadedFile) {
            alert('Upload surat izin / sakit wajib');
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
                    alertBox.textContent = message;
                    alertBox.style.display = 'block';
                }
            } else {
                alert(message);
            }
            throw new Error(message);
        }

        return payload;
    })
    .then(data => {
        alert(data?.message || 'Absen berhasil');
        window.location.reload();
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
