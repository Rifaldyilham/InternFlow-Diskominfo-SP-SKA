@extends('layouts.peserta')

@section('title', 'Logbook Harian')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/peserta/peserta.css') }}">
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
            
            <!-- Form Logbook (HADIR) -->
            <div id="logbookHadirForm">
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
                        <label>Upload Bukti Kegiatan *</label>
                        <div class="upload-area" onclick="document.getElementById('bukti_file').click()">
                            <div class="upload-icon">
                                <i class='bx bx-image-add'></i>
                            </div>
                            <div style="font-weight: 600; margin-bottom: 5px;">Unggah Foto/Dokumen</div>
                            <small>Format JPG/PNG/PDF, max 5MB</small>
                            <input type="file" id="bukti_file" name="bukti_file" accept=".jpg,.jpeg,.png,.pdf" class="hidden" required onchange="previewBukti(this)">
                        </div>
                        <div id="bukti_preview" class="mt-3"></div>
                    </div>
                    
                    <input type="hidden" id="logbook_id" name="logbook_id">
                    <input type="hidden" id="logbook_tanggal" name="tanggal">
                    
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

        const response = await fetch('/api/peserta/logbook/status', {
            headers: { 'Accept': 'application/json' }
        });
        if (!response.ok) {
            throw new Error('Gagal memuat status logbook.');
        }
        const data = await response.json();

        document.getElementById('loadingState').classList.add('hidden');
        hideAllStates();

        if (!data.status_verifikasi || data.status_verifikasi !== 'terverifikasi') {
            document.getElementById('notVerifiedState').classList.remove('hidden');
            return;
        }

        if (data.status_magang === 'nonaktif') {
            document.getElementById('finishedState').classList.remove('hidden');
            return;
        }

        state.magangData = data.magang || null;
        state.logbookStats = data.stats || null;
        document.getElementById('activeState').classList.remove('hidden');
        initializeActiveState();
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
        const params = new URLSearchParams({
            page: page,
            bulan: state.filters.bulan,
            status: state.filters.status
        });
        const response = await fetch(`/api/peserta/logbook?${params}`, {
            headers: { 'Accept': 'application/json' }
        });
        if (!response.ok) {
            throw new Error('Gagal memuat data logbook.');
        }
        const data = await response.json();

        state.currentPage = data.current_page || 1;
        state.lastPage = data.last_page || 1;
        state.logbookList = page === 1 ? (data.data || []) : [...state.logbookList, ...(data.data || [])];
        renderLogbookList();
        
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
        
        // Add form data
        formData.append('tanggal', state.selectedDate);
        formData.append('kegiatan', document.getElementById('kegiatan').value);
        formData.append('deskripsi', document.getElementById('deskripsi').value);
        
        // Add file if exists
        const fileInput = document.getElementById('bukti_file');
        if (fileInput.files[0]) {
            formData.append('bukti_file', fileInput.files[0]);
        }

        const response = await fetch('/api/peserta/logbook', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        });

        if (!response.ok) {
            const data = await response.json().catch(() => ({}));
            throw new Error(data.message || 'Gagal menyimpan logbook.');
        }

        showSuccessModal();
        resetForm();
        loadLogbookList();
        
    } catch (error) {
        console.error('Error submitting logbook:', error);
        showNotification('error', error.message || 'Gagal menyimpan logbook. Silakan coba lagi.');
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
    const fileInput = document.getElementById('bukti_file');
    
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

    if (!fileInput.files[0]) {
        showNotification('error', 'Harap upload bukti kegiatan!');
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


function resetForm() {
    document.getElementById('kegiatan').value = '';
    document.getElementById('deskripsi').value = '';
    document.getElementById('bukti_file').value = '';
    document.getElementById('bukti_preview').innerHTML = '';
    document.getElementById('logbook_id').value = '';
    
    // Reset tanggal to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggalKegiatan').value = today;
    state.selectedDate = today;
    
    updateTanggalInfo();
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

function renderLogbookList() {
    const container = document.getElementById('logbookList');
    const loadMoreContainer = document.getElementById('loadMoreContainer');

    if (!state.logbookList || state.logbookList.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class='bx bx-book-open'></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Logbook</h4>
                <p class="text-gray-500 mb-4">Silakan isi logbook harian untuk melihat riwayatnya.</p>
            </div>
        `;
        if (loadMoreContainer) loadMoreContainer.classList.add('hidden');
        return;
    }

    const itemsHtml = state.logbookList.map(item => {
        const statusText = getLogbookStatusText(item.status);
        const statusClass = getLogbookStatusClass(item.status);
        const tanggal = formatDate(item.tanggal);

        return `
            <div class="logbook-item">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="text-sm text-gray-500">${tanggal}</div>
                        <div class="font-semibold text-primary">${item.kegiatan}</div>
                    </div>
                    <span class="status-badge ${statusClass}">${statusText}</span>
                </div>
                <div class="text-gray-700 text-sm">
                    ${item.deskripsi || '-'}
                </div>
            </div>
        `;
    }).join('');

    container.innerHTML = itemsHtml;

    if (loadMoreContainer) {
        if (state.currentPage < state.lastPage) {
            loadMoreContainer.classList.remove('hidden');
        } else {
            loadMoreContainer.classList.add('hidden');
        }
    }
}

function getLogbookStatusText(status) {
    if (status === 'diverifikasi') return 'Diverifikasi';
    if (status === 'ditolak') return 'Ditolak';
    return 'Menunggu';
}

function getLogbookStatusClass(status) {
    if (status === 'diverifikasi') return 'status-approved';
    if (status === 'ditolak') return 'status-rejected';
    return 'status-pending';
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
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
