@extends('layouts.admin')

@section('title', 'Verifikasi Berkas Peserta')
@section('subtitle', 'Kelola Pengajuan Magang Peserta')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endsection

@section('content')
<div class="content-header">
    <div class="header-actions">
        <button onclick="refreshData()" class="btn btn-secondary">
            <i class='bx bx-refresh'></i> Refresh
        </button>
    </div>
</div>

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
    <h3 class="text-xl font-bold mb-6">Daftar Peserta Pengajuan Magang</h3>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Peserta</th>
                    <th>NIM</th>
                    <th>Universitas</th>
                    <th>Status</th>
                    <th>Tanggal Pendaftaran</th>
                    <th class="w-40">Aksi</th>
                </tr>
            </thead>
            <tbody id="pesertaTableBody">
                <tr id="loadingRow">
                    <td colspan="6" style="text-align: center; padding: 50px;">
                        <i class='bx bx-loader-circle bx-spin'></i> Memuat data...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div id="emptyState" class="hidden text-center py-12">
        <i class='bx bx-inbox text-4xl text-gray-300'></i>
        <p class="text-gray-500 mt-4">Tidak ada peserta</p>
    </div>
</div>

<!-- Modal Verifikasi -->
<div id="verifikasiModal" class="modal">
    <div class="modal-content max-w-2xl">
        <div class="modal-header">
            <h3 class="modal-title">Verifikasi Berkas Peserta</h3>
            <button class="modal-close" onclick="closeModal('verifikasiModal')">&times;</button>
        </div>
        <div class="modal-body" id="verifikasiContent">
            <!-- Content akan dimuat -->
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

let state = {
    pesertaList: [],
    filteredPeserta: []
};

document.addEventListener('DOMContentLoaded', function() {
    setupCSRF();
    loadData();
    setupEventListeners();
});

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

    tbody.innerHTML = state.filteredPeserta.map(p => `
        <tr>
            <td><strong>${p.nama}</strong></td>
            <td>${p.nim}</td>
            <td>${p.universitas || '-'}</td>
            <td>
                <span class="status-badge ${getStatusClass(p.status_verifikasi)}">
                    ${getStatusText(p.status_verifikasi)}
                </span>
            </td>
            <td>${formatDate(p.created_at)}</td>
            <td>
                <button onclick="showVerifikasi('${p.id}')" class="action-btn edit">
                    <i class='bx bx-check'></i>
                </button>
            </td>
        </tr>
    `).join('');
}

async function showVerifikasi(pesertaId) {
    try {
        const response = await fetch(`${API_ENDPOINTS.detail}/${pesertaId}`);
        if (!response.ok) throw new Error('Gagal memuat detail');
        
        const result = await response.json();
        const p = result.data;

        document.getElementById('verifikasiContent').innerHTML = `
            <div class="space-y-4">
                <div class="p-4 bg-blue-50 rounded-lg">
                    <h4 class="font-bold">${p.nama}</h4>
                    <p class="text-sm text-gray-600">${p.nim} | ${p.universitas}</p>
                </div>

                <div>
                    <label class="block font-medium mb-2">Berkas:</label>
                    ${p.surat_penempatan_url ? `<a href="${p.surat_penempatan_url}" target="_blank" class="text-blue-600 text-sm">📄 Surat Penempatan</a><br>` : ''}
                    ${p.cv_url ? `<a href="${p.cv_url}" target="_blank" class="text-blue-600 text-sm">📄 CV</a>` : ''}
                </div>

                <div>
                    <label class="block font-medium mb-2">Keputusan:</label>
                    <select id="verifikasiStatus" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="terverifikasi">Terima (Terverifikasi)</option>
                        <option value="ditolak">Tolak (Ditolak)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-2">Catatan (Opsional):</label>
                    <textarea id="verifikasiCatatan" class="form-input" rows="3" placeholder="Tulis catatan..."></textarea>
                </div>
            </div>
        `;

        document.getElementById('verifikasiModal').querySelector('.modal-footer')?.remove();
        const footer = document.createElement('div');
        footer.className = 'modal-footer';
        footer.innerHTML = `
            <button class="btn btn-secondary" onclick="closeModal('verifikasiModal')">Batal</button>
            <button class="btn btn-primary" onclick="submitVerifikasi('${pesertaId}')">Simpan</button>
        `;
        document.getElementById('verifikasiModal').appendChild(footer);

        openModal('verifikasiModal');
    } catch (error) {
        showNotification('Gagal memuat detail', 'error');
    }
}

async function submitVerifikasi(pesertaId) {
    const status = document.getElementById('verifikasiStatus').value;
    const catatan = document.getElementById('verifikasiCatatan').value;

    if (!status) {
        showNotification('Pilih status terlebih dahulu', 'error');
        return;
    }

    try {
        const response = await fetch(API_ENDPOINTS.verify, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify({ peserta_id: pesertaId, status, catatan })
        });

        if (!response.ok) throw new Error('Gagal menyimpan verifikasi');

        showNotification('Verifikasi berhasil disimpan', 'success');
        closeModal('verifikasiModal');
        loadData();
    } catch (error) {
        showNotification('Gagal menyimpan verifikasi', 'error');
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

function refreshData() {
    loadData();
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
</script>
@endsection