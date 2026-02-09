@extends('layouts.admin')

@section('title', 'Verifikasi Berkas Peserta')
@section('subtitle', 'Kelola Pengajuan Magang Peserta')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endsection

@section('content')

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card border-blue">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="pendingCount">0</div>
                <div class="stat-label">Menunggu Verifikasi</div>
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
                <div class="stat-label">Terverifikasi</div>
            </div>
            <div class="stat-icon green">
                <i class='bx bx-check-circle'></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card border-red">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="rejectedCount">0</div>
                <div class="stat-label">Ditolak</div>
            </div>
            <div class="stat-icon red">
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
            <input type="text" id="searchInput" placeholder="Nama, NIM..." 
                   class="filter-input">
        </div>
        
        <div class="filter-group">
            <label for="statusFilter" class="filter-label">
                <i class='bx bx-filter-alt'></i> Status
            </label>
            <select id="statusFilter" class="filter-select">
                <option value="">Semua Status</option>
                <option value="pending">Menunggu Verifikasi</option>
                <option value="terverifikasi">Terverifikasi</option>
                <option value="ditolak">Ditolak</option>
            </select>
        </div>
        
        <div class="filter-actions">
            <button onclick="resetFilters()" class="btn btn-secondary">
                <i class='bx bx-reset'></i> Reset Filter
            </button>
        </div>
    </div>
</div>

<!-- Tabel Peserta -->
<div class="form-card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-primary flex items-center gap-2">
            Daftar Peserta Pengajuan Magang
        </h3>
        <div class="table-count">
            Menampilkan semua pengajuan
        </div>
    </div>
    
    <div class="table-container overflow-x-auto">
        <table class="data-table min-w-[800px]">
            <thead>
                <tr>
                    <th>Peserta</th>
                    <th>Universitas</th>
                    <th>Status</th>
                    <th>Tanggal Pendaftaran</th>
                    <th class="w-40">Aksi</th>
                </tr>
            </thead>
            <tbody id="pesertaTableBody"></tbody>
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
</div>

<!-- Modal Verifikasi (Detail Peserta Style) -->
<div id="verifikasiModal" class="modal">
    <div class="modal-content max-w-3xl">
        <div class="modal-header">
            <h3 class="modal-title">Detail & Verifikasi Peserta</h3>
        </div>
        <div class="modal-body" id="verifikasiContent"></div>
    </div>
</div>


<!-- Modal Penempatan -->
<div id="penempatanModal" class="modal">
    <div class="modal-content max-w-md">
        <div class="modal-header">
            <h3 class="modal-title">Tempatkan Peserta</h3>
            <button class="modal-close"
                onclick="closeModal('penempatanModal')">&times;</button>
        </div>

        <div class="modal-body">
            <label><b>Tempatkan ke Bidang:</b></label>
            <select id="bidangPenempatan" class="filter-select">
                <option value="">-- Pilih Bidang --</option>
                @foreach ($bidang as $b)
                    <option value="{{ $b->id_bidang }}">
                        {{ $b->nama_bidang }}
                    </option>
                @endforeach
            </select>

            <div style="margin-top:20px; text-align:right;">
                <button class="btn btn-success"
                    onclick="submitPenempatan()">
                    Terima
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const API_ENDPOINTS = {
    list: '/api/admin/verifikasi-berkas/list',
    detail: '/api/admin/verifikasi-berkas/detail',
    verify: '/api/admin/verifikasi-berkas/verify'
};

window.state = {
    pesertaList: [],
    filteredPeserta: []
};

document.addEventListener('DOMContentLoaded', function() {
    setupCSRF();
    loadData();
    setupEventListeners();
});

//terima peserta 
let selectedPesertaId = null;
function approvePeserta(pesertaId) {
    selectedPesertaId = pesertaId
    document.getElementById('penempatanModal').style.display = 'flex';
}

async function submitPenempatan() {
    const idBidang = document.getElementById('bidangPenempatan').value;

    if (!idBidang) {
        alert('Pilih bidang penempatan dulu');
        return;
    }

    await submitVerifikasiAPI(
        selectedPesertaId,
        'terverifikasi',
        null,
        idBidang
    );

    closeModal('penempatanModal');
    loadData();
}

// Ganti function rejectPeserta dengan ini:
let rejectPesertaId = null;

function showRejectModal(pesertaId) {
    rejectPesertaId = pesertaId;
    
    // Cari data peserta untuk nama
    const peserta = state.pesertaList.find(p => p.id === pesertaId);
    
    // Buat modal reject custom
    const modalHTML = `
        <div id="rejectModal" class="modal">
            <div class="modal-content" style="max-width: 500px;">
                <div class="modal-header">
                    <h3 class="modal-title">Tolak Peserta</h3>
                    <button class="modal-close" onclick="closeModal('rejectModal')">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center gap-3">
                            <i class='bx bx-error-circle text-red-600 text-2xl'></i>
                            <div>
                                <h4 class="font-bold text-red-800">Konfirmasi Penolakan</h4>
                                <p class="text-red-700 text-sm mt-1">
                                    Anda akan menolak peserta: <span class="font-bold">${peserta?.nama || 'Peserta'}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex gap-3 justify-end">
                        <button onclick="closeModal('rejectModal')" 
                                class="btn btn-secondary">
                            <i class='bx bx-x'></i> Batal
                        </button>
                        <button onclick="confirmReject()" 
                                class="btn btn-danger">
                            <i class='bx bx-check'></i> Konfirmasi Tolak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Tambahkan modal ke body
    const modalContainer = document.createElement('div');
    modalContainer.innerHTML = modalHTML;
    document.body.appendChild(modalContainer);
    
    // Tampilkan modal
    document.getElementById('rejectModal').style.display = 'flex';
}

async function confirmReject() {
    const catatan = null; // tidak perlu alasan penolakan
    
    try {
        await submitVerifikasiAPI(rejectPesertaId, 'ditolak', catatan);
        
        // Hapus modal dari DOM
        const modal = document.getElementById('rejectModal');
        if (modal) {
            modal.parentElement.remove();
        }
        
        // Tutup modal detail juga
        closeModal('verifikasiModal');
        
    } catch (error) {
        console.error('Error rejecting peserta:', error);
    }
}

function setupCSRF() {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (token) window.csrfToken = token;
}

function setupEventListeners() {
    const statusEl = document.getElementById('statusFilter');
    if (statusEl) statusEl.addEventListener('change', filterData);
    const searchEl = document.getElementById('searchInput');
    if (searchEl) searchEl.addEventListener('input', debounce(filterData, 300));
}

async function loadData() {
    try {
        const response = await fetch(API_ENDPOINTS.list);
        if (!response.ok) throw new Error('Gagal memuat data');
        
        const result = await response.json();
        state.pesertaList = result.data || [];
        updateStats();
        filterData();
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat data peserta', 'error');
    }
}

function updateStats() {
    const pending = state.pesertaList.filter(p => p.status_verifikasi === 'pending').length;
    const terverifikasi = state.pesertaList.filter(p => p.status_verifikasi === 'terverifikasi').length;
    const ditolak = state.pesertaList.filter(p => p.status_verifikasi === 'ditolak').length;

    document.getElementById('pendingCount').textContent = pending;
    document.getElementById('acceptedCount').textContent = terverifikasi;
    document.getElementById('rejectedCount').textContent = ditolak;
    document.getElementById('totalCount').textContent = state.pesertaList.length;
}

function filterData() {
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchInput').value.toLowerCase();

    state.filteredPeserta = state.pesertaList.filter(p => {
        const matchStatus = !status || p.status_verifikasi === status;
        const matchSearch = !search || p.nama.toLowerCase().includes(search) || p.nim.toLowerCase().includes(search);
        return matchStatus && matchSearch;
    });

    renderTable();
}

function renderTable() {
    const tbody = document.getElementById('pesertaTableBody');
    const emptyState = document.getElementById('emptyState');

    if (state.filteredPeserta.length === 0) {
        tbody.innerHTML = '';
        emptyState.classList.remove('hidden');
        return;
    }

    emptyState.classList.add('hidden');

    let html = '';

    state.filteredPeserta.forEach(p => {
        html += `
        <tr>
            <td>
                <div class="flex items-center gap-3">
                    <div class="peserta-avatar">
                        ${getInitials(p.nama)}
                    </div>
                    <div class="flex-1">
                        <div class="font-medium">${p.nama}</div>
                        <div class="text-sm text-gray-500">${p.nim}</div>
                    </div>
                </div>
            </td>

            <td>${p.universitas || '-'}</td>

            <td>
                <span class="status-badge ${getStatusClass(p.status_verifikasi)}">
                    ${getStatusText(p.status_verifikasi)}
                </span>
            </td>

            <td>${formatDate(p.created_at)}</td>

            <td>
                <div class="action-buttons flex gap-2 justify-end">
                    <button onclick="showVerifikasi('${p.id}')"
                        class="action-btn view"
                        title="Lihat Detail">
                        <i class='bx bx-show'></i>
                    </button>

                    ${p.status_verifikasi === 'pending' ? `
                        <button onclick="approvePeserta(${p.id})"
                            class="action-btn approve"
                            title="Terima">
                            <i class='bx bx-check'></i>
                        </button>

                        <button onclick="showRejectModal(${p.id})"
        class="action-btn reject"
        title="Tolak">
    <i class='bx bx-x'></i>
</button>
                    ` : ''}
                </div>
            </td>
        </tr>
        `;
    });

    tbody.innerHTML = html;
}

function getInitials(name) {
    if (!name) return '--';
    return name
        .split(' ')
        .map(n => n.charAt(0).toUpperCase())
        .join('')
        .substring(0, 2);
}


async function showVerifikasi(pesertaId) {
    try {
        const res = await fetch(`/api/admin/verifikasi-berkas/detail/${pesertaId}`);
        if (!res.ok) throw new Error('Gagal memuat detail');
        
        const { data: p } = await res.json();

        // Format tanggal
        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }

        // Get initials for avatar
        function getInitials(name) {
            if (!name) return '--';
            return name
                .split(' ')
                .map(n => n.charAt(0).toUpperCase())
                .join('')
                .substring(0, 2);
        }

        // Format berkas dengan layout responsif (FIX)
        let berkasHtml = '';

        if (p.berkas) {
            berkasHtml = `
                <div class="mt-4">
                    <h5 class="font-semibold mb-3 text-primary">Dokumen</h5>

                    <div class="space-y-4">
                        ${
                            p.berkas['CV / Resume']
                            ? `
                            <div class="border rounded-xl p-4 bg-white space-y-3">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center shrink-0">
                                        <i class='bx bx-file text-red-600'></i>
                                    </div>

                                    <div>
                                        <div class="font-semibold text-gray-800">CV / Resume</div>
                                        <div class="text-sm text-gray-500">Dokumen CV Peserta</div>
                                    </div>
                                </div>

                                <a href="${p.berkas['CV / Resume']}"
                                target="_blank"
                                class="w-full inline-flex items-center justify-center gap-2
                                        rounded-lg border px-4 py-2 text-sm font-medium
                                        hover:bg-gray-50 transition">
                                    <i class='bx bx-show'></i> Lihat Dokumen
                                </a>
                            </div>
                            `
                            : `<div class="text-sm text-gray-500">CV / Resume tidak tersedia</div>`
                        }

                        ${
                            p.berkas['Surat Pengantar']
                            ? `
                            <div class="border rounded-xl p-4 bg-white space-y-3">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                                        <i class='bx bx-file text-blue-600'></i>
                                    </div>

                                    <div>
                                        <div class="font-semibold text-gray-800">Surat Pengantar</div>
                                        <div class="text-sm text-gray-500">Surat Pengantar Magang</div>
                                    </div>
                                </div>

                                <a href="${p.berkas['Surat Pengantar']}"
                                target="_blank"
                                class="w-full inline-flex items-center justify-center gap-2
                                        rounded-lg border px-4 py-2 text-sm font-medium
                                        hover:bg-gray-50 transition">
                                    <i class='bx bx-show'></i> Lihat Dokumen
                                </a>
                            </div>
                            `
                            : `<div class="text-sm text-gray-500">Surat Pengantar tidak tersedia</div>`
                        }
                    </div>
                </div>
            `;
        }


        document.getElementById('verifikasiContent').innerHTML = `
            <div class="space-y-6">
                <!-- Header dengan avatar -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 p-4 bg-blue-50 rounded-lg text-center sm:text-left">
                    <div class="peserta-avatar" style="width: 60px; height: 60px; font-size: 1.2rem;">
                        ${getInitials(p.nama)}
                    </div>
                    <div>
                        <h4 class="font-bold text-lg">${p.nama}</h4>
                        <div class="text-gray-600">${p.nim} • ${p.universitas}</div>
                    </div>
                </div>
                
                <!-- Informasi Utama -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">NIM</label>
                        <div class="font-medium">${p.nim || '-'}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Program Studi</label>
                        <div class="font-medium">${p.program_studi || '-'}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Email</label>
                        <div class="font-medium">${p.email || '-'}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Telepon</label>
                        <div class="font-medium">${p.no_telp || '-'}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Bidang Pilihan</label>
                        <div class="font-medium">${p.bidang_pilihan || '-'}</div>
                    </div>
                </div>
                
                <!-- Periode Magang -->
                <div>
                    <h5 class="font-semibold mb-3 text-primary">Periode Magang</h5>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <label class="text-sm text-gray-500 block mb-1">Tanggal Mulai</label>
                            <div class="font-medium">${formatDate(p.tanggal_mulai)}</div>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <label class="text-sm text-gray-500 block mb-1">Tanggal Selesai</label>
                            <div class="font-medium">${formatDate(p.tanggal_selesai)}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Alasan & Catatan -->
                <div>
                    <h5 class="font-semibold mb-3 text-primary">Alasan Magang</h5>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="text-gray-700 leading-relaxed whitespace-pre-line max-h-40 overflow-y-auto">
                            ${p.alasan || '-'}
                        </div>
                    </div>
                    ${p.catatan_verifikasi ? `
                        <div class="mt-4">
                            <h5 class="font-semibold mb-3 text-primary">Catatan Verifikasi</h5>
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <i class='bx bx-note text-yellow-600 mt-0.5'></i>
                                    <div class="text-gray-700">${p.catatan_verifikasi}</div>
                                </div>
                            </div>
                        </div>
                    ` : ''}
                </div>
                
                <!-- Dokumen -->
                ${berkasHtml}
                
                <!-- Tombol Aksi -->
                ${p.status_verifikasi === 'pending' ? `
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex justify-end gap-3">
                            <button onclick="approvePeserta(${p.id})" 
                                    class="btn btn-success flex items-center gap-2">
                                <i class='bx bx-check'></i> Terima
                            </button>
                            <button onclick="showRejectModal(${p.id})" 
                                    class="btn btn-danger flex items-center gap-2">
                                <i class='bx bx-x'></i> Tolak
                            </button>
                        </div>
                    </div>
                ` : `
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex justify-end">
                            <button onclick="closeModal('verifikasiModal')" 
                                    class="btn btn-secondary">
                                <i class='bx bx-x'></i> Tutup
                            </button>
                        </div>
                    </div>
                `}
            </div>
        `;
        
        document.getElementById('verifikasiModal').style.display = 'flex';

    } catch (err) {
        console.error(err);
        showNotification('Gagal memuat detail peserta', 'error');
    }
}


async function submitVerifikasiAPI(pesertaId, status, catatan = null, idBidang = null) {
    try {
        const response = await fetch(API_ENDPOINTS.verify, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify({
                peserta_id: pesertaId,
                status: status,
                catatan: catatan,
                id_bidang: idBidang
            })
        });

        if (!response.ok) {
            throw new Error('Gagal menyimpan verifikasi');
        }

        showNotification(
            status === 'terverifikasi'
                ? 'Peserta berhasil diterima'
                : 'Peserta berhasil ditolak',
            'success'
        );

        closeModal('verifikasiModal');
        loadData(); // refresh tabel
    } catch (error) {
        console.error(error);
        showNotification('Gagal memproses verifikasi', 'error');
    }
}


function getStatusClass(status) {
    return status === 'pending' ? 'status-pending' : status === 'terverifikasi' ? 'status-approved' : 'status-rejected';
}

function getStatusText(status) {
    return status === 'pending' ? 'MENUNGGU' : status === 'terverifikasi' ? 'TERVERIFIKASI' : 'DITOLAK';
}

function formatDate(dateString) {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID');
}

function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

function resetFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('searchInput').value = '';
    filterData();
}


function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func(...args), wait);
    };
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed; top: 20px; right: 20px;
        background: ${type === 'success' ? '#d4edda' : '#f8d7da'};
        color: ${type === 'success' ? '#155724' : '#721c24'};
        padding: 15px 20px; border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        z-index: 1000;
    `;
    notification.innerHTML = `<i class='bx ${type === 'success' ? 'bx-check-circle' : 'bx-error-circle'}'></i> ${message}`;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}


function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    if (modal) {
        modal.style.display = 'none';
        // Hapus dari DOM setelah animasi
        setTimeout(() => {
            if (modal.parentElement) {
                modal.parentElement.remove();
            }
        }, 300);
    }
}

// Update closeModal function
function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'none';
    }
}
</script>
@endsection
