@extends('layouts.admin')

@section('title', 'Verifikasi Berkas Peserta')
@section('subtitle', 'Kelola Pengajuan Magang Peserta')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endsection

@section('content')
<div class="content-header">
    <div class="header-actions">
        <!-- Tombol download semua dihapus -->
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card border-blue">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="pendingCount">0</div>
                <div class="stat-label">Menunggu</div>
            </div>
            <div class="stat-icon blue">
                <i class='bx bx-time'></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card border-green">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="acceptedCount">0</div>
                <div class="stat-label">Diterima</div>
            </div>
            <div class="stat-icon green">
                <i class='bx bx-check-circle'></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card border-orange">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="rejectedCount">0</div>
                <div class="stat-label">Ditolak</div>
            </div>
            <div class="stat-icon orange">
                <i class='bx bx-x-circle'></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card border-purple">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="totalCount">0</div>
                <div class="stat-label">Total Pengajuan</div>
            </div>
            <div class="stat-icon purple">
                <i class='bx bx-user'></i>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-container">
    <div class="filter-grid">
        <div class="filter-group">
            <label for="searchInput" class="filter-label">
                <i class='bx bx-search'></i> Cari Peserta
            </label>
            <input type="text" id="searchInput" placeholder="Nama, NIM, universitas..." 
                   class="filter-input">
        </div>
        
        <div class="filter-group">
            <label for="statusFilter" class="filter-label">
                <i class='bx bx-filter-alt'></i> Status
            </label>
            <select id="statusFilter" class="filter-select">
                <option value="all">Semua Status</option>
                <option value="pending">Menunggu</option>
                <option value="accepted">Diterima</option>
                <option value="rejected">Ditolak</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="bidangFilter" class="filter-label">
                <i class='bx bx-briefcase'></i> Bidang
            </label>
            <select id="bidangFilter" class="filter-select">
                <option value="all">Semua Bidang</option>
                <option value="statistik">Statistik</option>
                <option value="informatika">Informatika</option>
                <option value="sekretariat">Sekretariat</option>
                <option value="e-goverment">E-Goverment</option>
                <!-- Data bidang akan diisi via JavaScript -->
            </select>
        </div>
        
        <div class="filter-actions">
            <button onclick="filterPengajuan()" class="btn btn-primary">
                <i class='bx bx-filter'></i> Terapkan
            </button>
            <button onclick="resetFilter()" class="btn btn-secondary">
                <i class='bx bx-reset'></i> Reset
            </button>
        </div>
    </div>
</div>

<!-- Table Container -->
<div class="table-container">
    <div class="table-header">
        <h3>Daftar Pengajuan Magang</h3>
        <span class="table-count" id="pengajuanCount">0 pengajuan</span>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Peserta</th>
                <th>Universitas</th>
                <th>Bidang Pilihan</th>
                <th>Tanggal Pengajuan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="pengajuanTable">
            <!-- Data akan dimuat via JavaScript -->
            <tr id="loadingRow">
                <td colspan="6" style="text-align: center; padding: 50px 20px;">
                    <div class="loading-skeleton" style="display: flex; flex-direction: column; align-items: center; gap: 20px;">
                        <i class='bx bx-loader-circle bx-spin' style="font-size: 3rem; color: var(--primary);"></i>
                        <div style="text-align: center; color: #666;">
                            <div style="font-weight: 600; margin-bottom: 5px;">Memuat data...</div>
                            <div style="font-size: 0.9rem;">Mohon tunggu sebentar</div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    
    <!-- Pagination -->
    <div class="pagination">
        <div class="pagination-info" id="pageInfo">Menampilkan 0 - 0 dari 0 pengajuan</div>
        <div class="pagination-controls">
            <button id="prevPageBtn" onclick="prevPage()" class="pagination-btn" disabled>
                <i class='bx bx-chevron-left'></i>
            </button>
            <button id="nextPageBtn" onclick="nextPage()" class="pagination-btn" disabled>
                <i class='bx bx-chevron-right'></i>
            </button>
        </div>
    </div>
</div>

<!-- Modal Detail Pengajuan -->
<div id="detailModal" class="modal">
    <div class="modal-content" style="max-width: 900px;">
        <div class="modal-header">
            <h3 id="modalTitle">Detail Pengajuan Magang</h3>
            <button class="modal-close" onclick="closeModal('detailModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div id="modalContent">
                <!-- Konten akan dimuat via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi -->
<div id="verifikasiModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3 id="verifikasiTitle">Verifikasi Pengajuan</h3>
            <button class="modal-close" onclick="closeModal('verifikasiModal')">&times;</button>
        </div>
        <form id="verifikasiForm">
            @csrf
            <div class="modal-body">
                <input type="hidden" id="verifikasiId">
                <input type="hidden" id="pengajuanId">
                
                <div class="form-section">
                    <h4><i class='bx bx-check-shield'></i> Status Verifikasi</h4>
                    <div class="form-group">
                        <label for="statusVerifikasi">Status *</label>
                        <select id="statusVerifikasi" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="accepted">Diterima</option>
                            <option value="rejected">Ditolak</option>
                            <option value="pending">Tunda</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="catatanVerifikasi">Catatan/Keterangan</label>
                        <textarea id="catatanVerifikasi" name="catatan" rows="4" 
                                  placeholder="Berikan catatan atau alasan verifikasi..."></textarea>
                        <small>Catatan akan dikirimkan ke peserta via email</small>
                    </div>
                </div>
                
                <div class="form-section">
                    <h4><i class='bx bx-briefcase'></i> Penempatan Final</h4>
                    <div class="form-group">
                        <label for="bidangFinal">Bidang Penempatan</label>
                        <select id="bidangFinal" name="bidang_penempatan">
                            <option value="">Pilih Bidang (Opsional)</option>
                            <!-- Data bidang akan diisi via JavaScript -->
                        </select>
                        <small>Isi jika ingin menempatkan ke bidang yang berbeda dengan pilihan peserta</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('verifikasiModal')">Batal</button>
                <button type="submit" class="btn btn-primary" id="submitVerifikasiBtn">
                    <i class='bx bx-check'></i> Simpan Verifikasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Konfigurasi API
const API_CONFIG = {
    baseUrl: window.location.origin,
    endpoints: {
        pengajuan: '/api/admin/pengajuan',
        bidang: '/api/bidang',
        verifikasi: '/api/admin/pengajuan/verifikasi',
        download: '/api/admin/pengajuan/download'
    }
};

// State management
let state = {
    pengajuan: [],
    filteredPengajuan: [],
    bidangList: [],
    currentPage: 1,
    itemsPerPage: 10,
    totalPages: 1,
    totalItems: 0,
    currentFilters: {
        search: '',
        status: 'all',
        bidang: 'all'
    }
};

// Inisialisasi
document.addEventListener('DOMContentLoaded', function() {
    setupCSRF();
    fetchBidangList();
    fetchPengajuan();
    setupEventListeners();
});

// Setup CSRF token
function setupCSRF() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    if (token) {
        window.csrfToken = token;
    }
}

// Setup event listeners
function setupEventListeners() {
    document.getElementById('searchInput').addEventListener('input', function(e) {
        state.currentFilters.search = e.target.value;
        debounce(filterPengajuan, 300)();
    });
    
    document.getElementById('statusFilter').addEventListener('change', function(e) {
        state.currentFilters.status = e.target.value;
        filterPengajuan();
    });
    
    document.getElementById('bidangFilter').addEventListener('change', function(e) {
        state.currentFilters.bidang = e.target.value;
        filterPengajuan();
    });
    
    document.getElementById('verifikasiForm').addEventListener('submit', handleVerifikasiSubmit);
}

// Fetch data bidang
async function fetchBidangList() {
    try {
        const response = await fetch(API_CONFIG.endpoints.bidang, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) throw new Error('Gagal mengambil data bidang');
        
        const data = await response.json();
        state.bidangList = data.data || data;
        populateBidangSelects();
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat data bidang', 'error');
        
        // Fallback data
        state.bidangList = [
            { id: 1, nama_bidang: 'Statistik' },
            { id: 2, nama_bidang: 'Informatika' },
            { id: 3, nama_bidang: 'Sekretariat' },
            { id: 4, nama_bidang: 'E-Goverment' }
        ];
        populateBidangSelects();
    }
}

// Populate bidang selects
function populateBidangSelects() {
    const bidangFilter = document.getElementById('bidangFilter');
    const bidangFinal = document.getElementById('bidangFinal');
    
    // Clear existing options
    bidangFilter.innerHTML = '<option value="all">Semua Bidang</option>';
    bidangFinal.innerHTML = '<option value="">Pilih Bidang (Opsional)</option>';
    
    state.bidangList.forEach(bidang => {
        const filterOption = document.createElement('option');
        filterOption.value = bidang.id || bidang.nama_bidang;
        filterOption.textContent = bidang.nama_bidang;
        bidangFilter.appendChild(filterOption);
        
        const finalOption = document.createElement('option');
        finalOption.value = bidang.id || bidang.nama_bidang;
        finalOption.textContent = bidang.nama_bidang;
        bidangFinal.appendChild(finalOption);
    });
}

// Fetch data pengajuan
async function fetchPengajuan() {
    try {
        showLoading(true);
        
        let url = `${API_CONFIG.endpoints.pengajuan}?page=${state.currentPage}&per_page=${state.itemsPerPage}`;
        
        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) throw new Error('Gagal mengambil data pengajuan');
        
        const data = await response.json();
        state.pengajuan = data.data || data.pengajuan || [];
        state.totalItems = data.meta?.total || data.total || state.pengajuan.length;
        state.totalPages = Math.ceil(state.totalItems / state.itemsPerPage);
        
        filterPengajuan();
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat data pengajuan', 'error');
        renderEmptyTable('Terjadi kesalahan saat memuat data');
    } finally {
        showLoading(false);
    }
}

// Filter pengajuan
function filterPengajuan() {
    const searchTerm = state.currentFilters.search.toLowerCase();
    const statusFilter = state.currentFilters.status;
    const bidangFilter = state.currentFilters.bidang;
    
    state.filteredPengajuan = state.pengajuan.filter(pengajuan => {
        // Filter pencarian
        if (searchTerm) {
            const matchesSearch = 
                pengajuan.nama?.toLowerCase().includes(searchTerm) ||
                pengajuan.nim?.toLowerCase().includes(searchTerm) ||
                pengajuan.universitas?.toLowerCase().includes(searchTerm) ||
                pengajuan.jurusan?.toLowerCase().includes(searchTerm);
            if (!matchesSearch) return false;
        }
        
        // Filter status
        if (statusFilter !== 'all' && pengajuan.status !== statusFilter) {
            return false;
        }
        
        // Filter bidang
        if (bidangFilter !== 'all') {
            const bidangId = pengajuan.bidang?.id || pengajuan.id_bidang;
            if (bidangId != bidangFilter) return false;
        }
        
        return true;
    });
    
    // Sort by tanggal pengajuan (terbaru pertama)
    state.filteredPengajuan.sort((a, b) => {
        return new Date(b.tanggal_pengajuan || b.created_at) - new Date(a.tanggal_pengajuan || a.created_at);
    });
    
    renderTable();
    updateStats();
}

// Reset filter
function resetFilter() {
    state.currentFilters = {
        search: '',
        status: 'all',
        bidang: 'all'
    };
    
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('bidangFilter').value = 'all';
    
    state.currentPage = 1;
    filterPengajuan();
}

// Render tabel
function renderTable() {
    const container = document.getElementById('pengajuanTable');
    const totalItems = state.filteredPengajuan.length;
    
    if (totalItems === 0) {
        renderEmptyTable('Tidak ada pengajuan ditemukan');
        updatePageInfo(0, 0, 0);
        return;
    }
    
    const start = (state.currentPage - 1) * state.itemsPerPage;
    const end = start + state.itemsPerPage;
    const pageData = state.filteredPengajuan.slice(start, end);
    
    container.innerHTML = pageData.map(pengajuan => {
        const statusConfig = getStatusConfig(pengajuan.status);
        const tanggal = formatDate(pengajuan.tanggal_pengajuan || pengajuan.created_at);
        const bidangNama = pengajuan.bidang?.nama_bidang || pengajuan.bidang_pilihan || '-';
        const user = pengajuan.user || pengajuan.peserta || {};
        
        return `
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="user-avatar">${getInitials(user.nama || pengajuan.nama)}</div>
                        <div>
                            <div style="font-weight: 600; color: var(--primary);">${user.nama || pengajuan.nama}</div>
                            <div style="font-size: 0.85rem; color: #666;">${user.nim || pengajuan.nim || ''}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="font-weight: 500;">${user.universitas || pengajuan.universitas || '-'}</div>
                    <div style="font-size: 0.85rem; color: #666;">${user.jurusan || pengajuan.jurusan || ''}</div>
                </td>
                <td>
                    <span class="bidang-badge">${bidangNama}</span>
                </td>
                <td>
                    <div style="color: #666;">${tanggal}</div>
                </td>
                <td>
                    <span class="status-badge ${statusConfig.class}">${statusConfig.text}</span>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view" onclick="showDetail('${pengajuan.id}')" title="Lihat Detail">
                            <i class='bx bx-show'></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
    
    updatePageInfo(start + 1, Math.min(end, totalItems), totalItems);
    updatePaginationButtons();
}

// Render empty table
function renderEmptyTable(message) {
    const container = document.getElementById('pengajuanTable');
    container.innerHTML = `
        <tr>
            <td colspan="6" style="text-align: center; padding: 50px 20px;">
                <div style="display: flex; flex-direction: column; align-items: center; gap: 15px;">
                    <i class='bx bx-search-alt' style="font-size: 3rem; color: #ccc;"></i>
                    <div style="color: #888; font-weight: 500;">${message}</div>
                    <div style="font-size: 0.9rem; color: #999;">Coba dengan filter yang berbeda</div>
                </div>
            </td>
        </tr>
    `;
}

// Update statistik
function updateStats() {
    const pending = state.filteredPengajuan.filter(p => p.status === 'pending').length;
    const accepted = state.filteredPengajuan.filter(p => p.status === 'accepted').length;
    const rejected = state.filteredPengajuan.filter(p => p.status === 'rejected').length;
    const total = state.filteredPengajuan.length;
    
    document.getElementById('pendingCount').textContent = pending;
    document.getElementById('acceptedCount').textContent = accepted;
    document.getElementById('rejectedCount').textContent = rejected;
    document.getElementById('totalCount').textContent = total;
    document.getElementById('pengajuanCount').textContent = `${total} pengajuan`;
}

// Show detail pengajuan
async function showDetail(pengajuanId) {
    try {
        showLoading('detail', true);
        
        const response = await fetch(`${API_CONFIG.endpoints.pengajuan}/${pengajuanId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) throw new Error('Gagal mengambil detail pengajuan');
        
        const data = await response.json();
        const pengajuan = data.data || data;
        
        renderDetailModal(pengajuan);
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat detail pengajuan', 'error');
    } finally {
        showLoading('detail', false);
    }
}

// Render detail modal
function renderDetailModal(pengajuan) {
    const statusConfig = getStatusConfig(pengajuan.status);
    const user = pengajuan.user || pengajuan.peserta || {};
    const bidangNama = pengajuan.bidang?.nama_bidang || pengajuan.bidang_pilihan || '-';
    
    document.getElementById('modalTitle').textContent = `Detail Pengajuan - ${user.nama || pengajuan.nama}`;
    
    document.getElementById('modalContent').innerHTML = `
        <div class="detail-section">
            <div class="detail-header">
                <div class="detail-title">
                    <div class="detail-avatar">${getInitials(user.nama || pengajuan.nama)}</div>
                    <div>
                        <h4>${user.nama || pengajuan.nama}</h4>
                        <p>${user.nim || pengajuan.nim || ''} • ${user.universitas || pengajuan.universitas || '-'}</p>
                    </div>
                </div>
                <span class="status-badge ${statusConfig.class}">${statusConfig.text}</span>
            </div>
        </div>
        
        <div class="detail-grid">
            <div class="detail-item">
                <label><i class='bx bx-envelope'></i> Email</label>
                <span>${user.email || pengajuan.email || '-'}</span>
            </div>
            <div class="detail-item">
                <label><i class='bx bx-phone'></i> No. Telepon</label>
                <span>${user.no_telp || pengajuan.no_telp || '-'}</span>
            </div>
            <div class="detail-item">
                <label><i class='bx bx-book'></i> Program Studi</label>
                <span>${user.jurusan || pengajuan.jurusan || '-'} - Semester ${user.semester || pengajuan.semester || '-'}</span>
            </div>
            <div class="detail-item">
                <label><i class='bx bx-briefcase'></i> Bidang Pilihan</label>
                <span class="bidang-badge">${bidangNama}</span>
            </div>
            <div class="detail-item">
                <label><i class='bx bx-calendar'></i> Periode Magang</label>
                <span>${formatDate(pengajuan.tanggal_mulai)} - ${formatDate(pengajuan.tanggal_selesai)}</span>
            </div>
            <div class="detail-item">
                <label><i class='bx bx-time'></i> Tanggal Pengajuan</label>
                <span>${formatDate(pengajuan.tanggal_pengajuan || pengajuan.created_at)}</span>
            </div>
        </div>
        
        <div class="detail-section">
            <h4><i class='bx bx-message'></i> Alasan Memilih Bidang</h4>
            <div class="detail-card">
                <p>${pengajuan.alasan || '-'}</p>
            </div>
        </div>
        
        <div class="detail-section">
            <h4><i class='bx bx-file'></i> Berkas Pendaftaran</h4>
            <div class="berkas-grid">
                ${renderBerkas(pengajuan.berkas)}
            </div>
        </div>
        
        ${pengajuan.status !== 'pending' ? renderVerifikasiInfo(pengajuan) : ''}
        
        <div class="detail-actions">
            <button onclick="showVerifikasiModal('${pengajuan.id}')" class="btn btn-primary">
                <i class='bx bx-check'></i> Verifikasi Pengajuan
            </button>
            <button onclick="closeModal('detailModal')" class="btn btn-secondary">Tutup</button>
        </div>
    `;
    
    openModal('detailModal');
}

// Render berkas
function renderBerkas(berkasData) {
    if (!berkasData || Object.keys(berkasData).length === 0) {
        return '<p>Tidak ada berkas tersedia</p>';
    }
    
    const berkas = Array.isArray(berkasData) ? berkasData : [berkasData];
    
    return berkas.map(berkas => `
        <div class="berkas-card">
            <div class="berkas-header">
                <i class='bx ${berkas.type === 'pdf' ? 'bx-file-pdf' : 'bx-file'}'
                   style="color: ${berkas.type === 'pdf' ? '#e74c3c' : '#3498db'};"></i>
                <div>
                    <div class="berkas-name">${berkas.nama_file || berkas.nama}</div>
                    <div class="berkas-size">${formatFileSize(berkas.ukuran)}</div>
                </div>
            </div>
            <button onclick="downloadBerkas('${berkas.id || berkas.nama_file}')" class="berkas-btn">
                <i class='bx bx-download'></i> Download
            </button>
        </div>
    `).join('');
}

// Render verifikasi info
function renderVerifikasiInfo(pengajuan) {
    return `
        <div class="detail-section">
            <h4><i class='bx bx-check-shield'></i> Informasi Verifikasi</h4>
            <div class="detail-card">
                <div class="verifikasi-grid">
                    <div class="verifikasi-item">
                        <label>Tanggal Verifikasi</label>
                        <span>${formatDate(pengajuan.tanggal_verifikasi || pengajuan.updated_at)}</span>
                    </div>
                    <div class="verifikasi-item">
                        <label>Verifikator</label>
                        <span>${pengajuan.verifikator?.nama || pengajuan.verifikator || '-'}</span>
                    </div>
                    <div class="verifikasi-item full">
                        <label>Catatan</label>
                        <span>${pengajuan.catatan_verifikasi || pengajuan.catatan || '-'}</span>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Show verifikasi modal
function showVerifikasiModal(pengajuanId) {
    const pengajuan = state.pengajuan.find(p => p.id === pengajuanId);
    if (!pengajuan) return;
    
    document.getElementById('verifikasiId').value = pengajuanId;
    document.getElementById('pengajuanId').value = pengajuanId;
    document.getElementById('verifikasiTitle').textContent = `Verifikasi - ${pengajuan.user?.nama || pengajuan.nama}`;
    document.getElementById('statusVerifikasi').value = pengajuan.status || '';
    document.getElementById('catatanVerifikasi').value = pengajuan.catatan_verifikasi || '';
    document.getElementById('bidangFinal').value = pengajuan.bidang_penempatan || pengajuan.bidang?.id || '';
    
    closeModal('detailModal');
    openModal('verifikasiModal');
}

// Handle verifikasi submit
async function handleVerifikasiSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const pengajuanId = document.getElementById('pengajuanId').value;
    
    try {
        showSubmitLoading(true);
        
        const response = await fetch(`${API_CONFIG.endpoints.verifikasi}/${pengajuanId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify(Object.fromEntries(formData))
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Gagal melakukan verifikasi');
        }
        
        showNotification('Verifikasi berhasil disimpan', 'success');
        closeModal('verifikasiModal');
        fetchPengajuan(); // Refresh data
        
    } catch (error) {
        console.error('Error:', error);
        showNotification(error.message || 'Gagal melakukan verifikasi', 'error');
    } finally {
        showSubmitLoading(false);
    }
}

// Download berkas
async function downloadBerkas(berkasId) {
    try {
        const response = await fetch(`${API_CONFIG.endpoints.download}/${berkasId}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        if (!response.ok) throw new Error('Gagal mendownload berkas');
        
        // Handle file download
        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `berkas-${berkasId}`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
        
        showNotification('Berkas berhasil didownload', 'success');
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal mendownload berkas', 'error');
    }
}

// Pagination
function nextPage() {
    const totalPages = Math.ceil(state.filteredPengajuan.length / state.itemsPerPage);
    if (state.currentPage < totalPages) {
        state.currentPage++;
        renderTable();
    }
}

function prevPage() {
    if (state.currentPage > 1) {
        state.currentPage--;
        renderTable();
    }
}

// Helper functions
function getStatusConfig(status) {
    const configs = {
        'pending': { class: 'status-pending', text: 'MENUNGGU' },
        'accepted': { class: 'status-approved', text: 'DITERIMA' },
        'rejected': { class: 'status-rejected', text: 'DITOLAK' }
    };
    return configs[status] || { class: 'status-pending', text: 'MENUNGGU' };
}

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
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
}

function formatFileSize(bytes) {
    if (!bytes) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function updatePageInfo(start, end, total) {
    document.getElementById('pageInfo').textContent = 
        `Menampilkan ${start} - ${end} dari ${total} pengajuan`;
}

function updatePaginationButtons() {
    const totalPages = Math.ceil(state.filteredPengajuan.length / state.itemsPerPage);
    document.getElementById('prevPageBtn').disabled = state.currentPage <= 1;
    document.getElementById('nextPageBtn').disabled = state.currentPage >= totalPages;
}

// Modal functions
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Loading states
function showLoading(context, isLoading) {
    const loaders = {
        'table': () => {
            const loadingRow = document.getElementById('loadingRow');
            if (loadingRow) {
                loadingRow.style.display = isLoading ? 'table-row' : 'none';
            }
        },
        'detail': () => {
            const modalContent = document.getElementById('modalContent');
            if (modalContent && isLoading) {
                modalContent.innerHTML = `
                    <div style="text-align: center; padding: 40px;">
                        <i class='bx bx-loader-circle bx-spin' style="font-size: 3rem; color: var(--primary);"></i>
                        <div style="margin-top: 15px; color: #666;">Memuat detail...</div>
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
    const btn = document.getElementById('submitVerifikasiBtn');
    if (btn) {
        btn.disabled = show;
        btn.innerHTML = show 
            ? '<i class="bx bx-loader-circle bx-spin"></i> Memproses...'
            : '<i class="bx bx-check"></i> Simpan Verifikasi';
    }
}

// Utility functions
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

function showNotification(message, type = 'info') {
    // Reuse the notification function from admin.js or create a simple one
    if (typeof window.showNotification === 'function') {
        window.showNotification(message, type);
        return;
    }
    
    // Simple notification implementation
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 10px;">
            <i class='bx ${type === 'success' ? 'bx-check-circle' : type === 'error' ? 'bx-error-circle' : 'bx-info-circle'}'></i>
            <span>${message}</span>
        </div>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#d4edda' : type === 'error' ? '#f8d7da' : '#d1ecf1'};
        color: ${type === 'success' ? '#155724' : type === 'error' ? '#721c24' : '#0c5460'};
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        z-index: 1000;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Add notification animation
if (!document.querySelector('#notification-animation')) {
    const style = document.createElement('style');
    style.id = 'notification-animation';
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);
}
</script>
@endsection