@extends('layouts.admin-bidang')

@section('title', 'Manajemen Mentor')
@section('subtitle', 'Kelola Mentor Bidang')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin-bidang/admin-bidang.css') }}">
@endsection

@section('content')
<div class="content-header">
    <div class="header-actions">
        <button class="btn btn-primary" onclick="showAddMentorModal()">
            <i class='bx bx-plus-circle'></i> Tambah Mentor
        </button>
    </div>
</div>

<!-- Filter Section (Hanya pencarian nama) -->
<div class="filter-container">
    <div class="filter-grid">
        <div class="filter-group">
            <label for="searchInput" class="filter-label">
                <i class='bx bx-search'></i> Cari Mentor
            </label>
            <input type="text" id="searchInput" placeholder="Cari berdasarkan nama..." 
                   class="filter-input">
        </div>
    </div>
</div>

<!-- Mentor Table -->
<div class="table-container">
    <div class="table-header">
        <h3>Daftar Mentor Bidang</h3>
        <span class="table-count" id="mentorCount">0 mentor</span>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Nama Mentor</th>
                <th>Status</th>
                <th>Jumlah Bimbingan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="mentorTableBody">
            <!-- Data akan dimuat via AJAX -->
            <tr id="loadingRow">
                <td colspan="4" style="text-align: center; padding: 50px 20px;">
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
        <div class="pagination-info" id="pageInfo">Menampilkan 0 - 0 dari 0 mentor</div>
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

<!-- Modal Tambah/Edit Mentor -->
<div id="mentorModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3 id="modalMentorTitle">Tambah Mentor Baru</h3>
            <button class="modal-close" onclick="closeMentorModal()">&times;</button>
        </div>
        <form id="mentorForm">
            @csrf
            <div class="modal-body">
                <input type="hidden" id="editMentorId">
                
                <div class="form-group">
                    <label for="nama_mentor">Nama Mentor *</label>
                    <input type="text" id="nama_mentor" name="nama_mentor" required 
                           placeholder="Masukkan nama mentor" maxlength="100">
                </div>

                <div class="form-group">
                    <label for="jumlah_bimbingan_maks">Jumlah Bimbingan Maksimal *</label>
                    <input type="number" id="jumlah_bimbingan_maks" name="jumlah_bimbingan_maks" 
                            required min="1" max="10" value="5">
                            <small>Maksimal jumlah peserta yang dapat dibimbing</small>
                </div>
                
                <div class="form-group">
                    <label for="no_hp">No. Telepon</label>
                    <input type="text" id="no_hp" name="no_hp" 
                           placeholder="08xxxxxxxxxx">
                </div>

                <div class="form-group">
                    <label for="catatan_mentor">Catatan/Keterangan</label>
                    <textarea id="catatan_mentor" name="catatan" rows="3" 
                            placeholder="Catatan tambahan tentang mentor..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="status_mentor">Status *</label>
                    <select id="status_mentor" name="status" required>
                        <option value="aktif" selected>Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeMentorModal()">Batal</button>
                <button type="submit" class="btn btn-primary" id="modalMentorSubmitBtn">
                    <span id="submitBtnText">Simpan Mentor</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Mentor -->
<div id="detailModal" class="modal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header">
            <h3 id="detailMentorTitle">Detail Mentor</h3>
            <button class="modal-close" onclick="closeDetailModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="detailModalContent">
                <!-- Konten akan dimuat via JavaScript -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeDetailModal()">Tutup</button>
        </div>
    </div>
</div>

<!-- Modal Hapus Mentor -->
<div id="deleteMentorModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Konfirmasi Hapus Mentor</h3>
            <button class="modal-close" onclick="closeDeleteMentorModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="warning-icon" style="text-align: center; margin-bottom: 20px;">
                <i class='bx bx-error-circle' style="font-size: 4rem; color: #e74c3c;"></i>
            </div>
            
            <p style="text-align: center; margin-bottom: 20px;">
                Apakah Anda yakin ingin menghapus mentor <strong id="deleteMentorName"></strong>?
            </p>
            
            <div class="alert alert-info">
                <i class='bx bx-info-circle'></i>
                <div>
                    <strong>Informasi Penting</strong>
                    <p>Data yang akan terpengaruh:</p>
                    <ul style="margin-top: 10px; padding-left: 20px;">
                        <li>Peserta yang dibimbing akan kehilangan mentor</li>
                        <li>Semua data bimbingan akan dihapus</li>
                        <li>Akun mentor tetap ada di sistem dengan role yang sama</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeDeleteMentorModal()">Batal</button>
            <button class="btn btn-danger" id="confirmDeleteBtn" onclick="confirmDeleteMentor()">
                <i class='bx bx-trash'></i> Hapus Mentor
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// **PERHATIAN:**
// File ini berisi JavaScript untuk halaman admin-bidang/mentor.
// Data dummy telah dihapus dan struktur API disiapkan untuk Backend nantinya.

// ============================
// KONFIGURASI API (Backend-ready)
// ============================
const API_CONFIG = {
    baseUrl: window.location.origin,
    endpoints: {
        // Endpoint untuk mentor (akan dibuat di backend)
        mentor: '/api/admin-bidang/mentor',
        mentorWithBimbingan: '/api/admin-bidang/mentor/with-bimbingan',
        // Endpoint untuk peserta yang bisa menjadi mentor (dari bidang ini)
        usersByBidang: '/api/admin-bidang/users/by-bidang',
        // Endpoint untuk bimbingan mentor
        bimbinganByMentor: '/api/admin-bidang/bimbingan/by-mentor'
    }
};

// ============================
// STATE MANAGEMENT
// ============================
let state = {
    mentorList: [],
    filteredMentorList: [],
    currentMentorDetail: null,
    mentorToDelete: null,
    currentPage: 1,
    itemsPerPage: 10,
    totalPages: 1,
    totalItems: 0,
    currentFilters: {
        search: ''
    }
};

// ============================
// INISIALISASI
// ============================
document.addEventListener('DOMContentLoaded', function() {
    setupCSRF();
    fetchMentorData();
    setupEventListeners();
});

// Setup CSRF token untuk Laravel
function setupCSRF() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    if (token) {
        window.csrfToken = token;
    }
}

// Setup event listeners
function setupEventListeners() {
    document.getElementById('mentorForm').addEventListener('submit', handleMentorSubmit);
    
    // Pencarian real-time dengan debounce
    document.getElementById('searchInput').addEventListener('input', function(e) {
        state.currentFilters.search = e.target.value;
        debounce(filterMentorData, 300)();
    });
}

// ============================
// FUNGSI API (Backend-ready)
// ============================

// Fetch data mentor dari backend
async function fetchMentorData() {
    try {
        showLoading(true);
        
        // **STRUKTUR API UNTUK BACKEND:**
        // GET /api/admin-bidang/mentor?page=1&per_page=10
        const response = await fetch(
            `${API_CONFIG.endpoints.mentor}?page=${state.currentPage}&per_page=${state.itemsPerPage}`,
            {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }
        );
        
        if (!response.ok) throw new Error('Gagal mengambil data mentor');
        
        const data = await response.json();
        
        // **STRUKTUR RESPONSE BACKEND YANG DIHARAPKAN:**
        // {
        //     "data": [array of mentors],
        //     "meta": {
        //         "total": 100,
        //         "per_page": 10,
        //         "current_page": 1,
        //         "last_page": 10
        //     }
        // }
        state.mentorList = data.data || [];
        state.totalItems = data.meta?.total || data.total || state.mentorList.length;
        state.totalPages = data.meta?.last_page || Math.ceil(state.totalItems / state.itemsPerPage);
        
        filterMentorData();
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat data mentor', 'error');
        renderEmptyTable('Terjadi kesalahan saat memuat data');
    } finally {
        showLoading(false);
    }
}

// Filter data mentor berdasarkan pencarian
function filterMentorData() {
    const searchTerm = state.currentFilters.search.toLowerCase();
    
    if (!searchTerm) {
        state.filteredMentorList = [...state.mentorList];
    } else {
        state.filteredMentorList = state.mentorList.filter(mentor => {
            const mentorName = mentor.nama?.toLowerCase() || mentor.name?.toLowerCase() || '';
            return mentorName.includes(searchTerm);
        });
    }
    
    renderMentorTable();
    updatePageInfo();
}

// ============================
// RENDER TABLE
// ============================
function renderMentorTable() {
    const tbody = document.getElementById('mentorTableBody');
    
    if (!state.filteredMentorList || state.filteredMentorList.length === 0) {
        renderEmptyTable(
            state.currentFilters.search 
                ? 'Tidak ditemukan mentor dengan nama tersebut' 
                : 'Belum ada data mentor'
        );
        return;
    }
    
    const start = (state.currentPage - 1) * state.itemsPerPage;
    const end = start + state.itemsPerPage;
    const pageData = state.filteredMentorList.slice(start, end);
    
    tbody.innerHTML = pageData.map(mentor => {
        const statusClass = mentor.status === 'aktif' ? 'status-active' : 'status-inactive';
        const statusText = mentor.status === 'aktif' ? 'Aktif' : 'Nonaktif';
        const jumlahBimbingan = mentor.jumlah_bimbingan || mentor.total_bimbingan || 0;
        
        return `
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="avatar">${getInitials(mentor.nama || mentor.name)}</div>
                        <div>
                            <div style="font-weight: 600; color: var(--primary);">${mentor.nama || mentor.name}</div>
                            <div style="font-size: 0.85rem; color: #666;">
                                ${mentor.email || ''}
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="status-badge ${statusClass}">${statusText}</span>
                </td>
                <td>
                    <div style="font-weight: 600; color: var(--primary);">${jumlahBimbingan}</div>
                    <div style="font-size: 0.85rem; color: #666;">peserta</div>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view" title="Lihat Detail" onclick="showDetailModal('${mentor.id}')">
                            <i class='bx bx-show'></i>
                        </button>
                        <button class="action-btn edit" title="Edit" onclick="editMentor('${mentor.id}')">
                            <i class='bx bx-edit'></i>
                        </button>
                        <button class="action-btn delete" title="Hapus" onclick="showDeleteMentorModal('${mentor.id}', '${mentor.nama || mentor.name}')">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
    
    document.getElementById('mentorCount').textContent = `${state.filteredMentorList.length} mentor`;
    updatePageInfo();
    updatePaginationButtons();
}

function renderEmptyTable(message) {
    const tbody = document.getElementById('mentorTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="4" style="text-align: center; padding: 50px 20px;">
                <div style="display: flex; flex-direction: column; align-items: center; gap: 15px;">
                    <i class='bx bx-user-x' style="font-size: 3rem; color: #ccc;"></i>
                    <div style="color: #888; font-weight: 500;">${message}</div>
                    ${!state.currentFilters.search ? `
                        <button class="btn btn-primary btn-sm" onclick="showAddMentorModal()">
                            <i class='bx bx-plus'></i> Tambah Mentor Pertama
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
function showAddMentorModal() {
    document.getElementById('modalMentorTitle').textContent = 'Tambah Mentor Baru';
    document.getElementById('mentorForm').reset();
    document.getElementById('editMentorId').value = '';
    document.getElementById('status_mentor').value = 'aktif';
    document.getElementById('submitBtnText').textContent = 'Simpan Mentor';
    openModal('mentorModal');
}

async function editMentor(id) {
    try {
        showLoading('modal', true);
        
        // **API BACKEND:** GET /api/admin-bidang/mentor/{id}
        const response = await fetch(`${API_CONFIG.endpoints.mentor}/${id}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) throw new Error('Gagal mengambil data mentor');
        
        const data = await response.json();
        const mentor = data.data || data;
        
        document.getElementById('modalMentorTitle').textContent = 'Edit Mentor';
        document.getElementById('editMentorId').value = mentor.id;
        document.getElementById('nama_mentor').value = mentor.nama || mentor.name;
        document.getElementById('email_mentor').value = mentor.email;
        document.getElementById('no_hp').value = mentor.no_hp || mentor.telepon || '';
        document.getElementById('status_mentor').value = mentor.status || 'aktif';
        document.getElementById('submitBtnText').textContent = 'Update Mentor';
        
        openModal('mentorModal');
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat data mentor', 'error');
    } finally {
        showLoading('modal', false);
    }
}

// ============================
// FORM HANDLING
// ============================
async function handleMentorSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const mentorId = document.getElementById('editMentorId').value;
    const isEdit = !!mentorId;
    
    try {
        showSubmitLoading(true);
        
        // **STRUKTUR API BACKEND:**
        // POST /api/admin-bidang/mentor (untuk tambah)
        // PUT /api/admin-bidang/mentor/{id} (untuk edit)
        const url = isEdit 
            ? `${API_CONFIG.endpoints.mentor}/${mentorId}`
            : API_CONFIG.endpoints.mentor;
        
        const method = isEdit ? 'PUT' : 'POST';
        
        const jsonData = {};
        formData.forEach((value, key) => {
            jsonData[key] = value;
        });
        
        // Tambahkan CSRF token untuk Laravel
        jsonData._token = window.csrfToken;
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify(jsonData)
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Request failed');
        }
        
        showNotification(
            isEdit ? 'Data mentor berhasil diupdate' : 'Mentor berhasil ditambahkan',
            'success'
        );
        
        closeMentorModal();
        fetchMentorData();
        
    } catch (error) {
        console.error('Error:', error);
        showNotification(error.message || 'Gagal menyimpan data', 'error');
    } finally {
        showSubmitLoading(false);
    }
}

// ============================
// DETAIL MODAL
// ============================
async function showDetailModal(id) {
    try {
        showLoading('detail', true);
        
        // **API BACKEND:** GET /api/admin-bidang/mentor/{id}/detail
        const response = await fetch(`${API_CONFIG.endpoints.mentor}/${id}/detail`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (!response.ok) throw new Error('Gagal mengambil detail mentor');
        
        const data = await response.json();
        const mentor = data.data || data;
        
        renderDetailModal(mentor);
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat detail mentor', 'error');
    } finally {
        showLoading('detail', false);
    }
}

function renderDetailModal(mentor) {
    state.currentMentorDetail = mentor;
    
    const statusClass = mentor.status === 'aktif' ? 'status-active' : 'status-inactive';
    const statusText = mentor.status === 'aktif' ? 'Aktif' : 'Nonaktif';
    
    document.getElementById('detailMentorTitle').textContent = `Detail Mentor - ${mentor.nama || mentor.name}`;
    
    document.getElementById('detailModalContent').innerHTML = `
        <div class="detail-section">
            <div class="detail-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div class="avatar" style="width: 60px; height: 60px; font-size: 1.2rem;">
                        ${getInitials(mentor.nama || mentor.name)}
                    </div>
                    <div>
                        <h3 style="margin: 0; color: var(--primary);">${mentor.nama || mentor.name}</h3>
                        <p style="margin: 5px 0 0 0; color: #666; font-size: 0.9rem;">
                            Status: <span class="status-badge ${statusClass}">${statusText}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="detail-section">
            <h4 style="color: var(--primary); margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                <i class='bx bx-info-circle'></i> Informasi Kontak
            </h4>
            
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Email:</label>
                    <span>${mentor.email || '-'}</span>
                </div>
                <div class="detail-item">
                    <label>No. Telepon:</label>
                    <span>${mentor.no_hp || mentor.telepon || '-'}</span>
                </div>
                <div class="detail-item">
                    <label>Tanggal Bergabung:</label>
                    <span>${formatDate(mentor.created_at)}</span>
                </div>
            </div>
        </div>
        
        <div class="detail-section">
            <div class="section-header">
                <h4 style="color: var(--primary); margin: 0; display: flex; align-items: center; gap: 10px;">
                    <i class='bx bx-user'></i> Peserta yang Dibimbing
                    <span style="background: #e3f2fd; color: #1976d2; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem;">
                        ${mentor.jumlah_bimbingan || 0} peserta
                    </span>
                </h4>
            </div>
            
            ${renderPesertaList(mentor.peserta || [])}
        </div>
    `;
    
    openModal('detailModal');
}

function renderPesertaList(peserta) {
    if (!peserta || peserta.length === 0) {
        return `
            <div class="no-data">
                <i class='bx bx-user-x'></i>
                <p>Belum ada peserta yang dibimbing</p>
            </div>
        `;
    }
    
    let html = '<div style="margin-top: 15px;">';
    peserta.forEach((p, index) => {
        html += `
            <div style="display: flex; align-items: center; justify-content: space-between; 
                        padding: 12px; border-bottom: 1px solid #eee; ${index % 2 === 0 ? 'background: #f8f9fa;' : ''}">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 35px; height: 35px; border-radius: 50%; 
                                background: linear-gradient(45deg, var(--primary), var(--secondary));
                                color: white; display: flex; align-items: center; justify-content: center; 
                                font-weight: 600; font-size: 0.9rem;">
                        ${getInitials(p.nama || p.name)}
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary);">${p.nama || p.name}</div>
                        <div style="font-size: 0.85rem; color: #666;">${p.nim || '-'}</div>
                    </div>
                </div>
                <span class="status-badge status-active">Aktif</span>
            </div>
        `;
    });
    html += '</div>';
    
    return html;
}

// ============================
// DELETE FUNCTIONS
// ============================
function showDeleteMentorModal(id, name) {
    state.mentorToDelete = id;
    document.getElementById('deleteMentorName').textContent = name;
    openModal('deleteMentorModal');
}

async function confirmDeleteMentor() {
    if (!state.mentorToDelete) return;
    
    try {
        showLoading('delete', true);
        
        // **API BACKEND:** DELETE /api/admin-bidang/mentor/{id}
        const response = await fetch(`${API_CONFIG.endpoints.mentor}/${state.mentorToDelete}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to delete mentor');
        }
        
        showNotification('Mentor berhasil dihapus', 'success');
        closeDeleteMentorModal();
        fetchMentorData();
        
    } catch (error) {
        console.error('Error:', error);
        showNotification(error.message || 'Gagal menghapus mentor', 'error');
    } finally {
        showLoading('delete', false);
    }
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
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    } catch (e) {
        return dateString;
    }
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

// Pagination
function nextPage() {
    if (state.currentPage < state.totalPages) {
        state.currentPage++;
        fetchMentorData();
    }
}

function prevPage() {
    if (state.currentPage > 1) {
        state.currentPage--;
        fetchMentorData();
    }
}

function updatePageInfo() {
    const start = (state.currentPage - 1) * state.itemsPerPage + 1;
    const end = Math.min(state.currentPage * state.itemsPerPage, state.totalItems);
    document.getElementById('pageInfo').textContent = 
        `Menampilkan ${start} - ${end} dari ${state.totalItems} mentor`;
}

function updatePaginationButtons() {
    document.getElementById('prevPageBtn').disabled = state.currentPage <= 1;
    document.getElementById('nextPageBtn').disabled = state.currentPage >= state.totalPages;
}

// Modal functions
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
}

function closeMentorModal() {
    document.getElementById('mentorModal').style.display = 'none';
}

function closeDetailModal() {
    document.getElementById('detailModal').style.display = 'none';
    state.currentMentorDetail = null;
}

function closeDeleteMentorModal() {
    document.getElementById('deleteMentorModal').style.display = 'none';
    state.mentorToDelete = null;
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
        'modal': () => {
            const modalContent = document.getElementById('detailModalContent');
            if (modalContent && isLoading) {
                modalContent.innerHTML = `
                    <div style="text-align: center; padding: 40px;">
                        <i class='bx bx-loader-circle bx-spin' style="font-size: 3rem; color: var(--primary);"></i>
                        <div style="margin-top: 15px; color: #666;">Memuat detail mentor...</div>
                    </div>
                `;
            }
        },
        'detail': () => {
            const modalContent = document.getElementById('detailModalContent');
            if (modalContent && isLoading) {
                modalContent.innerHTML = `
                    <div style="text-align: center; padding: 40px;">
                        <i class='bx bx-loader-circle bx-spin' style="font-size: 3rem; color: var(--primary);"></i>
                        <div style="margin-top: 15px; color: #666;">Memuat detail mentor...</div>
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
                    : '<i class="bx bx-trash"></i> Hapus Mentor';
            }
        }
    };
    
    if (loaders[context]) {
        loaders[context]();
    }
}

function showSubmitLoading(show) {
    const btn = document.getElementById('modalMentorSubmitBtn');
    if (btn) {
        btn.disabled = show;
        btn.innerHTML = show 
            ? '<i class="bx bx-loader-circle bx-spin"></i> Memproses...'
            : '<i class="bx bx-save"></i> Simpan Mentor';
    }
}

// Notification function (reusable)
function showNotification(message, type = 'info') {
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

// Add notification animation if not exists
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