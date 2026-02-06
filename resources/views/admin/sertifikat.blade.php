@extends('layouts.admin')

@section('title', 'Manajemen Sertifikat')
@section('subtitle', 'Upload Sertifikat Peserta Magang')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endsection

@section('content')

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card border-blue">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="totalPeserta">0</div>
                <div class="stat-label">Total Peserta</div>
            </div>
            <div class="stat-icon blue">
                <i class='bx bx-user'></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card border-green">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="sudahSertifikat">0</div>
                <div class="stat-label">Sudah Upload</div>
            </div>
            <div class="stat-icon green">
                <i class='bx bx-check-circle'></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card border-orange">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="belumSertifikat">0</div>
                <div class="stat-label">Belum Upload</div>
            </div>
            <div class="stat-icon orange">
                <i class='bx bx-time'></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card border-purple">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="selesaiMagang">0</div>
                <div class="stat-label">Selesai Magang</div>
            </div>
            <div class="stat-icon purple">
                <i class='bx bx-calendar-check'></i>
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
            <input type="text" id="searchInput" placeholder="Nama, NIM, bidang..." 
                   class="filter-input">
        </div>
        
        <div class="filter-group">
            <label for="statusFilter" class="filter-label">
                <i class='bx bx-filter-alt'></i> Status Sertifikat
            </label>
            <select id="statusFilter" class="filter-select">
                <option value="all">Semua Status</option>
                <option value="sudah">Sudah Upload</option>
                <option value="belum">Belum Upload</option>
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
            <button onclick="filterPeserta()" class="btn btn-primary">
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
        <h3>Daftar Peserta Magang</h3>
        <div class="table-actions">
            <span class="table-count" id="pesertaCount">0 peserta</span>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Peserta</th>
                <th>Universitas</th>
                <th>Bidang</th>
                <th>Periode Magang</th>
                <th>Status</th>
                <th>Sertifikat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="pesertaTable">
            <!-- Data akan dimuat via AJAX -->
            <tr id="loadingRow">
                <td colspan="7" style="text-align: center; padding: 50px 20px;">
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
        <div class="pagination-info" id="pageInfo">Menampilkan 0 - 0 dari 0 peserta</div>
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

<!-- Modal Detail Peserta -->
<div id="detailModal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3 id="modalTitle">Detail Peserta Magang</h3>
            <button class="modal-close" onclick="closeModal('detailModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div id="detailModalContent">
                <!-- Konten akan dimuat via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Sertifikat -->
<div id="uploadModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3 id="uploadTitle">Upload Sertifikat</h3>
            <button class="modal-close" onclick="closeModal('uploadModal')">&times;</button>
        </div>
        <form id="uploadForm" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <input type="hidden" id="uploadPesertaId" name="peserta_id">
                
                <div class="detail-section">
                    <div class="detail-header">
                        <div class="detail-title">
                            <div class="detail-avatar" id="uploadAvatar"></div>
                            <div>
                                <h4 id="uploadNama"></h4>
                                <p id="uploadNim"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h4><i class='bx bx-file-pdf'></i> File Sertifikat</h4>
                    
                    <div class="form-group">
                        <label for="nomorSertifikat">Nomor Sertifikat *</label>
                        <input type="text" id="nomorSertifikat" name="nomor_sertifikat" required 
                               placeholder="Contoh: SK/DISKOMINFO/2024/001">
                    </div>
                    
                    <div class="form-group">
                        <label for="tanggalTerbit">Tanggal Terbit *</label>
                        <input type="date" id="tanggalTerbit" name="tanggal_terbit" required 
                               value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="fileSertifikat">File Sertifikat (PDF) *</label>
                        <div class="file-upload-container">
                            <input type="file" id="fileSertifikat" name="file_sertifikat" 
                                   accept=".pdf" required class="file-input" 
                                   onchange="previewFileName(this)">
                            <label for="fileSertifikat" class="file-upload-label">
                                <div class="file-upload-content">
                                    <i class='bx bx-cloud-upload'></i>
                                    <div class="file-upload-text">
                                        <div class="file-upload-title">Klik untuk upload file</div>
                                        <div class="file-upload-subtitle">Format PDF, maksimal 5MB</div>
                                    </div>
                                </div>
                                <div class="file-name" id="fileName">Belum ada file dipilih</div>
                            </label>
                        </div>
                        <small id="fileError" style="color: #e74c3c; display: none;"></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('uploadModal')">Batal</button>
                <button type="submit" class="btn btn-primary" id="submitUploadBtn">
                    <i class='bx bx-upload'></i> Upload Sertifikat
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Lihat Sertifikat -->
<div id="viewSertifikatModal" class="modal">
    <div class="modal-content" style="max-width: 900px;">
        <div class="modal-header">
            <h3 id="viewSertifikatTitle">Detail Sertifikat</h3>
            <button class="modal-close" onclick="closeModal('viewSertifikatModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div id="viewSertifikatContent">
                <!-- Konten akan dimuat via JavaScript -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('viewSertifikatModal')">Tutup</button>
            <button type="button" class="btn btn-primary" onclick="downloadSertifikat()" id="downloadBtn">
                <i class='bx bx-download'></i> Download Sertifikat
            </button>
        </div>
    </div>
</div>

<!-- Modal Hapus Sertifikat -->
<div id="deleteSertifikatModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Konfirmasi Hapus Sertifikat</h3>
            <button class="modal-close" onclick="closeModal('deleteSertifikatModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus sertifikat untuk <strong id="deletePesertaName"></strong>?</p>
            
            <div class="warning-box">
                <i class='bx bx-error'></i>
                <div>
                    <strong>Peringatan!</strong>
                    <p>Sertifikat yang dihapus tidak dapat dikembalikan. Pastikan sudah membuat backup file sertifikat.</p>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('deleteSertifikatModal')">Batal</button>
            <button class="btn btn-danger" id="confirmDeleteBtn" onclick="confirmDeleteSertifikat()">
                <i class='bx bx-trash'></i> Hapus Sertifikat
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Konfigurasi API
const API_CONFIG = {
    baseUrl: window.location.origin,
    endpoints: {
        peserta: '/api/admin/peserta/sertifikat',
        bidang: '/api/admin/bidang',
        sertifikat: {
            upload: '/api/admin/sertifikat/upload',
            detail: '/api/admin/sertifikat',
            download: '/api/admin/sertifikat/download',
            delete: '/api/admin/sertifikat'
        }
    }
};

// State management
let state = {
    pesertaList: [],
    filteredPeserta: [],
    bidangList: [],
    currentPage: 1,
    itemsPerPage: 10,
    totalPages: 1,
    totalItems: 0,
    currentPesertaId: null,
    currentSertifikatId: null,
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
    fetchPesertaData();
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
        debounce(filterPeserta, 300)();
    });
    
    document.getElementById('statusFilter').addEventListener('change', function(e) {
        state.currentFilters.status = e.target.value;
        filterPeserta();
    });
    
    document.getElementById('bidangFilter').addEventListener('change', function(e) {
        state.currentFilters.bidang = e.target.value;
        filterPeserta();
    });
    
    document.getElementById('uploadForm').addEventListener('submit', handleUploadSubmit);
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
        populateBidangFilter();
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat data bidang', 'error');
    }
}

function populateBidangFilter() {
    const bidangFilter = document.getElementById('bidangFilter');
    bidangFilter.innerHTML = '<option value="all">Semua Bidang</option>';
    
    state.bidangList.forEach(bidang => {
        const option = document.createElement('option');
        option.value = bidang.id || bidang.nama_bidang;
        option.textContent = bidang.nama_bidang;
        bidangFilter.appendChild(option);
    });
}

// Fetch data peserta
async function fetchPesertaData() {
    try {
        showLoading(true);
        
        let url = `${API_CONFIG.endpoints.peserta}?page=${state.currentPage}&per_page=${state.itemsPerPage}`;
        
        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) throw new Error('Gagal mengambil data peserta');
        
        const data = await response.json();
        state.pesertaList = data.data || data.peserta || [];
        state.totalItems = data.meta?.total || data.total || state.pesertaList.length;
        state.totalPages = Math.ceil(state.totalItems / state.itemsPerPage);
        
        filterPeserta();
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat data peserta', 'error');
        renderEmptyTable('Terjadi kesalahan saat memuat data');
    } finally {
        showLoading(false);
    }
}

// Filter peserta
function filterPeserta() {
    const searchTerm = state.currentFilters.search.toLowerCase();
    const statusFilter = state.currentFilters.status;
    const bidangFilter = state.currentFilters.bidang;
    
    state.filteredPeserta = state.pesertaList.filter(peserta => {
        // Filter pencarian
        if (searchTerm) {
            const matchesSearch = 
                peserta.nama?.toLowerCase().includes(searchTerm) ||
                peserta.nim?.toLowerCase().includes(searchTerm) ||
                peserta.universitas?.toLowerCase().includes(searchTerm) ||
                peserta.bidang?.nama_bidang?.toLowerCase().includes(searchTerm);
            if (!matchesSearch) return false;
        }
        
        // Filter status sertifikat
        if (statusFilter !== 'all') {
            const hasSertifikat = peserta.sertifikat !== null;
            if (statusFilter === 'sudah' && !hasSertifikat) return false;
            if (statusFilter === 'belum' && hasSertifikat) return false;
        }
        
        // Filter bidang
        if (bidangFilter !== 'all') {
            const bidangId = peserta.bidang?.id || peserta.id_bidang;
            if (bidangId != bidangFilter) return false;
        }
        
        return true;
    });
    
    // Sort by tanggal selesai (terbaru pertama)
    state.filteredPeserta.sort((a, b) => {
        const dateA = a.tanggal_selesai_actual || a.tanggal_selesai;
        const dateB = b.tanggal_selesai_actual || b.tanggal_selesai;
        return new Date(dateB) - new Date(dateA);
    });
    
    renderTable();
    updateStats();
}

function isSelesaiMagang(peserta) {
    if (peserta.status_magang === 'selesai') return true;
    if (peserta.tanggal_selesai) {
        const selesai = new Date(peserta.tanggal_selesai);
        const today = new Date();
        selesai.setHours(0, 0, 0, 0);
        today.setHours(0, 0, 0, 0);
        return selesai <= today;
    }
    return false;
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
    filterPeserta();
}

// Render tabel
function renderTable() {
    const container = document.getElementById('pesertaTable');
    const totalItems = state.filteredPeserta.length;
    
    if (totalItems === 0) {
        renderEmptyTable('Tidak ada peserta ditemukan');
        updatePageInfo(0, 0, 0);
        return;
    }
    
    const start = (state.currentPage - 1) * state.itemsPerPage;
    const end = start + state.itemsPerPage;
    const pageData = state.filteredPeserta.slice(start, end);
    
    container.innerHTML = pageData.map(peserta => {
        const tanggalMulai = formatDate(peserta.tanggal_mulai);
        const tanggalSelesai = formatDate(peserta.tanggal_selesai);
        const hasSertifikat = peserta.sertifikat !== null;
        const isSelesai = isSelesaiMagang(peserta);
        const bidangNama = peserta.bidang?.nama_bidang || '-';
        
        return `
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="user-avatar">${getInitials(peserta.nama)}</div>
                        <div>
                            <div style="font-weight: 600; color: var(--primary);">${peserta.nama}</div>
                            <div style="font-size: 0.85rem; color: #666;">${peserta.nim || ''}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="font-weight: 500;">${peserta.universitas || '-'}</div>
                    <div style="font-size: 0.85rem; color: #666;">${peserta.jurusan || peserta.program_studi || ''}</div>
                </td>
                <td>
                    <span class="bidang-badge">${bidangNama}</span>
                </td>
                <td>
                    <div style="color: #666;">${tanggalMulai} - ${tanggalSelesai}</div>
                </td>
                <td>
                    <span class="status-badge ${isSelesai ? 'status-selesai' : 'status-berjalan'}">
                        ${isSelesai ? 'SELESAI' : 'BERJALAN'}
                    </span>
                </td>
                <td>
                    ${hasSertifikat ? `
                        <div class="sertifikat-info">
                            <i class='bx bx-check-circle' style="color: #2ecc71; font-size: 1.2rem;"></i>
                            <span style="font-size: 0.85rem; color: #2ecc71;">Sudah Upload</span>
                        </div>
                    ` : `
                        <div class="sertifikat-info">
                            <i class='bx bx-time' style="color: #e74c3c; font-size: 1.2rem;"></i>
                            <span style="font-size: 0.85rem; color: #e74c3c;">Belum Upload</span>
                        </div>
                    `}
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view" onclick="showDetail('${peserta.id}')" title="Lihat Detail">
                            <i class='bx bx-show'></i>
                        </button>
                        ${isSelesai ? `
                            ${hasSertifikat ? `
                                <button class="action-btn delete" onclick="showDeleteSertifikatModal('${peserta.id}', '${peserta.nama}')" title="Hapus Sertifikat">
                                    <i class='bx bx-trash'></i>
                                </button>
                            ` : `
                                <button class="action-btn upload" onclick="showUploadModal('${peserta.id}')" title="Upload Sertifikat">
                                    <i class='bx bx-upload'></i>
                                </button>
                            `}
                        ` : ''}
                    </div>
                </td>
            </tr>
        `;
    }).join('');
    
    updatePageInfo(start + 1, Math.min(end, totalItems), totalItems);
    updatePaginationButtons();
}

function renderEmptyTable(message) {
    const container = document.getElementById('pesertaTable');
    container.innerHTML = `
        <tr>
            <td colspan="7" style="text-align: center; padding: 50px 20px;">
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
    const totalPeserta = state.filteredPeserta.length;
    const sudahSertifikat = state.filteredPeserta.filter(p => p.sertifikat !== null).length;
    const belumSertifikat = state.filteredPeserta.filter(p => p.sertifikat === null).length;
    const selesaiMagang = state.filteredPeserta.filter(p => isSelesaiMagang(p)).length;
    
    document.getElementById('totalPeserta').textContent = totalPeserta;
    document.getElementById('sudahSertifikat').textContent = sudahSertifikat;
    document.getElementById('belumSertifikat').textContent = belumSertifikat;
    document.getElementById('selesaiMagang').textContent = selesaiMagang;
    document.getElementById('pesertaCount').textContent = `${totalPeserta} peserta`;
}

// Detail peserta
async function showDetail(pesertaId) {
    try {
        showLoading('detail', true);
        
        const peserta = state.pesertaList.find(p => p.id == pesertaId);
        if (!peserta) {
            throw new Error('Data peserta tidak ditemukan');
        }
        renderDetailModal(peserta);
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat detail peserta', 'error');
    } finally {
        showLoading('detail', false);
    }
}

function renderDetailModal(peserta) {
    const tanggalMulai = formatDate(peserta.tanggal_mulai);
    const tanggalSelesai = formatDate(peserta.tanggal_selesai);
    const isSelesai = isSelesaiMagang(peserta);
    const hasSertifikat = peserta.sertifikat !== null;
    const bidangNama = peserta.bidang?.nama_bidang || '-';
    
    document.getElementById('modalTitle').textContent = `Detail Peserta - ${peserta.nama}`;
    
    document.getElementById('detailModalContent').innerHTML = `
        <div class="detail-section">
            <div class="detail-header">
                <div class="detail-title">
                    <div class="detail-avatar">${getInitials(peserta.nama)}</div>
                    <div>
                        <h4>${peserta.nama}</h4>
                        <p>${peserta.nim || ''} • ${peserta.universitas || '-'}</p>
                    </div>
                </div>
                <span class="status-badge ${isSelesai ? 'status-selesai' : 'status-berjalan'}">
                    ${isSelesai ? 'SELESAI' : 'BERJALAN'}
                </span>
            </div>
        </div>
        
        <div class="detail-grid">
            <div class="detail-item">
                <label><i class='bx bx-envelope'></i> Email</label>
                <span>${peserta.email || '-'}</span>
            </div>
            <div class="detail-item">
                <label><i class='bx bx-phone'></i> No. Telepon</label>
                <span>${peserta.no_telp || '-'}</span>
            </div>
            <div class="detail-item">
                <label><i class='bx bx-book'></i> Program Studi</label>
                <span>${peserta.jurusan || '-'}</span>
            </div>
            <div class="detail-item">
                <label><i class='bx bx-briefcase'></i> Bidang</label>
                <span class="bidang-badge">${bidangNama}</span>
            </div>
            <div class="detail-item">
                <label><i class='bx bx-calendar'></i> Periode Magang</label>
                <span>${tanggalMulai} - ${tanggalSelesai}</span>
            </div>
            <div class="detail-item">
                <label><i class='bx bx-calendar-check'></i> Status Magang</label>
                <span style="color: ${isSelesai ? '#2ecc71' : '#f39c12'}; font-weight: 600;">
                    ${isSelesai ? 'Selesai' : 'Sedang Berjalan'}
                </span>
            </div>
        </div>
        
        <div class="detail-section">
            <h4 style="color: var(--primary); margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                <i class='bx bx-certificate'></i> Status Sertifikat
            </h4>
            <div class="detail-card">
                ${hasSertifikat ? `
                    <div class="sertifikat-status success">
                        <i class='bx bx-check-circle'></i>
                        <div>
                            <div style="font-weight: 600; color: #2ecc71;">Sertifikat Sudah Tersedia</div>
                            <div style="font-size: 0.9rem; color: #666;">
                                Diupload pada ${formatDate(peserta.sertifikat.upload_date)}
                            </div>
                        </div>
                    </div>
                ` : `
                    ${isSelesai ? `
                        <div class="sertifikat-status warning">
                            <i class='bx bx-time'></i>
                            <div>
                                <div style="font-weight: 600; color: #f39c12;">Sertifikat Belum Diupload</div>
                                <div style="font-size: 0.9rem; color: #666;">
                                    Peserta telah menyelesaikan magang. Silakan upload sertifikat.
                                </div>
                            </div>
                        </div>
                    ` : `
                        <div class="sertifikat-status info">
                            <i class='bx bx-info-circle'></i>
                            <div>
                                <div style="font-weight: 600; color: #3498db;">Magang Masih Berjalan</div>
                                <div style="font-size: 0.9rem; color: #666;">
                                    Sertifikat dapat diupload setelah peserta menyelesaikan magang.
                                </div>
                            </div>
                        </div>
                    `}
                `}
            </div>
        </div>
        
        ${hasSertifikat ? `
            <div class="detail-section">
                <h4 style="color: var(--primary); margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                    <i class='bx bx-file-pdf'></i> Informasi Sertifikat
                </h4>
                <div class="detail-card">
                    <div class="sertifikat-grid">
                        <div class="sertifikat-item">
                            <label>Nomor Sertifikat</label>
                            <span>${peserta.sertifikat.nomor_sertifikat || peserta.sertifikat.nomor}</span>
                        </div>
                        <div class="sertifikat-item">
                            <label>Tanggal Terbit</label>
                            <span>${formatDate(peserta.sertifikat.tanggal_terbit)}</span>
                        </div>
                    </div>
                </div>
            </div>
        ` : ''}
        
        <div class="detail-actions">
            ${isSelesai && !hasSertifikat ? `
                <button onclick="showUploadModal('${peserta.id}')" class="btn btn-primary">
                    <i class='bx bx-upload'></i> Upload Sertifikat
                </button>
            ` : hasSertifikat ? `
                <button onclick="viewSertifikat('${peserta.id}')" class="btn btn-primary">
                    <i class='bx bx-file'></i> Lihat Sertifikat
                </button>
            ` : ''}
            <button onclick="closeModal('detailModal')" class="btn btn-secondary">Tutup</button>
        </div>
    `;
    
    openModal('detailModal');
}

// Upload modal
function showUploadModal(pesertaId) {
    const peserta = state.pesertaList.find(p => p.id == pesertaId);
    if (!peserta) return;
    
    state.currentPesertaId = pesertaId;
    
    document.getElementById('uploadPesertaId').value = pesertaId;
    document.getElementById('uploadTitle').textContent = `Upload Sertifikat - ${peserta.nama}`;
    document.getElementById('uploadNama').textContent = peserta.nama;
    document.getElementById('uploadNim').textContent = peserta.nim || '';
    document.getElementById('uploadAvatar').textContent = getInitials(peserta.nama);
    
    // Reset form
    document.getElementById('nomorSertifikat').value = `SK/DISKOMINFO/${new Date().getFullYear()}/XXX`;
    document.getElementById('tanggalTerbit').value = new Date().toISOString().split('T')[0];
    document.getElementById('fileSertifikat').value = '';
    document.getElementById('fileName').textContent = 'Belum ada file dipilih';
    document.getElementById('fileError').style.display = 'none';
    
    openModal('uploadModal');
}

// Handle upload submit
async function handleUploadSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const fileInput = document.getElementById('fileSertifikat');
    const file = fileInput.files[0];
    
    // Validate file
    if (!file) {
        showFileError('Pilih file sertifikat terlebih dahulu');
        return;
    }
    
    if (file.type !== 'application/pdf') {
        showFileError('File harus berformat PDF');
        return;
    }
    
    if (file.size > 5 * 1024 * 1024) { // 5MB
        showFileError('Ukuran file maksimal 5MB');
        return;
    }
    
    try {
        showSubmitLoading(true);
        
        const response = await fetch(API_CONFIG.endpoints.sertifikat.upload, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Gagal mengupload sertifikat');
        }
        
        showNotification('Sertifikat berhasil diupload', 'success');
        closeModal('uploadModal');
        fetchPesertaData(); // Refresh data
        
    } catch (error) {
        console.error('Error:', error);
        showNotification(error.message || 'Gagal mengupload sertifikat', 'error');
    } finally {
        showSubmitLoading(false);
    }
}

function showFileError(message) {
    const errorEl = document.getElementById('fileError');
    errorEl.textContent = message;
    errorEl.style.display = 'block';
}

// View sertifikat
async function viewSertifikat(pesertaId) {
    try {
        showLoading('view', true);
        state.currentPesertaId = pesertaId;
        
        const response = await fetch(`${API_CONFIG.endpoints.sertifikat.detail}/${pesertaId}`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (!response.ok) throw new Error('Gagal mengambil data sertifikat');
        
        const data = await response.json();
        const sertifikat = data.data || data;
        
        renderViewSertifikatModal(sertifikat);
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat detail sertifikat', 'error');
    } finally {
        showLoading('view', false);
    }
}

function renderViewSertifikatModal(sertifikat) {
    state.currentSertifikatId = sertifikat.id;
    const peserta = sertifikat.peserta
        || state.pesertaList.find(p => p.id == state.currentPesertaId)
        || {};

    document.getElementById('viewSertifikatTitle').textContent = `Sertifikat - ${peserta.nama || 'Tidak Diketahui'}`;
    
    document.getElementById('viewSertifikatContent').innerHTML = `
        <div class="detail-section">
            <div class="detail-header">
                <div class="detail-title">
                    <div class="detail-avatar">${getInitials(peserta.nama)}</div>
                    <div>
                        <h4></h4>
                        <p> • </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="pdf-preview">
            <i class='bx bx-file-pdf'></i>
            <div class="pdf-info">
                <div class="pdf-name">${sertifikat.nama_file}</div>
                <div class="pdf-details">
                    <span><i class='bx bx-hash'></i> ${sertifikat.nomor_sertifikat}</span>
                    <span><i class='bx bx-calendar'></i> ${formatDate(sertifikat.tanggal_terbit)}</span>
                    <span><i class='bx bx-data'></i> ${formatFileSize(sertifikat.ukuran_file)}</span>
                </div>
            </div>
        </div>
        
        <div class="sertifikat-details">
            <h4 style="color: var(--primary); margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                <i class='bx bx-info-circle'></i> Detail Sertifikat
            </h4>
            <div class="detail-card">
                <div class="sertifikat-grid">
                    <div class="sertifikat-item">
                        <label>Nomor Sertifikat</label>
                        <span class="nomor-sertifikat">${sertifikat.nomor_sertifikat}</span>
                    </div>
                    <div class="sertifikat-item">
                        <label>Tanggal Terbit</label>
                        <span>${formatDate(sertifikat.tanggal_terbit)}</span>
                    </div>
                    <div class="sertifikat-item">
                        <label>Tanggal Upload</label>
                        <span>${formatDate(sertifikat.created_at)}</span>
                    </div>
                    <div class="sertifikat-item">
                        <label>Status</label>
                        <span class="status-badge status-approved">TERBIT</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="sertifikat-preview-note">
            <i class='bx bx-info-circle'></i>
            <div>
                <strong>Informasi</strong>
                <p>Sertifikat ini akan tersedia di halaman peserta untuk diunduh.</p>
            </div>
        </div>
    `;
    
    openModal('viewSertifikatModal');
}

// Download sertifikat
async function downloadSertifikat() {
    if (!state.currentSertifikatId) return;
    
    try {
        const response = await fetch(`${API_CONFIG.endpoints.sertifikat.download}/${state.currentSertifikatId}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        if (!response.ok) throw new Error('Gagal mendownload sertifikat');
        
        // Handle file download
        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `sertifikat-${state.currentSertifikatId}.pdf`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
        
        showNotification('Sertifikat berhasil didownload', 'success');
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal mendownload sertifikat', 'error');
    }
}

// Delete sertifikat
function showDeleteSertifikatModal(pesertaId, nama) {
    state.currentPesertaId = pesertaId;
    document.getElementById('deletePesertaName').textContent = nama;
    openModal('deleteSertifikatModal');
}

async function confirmDeleteSertifikat() {
    if (!state.currentPesertaId) return;
    
    try {
        showLoading('delete', true);
        
        const response = await fetch(`${API_CONFIG.endpoints.sertifikat.delete}/${state.currentPesertaId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        if (!response.ok) {
            throw new Error('Gagal menghapus sertifikat');
        }
        
        showNotification('Sertifikat berhasil dihapus', 'success');
        closeModal('deleteSertifikatModal');
        fetchPesertaData(); // Refresh data
        
    } catch (error) {
        console.error('Error:', error);
        showNotification(error.message || 'Gagal menghapus sertifikat', 'error');
    } finally {
        showLoading('delete', false);
    }
}

// Pagination
function nextPage() {
    const totalPages = Math.ceil(state.filteredPeserta.length / state.itemsPerPage);
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
        `Menampilkan ${start} - ${end} dari ${total} peserta`;
}

function updatePaginationButtons() {
    const totalPages = Math.ceil(state.filteredPeserta.length / state.itemsPerPage);
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
            const modalContent = document.getElementById('detailModalContent');
            if (modalContent && isLoading) {
                modalContent.innerHTML = `
                    <div style="text-align: center; padding: 40px;">
                        <i class='bx bx-loader-circle bx-spin' style="font-size: 3rem; color: var(--primary);"></i>
                        <div style="margin-top: 15px; color: #666;">Memuat detail peserta...</div>
                    </div>
                `;
            }
        },
        'view': () => {
            const modalContent = document.getElementById('viewSertifikatContent');
            if (modalContent && isLoading) {
                modalContent.innerHTML = `
                    <div style="text-align: center; padding: 40px;">
                        <i class='bx bx-loader-circle bx-spin' style="font-size: 3rem; color: var(--primary);"></i>
                        <div style="margin-top: 15px; color: #666;">Memuat detail sertifikat...</div>
                    </div>
                `;
            }
        },
        'delete': () => {
            const btn = document.getElementById('confirmDeleteBtn');
            if (btn) {
                btn.disabled = isLoading;
                btn.innerHTML = isLoading 
                    ? '<i class="bx bx-loader-circle bx-spin"></i> Menghapus...'
                    : '<i class="bx bx-trash"></i> Hapus Sertifikat';
            }
        }
    };
    
    if (loaders[context]) {
        loaders[context]();
    }
}

function showSubmitLoading(show) {
    const btn = document.getElementById('submitUploadBtn');
    if (btn) {
        btn.disabled = show;
        btn.innerHTML = show 
            ? '<i class="bx bx-loader-circle bx-spin"></i> Mengupload...'
            : '<i class="bx bx-upload"></i> Upload Sertifikat';
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

function previewFileName(input) {
    const fileName = input.files[0] ? input.files[0].name : 'Belum ada file dipilih';
    document.getElementById('fileName').textContent = fileName;
    document.getElementById('fileError').style.display = 'none';
}


// Notification function (reuse from other files)
function showNotification(message, type = 'info') {
    // Avoid infinite recursion if this function is already on window
    if (window._adminSertifikatNotify && window._adminSertifikatNotify !== showNotification) {
        window._adminSertifikatNotify(message, type);
        return;
    }
    if (!window._adminSertifikatNotify) {
        window._adminSertifikatNotify = showNotification;
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
</script>
@endsection

