@extends('layouts.admin-bidang')

@section('title', 'Manajemen Mentor')
@section('subtitle', 'Kelola Mentor Bidang')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin-bidang/admin-bidang.css') }}">
@endsection

@section('content')

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
                <th>NIP</th>
                <th>Status</th>
                <th>Jumlah Bimbingan</th>
                <th class="w-24">Aksi</th>
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
@endsection

@section('scripts')
<script>

// C:\MAGANG\monitoring-magang\monitoring-magang\resources\views\admin-bidang\mentor.blade.php

// ============================
// KONFIGURASI API
// ============================
const API_CONFIG = {
    baseUrl: window.location.origin,
    endpoints: {
        mentor: '/api/admin-bidang/mentor',
        mentorDetail: '/api/admin-bidang/mentor'
    }
};

// ============================
// STATE MANAGEMENT
// ============================
let state = {
    mentorList: [],
    filteredMentorList: [],
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
    fetchMentorData();
    setupEventListeners();
});

// Setup event listeners
function setupEventListeners() {
    // Pencarian real-time dengan debounce
    document.getElementById('searchInput').addEventListener('input', function(e) {
        state.currentFilters.search = e.target.value;
        debounce(filterMentorData, 300)();
    });
}

// ============================
// FUNGSI API
// ============================

// Fetch data mentor dari backend
async function fetchMentorData() {
    try {
        showLoading(true);
        
        const response = await fetch(
            API_CONFIG.endpoints.mentor,
            {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }
        );
        
        if (!response.ok) throw new Error('Gagal mengambil data mentor');
        
        const data = await response.json();
        
        state.mentorList = data.data || [];
        state.totalItems = state.mentorList.length;
        state.totalPages = Math.ceil(state.totalItems / state.itemsPerPage);
        
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
            const mentorName = mentor.nama?.toLowerCase() || '';
            const mentorNip = mentor.nip?.toLowerCase() || '';
            const mentorEmail = mentor.email?.toLowerCase() || '';
            
            return mentorName.includes(searchTerm) || 
                   mentorNip.includes(searchTerm) || 
                   mentorEmail.includes(searchTerm);
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
                ? 'Tidak ditemukan mentor dengan kata kunci tersebut' 
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
        const jumlahBimbingan = mentor.jumlah_bimbingan || 0;
        
        return `
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="avatar">${getInitials(mentor.nama || '')}</div>
                        <div>
                            <div style="font-weight: 600; color: var(--primary);">${mentor.nama || '-'}</div>
                            <div style="font-size: 0.85rem; color: #666;">
                                ${mentor.email || ''}
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="font-family: monospace; font-size: 0.9rem;">${mentor.nip || '-'}</div>
                </td>
                <td>
                    <span class="status-badge ${statusClass}">${statusText}</span>
                </td>
                <td>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="font-weight: 600; color: var(--primary);">${jumlahBimbingan}</div>
                        <div style="font-size: 0.85rem; color: #666;">peserta</div>
                    </div>
                </td>
                <td>
                    <div class="action-buttons">
                        ${jumlahBimbingan > 0 ? `
                            <button class="action-btn view" title="Lihat Peserta yang Dibimbing" onclick="showMentorPeserta('${mentor.id}', '${mentor.nama}')">
                                <i class='bx bx-group'></i>
                            </button>
                        ` : `
                            <button class="action-btn view" title="Tidak ada peserta yang dibimbing" disabled style="opacity: 0.5; cursor: not-allowed;">
                                <i class='bx bx-group'></i>
                            </button>
                        `}
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
            <td colspan="5" style="text-align: center; padding: 50px 20px;">
                <div style="display: flex; flex-direction: column; align-items: center; gap: 15px;">
                    <i class='bx bx-user-x' style="font-size: 3rem; color: #ccc;"></i>
                    <div style="color: #888; font-weight: 500;">${message}</div>
                </div>
            </td>
        </tr>
    `;
}

// ============================
// DETAIL MODAL (Hanya lihat)
// ============================


// Fungsi untuk melihat peserta yang dibimbing (modal mini)
async function showMentorPeserta(mentorId, mentorName) {
    try {
        // Tampilkan loading
        const loadingHTML = `
            <div style="text-align: center; padding: 40px;">
                <i class='bx bx-loader-circle bx-spin' style="font-size: 2rem; color: var(--primary);"></i>
                <div style="margin-top: 15px; color: #666;">Memuat data peserta...</div>
            </div>
        `;
        
        // Buat modal
        const modalHTML = `
            <div class="modal" id="pesertaModal" style="display: flex;">
                <div class="modal-content" style="max-width: 700px; max-height: 80vh;">
                    <div class="modal-header">
                        <h3 style="display: flex; align-items: center; gap: 10px;">
                            <i class='bx bx-group'></i> Peserta yang Dibimbing
                        </h3>
                        <button class="modal-close" onclick="closeModal('pesertaModal')">&times;</button>
                    </div>
                    <div class="modal-body" id="pesertaModalContent">
                        ${loadingHTML}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeModal('pesertaModal')">Tutup</button>
                    </div>
                </div>
            </div>
        `;
        
        // Tambahkan modal ke body
        const modalContainer = document.createElement('div');
        modalContainer.innerHTML = modalHTML;
        document.body.appendChild(modalContainer);
        
        // Fetch data peserta
        const response = await fetch(`${API_CONFIG.endpoints.mentorDetail}/${mentorId}`, {
            headers: { 
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) throw new Error('Gagal mengambil data peserta');
        
        const data = await response.json();
        const mentor = data.data || data;
        
        // Render data peserta
        renderPesertaModalContent(mentor, mentorName);
        
    } catch (error) {
        console.error('Error:', error);
        
        // Update modal dengan error message
        const errorContent = document.getElementById('pesertaModalContent');
        if (errorContent) {
            errorContent.innerHTML = `
                <div style="text-align: center; padding: 40px; color: #e74c3c;">
                    <i class='bx bx-error-circle' style="font-size: 3rem; margin-bottom: 15px;"></i>
                    <h4 style="margin-bottom: 10px;">Gagal Memuat Data</h4>
                    <p>${error.message || 'Terjadi kesalahan saat mengambil data'}</p>
                </div>
            `;
        }
        
        showNotification('Gagal memuat data peserta', 'error');
    }
}

function renderPesertaModalContent(mentor, mentorName) {
    const content = document.getElementById('pesertaModalContent');
    
    let pesertaListHTML = '';
    if (mentor.peserta && mentor.peserta.length > 0) {
        pesertaListHTML = `
            <div class="table-container"style="margin-top: 15px; max-height: 400px; overflow: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8f9fa; position: sticky; top: 0;">
                        <tr>
                            <th style="padding: 12px 15px; text-align: left; border-bottom: 2px solid #dee2e6;">Nama Peserta</th>
                            <th style="padding: 12px 15px; text-align: left; border-bottom: 2px solid #dee2e6;">NIM</th>
                            <th style="padding: 12px 15px; text-align: left; border-bottom: 2px solid #dee2e6;">Universitas</th>
                            <th style="padding: 12px 15px; text-align: left; border-bottom: 2px solid #dee2e6;">Periode</th>
                            <th style="padding: 12px 15px; text-align: left; border-bottom: 2px solid #dee2e6;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${mentor.peserta.map((p, index) => `
                            <tr style="${index % 2 === 0 ? 'background: #f8f9fa;' : ''}">
                                <td style="padding: 12px 15px; border-bottom: 1px solid #eee;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div class="peserta-avatar">
                                            ${getInitials(p.nama)}
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: var(--primary);">${p.nama}</div>
                                            <div style="font-size: 0.8rem; color: #666;">${p.prodi || '-'}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #eee;">
                                    <div style="font-family: monospace; font-weight: 500;">${p.nim || '-'}</div>
                                </td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #eee;">
                                    <div>${p.universitas || '-'}</div>
                                </td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #eee;">
                                    <div style="font-size: 0.85rem;">
                                        <div>${formatDate(p.tanggal_mulai)}</div>
                                        <div style="color: #666; font-size: 0.8rem;">s/d ${formatDate(p.tanggal_selesai)}</div>
                                    </div>
                                </td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #eee;">
                                    <span class="status-badge status-active">Aktif</span>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
    } else {
        pesertaListHTML = `
            <div style="text-align: center; padding: 40px; color: #888;">
                <i class='bx bx-user-x' style="font-size: 3rem; margin-bottom: 15px;"></i>
                <h4 style="margin-bottom: 10px; color: #666;">Belum Ada Peserta</h4>
                <p>Mentor ini belum memiliki peserta yang dibimbing.</p>
            </div>
        `;
    }
    
    content.innerHTML = `
        <div class="mentor-header">
    <div class="mentor-header-card">

        <div class="mentor-avatar">
            ${getInitials(mentorName)}
        </div>

        <div class="mentor-info">
            <h4 class="mentor-name">${mentorName}</h4>

            <div class="mentor-meta">
                <div>
                    <span class="meta-label">NIP</span>
                    <span class="meta-value">${mentor.nip || '-'}</span>
                </div>

                <div>
                    <span class="meta-label">Email</span>
                    <span class="meta-value">${mentor.email || '-'}</span>
                </div>
            </div>
        </div>

        <div class="mentor-total">
            <span class="meta-label">Total Peserta</span>
            <span class="mentor-count">${mentor.peserta ? mentor.peserta.length : 0}</span>
        </div>
    </div>
</div>    
        ${pesertaListHTML}
    `;
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
        renderMentorTable();
        updatePaginationButtons();
    }
}

function prevPage() {
    if (state.currentPage > 1) {
        state.currentPage--;
        renderMentorTable();
        updatePaginationButtons();
    }
}

function updatePageInfo() {
    const start = ((state.currentPage - 1) * state.itemsPerPage) + 1;
    const end = Math.min(state.currentPage * state.itemsPerPage, state.filteredMentorList.length);
    document.getElementById('pageInfo').textContent = 
        `Menampilkan ${start} - ${end} dari ${state.filteredMentorList.length} mentor`;
}

function updatePaginationButtons() {
    document.getElementById('prevPageBtn').disabled = state.currentPage <= 1;
    document.getElementById('nextPageBtn').disabled = state.currentPage >= state.totalPages || state.totalPages === 0;
}

// Modal functions
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.remove(); // Hapus modal dari DOM
    }
    document.body.style.overflow = 'auto';
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
                        <div style="margin-top: 15px; color: #666;">Memuat detail mentor...</div>
                    </div>
                `;
            }
        }
    };
    
    if (loaders[context]) {
        loaders[context]();
    }
}

// Notification function
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
