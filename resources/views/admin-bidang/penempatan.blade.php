@extends('layouts.admin-bidang')

@section('title', 'Penempatan Peserta')
@section('subtitle', 'Tempatkan peserta yang sudah diverifikasi ke mentor')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin-bidang/admin-bidang.css') }}">
@endsection

@section('content')

<!-- Filter dan Pencarian -->
<div class="form-card mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium mb-2 text-gray-700">Status Penempatan</label>
            <select id="filterStatus" class="form-select">
                <option value="unassigned">Belum Ditempatkan</option>
                <option value="assigned">Sudah Ditempatkan</option>
                <option value="all">Semua Status</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium mb-2 text-gray-700">Cari Peserta</label>
            <div class="relative">
                <input type="text" id="searchPeserta" placeholder="Nama atau NIM..." 
                       class="form-input pl-10">
                <i class='bx bx-search absolute left-3 top-3 text-gray-400'></i>
            </div>
        </div>
        
        <div class="flex items-end">
            <button onclick="resetFilters()" class="btn btn-secondary w-full flex items-center justify-center gap-1">
                <i class='bx bx-reset'></i> Reset Filter
            </button>
        </div>
    </div>
    
    <div class="mt-4 flex justify-between items-center">
        <div class="text-sm text-gray-600">
            <span id="filterResultCount">0</span> peserta ditemukan
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 gap-6">
    <!-- Daftar Peserta -->
    <div>
        <div class="form-card">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-primary flex items-center gap-2">
                    </i> Daftar Peserta di Bidang Ini
                </h3>
                <div class="table-count">
                    Peserta sudah diverifikasi kepegawaian
                </div>
            </div>
            
            <!-- Tabel Peserta -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Peserta</th>
                            <th>Universitas</th>
                            <th>Program Studi</th>
                            <th>Tanggal Mulai</th>
                            <th>Status</th>
                            <th class="w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="pesertaTableBody">
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
            </div>
            
            <!-- Empty State -->
            <div id="emptyState" class="hidden text-center py-12">
                <i class='bx bx-user-x text-4xl text-gray-300 mb-4'></i>
                <h4 class="text-lg font-medium text-gray-500 mb-2">Tidak ada peserta</h4>
                <p class="text-gray-400 mb-6">Tidak ada peserta yang sesuai dengan filter yang dipilih</p>
                <button onclick="resetFilters()" class="btn btn-primary">
                    <i class='bx bx-reset'></i> Reset Filter
                </button>
            </div>
            
            <!-- Pagination -->
            <div class="mt-6 flex justify-between items-center">
                <div class="text-gray-600 text-sm">
                    Menampilkan <span id="startRow">1</span>-<span id="endRow">10</span> dari <span id="totalRows">0</span> peserta
                </div>
                <div class="flex gap-2">
                    <button id="prevPage" class="pagination-btn" disabled>
                        <i class='bx bx-chevron-left'></i>
                    </button>
                    <div class="flex items-center">
                        <span id="currentPage" class="px-3">1</span>
                        <span class="text-gray-400">/</span>
                        <span id="totalPages" class="px-3">1</span>
                    </div>
                    <button id="nextPage" class="pagination-btn" disabled>
                        <i class='bx bx-chevron-right'></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Detail Peserta -->
<div id="detailPesertaModal" class="modal">
    <div class="modal-content max-w-3xl">
        <div class="modal-header">
            <h3 class="modal-title">Detail Peserta</h3>
            <button class="modal-close" onclick="closeModal('detailPesertaModal')">&times;</button>
        </div>
        <div class="modal-body" id="detailPesertaContent">
            <!-- Konten detail akan dimuat via JS -->
        </div>
    </div>
</div>

<!-- Modal untuk Penempatan -->
<div id="placementModal" class="modal">
    <div class="modal-content max-w-2xl">
        <div class="modal-header">
            <h3 class="modal-title">Penempatan Peserta</h3>
            <button class="modal-close" onclick="closeModal('placementModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div id="placementInfo">
                <!-- Info peserta akan dimuat di sini -->
            </div>
            
            <div class="mt-6">
                <label class="block text-sm font-medium mb-3 text-gray-700">Pilih Mentor</label>
                <div id="mentorOptions" class="space-y-3 max-h-96 overflow-y-auto p-2">
                    <!-- Opsi mentor akan dimuat di sini -->
                </div>
            </div>
            
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <i class='bx bx-info-circle text-blue-600 text-xl mt-0.5'></i>
                    <div>
                        <h4 class="font-medium text-blue-800 mb-1">Informasi Penting</h4>
                        <p class="text-sm text-blue-700">
                            Pastikan mentor memiliki kapasitas yang cukup dan sesuai dengan bidang keahlian peserta.
                            Penempatan dapat diubah kapan saja.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('placementModal')">Batal</button>
            <button type="button" class="btn btn-primary" id="confirmPlacementBtn" onclick="confirmPlacement()">
                Simpan Penempatan
            </button>
        </div>
    </div>
</div>

<!-- Modal Detail Mentor (Dinamis) -->
<div id="dynamicModalContainer"></div>
@endsection

@section('scripts')
<script>

const API_CONFIG = {
    baseUrl: window.location.origin,
    endpoints: {
        // Endpoint untuk peserta yang sudah diverifikasi dan ditetapkan ke bidang ini
        peserta: '/api/admin-bidang/penempatan/peserta',
        // Endpoint untuk mentor di bidang ini
        mentor: '/api/admin-bidang/mentor',
        // Endpoint untuk melakukan penempatan
        placement: '/api/admin-bidang/penempatan/assign',
        // Endpoint untuk mendapatkan detail peserta
        detailPeserta: '/api/admin-bidang/penempatan/peserta',
        // Endpoint untuk mendapatkan detail mentor
        detailMentor: '/api/admin-bidang/mentor'
    }
};

let state = {
    pesertaList: [],           // Semua peserta di bidang ini
    mentorList: [],            // Semua mentor di bidang ini
    filteredPeserta: [],       // Peserta setelah difilter
    currentPage: 1,
    itemsPerPage: 10,
    totalPages: 1,
    totalItems: 0,
    currentFilters: {
        status: 'unassigned',  // Default: tampilkan yang belum ditempatkan
        search: ''
    },
    currentPlacement: null     // Untuk menyimpan data penempatan sementara
};

document.addEventListener('DOMContentLoaded', function() {
    setupCSRF();
    fetchAllData();
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
    // Filter listeners
    document.getElementById('filterStatus').addEventListener('change', filterPeserta);
    document.getElementById('searchPeserta').addEventListener('input', debounce(filterPeserta, 300));
    
    // Pagination listeners
    document.getElementById('prevPage').addEventListener('click', prevPage);
    document.getElementById('nextPage').addEventListener('click', nextPage);
}

// Fetch semua data yang diperlukan
async function fetchAllData() {
    try {
        showLoading(true);
        
        // Fetch data peserta yang sudah diverifikasi dan ditetapkan ke bidang ini
        const pesertaResponse = await fetch(
            `${API_CONFIG.endpoints.peserta}?page=${state.currentPage}&per_page=${state.itemsPerPage}`,
            {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }
        );
        
        if (!pesertaResponse.ok) throw new Error('Gagal mengambil data peserta');
        
        const pesertaData = await pesertaResponse.json();
        state.pesertaList = pesertaData.data || [];
        state.totalItems = pesertaData.meta?.total || pesertaData.total || state.pesertaList.length;
        state.totalPages = pesertaData.meta?.last_page || Math.ceil(state.totalItems / state.itemsPerPage);
        
        // Fetch data mentor di bidang ini
        const mentorResponse = await fetch(API_CONFIG.endpoints.mentor, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (mentorResponse.ok) {
            const mentorData = await mentorResponse.json();
            state.mentorList = mentorData.data || mentorData || [];
        }
        
        filterPeserta();
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat data', 'error');
        renderEmptyTable('Terjadi kesalahan saat memuat data');
    } finally {
        showLoading(false);
    }
}

// Filter peserta berdasarkan status dan pencarian
function filterPeserta() {
    const statusFilter = document.getElementById('filterStatus').value;
    const searchQuery = document.getElementById('searchPeserta').value.toLowerCase();
    
    state.currentFilters.status = statusFilter;
    state.currentFilters.search = searchQuery;
    
    state.filteredPeserta = state.pesertaList.filter(peserta => {
        // Filter berdasarkan status penempatan
        const isAssigned = peserta.mentor_id && peserta.status_penempatan === 'assigned';
        
        if (statusFilter === 'unassigned' && isAssigned) return false;
        if (statusFilter === 'assigned' && !isAssigned) return false;
        
        // Filter berdasarkan pencarian
        if (searchQuery) {
            const searchText = `${peserta.nama} ${peserta.nim}`.toLowerCase();
            if (!searchText.includes(searchQuery)) return false;
        }
        
        return true;
    });
    
    state.currentPage = 1;
    renderPesertaTable();
    updateFilterResultCount();
    updatePagination();
}

// Render tabel peserta
function renderPesertaTable() {
    const tbody = document.getElementById('pesertaTableBody');
    const emptyState = document.getElementById('emptyState');
    
    if (state.filteredPeserta.length === 0) {
        tbody.innerHTML = '';
        emptyState.classList.remove('hidden');
        return;
    }
    
    emptyState.classList.add('hidden');
    
    // Hitung data untuk halaman saat ini
    const startIndex = (state.currentPage - 1) * state.itemsPerPage;
    const endIndex = startIndex + state.itemsPerPage;
    const pageData = state.filteredPeserta.slice(startIndex, endIndex);
    
    let html = '';
    
    pageData.forEach(peserta => {
        // DEBUG: Console log untuk cek data
        console.log('Peserta data:', {
            id: peserta.id,
            nama: peserta.nama,
            mentor_id: peserta.mentor_id,
            mentor_nama: peserta.mentor_nama,
            status_penempatan: peserta.status_penempatan
        });
        
        // PERBAIKAN: Cek apakah mentor_id ada DAN bukan null/undefined
        const hasMentor = peserta.mentor_id && peserta.mentor_id !== 'null' && peserta.mentor_id !== 'undefined';
        const isAssigned = hasMentor || peserta.status_penempatan === 'assigned';
        
        const tanggalMulai = formatDate(peserta.tanggal_mulai);
        const tanggalSelesai = formatDate(peserta.tanggal_selesai);
        
        // DEBUG: Tampilkan status di console
        console.log(`Peserta ${peserta.nama}: hasMentor = ${hasMentor}, mentor_id = ${peserta.mentor_id}`);
        
        html += `
    <tr>
        <td>
            <div class="flex items-center gap-3">
                <div class="peserta-avatar">
                    ${getInitials(peserta.nama || peserta.name)}
                </div>
                <div class="flex-1">
                    <div class="font-medium">${peserta.nama || peserta.name}</div>
                    <div class="text-sm text-gray-500">${peserta.nim || '-'}</div>
                </div>
            </div>
        </td>
        <td>${peserta.universitas || '-'}</td>
        <td>${peserta.jurusan || peserta.prodi || '-'}</td>
        <td>
            <div>${tanggalMulai}</div>
            <div class="text-xs text-gray-500">s/d ${tanggalSelesai}</div>
        </td>
        <td>
            ${isAssigned ? 
                `<span class="status-badge status-active">
                    <i class='bx bx-user-check'></i> Sudah Ditempatkan
                    ${peserta.mentor_nama ? `<div class="text-xs mt-1">(${peserta.mentor_nama})</div>` : ''}
                </span>` : 
                `<span class="status-badge status-pending">
                    <i class='bx bx-time'></i> Belum Ditempatkan
                </span>`
            }
        </td>
        <td>
            <div class="action-buttons flex gap-2">
                <button onclick="showDetailPeserta('${peserta.id}')" 
                        class="action-btn view" title="Lihat Detail">
                    <i class='bx bx-show'></i>
                </button>
                <button onclick="showPlacementModal('${peserta.id}')" 
                        class="action-btn edit ${isAssigned ? 'disabled-btn' : ''}" 
                        title="Tempatkan ke Mentor"
                        ${isAssigned ? 'disabled' : ''}>
                    <i class='bx bx-transfer-alt'></i>
                </button>
            </div>
        </td>
    </tr>
`;
    });
    
    tbody.innerHTML = html;
    updateFilterResultCount();
}

function getInitials(name) {
    if (!name) return '--';
    return name
        .split(' ')
        .map(n => n.charAt(0).toUpperCase())
        .join('')
        .substring(0, 2);
}


// Render daftar mentor di modal
function renderMentorOptions(mentorList) {
    const mentorOptions = document.getElementById('mentorOptions');
    let html = '';
    
    if (!mentorList || mentorList.length === 0) {
        html = `
            <div class="text-center py-8 text-gray-500">
                <i class='bx bx-user-x text-3xl mb-2'></i>
                <p>Tidak ada mentor tersedia di bidang ini</p>
                <p class="text-sm">Hubungi admin untuk menambahkan mentor</p>
            </div>
        `;
    } else {
        mentorList.forEach(mentor => {
            const kapasitas = mentor.kapasitas || 5;
            const jumlahBimbingan = mentor.jumlah_bimbingan || 0;
            const kuotaTersedia = kapasitas - jumlahBimbingan;
            const isAvailable = kuotaTersedia > 0 && mentor.status === 'aktif';
            
            // DEBUG: Log data mentor
            console.log('Mentor data:', {
                id: mentor.id,
                nama: mentor.nama,
                kapasitas: kapasitas,
                jumlah_bimbingan: jumlahBimbingan,
                kuotaTersedia: kuotaTersedia,
                isAvailable: isAvailable
            });
            
            html += `
                <div class="mentor-option p-4 border rounded-lg transition-all hover:border-primary hover:shadow-sm 
                     ${isAvailable ? 'cursor-pointer border-gray-200' : 'cursor-not-allowed border-gray-100 bg-gray-50 opacity-60'}"
                     data-mentor-id="${mentor.id}"
                     data-available="${isAvailable}"
                     onclick="${isAvailable ? `selectMentor('${mentor.id}')` : ''}">
                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <div class="font-medium ${isAvailable ? 'text-gray-800' : 'text-gray-500'}">
                                ${mentor.nama || mentor.name}
                            </div>
                            <div class="text-sm ${isAvailable ? 'text-gray-600' : 'text-gray-400'} mt-1">
                                ${mentor.jabatan || 'Mentor'} • ${mentor.email || ''}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm ${isAvailable ? 'text-gray-600' : 'text-gray-400'} mb-1">
                                ${jumlahBimbingan}/${kapasitas} peserta
                            </div>
                            ${isAvailable ? 
                                `<span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">
                                    Tersedia: ${kuotaTersedia} slot
                                </span>` :
                                `<span class="text-xs px-2 py-1 bg-gray-100 text-gray-500 rounded-full">
                                    Kuota Penuh
                                </span>`
                            }
                        </div>
                    </div>
                    ${kuotaTersedia <= 2 && kuotaTersedia > 0 ? 
                        `<div class="mt-2 text-xs text-orange-600">
                            <i class='bx bx-info-circle'></i> Kuota hampir penuh
                        </div>` : ''
                    }
                </div>
            `;
        });
    }
    
    mentorOptions.innerHTML = html;
}

// Tampilkan detail peserta
async function showDetailPeserta(pesertaId) {
    try {
        showLoading('detail', true);
        
        // **API BACKEND:** GET /api/admin-bidang/penempatan/peserta/{id}
        const response = await fetch(`${API_CONFIG.endpoints.detailPeserta}/${pesertaId}`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (!response.ok) throw new Error('Gagal mengambil detail peserta');
        
        const data = await response.json();
        const peserta = data.data || data;
        
        renderDetailPeserta(peserta);
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat detail peserta', 'error');
    } finally {
        showLoading('detail', false);
    }
}

function renderDetailPeserta(peserta) {
    const modalContent = document.getElementById('detailPesertaContent');
    const isAssigned = peserta.mentor_id && peserta.status_penempatan === 'assigned';
    
    // Format berkas jika ada
    const berkasHTML = `
        ${peserta.surat_penempatan_path ? 
            `<div class="mt-3">
                <a href="${peserta.surat_penempatan_path}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800">
                    <i class='bx bx-file'></i> Surat Penempatan
                </a>
            </div>` : ''
        }
        ${peserta.cv_path ? 
            `<div class="mt-2">
                <a href="${peserta.cv_path}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800">
                    <i class='bx bx-file'></i> Curriculum Vitae
                </a>
            </div>` : ''
        }
    `;
    
    modalContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-700 mb-3">Informasi Pribadi</h4>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500">Nama Lengkap</label>
                        <div class="font-medium">${peserta.nama || peserta.name}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">NIM</label>
                        <div class="font-medium">${peserta.nim || '-'}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Email</label>
                        <div class="font-medium">${peserta.email || '-'}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Telepon</label>
                        <div class="font-medium">${peserta.telepon || peserta.no_telp || '-'}</div>
                    </div>
                </div>
                
                ${berkasHTML}
            </div>
            
            <div>
                <h4 class="font-medium text-gray-700 mb-3">Informasi Akademik & Magang</h4>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500">Universitas</label>
                        <div class="font-medium">${peserta.universitas || '-'}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Program Studi</label>
                        <div class="font-medium">${peserta.jurusan || peserta.prodi || '-'}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Periode Magang</label>
                        <div class="font-medium">
                            ${formatDate(peserta.tanggal_mulai)} s/d ${formatDate(peserta.tanggal_selesai)}
                        </div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Bidang Pilihan</label>
                        <div class="font-medium">${peserta.bidang_pilihan || '-'}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Bidang Penempatan</label>
                        <div class="font-medium">${peserta.bidang_penempatan || '-'}</div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h4 class="font-medium text-gray-700 mb-2">Alasan & Catatan</h4>
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                        <div class="mb-3">
                            <label class="text-sm text-gray-500 block mb-1">Alasan Magang:</label>
                            <div class="text-gray-800">${peserta.alasan || '-'}</div>
                        </div>
                        ${peserta.catatan_verifikasi ? `
                            <div>
                                <label class="text-sm text-gray-500 block mb-1">Catatan Verifikasi:</label>
                                <div class="text-gray-800">${peserta.catatan_verifikasi}</div>
                            </div>
                        ` : ''}
                    </div>
                </div>
                
                ${isAssigned && peserta.mentor_nama ? `
                    <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <h4 class="font-medium text-green-800 mb-2">Mentor Pembimbing</h4>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class='bx bx-user-check text-green-600 text-xl'></i>
                            </div>
                            <div>
                                <div class="font-medium">${peserta.mentor_nama}</div>
                                <div class="text-sm text-green-700">Sudah ditugaskan</div>
                            </div>
                        </div>
                    </div>
                ` : ''}
            </div>
        </div>
    `;
    
    openModal('detailPesertaModal');
}

// Tampilkan modal penempatan
async function showPlacementModal(pesertaId) {
    try {
        console.log('Opening placement modal for peserta:', pesertaId);
        
        // Cari data peserta dari state
        const peserta = state.pesertaList.find(p => p.id.toString() === pesertaId.toString());
        
        if (!peserta) {
            showNotification('Data peserta tidak ditemukan', 'error');
            return;
        }
        
        // Cek apakah sudah punya mentor
        if (peserta.mentor_id && peserta.mentor_id !== 'null' && peserta.mentor_id !== null) {
            showNotification(`Peserta ${peserta.nama} sudah memiliki mentor`, 'warning');
            return;
        }
        
        // Update info peserta di modal
        const placementInfo = document.getElementById('placementInfo');
        placementInfo.innerHTML = `
            <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-lg">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class='bx bx-user text-blue-600 text-2xl'></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-lg">${peserta.nama || peserta.name}</h4>
                    <div class="text-gray-600 text-sm">
                        ${peserta.nim || '-'} • ${peserta.universitas || '-'}
                    </div>
                    <div class="text-gray-600 text-sm">
                        ${peserta.jurusan || peserta.prodi || '-'}
                    </div>
                    <div class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1">
                            <i class='bx bx-calendar'></i> 
                            ${formatDate(peserta.tanggal_mulai)} - ${formatDate(peserta.tanggal_selesai)}
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                        Belum Ditempatkan
                    </span>
                </div>
            </div>
        `;
        
        // Render opsi mentor
        renderMentorOptions(state.mentorList);
        
        // Simpan data penempatan sementara
        state.currentPlacement = {
            pesertaId: pesertaId,
            mentorId: null,
            pesertaNama: peserta.nama || peserta.name
        };
        
        // Reset tombol konfirmasi
        const confirmBtn = document.getElementById('confirmPlacementBtn');
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = 'Simpan Penempatan';
        confirmBtn.classList.add('disabled-btn');
        
        // Buka modal
        openModal('placementModal');
        
        console.log('Placement modal opened successfully');
        
    } catch (error) {
        console.error('Error opening placement modal:', error);
        showNotification('Gagal membuka modal penempatan', 'error');
    }
}

// Pilih mentor di modal
function selectMentor(mentorId) {
    console.log('Mentor selected:', mentorId);
    
    // Reset semua selection
    document.querySelectorAll('.mentor-option').forEach(option => {
        option.classList.remove('border-primary', 'bg-blue-50', 'selected-mentor');
    });
    
    // Highlight mentor yang dipilih
    const selectedOption = document.querySelector(`[data-mentor-id="${mentorId}"]`);
    if (selectedOption) {
        selectedOption.classList.add('border-primary', 'bg-blue-50', 'selected-mentor');
    }
    
    // Simpan mentor yang dipilih
    state.currentPlacement.mentorId = mentorId;
    
    // Enable tombol simpan
    const confirmBtn = document.getElementById('confirmPlacementBtn');
    confirmBtn.disabled = false;
    confirmBtn.classList.remove('disabled-btn');
    
    console.log('Current placement:', state.currentPlacement);
}

// Konfirmasi penempatan
async function confirmPlacement() {
    if (!state.currentPlacement || !state.currentPlacement.mentorId) {
        showNotification('Pilih mentor terlebih dahulu', 'warning');
        return;
    }
    
    try {
        showSubmitLoading(true);
        
        console.log('Confirming placement:', state.currentPlacement);
        
        // Kirim data ke API
        const response = await fetch(API_CONFIG.endpoints.placement, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify({
                peserta_id: state.currentPlacement.pesertaId,
                mentor_id: state.currentPlacement.mentorId,
                _token: window.csrfToken
            })
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Gagal melakukan penempatan');
        }
        
        // Update data lokal
        const pesertaIndex = state.pesertaList.findIndex(p => 
            p.id.toString() === state.currentPlacement.pesertaId.toString()
        );
        
        if (pesertaIndex !== -1) {
            const mentor = state.mentorList.find(m => 
                m.id.toString() === state.currentPlacement.mentorId.toString()
            );
            
            // Update data peserta
            state.pesertaList[pesertaIndex].mentor_id = state.currentPlacement.mentorId;
            state.pesertaList[pesertaIndex].mentor_nama = mentor?.nama || mentor?.name;
            state.pesertaList[pesertaIndex].status_penempatan = 'assigned';
            
            // Update jumlah bimbingan mentor
            const mentorIndex = state.mentorList.findIndex(m => 
                m.id.toString() === state.currentPlacement.mentorId.toString()
            );
            
            if (mentorIndex !== -1) {
                state.mentorList[mentorIndex].jumlah_bimbingan = 
                    (state.mentorList[mentorIndex].jumlah_bimbingan || 0) + 1;
            }
        }
        
        showNotification(
            `Berhasil menempatkan ${state.currentPlacement.pesertaNama} ke mentor`,
            'success'
        );
        
        closeModal('placementModal');
        filterPeserta(); // Refresh tabel
        
    } catch (error) {
        console.error('Error confirming placement:', error);
        showNotification(error.message || 'Gagal melakukan penempatan', 'error');
    } finally {
        showSubmitLoading(false);
        state.currentPlacement = null;
    }
}

// Tampilkan detail mentor
async function showMentorDetail(mentorId) {
    try {
        // **API BACKEND:** GET /api/admin-bidang/mentor/{id}
        const response = await fetch(`${API_CONFIG.endpoints.detailMentor}/${mentorId}`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (!response.ok) throw new Error('Gagal mengambil detail mentor');
        
        const data = await response.json();
        const mentor = data.data || data;
        
        // Render modal dinamis
        renderMentorDetailModal(mentor);
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat detail mentor', 'error');
    }
}

function renderMentorDetailModal(mentor) {
    const pesertaDibimbing = state.pesertaList.filter(p => p.mentor_id === mentor.id);
    
    const modalHTML = `
        <div class="modal">
            <div class="modal-content" style="max-width: 700px;">
                <div class="modal-header">
                    <h3 class="modal-title">Detail Mentor</h3>
                    <button class="modal-close" onclick="closeModal('dynamic')">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="space-y-6">
                        <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-lg">
                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class='bx bx-user-circle text-blue-600 text-3xl'></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">${mentor.nama || mentor.name}</h4>
                                <div class="text-gray-600">${mentor.jabatan || 'Mentor'}</div>
                                <div class="text-sm text-gray-500">${mentor.email || '-'}</div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 border rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Kapasitas</div>
                                <div class="text-2xl font-bold text-primary">${mentor.kapasitas || 5}</div>
                            </div>
                            <div class="p-4 border rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Peserta Aktif</div>
                                <div class="text-2xl font-bold text-green-600">${mentor.jumlah_bimbingan || 0}</div>
                            </div>
                        </div>
                        
                        ${pesertaDibimbing.length > 0 ? `
                            <div>
                                <h4 class="font-medium text-gray-700 mb-3">Peserta yang Dibimbing:</h4>
                                <div class="space-y-2">
                                    ${pesertaDibimbing.map(p => `
                                        <div class="flex items-center justify-between p-3 border rounded-lg">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                                    <i class='bx bx-user text-gray-600'></i>
                                                </div>
                                                <div>
                                                    <div class="font-medium">${p.nama || p.name}</div>
                                                    <div class="text-sm text-gray-500">${p.universitas || '-'}</div>
                                                </div>
                                            </div>
                                            <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">
                                                ${formatDate(p.tanggal_mulai)} - ${formatDate(p.tanggal_selesai)}
                                            </span>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        ` : ''}
                    </div>
                    
                    <div class="mt-6 flex gap-3">
                        <button onclick="closeModal('dynamic')" 
                                class="flex-1 btn btn-secondary">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('dynamicModalContainer').innerHTML = modalHTML;
    openModal('dynamic');
}

// ============================
// UTILITY FUNCTIONS
// ============================

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

function resetFilters() {
    document.getElementById('filterStatus').value = 'all';
    document.getElementById('searchPeserta').value = '';
    state.currentFilters.status = 'all';
    state.currentFilters.search = '';
    filterPeserta();
}

function updateFilterResultCount() {
    document.getElementById('filterResultCount').textContent = state.filteredPeserta.length;
}

// Pagination
function updatePagination() {
    const totalPages = Math.ceil(state.filteredPeserta.length / state.itemsPerPage);
    const startRow = ((state.currentPage - 1) * state.itemsPerPage) + 1;
    const endRow = Math.min(state.currentPage * state.itemsPerPage, state.filteredPeserta.length);
    
    document.getElementById('startRow').textContent = startRow;
    document.getElementById('endRow').textContent = endRow;
    document.getElementById('totalRows').textContent = state.filteredPeserta.length;
    document.getElementById('currentPage').textContent = state.currentPage;
    document.getElementById('totalPages').textContent = totalPages;
    
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

// Modal functions
function openModal(modalId) {
    if (modalId === 'dynamic') {
        const modal = document.querySelector('#dynamicModalContainer .modal');
        if (modal) {
            modal.style.display = 'flex';
        }
    } else {
        document.getElementById(modalId).style.display = 'flex';
    }
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    if (modalId === 'dynamic') {
        const modal = document.querySelector('#dynamicModalContainer .modal');
        if (modal) {
            modal.style.display = 'none';
            document.getElementById('dynamicModalContainer').innerHTML = '';
        }
    } else {
        document.getElementById(modalId).style.display = 'none';
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
            const modalContent = document.getElementById('detailPesertaContent');
            if (modalContent && isLoading) {
                modalContent.innerHTML = `
                    <div style="text-align: center; padding: 40px;">
                        <i class='bx bx-loader-circle bx-spin' style="font-size: 3rem; color: var(--primary);"></i>
                        <div style="margin-top: 15px; color: #666;">Memuat detail peserta...</div>
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
    const btn = document.getElementById('confirmPlacementBtn');
    if (btn) {
        btn.disabled = show;
        btn.innerHTML = show 
            ? '<i class="bx bx-loader-circle bx-spin"></i> Memproses...'
            : 'Simpan Penempatan';
    }
}

function renderEmptyTable(message) {
    const tbody = document.getElementById('pesertaTableBody');
    const emptyState = document.getElementById('emptyState');
    
    tbody.innerHTML = '';
    emptyState.classList.remove('hidden');
    emptyState.innerHTML = `
        <i class='bx bx-user-x text-4xl text-gray-300 mb-4'></i>
        <h4 class="text-lg font-medium text-gray-500 mb-2">${message}</h4>
        <p class="text-gray-400 mb-6">Coba dengan filter yang berbeda</p>
        <button onclick="resetFilters()" class="btn btn-primary">
            <i class='bx bx-reset'></i> Reset Filter
        </button>
    `;
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
</script>
@endsection