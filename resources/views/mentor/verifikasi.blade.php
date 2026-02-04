@extends('layouts.mentor')

@section('title', $pageTitle ?? 'Verifikasi Peserta')
@section('subtitle', $pageSubtitle ?? 'Verifikasi logbook dan absensi peserta')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/mentor/mentor.css') }}">
@endsection

@section('content')
<div class="mentor-dashboard">
    <!-- Info Peserta yang Dipilih -->
    <div id="pesertaInfo" class="peserta-info-card mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="peserta-info-avatar" id="selectedPesertaAvatar">
                    <!-- Avatar akan diisi JavaScript -->
                </div>
                <div>
                    <h3 id="selectedPesertaName" class="text-xl font-bold text-white"></h3>
                    <div class="peserta-info-meta">
                        <span id="selectedPesertaNim"></span>
                        <span id="selectedPesertaUniv"></span>
                        <span id="selectedPesertaBidang"></span>
                    </div>
                </div>
            </div>
            <div class="peserta-info-right">
                <a href="/mentor/bimbingan" class="btn btn-white">
                    <i class='bx bx-arrow-back'></i> Kembali
                </a>
                <button onclick="clearSelectedPeserta()" class="text-white hover:text-gray-200">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="tabs-mentor mb-6">
        <button id="tab-logbook" class="tab-mentor active" onclick="switchTab('logbook')">
            <i class='bx bx-book mr-2'></i> Logbook
            <span class="tab-badge" id="logbookBadge">0</span>
        </button>
        <button id="tab-absensi" class="tab-mentor" onclick="switchTab('absensi')">
            <i class='bx bx-calendar-check mr-2'></i> Absensi
            <span class="tab-badge" id="absensiBadge">0</span>
        </button>
    </div>

    <!-- Tab Content: Logbook -->
    <div id="logbook-content" class="tab-content-mentor active">
        <div class="filter-container-mentor mb-6">
            <div class="filter-grid-mentor">
                <div class="filter-group-mentor">
                    <label for="searchLogbook" class="filter-label-mentor">
                        <i class='bx bx-search'></i> Pencarian Logbook
                    </label>
                    <input type="text" id="searchLogbook" placeholder="Cari kegiatan atau deskripsi..." 
                           class="filter-input-mentor">
                </div>
                
                <div class="filter-group-mentor">
                    <label for="dateLogbook" class="filter-label-mentor">
                        <i class='bx bx-calendar'></i> Tanggal
                    </label>
                    <input type="date" id="dateLogbook" class="filter-input-mentor">
                </div>
                
                <div class="filter-group-mentor">
                    <label for="statusLogbook" class="filter-label-mentor">
                        <i class='bx bx-filter-alt'></i> Status
                    </label>
                    <select id="statusLogbook" class="filter-select-mentor">
                        <option value="all">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Disetujui</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </div>
            </div>
            
            <div class="flex gap-3 mt-4">
                <button onclick="filterLogbookData()" class="btn btn-primary">
                    <i class='bx bx-filter'></i> Terapkan Filter
                </button>
                <button onclick="resetLogbookFilter()" class="btn btn-secondary">
                    <i class='bx bx-reset'></i> Reset
                </button>
            </div>
        </div>

        <div class="mentor-table-container">
            <div class="mentor-table-header">
                <h3>Daftar Logbook</h3>
                <span class="mentor-table-count" id="logbookCount">0 logbook</span>
            </div>
            
            <table class="mentor-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kegiatan</th>
                        <th>Waktu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="logbookTableBody">
                    <!-- Data akan diisi oleh JavaScript -->
                    <tr id="loadingRow">
                        <td colspan="5" class="text-center py-12">
                            <div class="loading-skeleton-mentor flex flex-col items-center gap-5">
                                <i class='bx bx-loader-circle bx-spin text-4xl text-primary'></i>
                                <div class="text-center text-gray-600">
                                    <div class="font-semibold mb-2">Memuat data logbook...</div>
                                    <div class="text-sm">Mohon tunggu sebentar</div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="pagination-mentor">
                <div class="pagination-info-mentor" id="logbookPageInfo">
                    Menampilkan 0 - 0 dari 0 logbook
                </div>
                <div class="pagination-controls-mentor">
                    <button id="logbookPrevPage" class="pagination-btn-mentor" disabled>
                        <i class='bx bx-chevron-left'></i>
                    </button>
                    <button id="logbookNextPage" class="pagination-btn-mentor" disabled>
                        <i class='bx bx-chevron-right'></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Content: Absensi -->
    <div id="absensi-content" class="tab-content-mentor">
        <div class="filter-container-mentor mb-6">
            <div class="filter-grid-mentor">
                <div class="filter-group-mentor">
                    <label for="searchAbsensi" class="filter-label-mentor">
                        <i class='bx bx-search'></i> Pencarian Absensi
                    </label>
                    <input type="text" id="searchAbsensi" placeholder="Cari berdasarkan status atau lokasi..." 
                           class="filter-input-mentor">
                </div>
                
                <div class="filter-group-mentor">
                    <label for="dateAbsensi" class="filter-label-mentor">
                        <i class='bx bx-calendar'></i> Tanggal
                    </label>
                    <input type="date" id="dateAbsensi" class="filter-input-mentor">
                </div>
                
                <div class="filter-group-mentor">
                    <label for="statusAbsensi" class="filter-label-mentor">
                        <i class='bx bx-filter-alt'></i> Status
                    </label>
                    <select id="statusAbsensi" class="filter-select-mentor">
                        <option value="all">Semua Status</option>
                        <option value="hadir">Hadir</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                        <option value="alpha">Alpha</option>
                    </select>
                </div>
            </div>
            
            <div class="flex gap-3 mt-4">
                <button onclick="filterAbsensiData()" class="btn btn-primary">
                    <i class='bx bx-filter'></i> Terapkan Filter
                </button>
                <button onclick="resetAbsensiFilter()" class="btn btn-secondary">
                    <i class='bx bx-reset'></i> Reset
                </button>
            </div>
        </div>

        <div class="mentor-table-container">
            <div class="mentor-table-header">
                <h3>Daftar Absensi</h3>
                <span class="mentor-table-count" id="absensiCount">0 absensi</span>
            </div>
            
            <table class="mentor-table">
                <thead>
                    <tr>
                        <th>Peserta</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Waktu</th>
                        <th>Lokasi</th>
                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="absensiTableBody">
                    <!-- Data akan diisi oleh JavaScript -->
                    <tr id="loadingRowAbsensi">
                        <td colspan="5" class="text-center py-12">
                            <div class="loading-skeleton-mentor flex flex-col items-center gap-5">
                                <i class='bx bx-loader-circle bx-spin text-4xl text-primary'></i>
                                <div class="text-center text-gray-600">
                                    <div class="font-semibold mb-2">Memuat data absensi...</div>
                                    <div class="text-sm">Mohon tunggu sebentar</div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="pagination-mentor">
                <div class="pagination-info-mentor" id="absensiPageInfo">
                    Menampilkan 0 - 0 dari 0 absensi
                </div>
                <div class="pagination-controls-mentor">
                    <button id="absensiPrevPage" class="pagination-btn-mentor" disabled>
                        <i class='bx bx-chevron-left'></i>
                    </button>
                    <button id="absensiNextPage" class="pagination-btn-mentor" disabled>
                        <i class='bx bx-chevron-right'></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Logbook -->
<div id="logbookModal" class="modal-mentor">
    <div class="modal-content-mentor" style="max-width: 800px;">
        <div class="modal-header-mentor">
            <h3 class="modal-title-mentor" id="logbookModalTitle">Detail Logbook</h3>
            <button class="modal-close-mentor" onclick="closeLogbookModal()">&times;</button>
        </div>
        <div class="modal-body-mentor">
            <div id="logbookModalContent">
                <!-- Konten akan diisi oleh JavaScript -->
            </div>
        </div>
        <div class="modal-footer-mentor">
            <button type="button" class="btn btn-secondary" onclick="closeLogbookModal()">Tutup</button>
            <button type="button" class="btn btn-primary" id="verifyBtn" onclick="openVerificationModal()">
                <i class='bx bx-check'></i> Verifikasi
            </button>
        </div>
    </div>
</div>

<!-- Modal Verifikasi Logbook -->
<div id="verificationModal" class="modal-mentor">
    <div class="modal-content-mentor" style="max-width: 600px;">
        <div class="modal-header-mentor">
            <h3 class="modal-title-mentor">Verifikasi Logbook</h3>
            <button class="modal-close-mentor" onclick="closeVerificationModal()">&times;</button>
        </div>
        <div class="modal-body-mentor">
            <form id="verificationForm">
                @csrf
                <input type="hidden" id="logbookId" name="logbook_id">
                
                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                    <h4 class="font-bold text-primary mb-2" id="verificationPesertaName"></h4>
                    <div class="text-sm text-gray-600">
                        <div id="verificationLogbookInfo"></div>
                    </div>
                </div>
                
                <div class="form-group-mentor">
                    <label class="form-label-mentor">Status Verifikasi *</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="verification-option">
                            <input type="radio" name="status" value="approved" required>
                            <div class="verification-card">
                                <i class='bx bx-check-circle text-2xl text-green-600'></i>
                                <div>
                                    <div class="font-semibold">Disetujui</div>
                                    <div class="text-xs text-gray-500">Logbook diterima</div>
                                </div>
                            </div>
                        </label>
                        <label class="verification-option">
                            <input type="radio" name="status" value="rejected" required>
                            <div class="verification-card">
                                <i class='bx bx-x-circle text-2xl text-red-600'></i>
                                <div>
                                    <div class="font-semibold">Ditolak</div>
                                    <div class="text-xs text-gray-500">Logbook ditolak</div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="form-group-mentor">
                    <label for="catatan" class="form-label-mentor">Catatan (Opsional)</label>
                    <textarea id="catatan" name="catatan" rows="4" 
                              class="form-textarea-mentor" 
                              placeholder="Berikan catatan atau masukan untuk peserta..."></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer-mentor">
            <button type="button" class="btn btn-secondary" onclick="closeVerificationModal()">Batal</button>
            <button type="submit" form="verificationForm" class="btn btn-primary">
                <i class='bx bx-check'></i> Simpan Verifikasi
            </button>
        </div>
    </div>
</div>

<!-- Modal Detail Absensi -->
<div id="absensiModal" class="modal-mentor">
    <div class="modal-content-mentor" style="max-width: 800px;">
        <div class="modal-header-mentor">
            <h3 class="modal-title-mentor" id="absensiModalTitle">Detail Absensi</h3>
            <button class="modal-close-mentor" onclick="closeAbsensiModal()">&times;</button>
        </div>
        <div class="modal-body-mentor">
            <div id="absensiModalContent">
                <!-- Konten akan diisi oleh JavaScript -->
            </div>
        </div>
        <div class="modal-footer-mentor">
            <button type="button" class="btn btn-secondary" onclick="closeAbsensiModal()">Tutup</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
window.initialTab = @json($initialTab ?? 'logbook');
// ============================
// KONFIGURASI API (Backend-ready)
// ============================
const API_CONFIG = {
    baseUrl: window.location.origin,
    endpoints: {
        // Data dari URL parameter (peserta yang dipilih)
        detailPeserta: '/api/mentor/peserta',
        // Logbook peserta yang dipilih
        logbookPeserta: '/api/mentor/logbook',
        // Verifikasi logbook
        verifyLogbook: '/api/mentor/logbook/verify',
        // Absensi peserta yang dipilih
        absensiPeserta: '/api/mentor/absensi',
        // Statistik verifikasi
        statsVerifikasi: '/api/mentor/verifikasi/stats'
    }
};

// ============================
// STATE MANAGEMENT
// ============================
let state = {
    selectedPeserta: null,  // Peserta yang dipilih dari halaman bimbingan
    currentTab: 'logbook',   // Tab aktif
    // Logbook state
    logbookList: [],
    filteredLogbook: [],
    logbookCurrentPage: 1,
    logbookItemsPerPage: 10,
    logbookTotalItems: 0,
    // Absensi state
    absensiList: [],
    filteredAbsensi: [],
    absensiCurrentPage: 1,
    absensiItemsPerPage: 10,
    absensiTotalItems: 0,
    // Filters
    logbookFilters: {
        search: '',
        date: '',
        status: 'all'
    },
    absensiFilters: {
        search: '',
        date: '',
        status: 'all'
    },
    currentLogbook: null
};

// ============================
// INISIALISASI
// ============================
document.addEventListener('DOMContentLoaded', function() {
    setupCSRF();
    checkSelectedPeserta();
    setupEventListeners();
    if (window.initialTab && ['logbook', 'absensi'].includes(window.initialTab)) {
        switchTab(window.initialTab);
    }
});

function setupCSRF() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    if (token) {
        window.csrfToken = token;
    }
}

function checkSelectedPeserta() {
    // Cek parameter URL untuk peserta yang dipilih
    const urlParams = new URLSearchParams(window.location.search);
    const pesertaId = urlParams.get('peserta');
    const type = urlParams.get('type'); // 'logbook' atau 'absensi'
    
    if (pesertaId) {
        loadSelectedPeserta(pesertaId, type);
    } else {
        // Jika tidak ada peserta yang dipilih, coba dari localStorage
        const savedPeserta = localStorage.getItem('selectedPeserta');
        if (savedPeserta) {
            try {
                const peserta = JSON.parse(savedPeserta);
                loadSelectedPeserta(peserta.id, peserta.type);
            } catch (e) {
                console.error('Error parsing saved peserta:', e);
                showNoPesertaSelected();
            }
        } else {
            // Jika tidak ada data sama sekali, tampilkan pesan logbook saja
            showNoPesertaSelected();
            loadAbsensiData();
        }
    }
}

async function loadSelectedPeserta(pesertaId, type = 'logbook') {
    try {
        // Simulate loading
        showLoading('logbook', true);
        
        // **API BACKEND:** GET /api/mentor/peserta/{id}
        const response = await fetch(`${API_CONFIG.endpoints.detailPeserta}/${pesertaId}`, {
            headers: {
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });
        
        if (!response.ok) throw new Error('Gagal mengambil data peserta');
        
        const data = await response.json();
        const peserta = data.data || data;
        
        // Simpan data peserta ke state
        state.selectedPeserta = {
            id: peserta.id,
            nama: peserta.nama,
            nim: peserta.nim,
            universitas: peserta.universitas,
            bidang: peserta.bidang,
            foto: peserta.foto
        };
        
        // Tampilkan info peserta
        showPesertaInfo(state.selectedPeserta);
        
        // Load initial data
        await loadInitialData();
        
        // Set tab based on type
        if (type) {
            state.currentTab = type;
            setTimeout(() => switchTab(type), 100);
        }
        
    } catch (error) {
        console.error('Error loading peserta:', error);
        showNotification('Gagal memuat data peserta', 'error');
        showNoPesertaSelected();
    } finally {
        showLoading('logbook', false);
    }
}

async function loadInitialData() {
    if (!state.selectedPeserta) return;
    
    try {
        // Load statistik
        await loadStats();
        
        // Load data berdasarkan tab aktif
        if (state.currentTab === 'logbook') {
            await loadLogbookData();
        } else if (state.currentTab === 'absensi') {
            await loadAbsensiData();
        }
        
    } catch (error) {
        console.error('Error loading initial data:', error);
        showNotification('Gagal memuat data', 'error');
    }
}

// ============================
// FUNGSI API (Backend-ready)
// ============================

async function loadStats() {
    if (!state.selectedPeserta) return;
    
    try {
        // **API BACKEND:** GET /api/mentor/verifikasi/stats/{pesertaId}
        const response = await fetch(`${API_CONFIG.endpoints.statsVerifikasi}/${state.selectedPeserta.id}`, {
            headers: {
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });
        
        if (!response.ok) throw new Error('Gagal mengambil statistik');
        
        const data = await response.json();
        state.stats = data.data || data;
        
        // Update UI stats
        updateStatsUI();
        
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

async function loadLogbookData() {
    if (!state.selectedPeserta) return;
    
    try {
        showLoading('logbook', true);
        
        // **API BACKEND:** GET /api/mentor/logbook/{pesertaId}
        const response = await fetch(
            `${API_CONFIG.endpoints.logbookPeserta}/${state.selectedPeserta.id}?page=${state.logbookCurrentPage}&per_page=${state.logbookItemsPerPage}`,
            {
                headers: {
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            }
        );
        
        if (!response.ok) throw new Error('Gagal mengambil data logbook');
        
        const data = await response.json();
        
        // **STRUKTUR RESPONSE BACKEND YANG DIHARAPKAN:**
        // {
        //     "data": [
        //         {
        //             "id": 1,
        //             "tanggal": "2024-03-16",
        //             "kegiatan": "Pengembangan dashboard admin",
        //             "deskripsi": "Mengembangkan fitur dashboard admin...",
        //             "waktu_mulai": "08:00",
        //             "waktu_selesai": "16:00",
        //             "waktu": "08:00 - 16:00", // Format gabungan
        //             "status": "pending", // pending, approved, rejected
        //             "catatan_mentor": null,
        //             "created_at": "2024-03-16 16:30:00"
        //         }
        //     ],
        //     "meta": {
        //         "total": 20,
        //         "per_page": 10,
        //         "current_page": 1,
        //         "last_page": 2
        //     }
        // }
        
        state.logbookList = data.data || [];
        state.logbookTotalItems = data.meta?.total || data.total || state.logbookList.length;
        
        filterLogbookData();
        updateLogbookPagination();
        
    } catch (error) {
        console.error('Error loading logbook:', error);
        renderEmptyLogbookTable('Terjadi kesalahan saat memuat data logbook');
    } finally {
        showLoading('logbook', false);
    }
}

async function loadAbsensiData() {
    try {
        showLoading('absensi', true);
        
        const absensiUrl = state.selectedPeserta
            ? `${API_CONFIG.endpoints.absensiPeserta}/${state.selectedPeserta.id}?page=${state.absensiCurrentPage}&per_page=${state.absensiItemsPerPage}`
            : `${API_CONFIG.endpoints.absensiPeserta}?page=${state.absensiCurrentPage}&per_page=${state.absensiItemsPerPage}`;

        // **API BACKEND:** GET /api/mentor/absensi (all) atau /api/mentor/absensi/{pesertaId}
        const response = await fetch(absensiUrl, {
            headers: {
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });
        
        if (!response.ok) throw new Error('Gagal mengambil data absensi');
        
        const data = await response.json();
        
        // **STRUKTUR RESPONSE BACKEND YANG DIHARAPKAN:**
        // {
        //     "data": [
        //         {
        //             "id": 1,
        //             "tanggal": "2024-03-16",
        //             "waktu_submit": "08:15", // Waktu realtime saat submit
        //             "status": "hadir", // hadir, izin, sakit, alpha
        //             "lokasi": "Jl. Sudirman No. 2, Surakarta", // Alamat dari GPS
        //             "bukti": "foto_kantor.jpg",
        //             "koordinat": "-6.200000,106.816666",
        //             "keterangan": "Dalam kantor",
        //             "created_at": "2024-03-16 08:15:00"
        //         }
        //     ],
        //     "meta": {
        //         "total": 30,
        //         "per_page": 10,
        //         "current_page": 1,
        //         "last_page": 3
        //     }
        // }
        
        state.absensiList = data.data || data || [];
        state.absensiTotalItems = data.meta?.total || data.total || state.absensiList.length;
        
        filterAbsensiData();
        updateAbsensiPagination();
        
    } catch (error) {
        console.error('Error loading absensi:', error);
        renderEmptyAbsensiTable('Terjadi kesalahan saat memuat data absensi');
    } finally {
        showLoading('absensi', false);
    }
}

// ============================
// UI FUNCTIONS
// ============================

function showPesertaInfo(peserta) {
    const avatar = document.getElementById('selectedPesertaAvatar');
    const name = document.getElementById('selectedPesertaName');
    const nim = document.getElementById('selectedPesertaNim');
    const univ = document.getElementById('selectedPesertaUniv');
    const bidang = document.getElementById('selectedPesertaBidang');
    
    // Update UI
    avatar.textContent = getInitials(peserta.nama);
    name.textContent = peserta.nama;
    nim.textContent = `NIM: ${peserta.nim || 'N/A'}`;
    univ.textContent = peserta.universitas || '-';
    bidang.textContent = peserta.bidang || '-';
}

function clearSelectedPeserta() {
    state.selectedPeserta = null;
    localStorage.removeItem('selectedPeserta');
    window.location.href = '/mentor/bimbingan';
}

function showNoPesertaSelected() {
    const logbookTable = document.getElementById('logbookTableBody');
    const absensiTable = document.getElementById('absensiTableBody');
    const pesertaInfo = document.getElementById('pesertaInfo');
    
    const message = `
        <tr>
            <td colspan="5">
                <div class="empty-state-mentor">
                    <i class='bx bx-user-x'></i>
                    <h4>Belum ada peserta yang dipilih</h4>
                    <p>Silakan pilih peserta dari halaman Daftar Bimbingan</p>
                    <a href="/mentor/bimbingan" class="btn btn-primary mt-4">
                        <i class='bx bx-list-ul'></i> Ke Daftar Bimbingan
                    </a>
                </div>
            </td>
        </tr>
    `;
    
    if (logbookTable) logbookTable.innerHTML = message;
    if (pesertaInfo) pesertaInfo.style.display = 'none';
}

function switchTab(tabName) {
    state.currentTab = tabName;
    
    // Update tab UI
    document.querySelectorAll('.tab-mentor').forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelectorAll('.tab-content-mentor').forEach(content => {
        content.classList.remove('active');
    });
    
    // Activate selected tab
    document.getElementById(`tab-${tabName}`).classList.add('active');
    document.getElementById(`${tabName}-content`).classList.add('active');
    
    // Load data for selected tab
    if (tabName === 'logbook') {
        loadLogbookData();
    } else if (tabName === 'absensi') {
        loadAbsensiData();
    }
}

// ============================
// LOGBOOK FUNCTIONS
// ============================

function filterLogbookData() {
    const searchQuery = document.getElementById('searchLogbook').value.toLowerCase();
    const dateFilter = document.getElementById('dateLogbook').value;
    const statusFilter = document.getElementById('statusLogbook').value;
    
    state.logbookFilters.search = searchQuery;
    state.logbookFilters.date = dateFilter;
    state.logbookFilters.status = statusFilter;
    
    state.filteredLogbook = state.logbookList.filter(logbook => {
        // Filter berdasarkan pencarian
        if (searchQuery) {
            const searchText = `${logbook.kegiatan} ${logbook.deskripsi}`.toLowerCase();
            if (!searchText.includes(searchQuery)) {
                return false;
            }
        }
        
        // Filter berdasarkan tanggal
        if (dateFilter) {
            if (logbook.tanggal !== dateFilter) {
                return false;
            }
        }
        
        // Filter berdasarkan status
        if (statusFilter !== 'all') {
            if (logbook.status !== statusFilter) {
                return false;
            }
        }
        
        return true;
    });
    
    renderLogbookTable();
    updateLogbookCount();
}

function renderLogbookTable() {
    const tbody = document.getElementById('logbookTableBody');
    
    if (!state.filteredLogbook || state.filteredLogbook.length === 0) {
        renderEmptyLogbookTable('Tidak ada data logbook yang sesuai dengan filter');
        return;
    }
    
    const start = (state.logbookCurrentPage - 1) * state.logbookItemsPerPage;
    const end = start + state.logbookItemsPerPage;
    const pageData = state.filteredLogbook.slice(start, end);
    
    tbody.innerHTML = pageData.map(logbook => {
        const statusClass = getLogbookStatusClass(logbook.status);
        const statusText = getLogbookStatusText(logbook.status);
        // Gunakan format waktu dari backend (contoh: "08:00 - 16:00")
        const waktuDisplay = logbook.waktu || `${logbook.waktu_mulai || '08:00'} - ${logbook.waktu_selesai || '16:00'}`;
        
        return `
            <tr>
                <td>
                    <div class="font-medium">${formatDate(logbook.tanggal)}</div>
                </td>
                <td>
                    <div class="font-medium truncate max-w-xs">${logbook.kegiatan}</div>
                    <div class="text-sm text-gray-500 truncate">${logbook.deskripsi?.substring(0, 60)}...</div>
                </td>
                <td>${waktuDisplay}</td>
                <td>
                    <span class="status-badge-mentor ${statusClass}">${statusText}</span>
                </td>
                <td>
                    <div class="mentor-action-buttons">
                        <button class="mentor-action-btn view" 
                                onclick="viewLogbookDetail('${logbook.id}')"
                                title="Lihat Detail">
                            <i class='bx bx-show'></i>
                        </button>
                        ${logbook.status === 'pending' ? `
                            <button class="mentor-action-btn primary"
                                    onclick="openVerificationModal('${logbook.id}')"
                                    title="Verifikasi">
                                <i class='bx bx-check'></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function renderEmptyLogbookTable(message) {
    const tbody = document.getElementById('logbookTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="5">
                <div class="empty-state-mentor">
                    <i class='bx bx-notepad'></i>
                    <h4>${message}</h4>
                    ${state.logbookFilters.search || state.logbookFilters.date || state.logbookFilters.status !== 'all' ? `
                        <button onclick="resetLogbookFilter()" class="btn btn-primary mt-4">
                            Reset Filter
                        </button>
                    ` : ''}
                </div>
            </td>
        </tr>
    `;
}

// ============================
// ABSENSI FUNCTIONS
// ============================

function filterAbsensiData() {
    const searchQuery = document.getElementById('searchAbsensi').value.toLowerCase();
    const dateFilter = document.getElementById('dateAbsensi').value;
    const statusFilter = document.getElementById('statusAbsensi').value;
    
    state.absensiFilters.search = searchQuery;
    state.absensiFilters.date = dateFilter;
    state.absensiFilters.status = statusFilter;
    
    state.filteredAbsensi = state.absensiList.filter(absensi => {
        // Filter berdasarkan pencarian
        if (searchQuery) {
            const searchText = `${absensi.nama || ''} ${absensi.status} ${absensi.lokasi} ${absensi.keterangan}`.toLowerCase();
            if (!searchText.includes(searchQuery)) {
                return false;
            }
        }
        
        // Filter berdasarkan tanggal
        if (dateFilter) {
            if (absensi.tanggal !== dateFilter) {
                return false;
            }
        }
        
        // Filter berdasarkan status
        if (statusFilter !== 'all') {
            if (absensi.status !== statusFilter) {
                return false;
            }
        }
        
        return true;
    });
    
    renderAbsensiTable();
    updateAbsensiCount();
}

function renderAbsensiTable() {
    const tbody = document.getElementById('absensiTableBody');
    
    if (!state.filteredAbsensi || state.filteredAbsensi.length === 0) {
        renderEmptyAbsensiTable('Tidak ada data absensi yang sesuai dengan filter');
        return;
    }
    
    const start = (state.absensiCurrentPage - 1) * state.absensiItemsPerPage;
    const end = start + state.absensiItemsPerPage;
    const pageData = state.filteredAbsensi.slice(start, end);
    
    tbody.innerHTML = pageData.map((absensi, idx) => {
        const statusClass = getAbsensiStatusClass(absensi.status);
        const statusText = getAbsensiStatusText(absensi.status);
        const pesertaNama = absensi.nama || state.selectedPeserta?.nama || '-';
        // Waktu submit absensi (realtime saat peserta submit)
        const waktuSubmit = absensi.waktu_submit || absensi.created_at?.split(' ')[1]?.substring(0, 5) || '-';
        const absensiIndex = start + idx;
        
        return `
            <tr>
                <td>
                    <div class="font-medium">${pesertaNama}</div>
                </td>
                <td>
                    <div class="font-medium">${formatDate(absensi.tanggal)}</div>
                </td>
                <td>
                    <span class="status-badge-mentor ${statusClass}">${statusText}</span>
                </td>
                <td>${waktuSubmit}</td>
                <td>
                    <div class="text-sm text-gray-600 truncate max-w-xs">${absensi.lokasi || '-'}</div>
                    ${absensi.koordinat ? `
                        <div class="text-xs text-gray-500">${absensi.koordinat}</div>
                    ` : ''}
                </td>
                <td>
                    ${absensi.bukti ? 'Ada' : '-'}
                </td>
                <td>
                    <button onclick="viewAbsensiDetailByIndex(${absensiIndex})" 
                            class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1">
                        <i class='bx bx-show'></i> Detail
                    </button>
                </td>
            </tr>
        `;
    }).join('');
}

function renderEmptyAbsensiTable(message) {
    const tbody = document.getElementById('absensiTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="7">
                <div class="empty-state-mentor">
                    <i class='bx bx-calendar-x'></i>
                    <h4>${message}</h4>
                    ${state.absensiFilters.search || state.absensiFilters.date || state.absensiFilters.status !== 'all' ? `
                        <button onclick="resetAbsensiFilter()" class="btn btn-primary mt-4">
                            Reset Filter
                        </button>
                    ` : ''}
                </div>
            </td>
        </tr>
    `;
}

// ============================
// MODAL FUNCTIONS
// ============================

async function viewLogbookDetail(logbookId) {
    try {
        showModalLoading('logbook', true);
        
        // **API BACKEND:** GET /api/mentor/logbook/{pesertaId}/{logbookId}
        const response = await fetch(`${API_CONFIG.endpoints.logbookPeserta}/${state.selectedPeserta.id}/${logbookId}`, {
            headers: {
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });
        
        if (!response.ok) throw new Error('Gagal mengambil detail logbook');
        
        const data = await response.json();
        const logbook = data.data || data;
        
        state.currentLogbook = logbook;
        renderLogbookDetailModal(logbook);
        openModal('logbookModal');
        
    } catch (error) {
        console.error('Error loading logbook detail:', error);
        showNotification('Gagal memuat detail logbook', 'error');
    } finally {
        showModalLoading('logbook', false);
    }
}

function renderLogbookDetailModal(logbook) {
    const waktuDisplay = logbook.waktu || `${logbook.waktu_mulai || '08:00'} - ${logbook.waktu_selesai || '16:00'}`;
    const statusClass = getLogbookStatusClass(logbook.status);
    const statusText = getLogbookStatusText(logbook.status);
    
    document.getElementById('logbookModalTitle').textContent = `Logbook - ${formatDate(logbook.tanggal)}`;
    
    const verifyBtn = document.getElementById('verifyBtn');
    if (logbook.status === 'pending') {
        verifyBtn.style.display = 'inline-flex';
        verifyBtn.onclick = () => openVerificationModal(logbook.id);
    } else {
        verifyBtn.style.display = 'none';
    }
    
    document.getElementById('logbookModalContent').innerHTML = `
        <div class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-500">Tanggal</label>
                    <div class="font-medium">${formatDate(logbook.tanggal)}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Waktu</label>
                    <div class="font-medium">${waktuDisplay}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Status</label>
                    <div>
                        <span class="status-badge-mentor ${statusClass}">${statusText}</span>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="text-sm text-gray-500 mb-2">Kegiatan</label>
                <div class="font-medium text-lg text-primary">${logbook.kegiatan}</div>
            </div>
            
            <div>
                <label class="text-sm text-gray-500 mb-2">Deskripsi Kegiatan</label>
                <div class="bg-gray-50 p-4 rounded-lg">
                    ${logbook.deskripsi || 'Tidak ada deskripsi'}
                </div>
            </div>
            
            ${logbook.hasil ? `
                <div>
                    <label class="text-sm text-gray-500 mb-2">Hasil / Progress</label>
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        ${logbook.hasil}
                    </div>
                </div>
            ` : ''}
            
            ${logbook.kendala ? `
                <div>
                    <label class="text-sm text-gray-500 mb-2">Kendala</label>
                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                        ${logbook.kendala}
                    </div>
                </div>
            ` : ''}
            
            ${logbook.catatan_mentor ? `
                <div>
                    <label class="text-sm text-gray-500 mb-2">Catatan Mentor</label>
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <div class="font-semibold text-primary mb-1">${formatDate(logbook.updated_at)}</div>
                        <div>${logbook.catatan_mentor}</div>
                    </div>
                </div>
            ` : ''}
        </div>
    `;
}

function openVerificationModal(logbookId = null) {
    const logbook = logbookId ? 
        state.logbookList.find(l => l.id == logbookId) : 
        state.currentLogbook;
    
    if (!logbook) return;
    
    document.getElementById('logbookId').value = logbook.id;
    document.getElementById('verificationPesertaName').textContent = state.selectedPeserta.nama;
    document.getElementById('verificationLogbookInfo').innerHTML = `
        ${formatDate(logbook.tanggal)} • ${logbook.kegiatan}
    `;
    
    // Reset form
    document.getElementById('verificationForm').reset();
    document.getElementById('catatan').value = logbook.catatan_mentor || '';
    
    openModal('verificationModal');
    closeLogbookModal();
}

// Verifikasi form submission
document.getElementById('verificationForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    try {
        showSubmitLoading(true);
        
        const formData = new FormData(this);
        formData.append('_token', window.csrfToken);
        
        // **API BACKEND:** POST /api/mentor/logbook/verify
        const response = await fetch(API_CONFIG.endpoints.verifyLogbook, {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            },
            credentials: 'same-origin',
            body: formData
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Gagal melakukan verifikasi');
        }
        
        showNotification('Logbook berhasil diverifikasi', 'success');
        closeVerificationModal();
        loadLogbookData();
        loadStats();
        
    } catch (error) {
        console.error('Error:', error);
        showNotification(error.message || 'Gagal melakukan verifikasi', 'error');
    } finally {
        showSubmitLoading(false);
    }
});

async function viewAbsensiBukti(absensiId) {
    try {
        const absensi = state.absensiList.find(a => a.id == absensiId);
        if (!absensi) return;
        
        renderAbsensiDetailModal(absensi);
        openModal('absensiModal');
        
    } catch (error) {
        console.error('Error loading absensi detail:', error);
        showNotification('Gagal memuat detail absensi', 'error');
    }
}

function viewAbsensiDetailByIndex(index) {
    try {
        const absensi = state.filteredAbsensi[index];
        if (!absensi) return;
        renderAbsensiDetailModal(absensi);
        openModal('absensiModal');
    } catch (error) {
        console.error('Error loading absensi detail:', error);
        showNotification('Gagal memuat detail absensi', 'error');
    }
}

function renderAbsensiDetailModal(absensi) {
    const statusClass = getAbsensiStatusClass(absensi.status);
    const statusText = getAbsensiStatusText(absensi.status);
    const waktuSubmit = absensi.waktu_submit || absensi.created_at?.split(' ')[1]?.substring(0, 5) || '-';
    const pesertaNama = absensi.nama || state.selectedPeserta?.nama || 'peserta';
    
    document.getElementById('absensiModalTitle').textContent = `Absensi - ${formatDate(absensi.tanggal)}`;
    
    let buktiHtml = '';
    if (absensi.bukti) {
        const isImage = ['jpg', 'jpeg', 'png', 'gif'].includes(absensi.bukti.split('.').pop().toLowerCase());
        
        if (isImage) {
            buktiHtml = `
                <div class="mt-4">
                    <label class="text-sm text-gray-500 mb-2">Bukti Kehadiran</label>
                    <div class="border rounded-lg p-4 text-center">
                        <img src="/storage/${absensi.bukti}" 
                             alt="Bukti Absensi" 
                             class="max-w-full h-auto rounded-lg mx-auto max-h-96">
                        <div class="mt-3 text-sm text-gray-600">
                            Foto bukti absensi ${pesertaNama}
                        </div>
                    </div>
                </div>
            `;
        } else {
            buktiHtml = `
                <div class="mt-4">
                    <label class="text-sm text-gray-500 mb-2">File Bukti</label>
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <i class='bx bx-file text-3xl text-gray-400'></i>
                            <div>
                                <div class="font-medium">${absensi.bukti}</div>
                                <div class="text-sm text-gray-500">File bukti kehadiran</div>
                            </div>
                            <button onclick="downloadBukti('${absensi.bukti}')" 
                                    class="ml-auto px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-800">
                                <i class='bx bx-download'></i> Download
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }
    }
    
    let alasanHtml = '';
    if (absensi.status === 'izin' || absensi.status === 'sakit') {
        alasanHtml = `
            <div>
                <label class="text-sm text-gray-500 mb-2">Alasan ${absensi.status === 'izin' ? 'Izin' : 'Sakit'}</label>
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                    ${absensi.keterangan || 'Tidak ada keterangan'}
                </div>
            </div>
        `;
    }
    
    document.getElementById('absensiModalContent').innerHTML = `
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-500">Tanggal</label>
                    <div class="font-medium">${formatDate(absensi.tanggal)}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Status</label>
                    <div>
                        <span class="status-badge-mentor ${statusClass}">${statusText}</span>
                    </div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Waktu Submit</label>
                    <div class="font-medium">${waktuSubmit}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Lokasi</label>
                    <div class="font-medium truncate">${absensi.lokasi || '-'}</div>
                </div>
            </div>
            
            ${absensi.koordinat ? `
                <div>
                    <label class="text-sm text-gray-500">Koordinat GPS</label>
                    <div class="bg-gray-50 p-3 rounded-lg text-sm font-mono">${absensi.koordinat}</div>
                    <div class="mt-2">
                        <a href="https://maps.google.com/?q=${absensi.koordinat}" 
                           target="_blank" 
                           class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1">
                            <i class='bx bx-map'></i> Lihat di Google Maps
                        </a>
                    </div>
                </div>
            ` : ''}
            
            ${alasanHtml}
            
            ${buktiHtml}
        </div>
    `;
}

function downloadBukti(filename) {
    // Redirect ke endpoint download
    window.open(`/api/mentor/absensi/download/${filename}`, '_blank');
}

// ============================
// UTILITY FUNCTIONS
// ============================

function getInitials(name) {
    if (!name) return '--';
    return name
        .split(' ')
        .map(n => n.charAt(0).toUpperCase())
        .join('')
        .substring(0, 2);
}

function formatDate(dateString) {
    if (!dateString) return '-';
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    } catch (e) {
        return dateString;
    }
}

function getLogbookStatusClass(status) {
    switch(status) {
        case 'approved': return 'status-approved';
        case 'rejected': return 'status-rejected';
        case 'pending': return 'status-pending';
        default: return 'status-pending';
    }
}

function getLogbookStatusText(status) {
    switch(status) {
        case 'approved': return 'Disetujui';
        case 'rejected': return 'Ditolak';
        case 'pending': return 'Menunggu';
        default: return 'Unknown';
    }
}

function getAbsensiStatusClass(status) {
    switch(status) {
        case 'hadir': return 'status-approved';
        case 'izin': return 'status-pending';
        case 'sakit': return 'status-rejected';
        case 'alpha': return 'status-rejected';
        default: return 'status-pending';
    }
}

function getAbsensiStatusText(status) {
    switch(status) {
        case 'hadir': return 'Hadir';
        case 'izin': return 'Izin';
        case 'sakit': return 'Sakit';
        case 'alpha': return 'Alpha';
        default: return 'Unknown';
    }
}

function setupEventListeners() {
    // Logbook filter event listeners
    document.getElementById('searchLogbook').addEventListener('input', 
        debounce(filterLogbookData, 300)
    );
    document.getElementById('dateLogbook').addEventListener('change', filterLogbookData);
    document.getElementById('statusLogbook').addEventListener('change', filterLogbookData);
    
    // Absensi filter event listeners
    document.getElementById('searchAbsensi').addEventListener('input', 
        debounce(filterAbsensiData, 300)
    );
    document.getElementById('dateAbsensi').addEventListener('change', filterAbsensiData);
    document.getElementById('statusAbsensi').addEventListener('change', filterAbsensiData);
    
    // Logbook pagination
    document.getElementById('logbookPrevPage').addEventListener('click', logbookPrevPage);
    document.getElementById('logbookNextPage').addEventListener('click', logbookNextPage);
    
    // Absensi pagination
    document.getElementById('absensiPrevPage').addEventListener('click', absensiPrevPage);
    document.getElementById('absensiNextPage').addEventListener('click', absensiNextPage);
}

function resetLogbookFilter() {
    document.getElementById('searchLogbook').value = '';
    document.getElementById('dateLogbook').value = '';
    document.getElementById('statusLogbook').value = 'all';
    
    state.logbookFilters.search = '';
    state.logbookFilters.date = '';
    state.logbookFilters.status = 'all';
    
    filterLogbookData();
}

function resetAbsensiFilter() {
    document.getElementById('searchAbsensi').value = '';
    document.getElementById('dateAbsensi').value = '';
    document.getElementById('statusAbsensi').value = 'all';
    
    state.absensiFilters.search = '';
    state.absensiFilters.date = '';
    state.absensiFilters.status = 'all';
    
    filterAbsensiData();
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ============================
// PAGINATION FUNCTIONS
// ============================

// Logbook pagination
function updateLogbookCount() {
    document.getElementById('logbookCount').textContent = 
        `${state.filteredLogbook.length} logbook`;
}

function updateLogbookPagination() {
    const totalPages = Math.ceil(state.filteredLogbook.length / state.logbookItemsPerPage);
    const start = ((state.logbookCurrentPage - 1) * state.logbookItemsPerPage) + 1;
    const end = Math.min(state.logbookCurrentPage * state.logbookItemsPerPage, state.filteredLogbook.length);
    
    document.getElementById('logbookPageInfo').textContent = 
        `Menampilkan ${start} - ${end} dari ${state.filteredLogbook.length} logbook`;
    
    document.getElementById('logbookPrevPage').disabled = state.logbookCurrentPage === 1;
    document.getElementById('logbookNextPage').disabled = state.logbookCurrentPage === totalPages || totalPages === 0;
}

function logbookPrevPage() {
    if (state.logbookCurrentPage > 1) {
        state.logbookCurrentPage--;
        renderLogbookTable();
        updateLogbookPagination();
    }
}

function logbookNextPage() {
    const totalPages = Math.ceil(state.filteredLogbook.length / state.logbookItemsPerPage);
    if (state.logbookCurrentPage < totalPages) {
        state.logbookCurrentPage++;
        renderLogbookTable();
        updateLogbookPagination();
    }
}

// Absensi pagination
function updateAbsensiCount() {
    document.getElementById('absensiCount').textContent = 
        `${state.filteredAbsensi.length} absensi`;
}

function updateAbsensiPagination() {
    const totalPages = Math.ceil(state.filteredAbsensi.length / state.absensiItemsPerPage);
    const start = ((state.absensiCurrentPage - 1) * state.absensiItemsPerPage) + 1;
    const end = Math.min(state.absensiCurrentPage * state.absensiItemsPerPage, state.filteredAbsensi.length);
    
    document.getElementById('absensiPageInfo').textContent = 
        `Menampilkan ${start} - ${end} dari ${state.filteredAbsensi.length} absensi`;
    
    document.getElementById('absensiPrevPage').disabled = state.absensiCurrentPage === 1;
    document.getElementById('absensiNextPage').disabled = state.absensiCurrentPage === totalPages || totalPages === 0;
}

function absensiPrevPage() {
    if (state.absensiCurrentPage > 1) {
        state.absensiCurrentPage--;
        renderAbsensiTable();
        updateAbsensiPagination();
    }
}

function absensiNextPage() {
    const totalPages = Math.ceil(state.filteredAbsensi.length / state.absensiItemsPerPage);
    if (state.absensiCurrentPage < totalPages) {
        state.absensiCurrentPage++;
        renderAbsensiTable();
        updateAbsensiPagination();
    }
}

// ============================
// MODAL UTILITIES
// ============================

function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeLogbookModal() {
    document.getElementById('logbookModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    state.currentLogbook = null;
}

function closeVerificationModal() {
    document.getElementById('verificationModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function closeAbsensiModal() {
    document.getElementById('absensiModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// ============================
// LOADING FUNCTIONS
// ============================

function showLoading(context, isLoading) {
    const loaders = {
        'logbook': () => {
            const loadingRow = document.getElementById('loadingRow');
            if (loadingRow) {
                loadingRow.style.display = isLoading ? 'table-row' : 'none';
            }
        },
        'absensi': () => {
            const loadingRow = document.getElementById('loadingRowAbsensi');
            if (loadingRow) {
                loadingRow.style.display = isLoading ? 'table-row' : 'none';
            }
        }
    };
    
    if (loaders[context]) {
        loaders[context]();
    }
}

function showModalLoading(context, isLoading) {
    const loaders = {
        'logbook': () => {
            const modalContent = document.getElementById('logbookModalContent');
            if (modalContent && isLoading) {
                modalContent.innerHTML = `
                    <div class="text-center py-10">
                        <i class='bx bx-loader-circle bx-spin text-4xl text-primary'></i>
                        <div class="mt-4 text-gray-600">Memuat detail logbook...</div>
                    </div>
                `;
            }
        }
    };
    
    if (loaders[context]) {
        loaders[context]();
    }
}

function showSubmitLoading(show) {
    const btn = document.querySelector('#verificationModal .btn-primary');
    if (btn) {
        btn.disabled = show;
        btn.innerHTML = show 
            ? '<i class="bx bx-loader-circle bx-spin"></i> Memproses...'
            : '<i class="bx bx-check"></i> Simpan Verifikasi';
    }
}

// ============================
// NOTIFICATION FUNCTION
// ============================

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const icon = type === 'success' ? 'bx-check-circle' : 
                type === 'error' ? 'bx-error-circle' : 'bx-info-circle';
    
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <i class='bx ${icon}'></i>
            <span>${message}</span>
        </div>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#d4edda' : 
                     type === 'error' ? '#f8d7da' : '#d1ecf1'};
        color: ${type === 'success' ? '#155724' : 
                type === 'error' ? '#721c24' : '#0c5460'};
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        z-index: 9999;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
</script>
@endsection
