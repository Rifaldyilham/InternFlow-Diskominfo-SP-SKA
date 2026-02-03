@extends('layouts.admin')

@section('title', 'Manajemen Bidang')
@section('subtitle', 'Kelola Bidang Magang')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endsection

@section('content')
<div class="content-header">
    <div class="header-actions">
        <button class="btn btn-primary" onclick="showAddBidangModal()">
            <i class='bx bx-plus-circle'></i> Tambah Bidang
        </button>
    </div>
</div>

<!-- Bidang Table -->
<div class="table-container">
    <div class="table-header">
        <h3>Daftar Bidang Magang</h3>
        <div class="table-actions">
            <button class="btn btn-secondary btn-sm" onclick="refreshData()">
                <i class='bx bx-refresh'></i> Refresh
            </button>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Nama Bidang</th>
                <th>Kuota</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="bidangTableBody">
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
</div>

<!-- Modal Tambah/Edit Bidang -->
<div id="bidangModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3 id="modalBidangTitle">Tambah Bidang Baru</h3>
            <button class="modal-close" onclick="closeBidangModal()">&times;</button>
        </div>
        <form id="bidangForm">
            @csrf
            <div class="modal-body">
                <input type="hidden" id="editBidangId">
                
                <div class="form-group">
                    <label for="nama_bidang">Nama Bidang *</label>
                    <input type="text" id="nama_bidang" name="nama_bidang" required 
                           placeholder="Masukkan nama bidang" maxlength="100">
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" 
                              placeholder="Deskripsi bidang (opsional)"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="kuota">Kuota</label>
                    <div class="input-with-info">
                        <input type="number" id="kuota" name="kuota" 
                               required min="1" max="50" value="10">
                        <div class="input-info">
                            <i class='bx bx-info-circle'></i>
                            <span>Jumlah maksimal peserta yang dapat ditampung</span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="status_bidang">Status *</label>
                    <select id="status_bidang" name="status" required>
                        <option value="aktif" selected>Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="penuh">Penuh</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeBidangModal()">Batal</button>
                <button type="submit" class="btn btn-primary" id="modalBidangSubmitBtn">
                    <span id="submitBtnText">Simpan Bidang</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Bidang -->
<div id="detailModal" class="modal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header">
            <h3 id="detailBidangTitle">Detail Bidang</h3>
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

<!-- Modal Hapus Bidang -->
<div id="deleteBidangModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Konfirmasi Hapus Bidang</h3>
            <button class="modal-close" onclick="closeDeleteBidangModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="warning-icon" style="text-align: center; margin-bottom: 20px;">
                <i class='bx bx-error-circle' style="font-size: 4rem; color: #e74c3c;"></i>
            </div>
            
            <p style="text-align: center; margin-bottom: 20px;">
                Apakah Anda yakin ingin menghapus bidang <strong id="deleteBidangName"></strong>?
            </p>
            
            <div class="alert alert-info">
                <i class='bx bx-info-circle'></i>
                <div>
                    <strong>Informasi Penting</strong>
                    <p>Data yang akan terpengaruh:</p>
                    <ul style="margin-top: 10px; padding-left: 20px;">
                        <li>Semua data terkait bidang akan dihapus</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeDeleteBidangModal()">Batal</button>
            <button class="btn btn-danger" id="confirmDeleteBtn" onclick="confirmDeleteBidang()">
                <i class='bx bx-trash'></i> Hapus Bidang
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
        bidang: '/api/admin/bidang',
        adminByBidang: (id) => `/api/admin/bidang/${id}/admin`,
        pesertaByBidang: (id) => `/api/admin/bidang/${id}/peserta`
    }
};

// State management
let state = {
    bidangList: [],
    currentBidangDetail: null,
    bidangToDelete: null,
    currentFilters: {
        status: 'all'
    }
};

// Inisialisasi
document.addEventListener('DOMContentLoaded', function() {
    setupCSRF();
    fetchBidangData();
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
    document.getElementById('bidangForm').addEventListener('submit', handleBidangSubmit);
}

// Fetch data bidang
async function fetchBidangData() {
    try {
        showLoading(true);
        
        const response = await fetch(API_CONFIG.endpoints.bidang, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) throw new Error('Gagal mengambil data bidang');
        
        const data = await response.json();
        state.bidangList = data.data || data;
        renderBidangTable();
        
    } catch (error) {
        console.error('Error:', error);
        localNotification('Gagal memuat data bidang', 'error');
        renderEmptyTable('Terjadi kesalahan saat memuat data');
    } finally {
        showLoading(false);
    }
}

// Render tabel
function renderBidangTable() {
    const tbody = document.getElementById('bidangTableBody');
    
    if (!state.bidangList || state.bidangList.length === 0) {
        renderEmptyTable('Belum ada data bidang');
        return;
    }
    
    tbody.innerHTML = state.bidangList.map(bidang => {
        const kuota = bidang.kuota || 0;
        // Pastikan kita mendapatkan jumlah peserta aktif yang benar
        const pesertaAktif = bidang.peserta_count || bidang.peserta_aktif || 0;
        
        const kuotaPercent = kuota > 0 ? Math.round((pesertaAktif / kuota) * 100) : 0;
        const kuotaTersedia = kuota - pesertaAktif;

        return `
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="bidang-icon" style="width: 40px; height: 40px; border-radius: 8px; 
                            background: ${getBidangColor(bidang.nama_bidang)}; 
                            color: white; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                            <i class='bx bx-briefcase-alt'></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--primary);">${bidang.nama_bidang}</div>
                            <div style="font-size: 0.85rem; color: #666;">
                                ${bidang.deskripsi ? bidang.deskripsi.substring(0, 60) + '...' : 'Tidak ada deskripsi'}
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="kuota-container">
                        <div class="kuota-info">
                            <span class="kuota-current">${pesertaAktif}</span>
                            <span class="kuota-separator">/</span>
                            <span class="kuota-max">${bidang.kuota}</span>
                            <span class="kuota-label">peserta</span>
                        </div>
                        <div class="kuota-progress-small">
                            <div class="progress-bar-small" style="width: ${kuotaPercent}%; 
                                background-color: ${getProgressBarColor(kuotaPercent)};">
                            </div>
                        </div>
                        <div class="kuota-tersedia">
                            <i class='bx bx-user-plus'></i> ${kuotaTersedia} tersedia
                        </div>
                    </div>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view" title="Lihat Detail" onclick="showDetailModal('${bidang.id_bidang}')">
                            <i class='bx bx-show'></i>
                        </button>
                        <button class="action-btn edit" title="Edit" onclick="editBidang('${bidang.id_bidang}')">
                            <i class='bx bx-edit'></i>
                        </button>
                        <button class="action-btn delete" title="Hapus" onclick="showDeleteBidangModal('${bidang.id_bidang}', '${bidang.nama_bidang}')">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function renderAdminInfo(admin) {
    return `
        <div class="admin-info">
            <div class="admin-name">${admin.name || admin.nama}</div>
            <div class="admin-email">${admin.email}</div>
            ${admin.status === 'nonaktif' ? '<span style="color: #e74c3c; font-size: 0.8rem;">(Nonaktif)</span>' : ''}
        </div>
    `;
}

function renderEmptyTable(message) {
    const tbody = document.getElementById('bidangTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="4" style="text-align: center; padding: 50px 20px;">
                <div style="display: flex; flex-direction: column; align-items: center; gap: 15px;">
                    <i class='bx bx-briefcase-alt' style="font-size: 3rem; color: #ccc;"></i>
                    <div style="color: #888; font-weight: 500;">${message}</div>
                    <button class="btn btn-primary btn-sm" onclick="showAddBidangModal()">
                        <i class='bx bx-plus'></i> Tambah Bidang Pertama
                    </button>
                </div>
            </td>
        </tr>
    `;
}

// Helper functions
function getBidangColor(bidangName) {
    const colors = {
        'Teknologi dan Informatika': '#2ecc71',
        'Statistika': '#3498db', // Diperbaiki dari 'Statistik' menjadi 'Statistika'
        'Sekretariat': '#9b59b6',
        'Komunikasi Publik dan Media': '#f39c12'
    };
    return colors[bidangName] || getComputedStyle(document.documentElement).getPropertyValue('--primary').trim();
}

function getProgressBarColor(percent) {
    if (percent >= 100) return '#ff4757';
    if (percent >= 80) return '#ffa502';
    if (percent >= 60) return '#2ed573';
    return '#3498db';
}

// Modal functions
function showAddBidangModal() {
    document.getElementById('modalBidangTitle').textContent = 'Tambah Bidang Baru';
    document.getElementById('bidangForm').reset();
    document.getElementById('editBidangId').value = '';
    document.getElementById('kuota').value = '10';
    document.getElementById('status_bidang').value = 'aktif';
    document.getElementById('submitBtnText').textContent = 'Simpan Bidang';
    openModal('bidangModal');
}

async function editBidang(id) {
    try {
        showLoading('modal', true);
        
        const response = await fetch(`${API_CONFIG.endpoints.bidang}/${id}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) throw new Error('Gagal mengambil data bidang');
        
        const data = await response.json();
        const bidang = data.data || data;
        
        document.getElementById('modalBidangTitle').textContent = 'Edit Bidang';
        document.getElementById('editBidangId').value = bidang.id_bidang;
        document.getElementById('nama_bidang').value = bidang.nama_bidang;
        document.getElementById('deskripsi').value = bidang.deskripsi || '';
        document.getElementById('kuota').value = bidang.kuota;
        document.getElementById('status_bidang').value = bidang.status || 'aktif';
        document.getElementById('submitBtnText').textContent = 'Update Bidang';
        
        openModal('bidangModal');
        
    } catch (error) {
        console.error('Error:', error);
        localNotification('Gagal memuat data bidang', 'error');
    } finally {
        showLoading('modal', false);
    }
}

function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
}

function closeBidangModal() {
    document.getElementById('bidangModal').style.display = 'none';
}

// Form submission
async function handleBidangSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const bidangId = document.getElementById('editBidangId').value;
    const isEdit = !!bidangId;
    
    try {
        showSubmitLoading(true);
        
        const url = isEdit 
            ? `${API_CONFIG.endpoints.bidang}/${bidangId}`
            : API_CONFIG.endpoints.bidang;
        
        const method = isEdit ? 'PUT' : 'POST';
        
        const jsonData = {};
        formData.forEach((value, key) => {
            jsonData[key] = value;
        });
        
        // Add CSRF token
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
        
        localNotification(
            isEdit ? 'Data bidang berhasil diupdate' : 'Bidang berhasil ditambahkan',
            'success'
        );
        
        closeBidangModal();
        fetchBidangData();
        
    } catch (error) {
        console.error('Error:', error);
        localNotification(error.message || 'Gagal menyimpan data', 'error');
    } finally {
        showSubmitLoading(false);
    }
}

// Detail Modal - FIXED VERSION
async function showDetailModal(id) {
    try {
        showLoading('detail', true);
        
        // Fetch bidang data
        const bidangResponse = await fetch(`${API_CONFIG.endpoints.bidang}/${id}`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (!bidangResponse.ok) throw new Error('Gagal mengambil data bidang');
        
        const bidangData = await bidangResponse.json();
        const bidang = bidangData.data || bidangData;
        
        // Fetch admin data
        let admin = null;
        const adminResponse = await fetch(`${API_CONFIG.endpoints.adminByBidang(id)}`, {
            headers: { 'Accept': 'application/json' }
        });
        if (adminResponse.ok) {
            const adminData = await adminResponse.json();
            admin = adminData.data || adminData || null;
        }
        
        // Fetch peserta data - Hanya peserta yang sudah diverifikasi
        let peserta = [];
        try {
            const pesertaResponse = await fetch(`${API_CONFIG.endpoints.pesertaByBidang(id)}`, {
                headers: { 'Accept': 'application/json' }
            });
            
            if (pesertaResponse.ok) {
                const pesertaData = await pesertaResponse.json();
                // Filter hanya peserta yang sudah diverifikasi
                peserta = Array.isArray(pesertaData.data) ? 
                    pesertaData.data.filter(p => p.status_verifikasi === 'terverifikasi') : 
                    (Array.isArray(pesertaData) ? 
                        pesertaData.filter(p => p.status_verifikasi === 'terverifikasi') : []);
            }
        } catch (error) {
            console.error('Error fetching peserta:', error);
            peserta = [];
        }
        
        renderDetailModal(bidang, admin, peserta);
        
    } catch (error) {
        console.error('Error:', error);
        localNotification('Gagal memuat detail bidang', 'error');
    } finally {
        showLoading('detail', false);
    }
}

function renderDetailModal(bidang, admin, peserta) {
    state.currentBidangDetail = bidang;
    
    const kuota = bidang.kuota || 0;
    const pesertaAktif = peserta.length || 0;
    const kuotaPercent = kuota > 0 ? Math.round((pesertaAktif / kuota) * 100) : 0;
    const kuotaTersedia = kuota - pesertaAktif;
    
    document.getElementById('detailBidangTitle').textContent = `Detail Bidang - ${bidang.nama_bidang}`;
    
    document.getElementById('detailModalContent').innerHTML = `
        <div class="detail-section">
            <div class="detail-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div class="bidang-icon-large" style="width: 60px; height: 60px; border-radius: 12px; 
                        background: ${getBidangColor(bidang.nama_bidang)}; 
                        color: white; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                        <i class='bx bx-briefcase-alt'></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; color: var(--primary);">${bidang.nama_bidang}</h3>
                        <p style="margin: 5px 0 0 0; color: #666; font-size: 0.9rem;">
                            Status: <span class="status-badge ${bidang.status === 'aktif' ? 'status-active' : 'status-inactive'}">${bidang.status}</span>
                        </p>
                    </div>
                </div>
            </div>
            
            ${bidang.deskripsi ? `
                <div class="detail-card" style="background: #f8f9fa; border-radius: 12px; padding: 15px; margin-bottom: 20px;">
                    <h4 style="margin: 0 0 10px 0; color: #666; font-size: 1rem;">Deskripsi Bidang</h4>
                    <p style="margin: 0; line-height: 1.6;">${bidang.deskripsi}</p>
                </div>
            ` : ''}
        </div>
        
        <div class="detail-section">
            <h4 style="color: var(--primary); margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                <i class='bx bx-user'></i> Informasi Kuota
            </h4>
            
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Kuota Saat Ini:</label>
                    <div style="font-weight: 600; font-size: 1.1rem;">
                        <span style="color: var(--primary);">${pesertaAktif}</span>
                        <span style="color: #666;"> / ${bidang.kuota} peserta</span>
                    </div>
                </div>
                
                <div class="detail-item">
                    <label>Kuota Tersedia:</label>
                    <div style="color: #2ed573; font-weight: 600; font-size: 1.1rem;">
                        ${kuotaTersedia} peserta
                    </div>
                </div>
            </div>
        </div>
        
        
        
        <div class="detail-section">
            <div class="section-header">
                <h4 style="color: var(--primary); margin: 0; display: flex; align-items: center; gap: 10px;">
                    <i class='bx bx-user'></i> Daftar Peserta (Terverifikasi)
                    <span style="background: #e3f2fd; color: #1976d2; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem;">
                        ${peserta.length} peserta
                    </span>
                </h4>
            </div>
            
            ${renderPesertaList(peserta)}
        </div>
    `;
    
    openModal('detailModal');
}

function renderPesertaList(peserta) {
    if (peserta.length === 0) {
        return `
            <div class="no-data">
                <i class='bx bx-user-x'></i>
                <p>Belum ada peserta yang sudah diverifikasi di bidang ini</p>
            </div>
        `;
    }
    
    let html = '<div class="peserta-table">';
    html += `
        <div class="table-header-small">
            <div style="grid-column: 1;">Nama</div>
            <div style="grid-column: 2;">NIM</div>
            <div style="grid-column: 3;">Universitas</div>
            <div style="grid-column: 4;">Program Studi</div>
        </div>
    `;
    
    peserta.forEach(p => {
        html += `
            <div class="peserta-row" style="display: grid; grid-template-columns: 2fr 1fr 2fr 2fr; padding: 12px 15px; border-bottom: 1px solid #eee; align-items: center;">
                <div class="peserta-cell" style="grid-column: 1; display: flex; align-items: center;">
                    <div class="peserta-avatar-small">${getInitials(p.nama)}</div>
                    <div class="peserta-info-small">
                        <div class="peserta-name">${p.nama || '-'}</div>
                    </div>
                </div>
                <div class="peserta-cell" style="grid-column: 2;">${p.nim || '-'}</div>
                <div class="peserta-cell" style="grid-column: 3;">${p.asal_univ || p.universitas || '-'}</div>
                <div class="peserta-cell" style="grid-column: 4;">${p.program_studi || p.prodi || '-'}</div>
            </div>
        `;
    });
    html += '</div>';
    
    return html;
}

function closeDetailModal() {
    document.getElementById('detailModal').style.display = 'none';
    state.currentBidangDetail = null;
}

// Delete Modal
function showDeleteBidangModal(id, name) {
    state.bidangToDelete = id;
    document.getElementById('deleteBidangName').textContent = name;
    openModal('deleteBidangModal');
}

function closeDeleteBidangModal() {
    document.getElementById('deleteBidangModal').style.display = 'none';
    state.bidangToDelete = null;
}

async function confirmDeleteBidang() {
    if (!state.bidangToDelete) return;
    
    try {
        showLoading('delete', true);
        
        const response = await fetch(`${API_CONFIG.endpoints.bidang}/${state.bidangToDelete}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to delete bidang');
        }
        
        localNotification('Bidang berhasil dihapus', 'success');
        closeDeleteBidangModal();
        fetchBidangData();
        
    } catch (error) {
        console.error('Error:', error);
        localNotification(error.message || 'Gagal menghapus bidang', 'error');
    } finally {
        showLoading('delete', false);
    }
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
                        <div style="margin-top: 15px; color: #666;">Memuat detail bidang...</div>
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
                        <div style="margin-top: 15px; color: #666;">Memuat detail bidang...</div>
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
                    : '<i class="bx bx-trash"></i> Hapus Bidang';
            }
        }
    };
    
    if (loaders[context]) {
        loaders[context]();
    }
}

function showSubmitLoading(show) {
    const btn = document.getElementById('modalBidangSubmitBtn');
    if (btn) {
        btn.disabled = show;
        btn.innerHTML = show 
            ? '<i class="bx bx-loader-circle bx-spin"></i> Memproses...'
            : '<i class="bx bx-save"></i> Simpan Bidang';
    }
}

// Utility functions
function getInitials(name) {
    if (!name) return '--';
    return name
        .split(' ')
        .map(n => n.charAt(0).toUpperCase())
        .join('')
        .substring(0, 2);
}

function refreshData() {
    fetchBidangData();
    localNotification('Data bidang diperbarui', 'success');
}

// Notification function
function localNotification(message, type = 'info') {
    // Reuse existing notification function or create simple one
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

// Tambahkan style untuk tampilan yang lebih baik
const style = document.createElement('style');
style.textContent = `
    .bidang-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: white;
        flex-shrink: 0;
    }
    
    .kuota-container {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .kuota-info {
        display: flex;
        align-items: center;
        gap: 4px;
        font-weight: 600;
    }
    
    .kuota-current {
        color: var(--primary);
        font-size: 1.1rem;
    }
    
    .kuota-max {
        color: #666;
    }
    
    .kuota-label {
        color: #888;
        font-size: 0.9rem;
        margin-left: 4px;
    }
    
    .kuota-progress-small {
        height: 6px;
        background: #f0f0f0;
        border-radius: 3px;
        overflow: hidden;
    }
    
    .progress-bar-small {
        height: 100%;
        border-radius: 3px;
        transition: width 0.3s;
    }
    
    .kuota-tersedia {
        font-size: 0.85rem;
        color: #2ed573;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    
    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        transition: all 0.2s;
    }
    
    .action-btn.view {
        background: rgba(155, 89, 182, 0.1);
        color: #9b59b6;
    }
    
    .action-btn.view:hover {
        background: #9b59b6;
        color: white;
    }
    
    .action-btn.edit {
        background: rgba(52, 152, 219, 0.1);
        color: #3498db;
    }
    
    .action-btn.edit:hover {
        background: #3498db;
        color: white;
    }
    
    .action-btn.delete {
        background: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
    }
    
    .action-btn.delete:hover {
        background: #e74c3c;
        color: white;
    }
    
    .no-data {
        text-align: center;
        padding: 40px 20px;
        color: #888;
    }
    
    .no-data i {
        font-size: 3rem;
        margin-bottom: 15px;
        display: block;
    }
    
    .no-data p {
        margin: 0;
        font-size: 0.95rem;
    }
    
    .peserta-avatar-small {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(45deg, var(--primary), var(--secondary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
        margin-right: 10px;
        flex-shrink: 0;
    }
    
    .peserta-name {
        font-weight: 600;
        color: var(--primary);
        font-size: 0.9rem;
    }
    
    .input-with-info {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    
    .input-info {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.85rem;
        color: #666;
    }
    
    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .status-active {
        background: rgba(46, 204, 113, 0.1);
        color: #27ae60;
    }
    
    .status-inactive {
        background: rgba(231, 76, 60, 0.1);
        color: #c0392b;
    }
    
    .admin-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .admin-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(45deg, var(--primary), var(--secondary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    .admin-name {
        font-weight: 600;
        color: var(--primary);
    }
    
    .admin-email {
        color: #666;
        font-size: 0.9rem;
    }
    
    .no-admin-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        color: #666;
    }
    
    .no-admin-card i {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #ccc;
        display: block;
    }
    
    .no-admin-card h5 {
        color: var(--primary);
        margin: 0 0 10px 0;
    }
    
    .no-admin-card p {
        margin: 0;
        font-size: 0.9rem;
    }
`;
document.head.appendChild(style);
</script>
@endsection