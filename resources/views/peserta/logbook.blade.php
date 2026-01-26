@extends('layouts.peserta')

@section('title', 'Logbook Harian')

@section('styles')
<link rel="stylesheet" href="{{ asset('peserta/peserta.css') }}">
@endsection

@section('content')
<div class="form-card">
    <h2 class="form-title">
        <i class='bx bx-book'></i> Logbook Harian Magang
    </h2>
    
    <!-- Loading State -->
    <div id="loadingState" class="text-center py-8">
        <i class='bx bx-loader-circle bx-spin text-4xl text-primary'></i>
        <div class="text-gray-600 mt-4">Memuat data logbook...</div>
    </div>
    
    <!-- Not Verified State -->
    <div id="notVerifiedState" class="hidden">
        <div class="info-alert">
            <i class='bx bx-info-circle'></i>
            <div>
                <strong>Fitur Logbook Belum Tersedia</strong>
                <p>Fitur logbook hanya tersedia setelah pengajuan magang Anda diverifikasi oleh admin. Silakan tunggu hingga pengajuan magang Anda disetujui.</p>
            </div>
        </div>
    </div>
    
    <!-- Finished Magang State -->
    <div id="finishedState" class="hidden">
        <div class="success-alert">
            <i class='bx bx-check-circle'></i>
            <div>
                <strong>Magang Telah Selesai</strong>
                <p>Masa magang Anda telah selesai. Fitur logbook dan absensi sudah tidak tersedia. Silakan cek sertifikat dan penilaian di halaman yang sesuai.</p>
            </div>
        </div>
    </div>
    
    <!-- Active Magang State -->
    <div id="activeState" class="hidden">
        <!-- Statistik Logbook -->
        <div class="stats-grid">
            <div class="stat-card border-blue">
                <div class="stat-header">
                    <div class="stat-icon blue">
                        <i class='bx bx-calendar'></i>
                    </div>
                    <div class="stat-value" id="totalHari">0</div>
                </div>
                <div class="stat-label">Total Hari</div>
            </div>
            <div class="stat-card border-green">
                <div class="stat-header">
                    <div class="stat-icon green">
                        <i class='bx bx-check'></i>
                    </div>
                    <div class="stat-value" id="sudahDiisi">0</div>
                </div>
                <div class="stat-label">Sudah Diisi</div>
            </div>
            <div class="stat-card border-orange">
                <div class="stat-header">
                    <div class="stat-icon orange">
                        <i class='bx bx-time'></i>
                    </div>
                    <div class="stat-value" id="menungguVerifikasi">0</div>
                </div>
                <div class="stat-label">Menunggu</div>
            </div>
            <div class="stat-card border-purple">
                <div class="stat-header">
                    <div class="stat-icon purple">
                        <i class='bx bx-file'></i>
                    </div>
                    <div class="stat-value" id="belumDiisi">0</div>
                </div>
                <div class="stat-label">Belum Diisi</div>
            </div>
        </div>
        
        <!-- Form Isi Logbook -->
        <div id="logbookForm" class="mb-8">
            <div class="flex justify-between items-start mb-6">
                <h3 class="section-title">
                    <i class='bx bx-edit'></i> Isi Logbook
                </h3>
                <div class="text-right">
                    <div class="text-sm text-gray-600 mb-1">Status Magang:</div>
                    <span class="status-badge status-active" id="magangStatusText">Sedang Berjalan</span>
                </div>
            </div>
            
            <!-- Tanggal Picker -->
            <div class="form-section">
                <h4 class="section-title">
                    <i class='bx bx-calendar'></i> Pilih Tanggal Kegiatan
                </h4>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="tanggalKegiatan">Tanggal Kegiatan *</label>
                        <div class="relative">
                            <input type="date" id="tanggalKegiatan" 
                                   class="w-full p-3 pl-10 border border-gray-300 rounded-lg"
                                   min="" max="">
                            <i class='bx bx-calendar absolute left-3 top-3 text-gray-400'></i>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">Pilih tanggal untuk mengisi logbook</div>
                        
                        <!-- Calendar Quick Select -->
                        <div class="mt-4">
                            <div class="text-sm text-gray-600 mb-2">Pilih cepat:</div>
                            <div class="calendar-quick-select">
                                <button type="button" onclick="setTanggal('today')" class="quick-date-btn bg-blue-100 text-blue-700">
                                    Hari Ini
                                </button>
                                <button type="button" onclick="setTanggal('yesterday')" class="quick-date-btn bg-gray-100 text-gray-700">
                                    Kemarin
                                </button>
                                <button type="button" onclick="setTanggal('tomorrow')" class="quick-date-btn bg-purple-100 text-purple-700">
                                    Besok
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Informasi Tanggal</label>
                        <div id="tanggalInfo" class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                            <div class="font-semibold text-primary text-lg" id="selectedDateText">Pilih tanggal terlebih dahulu</div>
                            <div class="text-sm text-gray-600 mt-1" id="dayInfo">-</div>
                            <div class="text-xs text-gray-500 mt-2" id="dateStatus">
                                <i class='bx bx-info-circle'></i> Pilih tanggal untuk melihat status
                            </div>
                            
                            <!-- Status Logbook untuk tanggal terpilih -->
                            <div id="logbookStatusInfo" class="mt-3 p-3 rounded-lg hidden">
                                <!-- Info status logbook akan muncul di sini -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Status Kehadiran -->
            <div id="statusSection" class="form-section hidden">
                <h4 class="section-title">
                    <i class='bx bx-user-check'></i> Status Kehadiran
                </h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <button type="button" onclick="setKehadiranStatus('hadir')" 
                            id="btnHadir"
                            class="p-4 border-2 border-green-200 rounded-lg text-left hover:bg-green-50 transition-colors">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class='bx bx-check-circle text-xl text-green-600'></i>
                            </div>
                            <div>
                                <div class="font-bold text-primary">Hadir</div>
                                <div class="text-sm text-gray-600">Bekerja di kantor</div>
                            </div>
                        </div>
                    </button>
                    
                    <button type="button" onclick="setKehadiranStatus('izin')" 
                            id="btnIzin"
                            class="p-4 border-2 border-yellow-200 rounded-lg text-left hover:bg-yellow-50 transition-colors">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class='bx bx-alarm-exclamation text-xl text-yellow-600'></i>
                            </div>
                            <div>
                                <div class="font-bold text-primary">Izin</div>
                                <div class="text-sm text-gray-600">Tidak masuk kantor</div>
                            </div>
                        </div>
                    </button>
                    
                    <button type="button" onclick="setKehadiranStatus('sakit')" 
                            id="btnSakit"
                            class="p-4 border-2 border-red-200 rounded-lg text-left hover:bg-red-50 transition-colors">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class='bx bx-plus-medical text-xl text-red-600'></i>
                            </div>
                            <div>
                                <div class="font-bold text-primary">Sakit</div>
                                <div class="text-sm text-gray-600">Tidak sehat</div>
                            </div>
                        </div>
                    </button>
                </div>
                
                <div class="mt-4" id="selectedStatusInfo">
                    <!-- Status info will be shown here -->
                </div>
            </div>
            
            <!-- Form Logbook (HADIR) -->
            <div id="logbookHadirForm" class="hidden">
                <form id="formLogbookHadir">
                    @csrf
                    <div class="form-group">
                        <label for="kegiatan">Kegiatan Utama Hari Ini *</label>
                        <input type="text" id="kegiatan" name="kegiatan" placeholder="Contoh: Meeting tim IT, pengembangan fitur dashboard" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Kegiatan *</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Jelaskan secara detail apa yang Anda kerjakan hari ini, kendala yang dihadapi, dan solusinya..." required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="hasil">Hasil/Pencapaian</label>
                        <textarea id="hasil" name="hasil" rows="3" placeholder="Apa yang berhasil diselesaikan atau dipelajari hari ini?"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="tantangan">Tantangan/Kendala</label>
                        <textarea id="tantangan" name="tantangan" rows="2" placeholder="Kendala teknis atau non-teknis yang dihadapi"></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="waktu_mulai">Waktu Mulai</label>
                            <input type="time" id="waktu_mulai" name="waktu_mulai" value="08:00">
                        </div>
                        
                        <div class="form-group">
                            <label for="waktu_selesai">Waktu Selesai</label>
                            <input type="time" id="waktu_selesai" name="waktu_selesai" value="16:00">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Upload Bukti Kegiatan (Opsional)</label>
                        <div class="upload-area" onclick="document.getElementById('bukti_file').click()">
                            <div class="upload-icon">
                                <i class='bx bx-image-add'></i>
                            </div>
                            <div style="font-weight: 600; margin-bottom: 5px;">Unggah Foto/Dokumen</div>
                            <small>Format JPG/PNG/PDF, max 5MB</small>
                            <input type="file" id="bukti_file" name="bukti_file" accept=".jpg,.jpeg,.png,.pdf" class="hidden" onchange="previewBukti(this)">
                        </div>
                        <div id="bukti_preview" class="mt-3"></div>
                    </div>
                    
                    <input type="hidden" id="logbook_id" name="logbook_id">
                    <input type="hidden" id="logbook_tanggal" name="tanggal">
                    <input type="hidden" id="logbook_jenis" name="jenis">
                    
                    <div class="form-actions">
                        <button type="button" onclick="submitLogbookHadir()" class="btn btn-primary btn-lg">
                            <i class='bx bx-save'></i> Simpan Logbook
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                            Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Daftar Logbook -->
        <div class="form-card">
            <div class="flex justify-between items-center mb-6">
                <h2 class="form-title">
                    <i class='bx bx-history'></i> Riwayat Logbook
                </h2>
                <div class="flex gap-3">
                    <select id="filterBulan" onchange="filterLogbook()" class="filter-select">
                        <option value="all">Semua Bulan</option>
                        <!-- Options will be populated by JavaScript -->
                    </select>
                    <select id="filterStatus" onchange="filterLogbook()" class="filter-select">
                        <option value="all">Semua Status</option>
                        <option value="verified">Diverifikasi</option>
                        <option value="pending">Menunggu</option>
                        <option value="izin">Izin/Sakit</option>
                    </select>
                </div>
            </div>
            
            <div id="logbookList">
                <!-- Logbook items will be loaded here -->
            </div>
            
            <div id="loadMoreContainer" class="text-center mt-8 hidden">
                <button onclick="loadMore()" class="btn btn-secondary">
                    <i class='bx bx-refresh'></i> Muat Lebih Banyak
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="p-6">
            <div class="text-center mb-6">
                <i class='bx bx-check-circle text-5xl text-green-500 mb-4'></i>
                <h3 class="text-xl font-bold text-primary mb-2">Logbook Berhasil Disimpan!</h3>
                <p class="text-gray-600">Logbook telah disimpan dan menunggu verifikasi mentor.</p>
            </div>
            <div class="text-center">
                <button onclick="closeSuccessModal()" class="btn btn-primary">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="p-6">
            <div class="text-center mb-6">
                <i class='bx bx-edit text-5xl text-blue-500 mb-4'></i>
                <h3 class="text-xl font-bold text-primary mb-2">Edit Logbook</h3>
                <p class="text-gray-600">Logbook sudah ada untuk tanggal ini. Apakah Anda ingin mengedit?</p>
            </div>
            <div class="flex justify-center gap-3">
                <button onclick="loadLogbookForEdit()" class="btn btn-primary">
                    <i class='bx bx-edit'></i> Edit
                </button>
                <button onclick="closeEditModal()" class="btn btn-secondary">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// ============================================
// GLOBAL STATE MANAGEMENT
// ============================================
let state = {
    magangStatus: null,
    magangData: null,
    logbookStats: null,
    logbookList: [],
    currentPage: 1,
    lastPage: 1,
    filters: {
        bulan: 'all',
        status: 'all'
    }
};

// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    checkMagangStatus();
    setupEventListeners();
});

function setupEventListeners() {
    // Date picker change
    document.getElementById('tanggalKegiatan').addEventListener('change', function() {
        state.selectedDate = this.value;
        checkDateStatus();
    });
}

// ============================================
// API FUNCTIONS (Backend akan mengisi ini)
// ============================================

/**
 * 1. Fungsi untuk cek status magang peserta
 * Backend perlu implement:
 * - GET /api/peserta/logbook/status
 * Return: { status: 'not_verified'|'active'|'finished', magang: {...}, stats: {...} }
 */
async function checkMagangStatus() {
    try {
        showLoading();
        
        // INI AKAN DIGANTI DENGAN API CALL DI BACKEND
        // const response = await fetch('/api/peserta/logbook/status');
        // const data = await response.json();
        
        // Contoh response yang diharapkan dari backend:
        // {
        //     "status": "active",
        //     "magang": {
        //         "id": 1,
        //         "bidang": "Informatika",
        //         "mentor": "Dr. Ahmad Fauzi, M.Kom.",
        //         "tanggal_mulai": "2024-01-01",
        //         "tanggal_selesai": "2024-03-30",
        //         "status": "berjalan",
        //         "hari_ke": 16,
        //         "total_hari": 90
        //     },
        //     "stats": {
        //         "total_hari": 15,
        //         "sudah_diisi": 8,
        //         "menunggu": 1,
        //         "belum_diisi": 6
        //     }
        // }
        
        // UNTUK SEKARANG: Tampilkan loading dan tunggu backend
        setTimeout(() => {
            showError('Backend belum diimplement. Silakan hubungi developer.');
        }, 1000);
        
    } catch (error) {
        console.error('Error checking magang status:', error);
        showError('Gagal memuat data. Silakan refresh halaman.');
    }
}

/**
 * 2. Fungsi untuk cek status tanggal
 * Backend perlu implement:
 * - GET /api/peserta/logbook/check?tanggal={date}
 */
async function checkDateStatus() {
    if (!state.selectedDate) return;
    
    // Show status section
    document.getElementById('statusSection').classList.remove('hidden');
    resetKehadiranStatus();
    updateTanggalInfo();
    
    try {
        // INI AKAN DIGANTI DENGAN API CALL DI BACKEND
        // const response = await fetch(`/api/peserta/logbook/check?tanggal=${state.selectedDate}`);
        // const data = await response.json();
        
        // if (data.exists) {
        //     showEditModal(data.logbook);
        // } else {
        //     checkAbsensiStatus();
        // }
        
    } catch (error) {
        console.error('Error checking date status:', error);
        showNotification('error', 'Gagal memeriksa status tanggal.');
    }
}

/**
 * 3. Fungsi untuk cek absensi
 * Backend perlu implement:
 * - GET /api/peserta/absensi/status/{tanggal}
 */
async function checkAbsensiStatus() {
    if (!state.selectedDate) return;
    
    try {
        // INI AKAN DIGANTI DENGAN API CALL DI BACKEND
        // const response = await fetch(`/api/peserta/absensi/status/${state.selectedDate}`);
        // const data = await response.json();
        
        // if (data.status === 'izin' || data.status === 'sakit') {
        //     autoSubmitIzinSakit(data.status, data.alasan);
        // }
        
    } catch (error) {
        console.error('Error checking absensi status:', error);
    }
}

/**
 * 4. Fungsi untuk load logbook list
 * Backend perlu implement:
 * - GET /api/peserta/logbook?page=1&bulan={month}&status={status}
 */
async function loadLogbookList(page = 1) {
    try {
        // INI AKAN DIGANTI DENGAN API CALL DI BACKEND
        // const params = new URLSearchParams({
        //     page: page,
        //     bulan: state.filters.bulan,
        //     status: state.filters.status
        // });
        // const response = await fetch(`/api/peserta/logbook?${params}`);
        // const data = await response.json();
        
        // state.currentPage = data.current_page;
        // state.lastPage = data.last_page;
        // state.logbookList = page === 1 ? data.data : [...state.logbookList, ...data.data];
        // renderLogbookList();
        
        // Tampilkan placeholder
        showLogbookPlaceholder();
        
    } catch (error) {
        console.error('Error loading logbook list:', error);
        showNotification('error', 'Gagal memuat data logbook.');
    }
}

/**
 * 5. Fungsi untuk submit logbook hadir
 * Backend perlu implement:
 * - POST /api/peserta/logbook (create new)
 * - PUT /api/peserta/logbook/{id} (update existing)
 */
async function submitLogbookHadir() {
    if (!validateFormHadir()) return;
    
    try {
        const formData = new FormData();
        const isEdit = document.getElementById('logbook_id').value !== '';
        
        // Add form data
        formData.append('tanggal', state.selectedDate);
        formData.append('jenis', 'hadir');
        formData.append('kegiatan', document.getElementById('kegiatan').value);
        formData.append('deskripsi', document.getElementById('deskripsi').value);
        formData.append('hasil', document.getElementById('hasil').value);
        formData.append('tantangan', document.getElementById('tantangan').value);
        formData.append('waktu_mulai', document.getElementById('waktu_mulai').value);
        formData.append('waktu_selesai', document.getElementById('waktu_selesai').value);
        
        // Add file if exists
        const fileInput = document.getElementById('bukti_file');
        if (fileInput.files[0]) {
            formData.append('bukti_file', fileInput.files[0]);
        }
        
        if (isEdit) {
            formData.append('_method', 'PUT');
            formData.append('logbook_id', document.getElementById('logbook_id').value);
        }
        
        // INI AKAN DIGANTI DENGAN API CALL DI BACKEND
        // const url = isEdit ? 
        //     `/api/peserta/logbook/${document.getElementById('logbook_id').value}` : 
        //     '/api/peserta/logbook';
        // const response = await fetch(url, {
        //     method: 'POST',
        //     headers: {
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        //         'Accept': 'application/json'
        //     },
        //     body: formData
        // });
        
        // if (response.ok) {
        //     showSuccessModal();
        //     resetForm();
        //     loadLogbookList();
        //     updateStats();
        // } else {
        //     throw new Error('Failed to save logbook');
        // }
        
        // Untuk sekarang tampilkan sukses dummy
        showSuccessModal();
        resetForm();
        
    } catch (error) {
        console.error('Error submitting logbook:', error);
        showNotification('error', 'Gagal menyimpan logbook. Silakan coba lagi.');
    }
}

/**
 * 6. Fungsi untuk submit izin/sakit
 * Backend perlu implement:
 * - POST /api/peserta/logbook/izin-sakit
 */
async function submitIzinSakit(jenis) {
    if (!state.selectedDate) {
        showNotification('error', 'Harap pilih tanggal terlebih dahulu!');
        return;
    }
    
    try {
        // INI AKAN DIGANTI DENGAN API CALL DI BACKEND
        // const response = await fetch('/api/peserta/logbook/izin-sakit', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json',
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        //     },
        //     body: JSON.stringify({
        //         tanggal: state.selectedDate,
        //         jenis: jenis,
        //         alasan: jenis === 'izin' ? 'Izin tidak masuk' : 'Sakit tidak masuk'
        //     })
        // });
        
        // if (response.ok) {
        //     showNotification('success', `${jenis === 'izin' ? 'Izin' : 'Laporan sakit'} berhasil dikirim!`);
        //     resetForm();
        //     loadLogbookList();
        //     updateStats();
        // }
        
        // Untuk sekarang tampilkan notifikasi dummy
        showNotification('success', `${jenis === 'izin' ? 'Izin' : 'Laporan sakit'} berhasil dikirim!`);
        resetForm();
        
    } catch (error) {
        console.error('Error submitting izin/sakit:', error);
        showNotification('error', `Gagal mengirim ${jenis === 'izin' ? 'izin' : 'laporan sakit'}. Silakan coba lagi.`);
    }
}

// ============================================
// HELPER FUNCTIONS (Frontend only)
// ============================================

function showLoading() {
    document.getElementById('loadingState').classList.remove('hidden');
    hideAllStates();
}

function hideAllStates() {
    ['notVerifiedState', 'finishedState', 'activeState'].forEach(id => {
        document.getElementById(id).classList.add('hidden');
    });
}

function showError(message) {
    document.getElementById('loadingState').innerHTML = `
        <div class="text-center py-8">
            <i class='bx bx-error-circle text-4xl text-red-500 mb-4'></i>
            <div class="text-red-600 mb-3">${message}</div>
            <button onclick="location.reload()" class="btn btn-secondary">
                <i class='bx bx-refresh'></i> Muat Ulang
            </button>
        </div>
    `;
}

function validateFormHadir() {
    const kegiatan = document.getElementById('kegiatan').value.trim();
    const deskripsi = document.getElementById('deskripsi').value.trim();
    
    if (!kegiatan) {
        showNotification('error', 'Harap isi kegiatan utama!');
        document.getElementById('kegiatan').focus();
        return false;
    }
    
    if (!deskripsi) {
        showNotification('error', 'Harap isi deskripsi kegiatan!');
        document.getElementById('deskripsi').focus();
        return false;
    }
    
    return true;
}

function setTanggal(type) {
    const dateInput = document.getElementById('tanggalKegiatan');
    const today = new Date();
    let newDate = new Date();
    
    switch(type) {
        case 'today':
            // Already today
            break;
        case 'yesterday':
            newDate.setDate(today.getDate() - 1);
            break;
        case 'tomorrow':
            newDate.setDate(today.getDate() + 1);
            break;
    }
    
    const formattedDate = newDate.toISOString().split('T')[0];
    dateInput.value = formattedDate;
    state.selectedDate = formattedDate;
    
    checkDateStatus();
}

function setKehadiranStatus(status) {
    state.currentStatus = status;
    resetKehadiranStatus();
    
    const btn = document.getElementById(`btn${status.charAt(0).toUpperCase() + status.slice(1)}`);
    
    if (status === 'hadir') {
        btn.style.background = '#e6fff3';
        btn.style.borderColor = '#2ecc71';
        
        document.getElementById('selectedStatusInfo').innerHTML = `
            <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                <div class="flex items-center gap-3">
                    <i class='bx bx-check-circle text-2xl text-green-600'></i>
                    <div>
                        <div class="font-bold text-green-700">Status: Hadir</div>
                        <div class="text-sm text-green-600">Silakan isi logbook kegiatan hari ini</div>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('logbookHadirForm').classList.remove('hidden');
        
    } else if (status === 'izin') {
        btn.style.background = '#fff9e6';
        btn.style.borderColor = '#f1c40f';
        
        document.getElementById('selectedStatusInfo').innerHTML = `
            <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500">
                <div class="flex items-center gap-3">
                    <i class='bx bx-alarm-exclamation text-2xl text-yellow-600'></i>
                    <div>
                        <div class="font-bold text-yellow-700">Status: Izin</div>
                        <div class="text-sm text-yellow-600">Izin akan dikirim otomatis</div>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('logbookHadirForm').classList.add('hidden');
        submitIzinSakit('izin');
        
    } else { // sakit
        btn.style.background = '#ffe6e6';
        btn.style.borderColor = '#e74c3c';
        
        document.getElementById('selectedStatusInfo').innerHTML = `
            <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-500">
                <div class="flex items-center gap-3">
                    <i class='bx bx-plus-medical text-2xl text-red-600'></i>
                    <div>
                        <div class="font-bold text-red-700">Status: Sakit</div>
                        <div class="text-sm text-red-600">Laporan sakit akan dikirim otomatis</div>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('logbookHadirForm').classList.add('hidden');
        submitIzinSakit('sakit');
    }
}

function resetKehadiranStatus() {
    ['Hadir', 'Izin', 'Sakit'].forEach(status => {
        const btn = document.getElementById(`btn${status}`);
        if (btn) {
            btn.style.background = '';
            btn.style.borderColor = status === 'Hadir' ? '#d1fae5' : 
                                  status === 'Izin' ? '#fef3c7' : 
                                  '#fee2e2';
        }
    });
    
    document.getElementById('logbookHadirForm').classList.add('hidden');
    document.getElementById('selectedStatusInfo').innerHTML = '';
}

function resetForm() {
    document.getElementById('kegiatan').value = '';
    document.getElementById('deskripsi').value = '';
    document.getElementById('hasil').value = '';
    document.getElementById('tantangan').value = '';
    document.getElementById('waktu_mulai').value = '08:00';
    document.getElementById('waktu_selesai').value = '16:00';
    document.getElementById('bukti_file').value = '';
    document.getElementById('bukti_preview').innerHTML = '';
    document.getElementById('logbook_id').value = '';
    
    // Reset tanggal to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggalKegiatan').value = today;
    state.selectedDate = today;
    
    updateTanggalInfo();
    resetKehadiranStatus();
    document.getElementById('statusSection').classList.add('hidden');
}

function updateTanggalInfo() {
    if (!state.selectedDate) return;
    
    const dateObj = new Date(state.selectedDate);
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const dateText = dateObj.toLocaleDateString('id-ID', options);
    
    document.getElementById('selectedDateText').textContent = dateText;
    
    // Calculate day info if magang data exists
    if (state.magangData) {
        const startDate = new Date(state.magangData.tanggal_mulai);
        const diffTime = Math.abs(dateObj - startDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        
        document.getElementById('dayInfo').textContent = `Hari ke-${diffDays} dari ${state.magangData.total_hari} hari magang`;
    }
    
    // Check if date is in the past or future
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const selected = new Date(state.selectedDate);
    selected.setHours(0, 0, 0, 0);
    
    let statusInfo = '';
    if (selected > today) {
        statusInfo = '<span class="text-blue-600"><i class="bx bx-calendar-plus"></i> Tanggal di masa depan</span>';
    } else if (selected < today) {
        statusInfo = '<span class="text-green-600"><i class="bx bx-calendar-check"></i> Tanggal di masa lalu</span>';
    } else {
        statusInfo = '<span class="text-primary"><i class="bx bx-calendar-star"></i> Hari ini</span>';
    }
    
    document.getElementById('dateStatus').innerHTML = statusInfo;
}

function showLogbookPlaceholder() {
    const container = document.getElementById('logbookList');
    container.innerHTML = `
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class='bx bx-book-open'></i>
            </div>
            <h4 class="text-lg font-semibold text-gray-700 mb-2">Backend Belum Tersedia</h4>
            <p class="text-gray-500 mb-4">Fitur logbook memerlukan implementasi backend terlebih dahulu.</p>
            <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                <div class="font-semibold text-blue-700 mb-2">Endpoints yang perlu diimplement:</div>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li><code>GET /api/peserta/logbook/status</code> - Status magang & stats</li>
                    <li><code>GET /api/peserta/logbook/check?tanggal=...</code> - Cek logbook per tanggal</li>
                    <li><code>POST /api/peserta/logbook</code> - Simpan logbook baru</li>
                    <li><code>PUT /api/peserta/logbook/{id}</code> - Update logbook</li>
                    <li><code>GET /api/peserta/logbook</code> - List logbook dengan filter</li>
                </ul>
            </div>
        </div>
    `;
}

function showEditModal(logbook) {
    // Simpan data logbook ke state
    state.currentLogbook = logbook;
    document.getElementById('logbook_id').value = logbook.id;
    
    // Show edit modal
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    state.currentLogbook = null;
    resetForm();
}

function loadLogbookForEdit() {
    if (!state.currentLogbook) return;
    
    // Fill form with existing data
    const logbook = state.currentLogbook;
    
    document.getElementById('tanggalKegiatan').value = logbook.tanggal;
    state.selectedDate = logbook.tanggal;
    document.getElementById('logbook_id').value = logbook.id;
    
    if (logbook.jenis === 'hadir') {
        setKehadiranStatus('hadir');
        document.getElementById('kegiatan').value = logbook.kegiatan;
        document.getElementById('deskripsi').value = logbook.deskripsi;
        document.getElementById('hasil').value = logbook.hasil || '';
        document.getElementById('tantangan').value = logbook.tantangan || '';
        document.getElementById('waktu_mulai').value = logbook.waktu_mulai || '08:00';
        document.getElementById('waktu_selesai').value = logbook.waktu_selesai || '16:00';
    } else {
        setKehadiranStatus(logbook.jenis);
    }
    
    closeEditModal();
    updateTanggalInfo();
}

function previewBukti(input) {
    const preview = document.getElementById('bukti_preview');
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileName = file.name;
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        
        if (fileSize > 5) {
            showNotification('error', 'File terlalu besar! Maksimal 5MB.');
            input.value = '';
            preview.innerHTML = '';
            return;
        }
        
        preview.innerHTML = `
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class='bx bx-file text-xl text-blue-600'></i>
                        <div>
                            <div class="font-semibold text-primary">${fileName}</div>
                            <div class="text-sm text-gray-600">${fileSize} MB</div>
                        </div>
                    </div>
                    <button onclick="removeBuktiFile()" class="text-red-500 hover:text-red-700">
                        <i class='bx bx-trash'></i>
                    </button>
                </div>
            </div>
        `;
    }
}

function removeBuktiFile() {
    document.getElementById('bukti_file').value = '';
    document.getElementById('bukti_preview').innerHTML = '';
}

function showSuccessModal() {
    document.getElementById('successModal').classList.remove('hidden');
}

function closeSuccessModal() {
    document.getElementById('successModal').classList.add('hidden');
}

function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-sm ${
        type === 'success' ? 'bg-green-100 border-l-4 border-green-500 text-green-700' :
        type === 'error' ? 'bg-red-100 border-l-4 border-red-500 text-red-700' :
        'bg-blue-100 border-l-4 border-blue-500 text-blue-700'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class='bx ${
                type === 'success' ? 'bx-check-circle text-xl' :
                type === 'error' ? 'bx-x-circle text-xl' :
                'bx-info-circle text-xl'
            } mr-3'></i>
            <div>
                <div class="font-bold">${type === 'success' ? 'Sukses!' : type === 'error' ? 'Error!' : 'Info'}</div>
                <div class="text-sm">${message}</div>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

function filterLogbook() {
    const bulan = document.getElementById('filterBulan').value;
    const status = document.getElementById('filterStatus').value;
    
    state.filters = { bulan, status };
    state.currentPage = 1;
    loadLogbookList();
}

function loadMore() {
    if (state.currentPage < state.lastPage) {
        state.currentPage++;
        loadLogbookList(state.currentPage);
    }
}

// Initialize
function initializeActiveState() {
    // Update form date limits
    const startDate = document.getElementById('tanggalKegiatan');
    if (state.magangData) {
        startDate.min = state.magangData.tanggal_mulai;
        startDate.max = state.magangData.tanggal_selesai;
        
        // Set default date to today
        const today = new Date().toISOString().split('T')[0];
        if (today >= state.magangData.tanggal_mulai && today <= state.magangData.tanggal_selesai) {
            startDate.value = today;
            state.selectedDate = today;
        } else {
            startDate.value = state.magangData.tanggal_mulai;
            state.selectedDate = state.magangData.tanggal_mulai;
        }
    }
    
    // Update stats
    if (state.logbookStats) {
        document.getElementById('totalHari').textContent = state.logbookStats.total_hari;
        document.getElementById('sudahDiisi').textContent = state.logbookStats.sudah_diisi;
        document.getElementById('menungguVerifikasi').textContent = state.logbookStats.menunggu;
        document.getElementById('belumDiisi').textContent = state.logbookStats.belum_diisi;
    }
    
    // Load logbook list
    loadLogbookList();
    
    // Update tanggal info
    updateTanggalInfo();
}
</script>
@endsection