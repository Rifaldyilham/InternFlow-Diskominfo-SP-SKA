@extends('layouts.mentor')

@section('title', 'Input Penilaian')
@section('subtitle', 'Upload penilaian untuk peserta yang telah selesai magang')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/mentor/mentor.css') }}">
@endsection

@section('content')
<div class="mentor-dashboard">
    <!-- Filter Pencarian -->
    <div class="filter-container-mentor">
        <div class="filter-grid-mentor">
            <div class="filter-group-mentor">
                <label for="searchInput" class="filter-label-mentor">
                    <i class='bx bx-search'></i> Cari Peserta
                </label>
                <input type="text" id="searchInput" placeholder="Nama peserta atau NIM..." 
                       class="filter-input-mentor">
            </div>
            
            <div class="filter-group-mentor">
                <label for="statusFilter" class="filter-label-mentor">
                    <i class='bx bx-filter-alt'></i> Status Penilaian
                </label>
                <select id="statusFilter" class="filter-select-mentor">
                    <option value="all">Semua Status</option>
                    <option value="sudah">Sudah Dinilai</option>
                    <option value="belum">Belum Dinilai</option>
                </select>
            </div>
            
            <div class="filter-group-mentor flex items-end">
                <button onclick="resetFilters()" class="btn btn-secondary w-full">
                    <i class='bx bx-reset'></i> Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Daftar Peserta -->
    <div class="mentor-table-container">
        <div class="mentor-table-header">
            <h3>Daftar Peserta Magang</h3>
            <span class="mentor-table-count" id="pesertaCount">0 peserta</span>
        </div>
        
        <table class="mentor-table">
            <thead>
                <tr>
                    <th>Nama Peserta</th>
                    <th>Universitas</th>
                    <th>Status Magang</th>
                    <th>Status Penilaian</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="pesertaTableBody">
                <!-- Data akan diisi oleh JavaScript -->
                <tr id="loadingRow">
                    <td colspan="5" class="text-center py-12">
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

<!-- Modal Upload Penilaian -->
<div id="uploadModal" class="modal-mentor">
    <div class="modal-content-mentor" style="max-width: 600px;">
        <div class="modal-header-mentor">
            <h3 class="modal-title-mentor" id="modalTitle">Upload File Penilaian</h3>
            <button class="modal-close-mentor" onclick="closeUploadModal()">&times;</button>
        </div>
        <div class="modal-body-mentor">
            <form id="uploadForm">
                @csrf
                <input type="hidden" id="pesertaId" name="peserta_id">
                
                <div id="modalContent">
                    <!-- Konten akan diisi oleh JavaScript -->
                </div>
                
                <div class="modal-footer-mentor">
                    <button type="button" class="btn btn-secondary" onclick="closeUploadModal()">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class='bx bx-upload'></i> Upload File
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Preview Penilaian -->
<div id="previewModal" class="modal-mentor">
    <div class="modal-content-mentor" style="max-width: 800px;">
        <div class="modal-header-mentor">
            <h3 class="modal-title-mentor" id="previewTitle">Preview File Penilaian</h3>
            <button class="modal-close-mentor" onclick="closePreviewModal()">&times;</button>
        </div>
        <div class="modal-body-mentor">
            <div id="previewContent">
                <!-- Preview akan diisi oleh JavaScript -->
            </div>
        </div>
        <div class="modal-footer-mentor">
            <button type="button" class="btn btn-secondary" onclick="closePreviewModal()">Tutup</button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="modal-mentor">
    <div class="modal-content-mentor" style="max-width: 500px;">
        <div class="modal-header-mentor">
            <h3 class="modal-title-mentor">Konfirmasi Hapus</h3>
            <button class="modal-close-mentor" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="modal-body-mentor">
            <div class="text-center">
                <i class='bx bx-error-circle text-5xl text-red-500 mb-4'></i>
                <p id="deleteMessage">Apakah Anda yakin ingin menghapus file penilaian ini?</p>
                <p class="text-sm text-gray-600 mt-2">Aksi ini tidak dapat dibatalkan.</p>
            </div>
        </div>
        <div class="modal-footer-mentor">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                <i class='bx bx-trash'></i> Hapus File
            </button>
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
        // Peserta yang sudah selesai magang (siap dinilai)
        pesertaSelesai: '/api/mentor/penilaian/peserta',
        // Upload file penilaian
        uploadPenilaian: '/api/mentor/penilaian/upload',
        // Get detail file penilaian
        getPenilaian: '/api/mentor/penilaian',
        // Delete file penilaian
        deletePenilaian: '/api/mentor/penilaian',
        // Download file penilaian
        downloadPenilaian: '/api/mentor/penilaian/download',
        // Statistik penilaian
        statsPenilaian: '/api/mentor/penilaian/stats'
    }
};

// ============================
// STATE MANAGEMENT
// ============================
let state = {
    pesertaList: [],
    filteredPeserta: [],
    currentPage: 1,
    itemsPerPage: 10,
    totalItems: 0,
    totalPages: 1,
    currentFilters: {
        search: '',
        status: 'all'
    },
    selectedPeserta: null,
    selectedFile: null
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
        
        // 1. Load statistik
        await loadStats();
        
        // 2. Load daftar peserta
        await loadPesertaSelesai();
        
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

async function loadStats() {
    try {
        const response = await fetch(API_CONFIG.endpoints.statsPenilaian, {
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

async function loadPesertaSelesai() {
    try {
        const response = await fetch(
            `${API_CONFIG.endpoints.pesertaSelesai}?page=${state.currentPage}&per_page=${state.itemsPerPage}`,
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
        //             "tanggal_mulai": "2024-01-15",
        //             "tanggal_selesai": "2024-06-15",
        //             "status_magang": "selesai",
        //             "status_penilaian": "sudah", // atau "belum"
        //             "file_penilaian": {
        //                 "nama": "penilaian_john_doe.pdf",
        //                 "ukuran": "2.4 MB",
        //                 "tanggal_upload": "2024-06-16",
        //                 "url": "/storage/penilaian/1.pdf"
        //             }
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


function filterPeserta() {
    const searchQuery = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    
    state.currentFilters.search = searchQuery;
    state.currentFilters.status = statusFilter;
    
    state.filteredPeserta = state.pesertaList.filter(peserta => {
        // Filter berdasarkan pencarian
        if (searchQuery) {
            const searchText = `${peserta.nama} ${peserta.nim} ${peserta.universitas}`.toLowerCase();
            if (!searchText.includes(searchQuery)) {
                return false;
            }
        }
        
        // Filter berdasarkan status penilaian
        if (statusFilter !== 'all') {
            const statusPenilaian = peserta.status_penilaian || 
                                  (peserta.file_penilaian ? 'sudah' : 'belum');
            if (statusFilter !== statusPenilaian) {
                return false;
            }
        }
        
        return true;
    });
    
    renderPesertaTable();
    updatePesertaCount();
}

function renderPesertaTable() {
    const tbody = document.getElementById('pesertaTableBody');
    
    if (state.filteredPeserta.length === 0) {
        renderEmptyTable(
            state.currentFilters.search || state.currentFilters.status !== 'all'
                ? 'Tidak ada peserta yang sesuai dengan filter' 
                : 'Belum ada peserta yang selesai magang'
        );
        return;
    }
    
    const start = (state.currentPage - 1) * state.itemsPerPage;
    const end = start + state.itemsPerPage;
    const pageData = state.filteredPeserta.slice(start, end);
    
    tbody.innerHTML = pageData.map(peserta => {
        const hasFile = peserta.file_penilaian || peserta.filePenilaian;
        const statusPenilaian = peserta.status_penilaian || 
                               (hasFile ? 'sudah' : 'belum');
        const fileData = peserta.file_penilaian || peserta.filePenilaian;
        
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
                            <div class="text-xs text-gray-500">${peserta.program_studi || peserta.prodi || ''}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="font-medium">${peserta.universitas || '-'}</div>
                </td>
                <td>
                    <span class="status-badge-mentor ${peserta.status_magang === 'selesai' ? 'status-selesai' : 'status-menunggu'}">
                        ${peserta.status_magang === 'selesai' ? 'Selesai' : 'Berlangsung'}
                    </span>
                </td>
                <td>
                    <span class="status-badge-mentor ${statusPenilaian === 'sudah' ? 'status-aktif' : 'status-menunggu'}">
                        ${statusPenilaian === 'sudah' ? 'Sudah Dinilai' : 'Belum Dinilai'}
                    </span>
                </td>
                <td>
                    <div class="mentor-action-buttons">
                        ${hasFile ? `
                            <button class="mentor-action-btn view" 
                                    onclick="previewPenilaian('${peserta.id}', '${peserta.nama}')"
                                    title="Preview File">
                                <i class='bx bx-show'></i>
                            </button>
                            <button class="mentor-action-btn warning"
                                    onclick="editPenilaian('${peserta.id}', '${peserta.nama}')"
                                    title="Edit File">
                                <i class='bx bx-edit'></i>
                            </button>
                            <button class="mentor-action-btn delete"
                                    onclick="confirmDeletePenilaian('${peserta.id}', '${peserta.nama}')"
                                    title="Hapus File">
                                <i class='bx bx-trash'></i>
                            </button>
                        ` : `
                            <button class="mentor-action-btn primary"
                                    onclick="uploadPenilaian('${peserta.id}', '${peserta.nama}')"
                                    title="Upload File Penilaian">
                                <i class='bx bx-upload'></i>
                            </button>
                        `}
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
            <td colspan="5">
                <div class="empty-state-mentor">
                    <i class='bx bx-file-blank'></i>
                    <h4>${message}</h4>
                    ${state.currentFilters.search || state.currentFilters.status !== 'all' ? `
                        <p>Silakan coba dengan filter yang berbeda</p>
                        <button onclick="resetFilters()" class="btn btn-primary mt-4">
                            Reset Filter
                        </button>
                    ` : `
                        <p>Belum ada peserta yang menyelesaikan magang</p>
                    `}
                </div>
            </td>
        </tr>
    `;
}

// ============================
// UPLOAD FUNCTIONS
// ============================

function uploadPenilaian(pesertaId, pesertaNama) {
    state.selectedPeserta = state.pesertaList.find(p => p.id == pesertaId);
    if (!state.selectedPeserta) return;
    
    document.getElementById('modalTitle').textContent = `Upload File Penilaian - ${pesertaNama}`;
    document.getElementById('pesertaId').value = pesertaId;
    document.getElementById('submitBtn').innerHTML = '<i class="bx bx-upload"></i> Upload File';
    
    const modalContent = `
        <div class="space-y-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center gap-3">
                    <i class='bx bx-info-circle text-blue-600 text-xl'></i>
                    <div>
                        <h4 class="font-bold text-blue-800">Informasi Peserta</h4>
                        <p class="text-sm text-blue-700">Pastikan file penilaian sesuai dengan peserta yang dipilih</p>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label-mentor">Nama Peserta</label>
                    <div class="p-3 bg-gray-50 rounded-lg font-medium">${pesertaNama}</div>
                </div>
                <div>
                    <label class="form-label-mentor">NIM</label>
                    <div class="p-3 bg-gray-50 rounded-lg font-medium">${state.selectedPeserta.nim || '-'}</div>
                </div>
                <div>
                    <label class="form-label-mentor">Universitas</label>
                    <div class="p-3 bg-gray-50 rounded-lg font-medium">${state.selectedPeserta.universitas || '-'}</div>
                </div>
                <div>
                    <label class="form-label-mentor">Program Studi</label>
                    <div class="p-3 bg-gray-50 rounded-lg font-medium">${state.selectedPeserta.program_studi || state.selectedPeserta.prodi || '-'}</div>
                </div>
            </div>
            
            <div>
                <label class="form-label-mentor">File Penilaian *</label>
                <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer transition-all hover:border-primary hover:bg-blue-50"
                     ondragover="handleDragOver(event)"
                     ondragleave="handleDragLeave(event)"
                     ondrop="handleDrop(event)">
                    <i class='bx bx-cloud-upload text-4xl text-gray-400 mb-4'></i>
                    <div class="font-medium text-gray-700 mb-2">Seret file ke sini atau klik untuk memilih</div>
                    <div class="text-sm text-gray-500 mb-4">Format: PDF, DOC, DOCX (Maks: 10MB)</div>
                    <button type="button" onclick="document.getElementById('fileInput').click()" 
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-800 transition">
                        <i class='bx bx-folder-open mr-2'></i> Pilih File
                    </button>
                </div>
                <input type="file" id="fileInput" class="hidden" accept=".pdf,.doc,.docx" 
                       onchange="handleFileSelect(event)">
                <div id="filePreview" class="mt-4">
                    <!-- File preview akan muncul di sini -->
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('modalContent').innerHTML = modalContent;
    openModal('uploadModal');
    
    // Reset file preview
    document.getElementById('filePreview').innerHTML = '';
    state.selectedFile = null;
}

function editPenilaian(pesertaId, pesertaNama) {
    uploadPenilaian(pesertaId, pesertaNama);
    document.getElementById('modalTitle').textContent = `Edit File Penilaian - ${pesertaNama}`;
    document.getElementById('submitBtn').innerHTML = '<i class="bx bx-save"></i> Update File';
    
    // Jika ada file sebelumnya, tampilkan info
    const peserta = state.pesertaList.find(p => p.id == pesertaId);
    if (peserta && peserta.file_penilaian) {
        const filePreview = document.getElementById('filePreview');
        const fileIcon = getFileIcon(peserta.file_penilaian.nama);
        
        filePreview.innerHTML = `
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class='bx ${fileIcon} text-xl text-yellow-600'></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">File Saat Ini:</div>
                            <div class="text-sm text-gray-600">${peserta.file_penilaian.nama}</div>
                        </div>
                    </div>
                </div>
                <div class="mt-2 text-xs text-gray-500">
                    Upload: ${formatDate(peserta.file_penilaian.tanggal_upload)} | 
                    Ukuran: ${peserta.file_penilaian.ukuran}
                </div>
            </div>
        `;
    }
}

// ============================
// FILE HANDLING FUNCTIONS
// ============================

function handleDragOver(e) {
    e.preventDefault();
    const dropZone = document.getElementById('dropZone');
    dropZone.classList.add('border-primary', 'bg-blue-50');
}

function handleDragLeave(e) {
    e.preventDefault();
    const dropZone = document.getElementById('dropZone');
    dropZone.classList.remove('border-primary', 'bg-blue-50');
}

function handleDrop(e) {
    e.preventDefault();
    const dropZone = document.getElementById('dropZone');
    dropZone.classList.remove('border-primary', 'bg-blue-50');
    
    const file = e.dataTransfer.files[0];
    if (file) {
        validateAndPreviewFile(file);
    }
}

function handleFileSelect(e) {
    const file = e.target.files[0];
    if (file) {
        validateAndPreviewFile(file);
    }
}

function validateAndPreviewFile(file) {
    // Validasi ukuran (max 10MB)
    if (file.size > 10 * 1024 * 1024) {
        showNotification('Ukuran file terlalu besar. Maksimal 10MB.', 'error');
        return;
    }
    
    // Validasi tipe file
    const allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];
    
    if (!allowedTypes.includes(file.type)) {
        showNotification('Format file tidak didukung. Hanya PDF dan DOC/DOCX.', 'error');
        return;
    }
    
    state.selectedFile = file;
    
    // Tampilkan preview
    const filePreview = document.getElementById('filePreview');
    const fileSize = (file.size / 1024 / 1024).toFixed(2);
    const fileIcon = getFileIcon(file.name);
    
    filePreview.innerHTML = `
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class='bx ${fileIcon} text-2xl text-green-600'></i>
                    </div>
                    <div>
                        <div class="font-bold text-primary">${file.name}</div>
                        <div class="text-sm text-gray-600">${fileSize} MB • ${file.type}</div>
                    </div>
                </div>
                <button onclick="removeSelectedFile()" class="text-red-500 hover:text-red-700">
                    <i class='bx bx-trash text-xl'></i>
                </button>
            </div>
        </div>
    `;
}

function removeSelectedFile() {
    state.selectedFile = null;
    document.getElementById('fileInput').value = '';
    document.getElementById('filePreview').innerHTML = '';
}

// ============================
// FORM SUBMISSION
// ============================

document.getElementById('uploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!state.selectedFile) {
        showNotification('Pilih file terlebih dahulu', 'error');
        return;
    }
    
    try {
        showSubmitLoading(true);
        
        const formData = new FormData();
        formData.append('peserta_id', document.getElementById('pesertaId').value);
        formData.append('file', state.selectedFile);
        formData.append('_token', window.csrfToken);
        
        const response = await fetch(API_CONFIG.endpoints.uploadPenilaian, {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Gagal mengupload file');
        }
        
        showNotification('File penilaian berhasil diupload', 'success');
        closeUploadModal();
        loadPesertaSelesai();
        loadStats();
        
    } catch (error) {
        console.error('Error:', error);
        showNotification(error.message || 'Gagal mengupload file', 'error');
    } finally {
        showSubmitLoading(false);
    }
});

// ============================
// PREVIEW & DELETE FUNCTIONS
// ============================

async function previewPenilaian(pesertaId, pesertaNama) {
    try {
        showLoading('preview', true);
        
        const response = await fetch(`${API_CONFIG.endpoints.getPenilaian}/${pesertaId}`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });
        
        if (!response.ok) throw new Error('Gagal mengambil data penilaian');
        
        const data = await response.json();
        const penilaian = data.data || data;
        
        renderPreviewModal(penilaian, pesertaNama);
        openModal('previewModal');
        
    } catch (error) {
        console.error('Error loading penilaian:', error);
        showNotification('Gagal memuat file penilaian', 'error');
    } finally {
        showLoading('preview', false);
    }
}

function renderPreviewModal(penilaian, pesertaNama) {
    document.getElementById('previewTitle').textContent = `Penilaian - ${pesertaNama}`;
    
    const previewContent = `
        <div class="space-y-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class='bx ${getFileIcon(penilaian.nama_file || penilaian.nama)} text-3xl text-blue-600'></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-primary text-lg">${penilaian.nama_file || penilaian.nama}</h4>
                        <div class="text-sm text-gray-600">
                            Ukuran: ${penilaian.ukuran_file || penilaian.ukuran} • 
                            Upload: ${formatDate(penilaian.tanggal_upload || penilaian.created_at)}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border rounded-lg p-4">
                <div class="text-center py-8">
                    <i class='bx bx-file text-6xl text-gray-400 mb-4'></i>
                    <div class="font-medium text-gray-700 mb-2">File tidak dapat dipreview di browser</div>
                    <div class="text-sm text-gray-500 mb-6">
                        Silakan download file untuk melihat isi penilaian
                    </div>
                    <button onclick="downloadPenilaian('${penilaian.id || penilaian.peserta_id}')" 
                            class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-blue-800 transition font-medium">
                        <i class='bx bx-download mr-2'></i> Download File
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-500">Tanggal Upload</label>
                    <div class="font-medium">${formatDate(penilaian.tanggal_upload || penilaian.created_at)}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Ukuran File</label>
                    <div class="font-medium">${penilaian.ukuran_file || penilaian.ukuran}</div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('previewContent').innerHTML = previewContent;
}

function confirmDeletePenilaian(pesertaId, pesertaNama) {
    state.selectedPeserta = state.pesertaList.find(p => p.id == pesertaId);
    if (!state.selectedPeserta) return;
    
    document.getElementById('deleteMessage').textContent = 
        `Apakah Anda yakin ingin menghapus file penilaian ${pesertaNama}?`;
    
    // Setup delete button
    const deleteBtn = document.getElementById('confirmDeleteBtn');
    deleteBtn.onclick = () => deletePenilaian(pesertaId);
    
    openModal('deleteModal');
}

async function deletePenilaian(pesertaId) {
    try {
        showLoading('delete', true);
        
        const response = await fetch(`${API_CONFIG.endpoints.deletePenilaian}/${pesertaId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        if (!response.ok) throw new Error('Gagal menghapus file penilaian');
        
        showNotification('File penilaian berhasil dihapus', 'success');
        closeDeleteModal();
        loadPesertaSelesai();
        loadStats();
        
    } catch (error) {
        console.error('Error deleting penilaian:', error);
        showNotification('Gagal menghapus file penilaian', 'error');
    } finally {
        showLoading('delete', false);
    }
}

async function downloadPenilaian(penilaianId) {
    try {
        // Redirect ke endpoint download
        window.open(`${API_CONFIG.endpoints.downloadPenilaian}/${penilaianId}`, '_blank');
        
    } catch (error) {
        console.error('Error downloading penilaian:', error);
        showNotification('Gagal mendownload file', 'error');
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
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    } catch (e) {
        return dateString;
    }
}

function getFileIcon(filename) {
    if (!filename) return 'bx-file';
    const ext = filename.split('.').pop().toLowerCase();
    if (ext === 'pdf') return 'bx-file-pdf';
    if (['doc', 'docx'].includes(ext)) return 'bx-file-doc';
    return 'bx-file';
}

function setupEventListeners() {
    document.getElementById('searchInput').addEventListener('input', 
        debounce(filterPeserta, 300)
    );
    
    document.getElementById('statusFilter').addEventListener('change', filterPeserta);
    
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

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = 'all';
    state.currentFilters.search = '';
    state.currentFilters.status = 'all';
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

function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeUploadModal() {
    document.getElementById('uploadModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    state.selectedFile = null;
    state.selectedPeserta = null;
}

function closePreviewModal() {
    document.getElementById('previewModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    state.selectedPeserta = null;
}

// ============================
// LOADING FUNCTIONS
// ============================

function showLoading(context, isLoading) {
    const loaders = {
        'table': () => {
            const loadingRow = document.getElementById('loadingRow');
            if (loadingRow) {
                loadingRow.style.display = isLoading ? 'table-row' : 'none';
            }
        },
        'preview': () => {
            const previewContent = document.getElementById('previewContent');
            if (previewContent && isLoading) {
                previewContent.innerHTML = `
                    <div class="text-center py-10">
                        <i class='bx bx-loader-circle bx-spin text-4xl text-primary'></i>
                        <div class="mt-4 text-gray-600">Memuat file penilaian...</div>
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
                    : '<i class="bx bx-trash"></i> Hapus File';
            }
        }
    };
    
    if (loaders[context]) {
        loaders[context]();
    }
}

function showSubmitLoading(show) {
    const btn = document.getElementById('submitBtn');
    if (btn) {
        btn.disabled = show;
        btn.innerHTML = show 
            ? '<i class="bx bx-loader-circle bx-spin"></i> Memproses...'
            : '<i class="bx bx-upload"></i> Upload File';
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