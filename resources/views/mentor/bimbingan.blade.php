@extends('layouts.mentor')

@section('title', 'Daftar Bimbingan')
@section('subtitle', 'Kelola peserta yang Anda bimbing')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/mentor/mentor.css') }}">
@endsection

@section('content')
<div class="mentor-dashboard">
    <!-- Stats Cards -->
    <div class="mentor-stats">
        <div class="mentor-stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="mentor-stat-value" id="totalPeserta">0</div>
                    <div class="mentor-stat-label">Total Peserta</div>
                </div>
                <i class='bx bx-group text-3xl text-primary'></i>
            </div>
        </div>
        
        <div class="mentor-stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="mentor-stat-value" id="pesertaAktif">0</div>
                    <div class="mentor-stat-label">Peserta Aktif</div>
                </div>
                <i class='bx bx-user-check text-3xl text-green-600'></i>
            </div>
        </div>
    </div>

    <!-- Filter Pencarian Nama Saja -->
    <div class="filter-container-mentor">
        <div class="flex flex-col md:flex-row md:items-end gap-4">
            <div class="flex-1">
                <label for="searchInput" class="filter-label-mentor">
                    <i class='bx bx-search'></i> Cari Peserta
                </label>
                <input type="text" id="searchInput" placeholder="Cari berdasarkan nama peserta..." 
                       class="filter-input-mentor w-full">
            </div>
            <div>
                <button onclick="resetSearch()" class="btn btn-secondary w-full md:w-auto flex items-center gap-2">
                    <i class='bx bx-reset'></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Daftar Peserta -->
    <div class="mentor-table-container">
        <div class="mentor-table-header">
            <h3>Daftar Peserta Bimbingan</h3>
            <span class="mentor-table-count" id="pesertaCount">0 peserta</span>
        </div>
        
        <table class="mentor-table">
            <thead>
                <tr>
                    <th>Nama Peserta</th>
                    <th>Universitas</th>
                    <th>Program Studi</th>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Selesai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="pesertaTableBody">
                <!-- Data akan diisi oleh JavaScript -->
                <tr id="loadingRow">
                    <td colspan="6" class="text-center py-12">
                        <div class="loading-skeleton-mentor flex flex-col items-center gap-5">
                            <i class='bx bx-loader-circle bx-spin text-4xl text-primary'></i>
                            <div class="text-center text-gray-600">
                                <div class="font-semibold mb-2">Memuat data peserta...</div>
                                <div class="text-sm">Mohon tunggu sebentar</div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="pagination-mentor">
            <div class="pagination-info-mentor" id="pageInfo">
                Menampilkan 0 - 0 dari 0 peserta
            </div>
            <div class="pagination-controls-mentor">
                <button id="prevPage" class="pagination-btn-mentor" disabled>
                    <i class='bx bx-chevron-left'></i>
                </button>
                <button id="nextPage" class="pagination-btn-mentor" disabled>
                    <i class='bx bx-chevron-right'></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Peserta -->
<div id="detailModal" class="modal-mentor">
    <div class="modal-content-mentor" style="max-width: 700px;">
        <div class="modal-header-mentor">
            <h3 class="modal-title-mentor">Detail Peserta</h3>
            <button class="modal-close-mentor" onclick="closeDetailModal()">&times;</button>
        </div>
        <div class="modal-body-mentor">
            <div id="detailModalContent">
                <!-- Konten akan diisi oleh JavaScript -->
            </div>
        </div>
        <div class="modal-footer-mentor">
            <button type="button" class="btn btn-secondary" onclick="closeDetailModal()">Tutup</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// ============================
// KONFIGURASI API (Backend-ready)
// ============================
const API_CONFIG = {
    baseUrl: window.location.origin,
    endpoints: {
        // Data mentor yang sudah login
        mentorProfile: '/api/mentor/profile',
        // Peserta yang dibimbing oleh mentor yang login
        pesertaBimbingan: '/api/mentor/peserta',
        // Statistik bimbingan
        statsBimbingan: '/api/mentor/stats',
        // Detail peserta
        detailPeserta: '/api/mentor/peserta',
        // Verifikasi logbook (untuk redirect)
        verifikasiLogbook: '/mentor/verifikasi',
        // Verifikasi absensi (untuk redirect)
        verifikasiAbsensi: '/mentor/absensi'
    }
};

// ============================
// STATE MANAGEMENT
// ============================
let state = {
    mentorData: null,
    pesertaList: [],
    filteredPeserta: [],
    currentPage: 1,
    itemsPerPage: 10,
    totalItems: 0,
    totalPages: 1,
    currentSearch: '',
    stats: {
        total: 0,
        aktif: 0,
    },
    selectedPesertaId: null
};

// ============================
// INISIALISASI
// ============================
document.addEventListener('DOMContentLoaded', function() {
    setupCSRF();
    loadInitialData();
    setupEventListeners();
});

function setupCSRF() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    if (token) {
        window.csrfToken = token;
    }
}

async function loadInitialData() {
    try {
        showLoading(true);
        
        // 1. Load profil mentor
        await loadMentorProfile();
        
        // 2. Load statistik
        await loadStats();
        
        // 3. Load daftar peserta
        await loadPesertaBimbingan();
        
    } catch (error) {
        console.error('Error loading initial data:', error);
        showNotification('Gagal memuat data', 'error');
    } finally {
        showLoading(false);
    }
}

// ============================
// FUNGSI API (Backend-ready)
// ============================

async function loadMentorProfile() {
    try {
        const response = await fetch(API_CONFIG.endpoints.mentorProfile, {
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });
        
        if (!response.ok) throw new Error('Gagal mengambil profil mentor');
        
        const data = await response.json();
        state.mentorData = data.data || data;
        
    } catch (error) {
        console.error('Error loading mentor profile:', error);
    }
}

async function loadStats() {
    try {
        const response = await fetch(API_CONFIG.endpoints.statsBimbingan, {
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
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

async function loadPesertaBimbingan() {
    try {
        const response = await fetch(
            `${API_CONFIG.endpoints.pesertaBimbingan}?page=${state.currentPage}&per_page=${state.itemsPerPage}`,
            {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            }
        );
        
        if (!response.ok) throw new Error('Gagal mengambil data peserta');
        
        const data = await response.json();
        
        // **STRUKTUR RESPONSE BACKEND YANG DIHARAPKAN:**
        // {
        //     "data": [
        //         {
        //             "id": 1,
        //             "nama": "John Doe",
        //             "nim": "123456789",
        //             "universitas": "Universitas Indonesia",
        //             "program_studi": "Teknik Informatika",
        //             "tanggal_masuk": "2024-01-15",
        //             "tanggal_selesai": "2024-06-15",
        //             "bidang": "Informatika",
        //             "status": "aktif"
        //         }
        //     ],
        //     "meta": {
        //         "total": 20,
        //         "per_page": 10,
        //         "current_page": 1,
        //         "last_page": 2
        //     }
        // }
        
        state.pesertaList = data.data || [];
        state.totalItems = data.meta?.total || data.total || state.pesertaList.length;
        state.totalPages = data.meta?.last_page || Math.ceil(state.totalItems / state.itemsPerPage);
        
        filterPeserta();
        updatePagination();
        
    } catch (error) {
        console.error('Error loading peserta:', error);
        renderEmptyTable('Terjadi kesalahan saat memuat data');
    }
}

// ============================
// UI FUNCTIONS
// ============================

function updateStatsUI() {
    document.getElementById('totalPeserta').textContent = state.stats.total || 0;
    document.getElementById('pesertaAktif').textContent = state.stats.aktif || 0;
}

function filterPeserta() {
    const searchQuery = document.getElementById('searchInput').value.toLowerCase();
    state.currentSearch = searchQuery;
    
    if (!searchQuery) {
        state.filteredPeserta = [...state.pesertaList];
    } else {
        state.filteredPeserta = state.pesertaList.filter(peserta => {
            const searchText = `${peserta.nama} ${peserta.nim}`.toLowerCase();
            return searchText.includes(searchQuery);
        });
    }
    
    renderPesertaTable();
    updatePesertaCount();
}

function renderPesertaTable() {
    const tbody = document.getElementById('pesertaTableBody');
    
    if (state.filteredPeserta.length === 0) {
        renderEmptyTable(
            state.currentSearch 
                ? 'Tidak ditemukan peserta dengan nama tersebut' 
                : 'Belum ada peserta yang Anda bimbing'
        );
        return;
    }
    
    const start = (state.currentPage - 1) * state.itemsPerPage;
    const end = start + state.itemsPerPage;
    const pageData = state.filteredPeserta.slice(start, end);
    
    tbody.innerHTML = pageData.map(peserta => {
        const isActive = peserta.status === 'aktif';
        const aktifClass = isActive ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
        
        return `
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="peserta-avatar-mentor">
                            ${getInitials(peserta.nama)}
                        </div>
                        <div>
                            <div class="peserta-name-mentor">${peserta.nama}</div>
                            <div class="text-sm text-gray-500">${peserta.nim || 'N/A'}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="font-medium">${peserta.universitas || '-'}</div>
                </td>
                <td>
                    <div class="font-medium">${peserta.program_studi || peserta.jurusan || '-'}</div>
                </td>
                <td>
                    <div class="text-sm font-medium">${formatDate(peserta.tanggal_masuk)}</div>
                </td>
                <td>
                    <div class="text-sm font-medium">${formatDate(peserta.tanggal_selesai)}</div>
                </td>
                <td>
                    <div class="mentor-action-buttons">
                        <button class="mentor-action-btn view" 
                                onclick="showDetailPeserta('${peserta.id}')"
                                title="Lihat Detail">
                            <i class='bx bx-show'></i>
                        </button>
                        <button class="mentor-action-btn primary"
                                onclick="goToLogbook('${peserta.id}', '${peserta.nama}')"
                                title="Verifikasi Logbook">
                            <i class='bx bx-notepad'></i>
                        </button>
                        <button class="mentor-action-btn success"
                                onclick="goToAbsensi('${peserta.id}', '${peserta.nama}')"
                                title="Verifikasi Absensi">
                            <i class='bx bx-calendar-check'></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function renderEmptyTable(message) {
    const tbody = document.getElementById('pesertaTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="6">
                <div class="empty-state-mentor">
                    <i class='bx bx-user-x'></i>
                    <h4>${message}</h4>
                    ${state.currentSearch ? `
                        <p>Silakan coba dengan kata kunci lain</p>
                        <button onclick="resetSearch()" class="btn btn-primary mt-4">
                            Reset Pencarian
                        </button>
                    ` : `
                        <p>Belum ada peserta yang ditempatkan untuk Anda bimbing</p>
                    `}
                </div>
            </td>
        </tr>
    `;
}

// ============================
// NAVIGASI KE VERIFIKASI
// ============================

function goToLogbook(pesertaId, pesertaNama) {
    // Simpan data peserta di localStorage untuk digunakan di halaman verifikasi
    const pesertaData = state.pesertaList.find(p => p.id == pesertaId);
    if (pesertaData) {
        localStorage.setItem('selectedPeserta', JSON.stringify({
            id: pesertaId,
            nama: pesertaNama,
            type: 'logbook'
        }));
    }
    
    // Redirect ke halaman verifikasi dengan parameter
    window.location.href = `${API_CONFIG.endpoints.verifikasiLogbook}?peserta=${pesertaId}&type=logbook`;
}

function goToAbsensi(pesertaId, pesertaNama) {
    // Simpan data peserta di localStorage untuk digunakan di halaman verifikasi
    const pesertaData = state.pesertaList.find(p => p.id == pesertaId);
    if (pesertaData) {
        localStorage.setItem('selectedPeserta', JSON.stringify({
            id: pesertaId,
            nama: pesertaNama,
            type: 'absensi'
        }));
    }
    
    // Redirect ke halaman verifikasi dengan parameter
    window.location.href = `${API_CONFIG.endpoints.verifikasiAbsensi}?peserta=${pesertaId}&type=absensi`;
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

function setupEventListeners() {
    document.getElementById('searchInput').addEventListener('input', 
        debounce(filterPeserta, 300)
    );
    
    document.getElementById('prevPage').addEventListener('click', prevPage);
    document.getElementById('nextPage').addEventListener('click', nextPage);
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

function resetSearch() {
    document.getElementById('searchInput').value = '';
    state.currentSearch = '';
    filterPeserta();
}

function updatePesertaCount() {
    document.getElementById('pesertaCount').textContent = 
        `${state.filteredPeserta.length} peserta`;
}

function updatePagination() {
    const totalPages = Math.ceil(state.filteredPeserta.length / state.itemsPerPage);
    const start = ((state.currentPage - 1) * state.itemsPerPage) + 1;
    const end = Math.min(state.currentPage * state.itemsPerPage, state.filteredPeserta.length);
    
    document.getElementById('pageInfo').textContent = 
        `Menampilkan ${start} - ${end} dari ${state.filteredPeserta.length} peserta`;
    
    document.getElementById('prevPage').disabled = state.currentPage === 1;
    document.getElementById('nextPage').disabled = state.currentPage === totalPages || totalPages === 0;
}

function prevPage() {
    if (state.currentPage > 1) {
        state.currentPage--;
        renderPesertaTable();
        updatePagination();
    }
}

function nextPage() {
    const totalPages = Math.ceil(state.filteredPeserta.length / state.itemsPerPage);
    if (state.currentPage < totalPages) {
        state.currentPage++;
        renderPesertaTable();
        updatePagination();
    }
}

// ============================
// MODAL FUNCTIONS
// ============================

async function showDetailPeserta(pesertaId) {
    try {
        showModalLoading(true);
        
        const response = await fetch(`${API_CONFIG.endpoints.detailPeserta}/${pesertaId}`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });
        
        if (!response.ok) throw new Error('Gagal mengambil detail peserta');
        
        const data = await response.json();
        const peserta = data.data || data;
        
        renderDetailModal(peserta);
        openModal('detailModal');
        
    } catch (error) {
        console.error('Error loading detail:', error);
        showNotification('Gagal memuat detail peserta', 'error');
    } finally {
        showModalLoading(false);
    }
}

function renderDetailModal(peserta) {
    const modalContent = document.getElementById('detailModalContent');
    const isActive = peserta.status === 'aktif';
    
    modalContent.innerHTML = `
        <div class="space-y-6">
            <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-lg">
                <div class="peserta-avatar-mentor" style="width: 60px; height: 60px; font-size: 1.2rem;">
                    ${getInitials(peserta.nama)}
                </div>
                <div>
                    <h4 class="font-bold text-lg">${peserta.nama}</h4>
                    <div class="text-gray-600">${peserta.nim || 'N/A'}</div>
                    <div class="mt-1">
                        <span class="px-3 py-1 rounded-full text-sm ${isActive ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                            ${isActive ? 'Aktif' : 'Tidak Aktif'}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-500">Universitas</label>
                    <div class="font-medium">${peserta.universitas || '-'}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Program Studi</label>
                    <div class="font-medium">${peserta.program_studi || peserta.jurusan || '-'}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">NIM</label>
                    <div class="font-medium">${peserta.nim || '-'}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Bidang</label>
                    <div class="font-medium">${peserta.bidang || '-'}</div>
                </div>
            </div>
            
            <div>
                <h5 class="font-semibold mb-3 text-primary">Periode Magang</h5>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <label class="text-sm text-gray-500 block mb-1">Tanggal Masuk</label>
                        <div class="font-medium">${formatDate(peserta.tanggal_masuk)}</div>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <label class="text-sm text-gray-500 block mb-1">Tanggal Selesai</label>
                        <div class="font-medium">${formatDate(peserta.tanggal_selesai)}</div>
                    </div>
                </div>
            </div>
            
            ${peserta.email ? `
                <div>
                    <h5 class="font-semibold mb-3 text-primary">Kontak</h5>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-2 mb-2">
                            <i class='bx bx-envelope text-gray-500'></i>
                            <span class="font-medium">${peserta.email}</span>
                        </div>
                        ${peserta.no_hp ? `
                            <div class="flex items-center gap-2">
                                <i class='bx bx-phone text-gray-500'></i>
                                <span class="font-medium">${peserta.no_hp}</span>
                            </div>
                        ` : ''}
                    </div>
                </div>
            ` : ''}
            
            <div class="flex gap-3 mt-6">
                <button onclick="goToLogbook('${peserta.id}', '${peserta.nama}')" 
                        class="flex-1 btn btn-primary flex items-center justify-center gap-2">
                    <i class='bx bx-notepad'></i>
                    Verifikasi Logbook
                </button>
                <button onclick="goToAbsensi('${peserta.id}', '${peserta.nama}')" 
                        class="flex-1 btn btn-success flex items-center justify-center gap-2">
                    <i class='bx bx-calendar-check'></i>
                    Verifikasi Absensi
                </button>
            </div>
        </div>
    `;
}

function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeDetailModal() {
    document.getElementById('detailModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// ============================
// LOADING FUNCTIONS
// ============================

function showLoading(show) {
    const loadingRow = document.getElementById('loadingRow');
    if (loadingRow) {
        loadingRow.style.display = show ? 'table-row' : 'none';
    }
}

function showModalLoading(show) {
    const modalContent = document.getElementById('detailModalContent');
    if (modalContent && show) {
        modalContent.innerHTML = `
            <div class="text-center py-10">
                <i class='bx bx-loader-circle bx-spin text-4xl text-primary'></i>
                <div class="mt-4 text-gray-600">Memuat detail peserta...</div>
            </div>
        `;
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

// ============================
// CONTOH DATA (Hapus ini saat backend sudah siap)
// ============================
// Hanya untuk testing UI, hapus saat backend siap
function initializeMockData() {
    // Mock data untuk development
    state.pesertaList = [
        {
            id: 1,
            nama: "Ahmad Fauzi",
            nim: "123456789",
            universitas: "Universitas Indonesia",
            program_studi: "Teknik Informatika",
            tanggal_masuk: "2024-01-15",
            tanggal_selesai: "2024-06-15",
            bidang: "Informatika",
            status: "aktif",
            email: "ahmad@example.com",
            no_hp: "08123456789"
        },
        {
            id: 2,
            nama: "Siti Rahma",
            nim: "987654321",
            universitas: "Universitas Gadjah Mada",
            program_studi: "Sistem Informasi",
            tanggal_masuk: "2024-02-01",
            tanggal_selesai: "2024-07-01",
            bidang: "E-Government",
            status: "aktif",
            email: "siti@example.com",
            no_hp: "08234567890"
        }
    ];
    
    state.stats = {
        total: 2,
        aktif: 2,
    };
    
    updateStatsUI();
    filterPeserta();
}

// Untuk testing saja, komentari saat backend siap
// initializeMockData();
</script>
@endsection