@extends('layouts.admin-bidang')

@section('title', 'Penempatan Peserta')
@section('subtitle', 'Tempatkan peserta yang sudah diverifikasi ke mentor')

@section('content')
 

<!-- Filter dan Pencarian -->
<div class="form-card mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium mb-2 text-gray-700">Status Penempatan</label>
            <select id="filterStatus" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                <option value="unassigned">Belum Ditempatkan</option>
                <option value="assigned">Sudah Ditempatkan</option>
                <option value="all">Semua Status</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium mb-2 text-gray-700">Cari Peserta</label>
            <div class="relative">
                <input type="text" id="searchPeserta" placeholder="Nama atau NIM..." 
                       class="w-full p-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                <i class='bx bx-search absolute left-3 top-3.5 text-gray-400'></i>
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
                    <i class='bx bx-list-ul'></i> Daftar Peserta di Bidang Ini
                </h3>
                <div class="text-sm text-gray-600">
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
                    <button id="prevPage" class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class='bx bx-chevron-left'></i>
                    </button>
                    <div class="flex items-center">
                        <span id="currentPage" class="px-3">1</span>
                        <span class="text-gray-400">/</span>
                        <span id="totalPages" class="px-3">1</span>
                    </div>
                    <button id="nextPage" class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
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

<script>
// Data global
let allPeserta = [];
let allMentor = [];
let filteredPeserta = [];
let currentPage = 1;
const itemsPerPage = 10;

// Data contoh (dalam implementasi real, ini dari API)
const mockPesertaData = [
    {
        id: 1,
        nama: "Rina Dewi",
        nim: "M0521001",
        universitas: "UNS",
        prodi: "Teknik Informatika",
        email: "rina@uns.ac.id",
        tanggal_mulai: "2024-03-15",
        tanggal_selesai: "2024-06-15",
        status: "unassigned",
        mentor_id: null,
        mentor_nama: null,
        telepon: "081234567890",
        jenis_kelamin: "P",
        foto: null
    },
    {
        id: 2,
        nama: "Ahmad Fauzi",
        nim: "M0521002",
        universitas: "UGM",
        prodi: "Sistem Informasi",
        email: "ahmad@ugm.ac.id",
        tanggal_mulai: "2024-03-14",
        tanggal_selesai: "2024-06-14",
        status: "unassigned",
        mentor_id: null,
        mentor_nama: null,
        telepon: "081234567891",
        jenis_kelamin: "L",
        foto: null
    },
    {
        id: 3,
        nama: "Siti Nurhaliza",
        nim: "M0521003",
        universitas: "UNDIP",
        prodi: "Statistika",
        email: "siti@undip.ac.id",
        tanggal_mulai: "2024-03-13",
        tanggal_selesai: "2024-06-13",
        status: "assigned",
        mentor_id: 1,
        mentor_nama: "Dr. Ahmad Fauzi, M.Kom.",
        telepon: "081234567892",
        jenis_kelamin: "P",
        foto: null
    },
    {
        id: 4,
        nama: "Bambang Pamungkas",
        nim: "M0521004",
        universitas: "ITB",
        prodi: "Teknik Komputer",
        email: "bambang@itb.ac.id",
        tanggal_mulai: "2024-03-12",
        tanggal_selesai: "2024-06-12",
        status: "unassigned",
        mentor_id: null,
        mentor_nama: null,
        telepon: "081234567893",
        jenis_kelamin: "L",
        foto: null
    },
    {
        id: 5,
        nama: "Dewi Lestari",
        nim: "M0521005",
        universitas: "UI",
        prodi: "Ilmu Komputer",
        email: "dewi@ui.ac.id",
        tanggal_mulai: "2024-03-11",
        tanggal_selesai: "2024-06-11",
        status: "assigned",
        mentor_id: 2,
        mentor_nama: "Dra. Siti Rahma, M.Si.",
        telepon: "081234567894",
        jenis_kelamin: "P",
        foto: null
    }
];

const mockMentorData = [
    {
        id: 1,
        nama: "Dr. Ahmad Fauzi, M.Kom.",
        email: "ahmad.fauzi@diskominfo.go.id",
        jabatan: "Kepala Sub Bidang",
        telepon: "08111222333",
        kapasitas: 6,
        bimbingan_aktif: 2,
        status: "active"
    },
    {
        id: 2,
        nama: "Dra. Siti Rahma, M.Si.",
        email: "siti.rahma@diskominfo.go.id",
        jabatan: "Analis Data",
        telepon: "08111222334",
        kapasitas: 5,
        bimbingan_aktif: 1,
        status: "active"
    },
    {
        id: 3,
        nama: "Ir. Bambang Sudarsono, M.T.",
        email: "bambang.s@diskominfo.go.id",
        jabatan: "Pengembang Sistem",
        telepon: "08111222335",
        kapasitas: 4,
        bimbingan_aktif: 0,
        status: "active"
    }
];

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    initData();
    setupEventListeners();
    renderAll();
});

// Inisialisasi data
function initData() {
    allPeserta = [...mockPesertaData];
    allMentor = [...mockMentorData];
    filteredPeserta = [...allPeserta];
    
    // Hitung statistik
    updateStatistics();
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

// Render semua komponen
function renderAll() {
    renderPesertaTable();
    renderMentorList();
    updatePagination();
}

// Render tabel peserta
function renderPesertaTable() {
    const tbody = document.getElementById('pesertaTableBody');
    const emptyState = document.getElementById('emptyState');
    
    if (filteredPeserta.length === 0) {
        tbody.innerHTML = '';
        emptyState.classList.remove('hidden');
        return;
    }
    
    emptyState.classList.add('hidden');
    
    // Hitung data untuk halaman saat ini
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const pageData = filteredPeserta.slice(startIndex, endIndex);
    
    let html = '';
    
    pageData.forEach(peserta => {
        const isAssigned = peserta.status === 'assigned';
        const tanggalMulai = formatDate(peserta.tanggal_mulai);
        const tanggalSelesai = formatDate(peserta.tanggal_selesai);
        
        html += `
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-user text-blue-600'></i>
                        </div>
                        <div>
                            <div class="font-medium">${peserta.nama}</div>
                            <div class="text-sm text-gray-500">${peserta.nim}</div>
                        </div>
                    </div>
                </td>
                <td>${peserta.universitas}</td>
                <td>${peserta.prodi}</td>
                <td>
                    <div>${tanggalMulai}</div>
                    <div class="text-xs text-gray-500">s/d ${tanggalSelesai}</div>
                </td>
                <td>
                    ${isAssigned ? 
                        `<span class="status-badge status-active">
                            <i class='bx bx-user-check'></i> Sudah Ditempatkan
                        </span>` : 
                        `<span class="status-badge status-pending">
                            <i class='bx bx-time'></i> Belum Ditempatkan
                        </span>`
                    }
                </td>
                <td>
                    <div class="flex gap-2">
                        <button onclick="showDetailPeserta(${peserta.id})" 
                                class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">
                            <i class='bx bx-show'></i>
                        </button>
                        <button onclick="showPlacementModal(${peserta.id})" 
                                class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-sm hover:bg-blue-100"
                                ${isAssigned ? 'disabled' : ''}>
                            <i class='bx bx-transfer-alt'></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

// Render daftar mentor
function renderMentorList() {
    const container = document.getElementById('mentorList');
    
    let html = '';
    
    allMentor.forEach(mentor => {
        const kuotaPersen = Math.round((mentor.bimbingan_aktif / mentor.kapasitas) * 100);
        const kuotaTersedia = mentor.kapasitas - mentor.bimbingan_aktif;
        const kuotaColor = kuotaPersen >= 100 ? 'bg-red-500' : 
                          kuotaPersen >= 80 ? 'bg-orange-500' : 
                          kuotaPersen >= 60 ? 'bg-yellow-500' : 'bg-green-500';
        
        html += `
            <div class="p-4 border border-gray-200 rounded-lg hover:border-primary transition-colors">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="font-medium text-primary">${mentor.nama}</div>
                        <div class="text-sm text-gray-500">${mentor.jabatan}</div>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full ${mentor.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                        ${mentor.status === 'active' ? 'Aktif' : 'Nonaktif'}
                    </span>
                </div>
                
                <div class="mb-3">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Kapasitas Bimbingan:</span>
                        <span class="font-medium">${mentor.bimbingan_aktif}/${mentor.kapasitas}</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full ${kuotaColor} transition-all duration-500" 
                             style="width: ${kuotaPersen}%"></div>
                    </div>
                </div>
                
                <div class="flex justify-between items-center">
                    <div class="text-sm">
                        <span class="text-gray-600">Kuota Tersedia:</span>
                        <span class="font-medium ml-1 ${kuotaTersedia > 0 ? 'text-green-600' : 'text-red-600'}">
                            ${kuotaTersedia}
                        </span>
                    </div>
                    <button onclick="showMentorDetail(${mentor.id})" 
                            class="text-sm text-primary hover:underline">
                        Detail
                    </button>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

// Filter peserta
function filterPeserta() {
    const statusFilter = document.getElementById('filterStatus').value;
    const searchQuery = document.getElementById('searchPeserta').value.toLowerCase();
    
    filteredPeserta = allPeserta.filter(peserta => {
        // Filter status
        if (statusFilter === 'unassigned' && peserta.status !== 'unassigned') return false;
        if (statusFilter === 'assigned' && peserta.status !== 'assigned') return false;
        
        // Search filter
        if (searchQuery) {
            const searchIn = `${peserta.nama} ${peserta.nim}`.toLowerCase();
            if (!searchIn.includes(searchQuery)) return false;
        }
        
        return true;
    });
    
    currentPage = 1;
    renderAll();
    updateFilterResultCount();
}

// Update statistik
function updateStatistics() {
    const totalPeserta = allPeserta.length;
    const pesertaBelumDitugaskan = allPeserta.filter(p => p.status === 'unassigned').length;
    const totalMentorAktif = allMentor.filter(m => m.status === 'active').length;
    const mentorKosong = allMentor.filter(m => m.bimbingan_aktif < m.kapasitas).length;
    const placedCount = allPeserta.filter(p => p.status === 'assigned').length;
    const placementProgress = totalPeserta > 0 ? Math.round((placedCount / totalPeserta) * 100) : 0;
    
    document.getElementById('totalPesertaBidang').textContent = totalPeserta;
    document.getElementById('pesertaBelumDitugaskan').textContent = pesertaBelumDitugaskan;
    document.getElementById('totalMentorAktif').textContent = totalMentorAktif;
    document.getElementById('mentorKosong').textContent = mentorKosong;
    document.getElementById('placementProgress').textContent = `${placementProgress}%`;
    document.getElementById('placedCount').textContent = placedCount;
    document.getElementById('totalPlacement').textContent = totalPeserta;
}

// Update filter result count
function updateFilterResultCount() {
    document.getElementById('filterResultCount').textContent = filteredPeserta.length;
}

// Pagination
function updatePagination() {
    const totalPages = Math.ceil(filteredPeserta.length / itemsPerPage);
    const startRow = ((currentPage - 1) * itemsPerPage) + 1;
    const endRow = Math.min(currentPage * itemsPerPage, filteredPeserta.length);
    
    document.getElementById('startRow').textContent = startRow;
    document.getElementById('endRow').textContent = endRow;
    document.getElementById('totalRows').textContent = filteredPeserta.length;
    document.getElementById('currentPage').textContent = currentPage;
    document.getElementById('totalPages').textContent = totalPages;
    
    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = currentPage === totalPages || totalPages === 0;
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        renderPesertaTable();
        updatePagination();
    }
}

function nextPage() {
    const totalPages = Math.ceil(filteredPeserta.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderPesertaTable();
        updatePagination();
    }
}

// Modal functions
function showDetailPeserta(pesertaId) {
    const peserta = allPeserta.find(p => p.id === pesertaId);
    if (!peserta) return;
    
    const modalContent = document.getElementById('detailPesertaContent');
    modalContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-700 mb-3">Informasi Pribadi</h4>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500">Nama Lengkap</label>
                        <div class="font-medium">${peserta.nama}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">NIM</label>
                        <div class="font-medium">${peserta.nim}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Email</label>
                        <div class="font-medium">${peserta.email}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Telepon</label>
                        <div class="font-medium">${peserta.telepon}</div>
                    </div>
                </div>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-700 mb-3">Informasi Akademik</h4>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500">Universitas</label>
                        <div class="font-medium">${peserta.universitas}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Program Studi</label>
                        <div class="font-medium">${peserta.prodi}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Periode Magang</label>
                        <div class="font-medium">
                            ${formatDate(peserta.tanggal_mulai)} s/d ${formatDate(peserta.tanggal_selesai)}
                        </div>
                    </div>
                </div>
                
                ${peserta.mentor_nama ? `
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

function showPlacementModal(pesertaId) {
    const peserta = allPeserta.find(p => p.id === pesertaId);
    if (!peserta) return;
    
    // Update info peserta
    const placementInfo = document.getElementById('placementInfo');
    placementInfo.innerHTML = `
        <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-lg">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                <i class='bx bx-user text-blue-600 text-2xl'></i>
            </div>
            <div>
                <h4 class="font-bold text-lg">${peserta.nama}</h4>
                <div class="text-gray-600">
                    ${peserta.nim} • ${peserta.universitas} • ${peserta.prodi}
                </div>
            </div>
        </div>
    `;
    
    // Update opsi mentor
    const mentorOptions = document.getElementById('mentorOptions');
    let mentorHtml = '';
    
    allMentor.forEach(mentor => {
        const kuotaTersedia = mentor.kapasitas - mentor.bimbingan_aktif;
        const isAvailable = kuotaTersedia > 0 && mentor.status === 'active';
        
        mentorHtml += `
            <div class="p-4 border rounded-lg cursor-pointer transition-all hover:border-primary hover:shadow-sm 
                 ${isAvailable ? 'border-gray-200' : 'border-gray-100 bg-gray-50 opacity-60'}"
                 onclick="${isAvailable ? `selectMentor(${mentor.id}, ${pesertaId})` : ''}">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="font-medium ${isAvailable ? 'text-gray-800' : 'text-gray-500'}">
                            ${mentor.nama}
                        </div>
                        <div class="text-sm ${isAvailable ? 'text-gray-600' : 'text-gray-400'}">
                            ${mentor.jabatan}
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm ${isAvailable ? 'text-gray-600' : 'text-gray-400'}">
                            Kuota: ${mentor.bimbingan_aktif}/${mentor.kapasitas}
                        </div>
                        ${isAvailable ? 
                            `<span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">
                                Tersedia: ${kuotaTersedia}
                            </span>` :
                            `<span class="text-xs px-2 py-1 bg-gray-100 text-gray-500 rounded-full">
                                Kuota Penuh
                            </span>`
                        }
                    </div>
                </div>
            </div>
        `;
    });
    
    mentorOptions.innerHTML = mentorHtml;
    
    // Set selected mentor
    window.currentPlacement = {
        pesertaId: pesertaId,
        mentorId: null
    };
    
    // Reset confirm button
    document.getElementById('confirmPlacementBtn').disabled = true;
    document.getElementById('confirmPlacementBtn').innerHTML = 'Simpan Penempatan';
    
    openModal('placementModal');
}

function selectMentor(mentorId, pesertaId) {
    window.currentPlacement.mentorId = mentorId;
    
    // Update UI untuk menunjukkan mentor yang dipilih
    document.querySelectorAll('#mentorOptions > div').forEach(div => {
        div.classList.remove('border-primary', 'bg-blue-50');
    });
    
    const selectedDiv = document.querySelector(`#mentorOptions > div[onclick*="selectMentor(${mentorId}, ${pesertaId})"]`);
    if (selectedDiv) {
        selectedDiv.classList.add('border-primary', 'bg-blue-50');
    }
    
    // Enable confirm button
    document.getElementById('confirmPlacementBtn').disabled = false;
}

function confirmPlacement() {
    if (!window.currentPlacement || !window.currentPlacement.mentorId) return;
    
    const { pesertaId, mentorId } = window.currentPlacement;
    const peserta = allPeserta.find(p => p.id === pesertaId);
    const mentor = allMentor.find(m => m.id === mentorId);
    
    if (!peserta || !mentor) return;
    
    // Update data
    peserta.status = 'assigned';
    peserta.mentor_id = mentorId;
    peserta.mentor_nama = mentor.nama;
    mentor.bimbingan_aktif += 1;
    
    // Update UI
    closeModal('placementModal');
    renderAll();
    updateStatistics();
    
    // Show success message
    showNotification(`${peserta.nama} berhasil ditempatkan ke ${mentor.nama}`, 'success');
}

// Utility functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
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
    document.getElementById('filterStatus').value = 'unassigned';
    document.getElementById('searchPeserta').value = '';
    
    filterPeserta();
}

function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'warning' ? 'bg-yellow-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    } text-white`;
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <i class='bx ${
                type === 'success' ? 'bx-check-circle' : 
                type === 'warning' ? 'bx-error-circle' : 
                type === 'error' ? 'bx-error-circle' : 'bx-info-circle'
            }'></i>
            <div>
                <div class="font-bold">${type === 'success' ? 'Berhasil' : type === 'warning' ? 'Perhatian' : type === 'error' ? 'Error' : 'Info'}</div>
                <div class="text-sm opacity-90">${message}</div>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 3000);
}

// Placeholder functions untuk fitur lain
function showPlacementGuide() {
    alert('Panduan penempatan peserta:\n\n1. Pilih peserta yang belum memiliki mentor\n2. Klik tombol "Tempatkan"\n3. Pilih mentor dengan kuota tersedia\n4. Konfirmasi penempatan\n\nPastikan mentor sesuai dengan bidang keahlian peserta.');
}

function showMentorDetail(mentorId) {
    const mentor = allMentor.find(m => m.id === mentorId);
    if (!mentor) return;
    
    const pesertaDibimbing = allPeserta.filter(p => p.mentor_id === mentorId);
    
    const modal = `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-primary">Detail Mentor</h3>
                        <button class="text-2xl text-gray-500 hover:text-gray-700" onclick="this.closest('.fixed').remove()">&times;</button>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-lg">
                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class='bx bx-user-circle text-blue-600 text-3xl'></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">${mentor.nama}</h4>
                                <div class="text-gray-600">${mentor.jabatan}</div>
                                <div class="text-sm text-gray-500">${mentor.email}</div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 border rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Kapasitas</div>
                                <div class="text-2xl font-bold text-primary">${mentor.kapasitas}</div>
                            </div>
                            <div class="p-4 border rounded-lg">
                                <div class="text-sm text-gray-500 mb-1">Peserta Aktif</div>
                                <div class="text-2xl font-bold text-green-600">${mentor.bimbingan_aktif}</div>
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
                                                    <div class="font-medium">${p.nama}</div>
                                                    <div class="text-sm text-gray-500">${p.universitas}</div>
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
                        <button onclick="this.closest('.fixed').remove()" 
                                class="flex-1 btn btn-secondary">Tutup</button>
                        <button onclick="window.location.href='/admin-bidang/mentor/edit/${mentorId}'" 
                                class="flex-1 btn btn-primary">Edit Mentor</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modal);
}
</script>

<style>
/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.modal-content {
    background: white;
    border-radius: 16px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    padding: 24px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--primary);
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.8rem;
    color: #9ca3af;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    transition: color 0.3s;
}

.modal-close:hover {
    color: #6b7280;
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    padding: 20px 24px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

.status-active {
    background: #e6fff3;
    color: #10b981;
    border: 1px solid #a7f3d0;
}

.status-pending {
    background: #fff9e6;
    color: #f59e0b;
    border: 1px solid #fde68a;
}

/* Responsive */
@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        margin: 0;
    }
    
    .modal-header {
        padding: 20px;
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .modal-footer {
        padding: 16px 20px;
        flex-direction: column;
    }
    
    .modal-footer button {
        width: 100%;
    }
}
</style>
@endsection