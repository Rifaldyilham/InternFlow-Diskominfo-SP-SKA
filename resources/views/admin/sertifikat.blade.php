@extends('layouts.admin')

@section('title', 'Manajemen Sertifikat')
@section('subtitle', 'Upload Sertifikat Peserta Magang')

@section('content')
<div class="content-header">
    <div class="header-actions">
        <!-- Bisa ditambahkan fitur export atau lainnya -->
    </div>
</div>

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
                <div class="stat-label">Sudah Sertifikat</div>
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
                <div class="stat-label">Belum Sertifikat</div>
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
                <option value="Statistik">Statistik</option>
                <option value="Informatika">Informatika</option>
                <option value="Sekretariat">Sekretariat</option>
                <option value="E-Goverment">E-Goverment</option>
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
        <span class="table-count" id="pesertaCount">0 peserta</span>
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
            <!-- Data akan dimuat via JavaScript -->
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
    <div class="modal-content" style="max-width: 900px;">
        <div class="modal-header">
            <h3 id="modalTitle">Detail Peserta Magang</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="modalContent">
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
            <button class="modal-close" onclick="closeUploadModal()">&times;</button>
        </div>
        <form id="uploadForm" onsubmit="event.preventDefault(); submitUpload();">
            <div class="modal-body">
                <input type="hidden" id="uploadPesertaId">
                
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
                        <label for="namaSertifikat">Nama Sertifikat *</label>
                        <input type="text" id="namaSertifikat" required 
                               placeholder="Contoh: Sertifikat Magang - John Doe">
                    </div>
                    
                    <div class="form-group">
                        <label for="nomorSertifikat">Nomor Sertifikat *</label>
                        <input type="text" id="nomorSertifikat" required 
                               placeholder="Contoh: SK/DISKOMINFO/2024/001">
                    </div>
                    
                    <div class="form-group">
                        <label for="tanggalTerbit">Tanggal Terbit *</label>
                        <input type="date" id="tanggalTerbit" required 
                               value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="fileSertifikat">File Sertifikat (PDF) *</label>
                        <div class="file-upload-container">
                            <input type="file" id="fileSertifikat" accept=".pdf" required 
                                   class="file-input" onchange="previewFileName(this)">
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
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeUploadModal()">Batal</button>
                <button type="submit" class="btn btn-primary">
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
            <button class="modal-close" onclick="closeViewSertifikatModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="viewSertifikatContent">
                <!-- Konten akan dimuat via JavaScript -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeViewSertifikatModal()">Tutup</button>
            <button type="button" class="btn btn-primary" onclick="downloadSertifikat()">
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
            <button class="modal-close" onclick="closeDeleteSertifikatModal()">&times;</button>
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
            
            <div class="form-group">
                <label for="delete_confirmation">Ketik "HAPUS" untuk konfirmasi</label>
                <input type="text" id="delete_confirmation" name="delete_confirmation" 
                       placeholder="HAPUS" oninput="validateDeleteConfirmation()">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeDeleteSertifikatModal()">Batal</button>
            <button class="btn btn-danger" id="confirmDeleteBtn" onclick="confirmDeleteSertifikat()" disabled>
                Hapus Sertifikat
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let pesertaData = [];
let currentPage = 1;
const itemsPerPage = 10;
let currentPesertaId = null;

// Inisialisasi data
document.addEventListener('DOMContentLoaded', function() {
    fetchPesertaData();
    document.getElementById('searchInput').addEventListener('input', filterPeserta);
    document.getElementById('statusFilter').addEventListener('change', filterPeserta);
    document.getElementById('bidangFilter').addEventListener('change', filterPeserta);
});

// Data dummy peserta magang
function fetchPesertaData() {
    pesertaData = [
        {
            id: 1,
            nama: "John Doe",
            nim: "G123456789",
            email: "john.doe@uns.ac.id",
            no_telp: "081234567890",
            universitas: "Universitas Sebelas Maret",
            jurusan: "Teknik Informatika",
            bidang: "Informatika",
            tanggal_mulai: "2024-01-01",
            tanggal_selesai: "2024-03-30",
            status_magang: "selesai",
            tanggal_selesai_actual: "2024-03-30",
            sertifikat: {
                nama_file: "Sertifikat_John_Doe.pdf",
                nomor: "SK/DISKOMINFO/2024/001",
                nama: "Sertifikat Magang - John Doe",
                tanggal_terbit: "2024-04-01",
                ukuran: "1.5 MB",
                upload_date: "2024-04-01",
                upload_by: "Admin Kepegawaian",
                catatan: "Peserta telah menyelesaikan program magang dengan baik."
            }
        },
        {
            id: 2,
            nama: "Jane Smith",
            nim: "S987654321",
            email: "jane.smith@ugm.ac.id",
            no_telp: "081298765432",
            universitas: "Universitas Gadjah Mada",
            jurusan: "Ilmu Komunikasi",
            bidang: "Sekretariat",
            tanggal_mulai: "2024-02-01",
            tanggal_selesai: "2024-04-30",
            status_magang: "selesai",
            tanggal_selesai_actual: "2024-04-30",
            sertifikat: null
        },
        {
            id: 3,
            nama: "Budi Santoso",
            nim: "M112233445",
            email: "budi@polines.ac.id",
            no_telp: "082134567890",
            universitas: "Politeknik Negeri Semarang",
            jurusan: "Sistem Informasi",
            bidang: "Statistik",
            tanggal_mulai: "2024-01-15",
            tanggal_selesai: "2024-04-15",
            status_magang: "berjalan",
            tanggal_selesai_actual: null,
            sertifikat: null
        },
        {
            id: 4,
            nama: "Siti Rahma",
            nim: "U556677889",
            email: "siti@ui.ac.id",
            no_telp: "081312345678",
            universitas: "Universitas Indonesia",
            jurusan: "Administrasi Bisnis",
            bidang: "E-Goverment",
            tanggal_mulai: "2024-02-15",
            tanggal_selesai: "2024-05-15",
            status_magang: "selesai",
            tanggal_selesai_actual: "2024-05-15",
            sertifikat: {
                nama_file: "Sertifikat_Siti_Rahma.pdf",
                nomor: "SK/DISKOMINFO/2024/002",
                nama: "Sertifikat Magang - Siti Rahma",
                tanggal_terbit: "2024-05-20",
                ukuran: "1.8 MB",
                upload_date: "2024-05-20",
                upload_by: "Admin Kepegawaian",
                catatan: "Performance sangat baik dalam mengelola sistem e-government."
            }
        },
        {
            id: 5,
            nama: "Ahmad Rizki",
            nim: "N667788990",
            email: "ahmad@unnes.ac.id",
            no_telp: "082245678901",
            universitas: "Universitas Negeri Semarang",
            jurusan: "Manajemen Informatika",
            bidang: "Informatika",
            tanggal_mulai: "2024-03-01",
            tanggal_selesai: "2024-06-01",
            status_magang: "selesai",
            tanggal_selesai_actual: "2024-06-01",
            sertifikat: null
        },
        {
            id: 6,
            nama: "Rifaldy Pratama",
            nim: "M0521001",
            email: "rifaldy@gmail.com",
            no_telp: "081234567891",
            universitas: "Universitas Diponegoro",
            jurusan: "Teknik Komputer",
            bidang: "Informatika",
            tanggal_mulai: "2024-01-10",
            tanggal_selesai: "2024-04-10",
            status_magang: "selesai",
            tanggal_selesai_actual: "2024-04-10",
            sertifikat: {
                nama_file: "Sertifikat_Rifaldy_Pratama.pdf",
                nomor: "SK/DISKOMINFO/2024/003",
                nama: "Sertifikat Magang - Rifaldy Pratama",
                tanggal_terbit: "2024-04-15",
                ukuran: "1.2 MB",
                upload_date: "2024-04-15",
                upload_by: "Admin Kepegawaian",
                catatan: "Kontribusi luar biasa dalam pengembangan sistem monitoring."
            }
        }
    ];
    
    filterPeserta();
}

// Filter peserta
function filterPeserta() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const bidangFilter = document.getElementById('bidangFilter').value;
    
    let filtered = pesertaData;
    
    // Filter pencarian
    if (searchTerm) {
        filtered = filtered.filter(p => 
            p.nama.toLowerCase().includes(searchTerm) ||
            p.nim.toLowerCase().includes(searchTerm) ||
            p.universitas.toLowerCase().includes(searchTerm) ||
            p.bidang.toLowerCase().includes(searchTerm)
        );
    }
    
    // Filter status sertifikat
    if (statusFilter !== 'all') {
        if (statusFilter === 'sudah') {
            filtered = filtered.filter(p => p.sertifikat !== null);
        } else if (statusFilter === 'belum') {
            filtered = filtered.filter(p => p.sertifikat === null);
        }
    }
    
    // Filter bidang
    if (bidangFilter !== 'all') {
        filtered = filtered.filter(p => p.bidang === bidangFilter);
    }
    
    // Sort by tanggal selesai (terbaru pertama)
    filtered.sort((a, b) => {
        const dateA = a.tanggal_selesai_actual || a.tanggal_selesai;
        const dateB = b.tanggal_selesai_actual || b.tanggal_selesai;
        return new Date(dateB) - new Date(dateA);
    });
    
    renderTable(filtered);
    updateStats(filtered);
}

// Reset filter
function resetFilter() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('bidangFilter').value = 'all';
    currentPage = 1;
    filterPeserta();
}

// Render tabel
function renderTable(data) {
    const container = document.getElementById('pesertaTable');
    const totalItems = data.length;
    const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
    
    if (currentPage > totalPages) currentPage = totalPages;
    
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = data.slice(start, end);
    
    if (pageData.length === 0) {
        container.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: #888;">
                    <i class='bx bx-search-alt' style="font-size: 3rem; margin-bottom: 10px; display: block;"></i>
                    <div style="font-weight: 500;">Tidak ada peserta ditemukan</div>
                    <div style="font-size: 0.9rem;">Coba dengan filter yang berbeda</div>
                </td>
            </tr>
        `;
    } else {
        container.innerHTML = pageData.map(peserta => {
            const tanggalMulai = new Date(peserta.tanggal_mulai).toLocaleDateString('id-ID');
            const tanggalSelesai = new Date(peserta.tanggal_selesai).toLocaleDateString('id-ID');
            const hasSertifikat = peserta.sertifikat !== null;
            const isSelesai = peserta.status_magang === 'selesai';
            
            return `
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="user-avatar">${getInitials(peserta.nama)}</div>
                            <div>
                                <div style="font-weight: 600; color: var(--primary);">${peserta.nama}</div>
                                <div style="font-size: 0.85rem; color: #666;">${peserta.nim}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 500;">${peserta.universitas}</div>
                        <div style="font-size: 0.85rem; color: #666;">${peserta.jurusan}</div>
                    </td>
                    <td>
                        <span class="bidang-badge">${peserta.bidang}</span>
                    </td>
                    <td>
                        <div style="color: #666;">${tanggalMulai} - ${tanggalSelesai}</div>
                    </td>
                    <td>
                        <span class="status-badge ${isSelesai ? 'status-approved' : 'status-pending'}">
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
                            <button class="action-btn view" onclick="showDetail(${peserta.id})" title="Lihat Detail">
                                <i class='bx bx-show'></i>
                            </button>
                            ${isSelesai ? `
                                ${hasSertifikat ? `
                                    <button class="action-btn delete" onclick="showDeleteSertifikatModal(${peserta.id}, '${peserta.nama}')" title="Hapus Sertifikat">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                ` : `
                                    <button class="action-btn upload" onclick="showUploadModal(${peserta.id})" title="Upload Sertifikat">
                                        <i class='bx bx-upload'></i>
                                    </button>
                                `}
                            ` : ''}
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }
    
    // Update info
    document.getElementById('pesertaCount').textContent = `${totalItems} peserta`;
    document.getElementById('pageInfo').textContent = `Menampilkan ${start + 1} - ${Math.min(end, totalItems)} dari ${totalItems}`;
    document.getElementById('prevPageBtn').disabled = currentPage === 1;
    document.getElementById('nextPageBtn').disabled = currentPage === totalPages;
}

// Update statistik
function updateStats(data) {
    const totalPeserta = data.length;
    const sudahSertifikat = data.filter(p => p.sertifikat !== null).length;
    const belumSertifikat = data.filter(p => p.sertifikat === null).length;
    const selesaiMagang = data.filter(p => p.status_magang === 'selesai').length;
    
    document.getElementById('totalPeserta').textContent = totalPeserta;
    document.getElementById('sudahSertifikat').textContent = sudahSertifikat;
    document.getElementById('belumSertifikat').textContent = belumSertifikat;
    document.getElementById('selesaiMagang').textContent = selesaiMagang;
}

// Tampilkan detail peserta
function showDetail(id) {
    const peserta = pesertaData.find(p => p.id == id);
    if (!peserta) return;
    
    const tanggalMulai = new Date(peserta.tanggal_mulai).toLocaleDateString('id-ID');
    const tanggalSelesai = new Date(peserta.tanggal_selesai).toLocaleDateString('id-ID');
    const isSelesai = peserta.status_magang === 'selesai';
    const hasSertifikat = peserta.sertifikat !== null;
    
    document.getElementById('modalTitle').textContent = `Detail Peserta - ${peserta.nama}`;
    
    document.getElementById('modalContent').innerHTML = `
        <div class="detail-section">
            <div class="detail-header">
                <div class="detail-title">
                    <div class="detail-avatar">${getInitials(peserta.nama)}</div>
                    <div>
                        <h4>${peserta.nama}</h4>
                        <p>${peserta.nim} • ${peserta.universitas}</p>
                    </div>
                </div>
                <span class="status-badge ${isSelesai ? 'status-approved' : 'status-pending'}">
                    ${isSelesai ? 'SELESAI' : 'BERJALAN'}
                </span>
            </div>
        </div>
        
        <div class="detail-grid">
            <div class="detail-item">
                <label><i class='bx bx-envelope'></i> Email</label>
                <span>${peserta.email}</span>
            </div>
            <div class="detail-item">
                <label><i class='bx bx-phone'></i> No. Telepon</label>
                <span>${peserta.no_telp}</span>
            </div>
            <div class="detail-item">
                <label><i class='bx bx-book'></i> Program Studi</label>
                <span>${peserta.jurusan}</span>
            </div>
            <div class="detail-item">
                <label><i class='bx bx-briefcase'></i> Bidang</label>
                <span class="bidang-badge">${peserta.bidang}</span>
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
            <h4><i class='bx bx-certificate'></i> Status Sertifikat</h4>
            <div class="detail-card">
                ${hasSertifikat ? `
                    <div class="sertifikat-status success">
                        <i class='bx bx-check-circle'></i>
                        <div>
                            <div style="font-weight: 600; color: #2ecc71;">Sertifikat Sudah Tersedia</div>
                            <div style="font-size: 0.9rem; color: #666;">
                                Diupload pada ${new Date(peserta.sertifikat.upload_date).toLocaleDateString('id-ID')}
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
                <h4><i class='bx bx-file-pdf'></i> Informasi Sertifikat</h4>
                <div class="detail-card">
                    <div class="sertifikat-grid">
                        <div class="sertifikat-item">
                            <label>Nomor Sertifikat</label>
                            <span>${peserta.sertifikat.nomor}</span>
                        </div>
                        <div class="sertifikat-item">
                            <label>Nama Sertifikat</label>
                            <span>${peserta.sertifikat.nama}</span>
                        </div>
                        <div class="sertifikat-item">
                            <label>Tanggal Terbit</label>
                            <span>${new Date(peserta.sertifikat.tanggal_terbit).toLocaleDateString('id-ID')}</span>
                        </div>
                        <div class="sertifikat-item">
                            <label>Ukuran File</label>
                            <span>${peserta.sertifikat.ukuran}</span>
                        </div>
                        <div class="sertifikat-item full">
                            <label>Catatan</label>
                            <span>${peserta.sertifikat.catatan}</span>
                        </div>
                    </div>
                </div>
            </div>
        ` : ''}
        
        <div class="detail-actions">
            ${isSelesai && !hasSertifikat ? `
                <button onclick="showUploadModal(${peserta.id})" class="btn btn-primary">
                    <i class='bx bx-upload'></i> Upload Sertifikat
                </button>
            ` : hasSertifikat ? `
                <button onclick="viewSertifikat(${peserta.id})" class="btn btn-primary">
                    <i class='bx bx-file'></i> Lihat Sertifikat
                </button>
            ` : ''}
            <button onclick="closeModal()" class="btn btn-secondary">Tutup</button>
        </div>
    `;
    
    document.getElementById('detailModal').style.display = 'flex';
}

// Tampilkan modal upload
function showUploadModal(id) {
    const peserta = pesertaData.find(p => p.id == id);
    if (!peserta) return;
    
    currentPesertaId = id;
    
    document.getElementById('uploadPesertaId').value = id;
    document.getElementById('uploadTitle').textContent = `Upload Sertifikat - ${peserta.nama}`;
    document.getElementById('uploadNama').textContent = peserta.nama;
    document.getElementById('uploadNim').textContent = peserta.nim;
    document.getElementById('uploadAvatar').textContent = getInitials(peserta.nama);
    
    // Reset form
    document.getElementById('namaSertifikat').value = `Sertifikat Magang - ${peserta.nama}`;
    document.getElementById('nomorSertifikat').value = `SK/DISKOMINFO/${new Date().getFullYear()}/XXX`;
    document.getElementById('tanggalTerbit').value = new Date().toISOString().split('T')[0];
    document.getElementById('fileSertifikat').value = '';
    document.getElementById('fileName').textContent = 'Belum ada file dipilih';
    
    document.getElementById('uploadModal').style.display = 'flex';
}

// Submit upload sertifikat
function submitUpload() {
    const id = document.getElementById('uploadPesertaId').value;
    const namaSertifikat = document.getElementById('namaSertifikat').value;
    const nomorSertifikat = document.getElementById('nomorSertifikat').value;
    const tanggalTerbit = document.getElementById('tanggalTerbit').value;
    const fileInput = document.getElementById('fileSertifikat');
    
    if (!namaSertifikat || !nomorSertifikat || !tanggalTerbit || !fileInput.files[0]) {
        showNotification('Harap isi semua field yang wajib diisi!', 'error');
        return;
    }
    
    const file = fileInput.files[0];
    if (file.type !== 'application/pdf') {
        showNotification('File harus berformat PDF!', 'error');
        return;
    }
    
    if (file.size > 5 * 1024 * 1024) { // 5MB
        showNotification('Ukuran file maksimal 5MB!', 'error');
        return;
    }
    
    const peserta = pesertaData.find(p => p.id == id);
    if (peserta) {
        // Simulasikan upload file
        const fileName = `Sertifikat_${peserta.nama.replace(/\s+/g, '_')}.pdf`;
        const fileSize = (file.size / 1024 / 1024).toFixed(1) + ' MB';
        
        peserta.sertifikat = {
            nama_file: fileName,
            nomor: nomorSertifikat,
            nama: namaSertifikat,
            tanggal_terbit: tanggalTerbit,
            ukuran: fileSize,
            upload_date: new Date().toISOString().split('T')[0],
            upload_by: "Admin Kepegawaian",
            catatan: "Sertifikat magang telah diterbitkan."
        };
        
        showNotification(`Sertifikat berhasil diupload untuk ${peserta.nama}`, 'success');
        closeUploadModal();
        filterPeserta();
    }
}

// Lihat sertifikat
function viewSertifikat(id) {
    const peserta = pesertaData.find(p => p.id == id);
    if (!peserta || !peserta.sertifikat) return;
    
    currentPesertaId = id;
    
    document.getElementById('viewSertifikatTitle').textContent = `Sertifikat - ${peserta.nama}`;
    
    document.getElementById('viewSertifikatContent').innerHTML = `
        <div class="detail-section">
            <div class="detail-header">
                <div class="detail-title">
                    <div class="detail-avatar">${getInitials(peserta.nama)}</div>
                    <div>
                        <h4>${peserta.nama}</h4>
                        <p>${peserta.nim} • ${peserta.universitas}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="sertifikat-preview">
            <div class="pdf-preview">
                <i class='bx bx-file-pdf'></i>
                <div class="pdf-info">
                    <div class="pdf-name">${peserta.sertifikat.nama_file}</div>
                    <div class="pdf-details">
                        <span><i class='bx bx-hash'></i> ${peserta.sertifikat.nomor}</span>
                        <span><i class='bx bx-calendar'></i> ${new Date(peserta.sertifikat.tanggal_terbit).toLocaleDateString('id-ID')}</span>
                        <span><i class='bx bx-data'></i> ${peserta.sertifikat.ukuran}</span>
                    </div>
                </div>
            </div>
            
            <div class="sertifikat-details">
                <h4><i class='bx bx-info-circle'></i> Detail Sertifikat</h4>
                <div class="detail-card">
                    <div class="sertifikat-grid">
                        <div class="sertifikat-item">
                            <label>Nama Sertifikat</label>
                            <span>${peserta.sertifikat.nama}</span>
                        </div>
                        <div class="sertifikat-item">
                            <label>Nomor Sertifikat</label>
                            <span class="nomor-sertifikat">${peserta.sertifikat.nomor}</span>
                        </div>
                        <div class="sertifikat-item">
                            <label>Tanggal Terbit</label>
                            <span>${new Date(peserta.sertifikat.tanggal_terbit).toLocaleDateString('id-ID')}</span>
                        </div>
                        <div class="sertifikat-item">
                            <label>Diupload Oleh</label>
                            <span>${peserta.sertifikat.upload_by}</span>
                        </div>
                        <div class="sertifikat-item">
                            <label>Tanggal Upload</label>
                            <span>${new Date(peserta.sertifikat.upload_date).toLocaleDateString('id-ID')}</span>
                        </div>
                        <div class="sertifikat-item">
                            <label>Status</label>
                            <span class="status-badge status-approved">TERBIT</span>
                        </div>
                        ${peserta.sertifikat.catatan ? `
                            <div class="sertifikat-item full">
                                <label>Catatan</label>
                                <span>${peserta.sertifikat.catatan}</span>
                            </div>
                        ` : ''}
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
        </div>
    `;
    
    document.getElementById('viewSertifikatModal').style.display = 'flex';
}

// Download sertifikat
function downloadSertifikat() {
    const peserta = pesertaData.find(p => p.id == currentPesertaId);
    if (!peserta || !peserta.sertifikat) return;
    
    // Simulasi download
    showNotification(`Mendownload ${peserta.sertifikat.nama_file}...`, 'info');
    
    // Buat link download dummy
    const link = document.createElement('a');
    link.href = '#';
    link.download = peserta.sertifikat.nama_file;
    link.click();
}

// Hapus sertifikat
function showDeleteSertifikatModal(id, nama) {
    currentPesertaId = id;
    document.getElementById('deletePesertaName').textContent = nama;
    document.getElementById('delete_confirmation').value = '';
    document.getElementById('confirmDeleteBtn').disabled = true;
    document.getElementById('deleteSertifikatModal').style.display = 'flex';
}

function validateDeleteConfirmation() {
    const input = document.getElementById('delete_confirmation').value;
    const btn = document.getElementById('confirmDeleteBtn');
    btn.disabled = input !== 'HAPUS';
}

function confirmDeleteSertifikat() {
    if (!currentPesertaId) return;
    
    const peserta = pesertaData.find(p => p.id == currentPesertaId);
    if (peserta) {
        peserta.sertifikat = null;
        showNotification(`Sertifikat untuk ${peserta.nama} telah dihapus`, 'success');
        closeDeleteSertifikatModal();
        filterPeserta();
    }
}

// Utility functions
function previewFileName(input) {
    const fileName = input.files[0] ? input.files[0].name : 'Belum ada file dipilih';
    document.getElementById('fileName').textContent = fileName;
}

// Modal functions
function closeModal() {
    document.getElementById('detailModal').style.display = 'none';
}

function closeUploadModal() {
    document.getElementById('uploadModal').style.display = 'none';
    document.getElementById('uploadForm').reset();
    document.getElementById('fileName').textContent = 'Belum ada file dipilih';
}

function closeViewSertifikatModal() {
    document.getElementById('viewSertifikatModal').style.display = 'none';
    currentPesertaId = null;
}

function closeDeleteSertifikatModal() {
    document.getElementById('deleteSertifikatModal').style.display = 'none';
    currentPesertaId = null;
}

// Pagination
function nextPage() {
    const filtered = getFilteredData();
    const totalPages = Math.max(1, Math.ceil(filtered.length / itemsPerPage));
    if (currentPage < totalPages) {
        currentPage++;
        renderTable(filtered);
    }
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        renderTable(getFilteredData());
    }
}

function getFilteredData() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const bidangFilter = document.getElementById('bidangFilter').value;
    
    let filtered = pesertaData;
    
    if (searchTerm) {
        filtered = filtered.filter(p => 
            p.nama.toLowerCase().includes(searchTerm) ||
            p.nim.toLowerCase().includes(searchTerm) ||
            p.universitas.toLowerCase().includes(searchTerm) ||
            p.bidang.toLowerCase().includes(searchTerm)
        );
    }
    
    if (statusFilter !== 'all') {
        if (statusFilter === 'sudah') {
            filtered = filtered.filter(p => p.sertifikat !== null);
        } else if (statusFilter === 'belum') {
            filtered = filtered.filter(p => p.sertifikat === null);
        }
    }
    
    if (bidangFilter !== 'all') {
        filtered = filtered.filter(p => p.bidang === bidangFilter);
    }
    
    return filtered;
}

function getInitials(name) {
    return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
}

// Notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification-toast notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class='bx ${type === 'success' ? 'bx-check-circle' : type === 'error' ? 'bx-error-circle' : 'bx-info-circle'}'></i>
            <span>${message}</span>
        </div>
        <button onclick="this.parentElement.remove()">&times;</button>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#d4edda' : type === 'error' ? '#f8d7da' : '#d1ecf1'};
        color: ${type === 'success' ? '#155724' : type === 'error' ? '#721c24' : '#0c5460'};
        padding: 15px 20px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-width: 300px;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Add CSS styles
const style = document.createElement('style');
style.textContent = `
    /* STATS CARDS */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border-top: 5px solid;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .stat-card.border-blue {
        border-color: var(--primary);
    }

    .stat-card.border-green {
        border-color: #2ed573;
    }

    .stat-card.border-orange {
        border-color: #ffa502;
    }

    .stat-card.border-purple {
        border-color: #7158e2;
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon.blue {
        background: rgba(33, 52, 72, 0.1);
        color: var(--primary);
    }

    .stat-icon.green {
        background: rgba(46, 213, 115, 0.1);
        color: #2ed573;
    }

    .stat-icon.orange {
        background: rgba(255, 165, 2, 0.1);
        color: #ffa502;
    }

    .stat-icon.purple {
        background: rgba(113, 88, 226, 0.1);
        color: #7158e2;
    }

    .stat-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--primary);
        line-height: 1;
    }

    .stat-label {
        color: #666;
        font-size: 0.95rem;
        margin-top: 8px;
    }

    .stat-change {
        font-size: 0.85rem;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .stat-change.positive {
        color: #2ed573;
    }

    .stat-change.negative {
        color: #ff4757;
    }

    /* FILTER SECTION */
    .filter-container {
        background: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .filter-label {
        font-size: 0.95rem;
        font-weight: 600;
        color: #333;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-input, .filter-select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: border-color 0.3s;
        background: white;
    }

    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    /* TABLE SECTION */
    .table-container {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .table-header {
        padding: 25px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-header h3 {
        color: var(--primary);
        font-size: 1.3rem;
        margin: 0;
    }

    .table-count {
        font-size: 0.95rem;
        color: #666;
        background: #f8f9fa;
        padding: 6px 12px;
        border-radius: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #f8f9fa;
    }

    th {
        padding: 15px 25px;
        text-align: left;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #dee2e6;
        font-size: 0.95rem;
    }

    td {
        padding: 15px 25px;
        border-bottom: 1px solid #eee;
        font-size: 0.95rem;
    }

    tr:hover {
        background: #f8f9fa;
    }

    /* USER AVATAR */
    .user-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(45deg, var(--primary), var(--secondary));
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    /* BADGES */
    .bidang-badge {
        display: inline-block;
        padding: 4px 12px;
        background: #e3f2fd;
        color: #1976d2;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-approved {
        background: #d4edda;
        color: #155724;
    }

    .status-rejected {
        background: #f8d7da;
        color: #721c24;
    }

    /* ACTION BUTTONS */
    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        font-size: 1.1rem;
        transition: all 0.3s;
        background: #e3f2fd;
        color: #1976d2;
    }

    .action-btn:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* PAGINATION */
    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 25px;
        border-top: 1px solid #eee;
    }

    .pagination-info {
        font-size: 0.95rem;
        color: #666;
    }

    .pagination-controls {
        display: flex;
        gap: 8px;
    }

    .pagination-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ddd;
        background: white;
        color: #666;
        cursor: pointer;
        transition: all 0.3s;
    }

    .pagination-btn:hover:not(:disabled) {
        border-color: var(--primary);
        color: var(--primary);
        transform: translateY(-2px);
    }

    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* MODAL */
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
        max-height: 90vh;
        overflow-y: auto;
        width: 100%;
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
        padding: 25px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        color: var(--primary);
        font-size: 1.3rem;
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.8rem;
        color: #888;
        cursor: pointer;
        line-height: 1;
        transition: color 0.3s;
    }

    .modal-close:hover {
        color: #666;
    }

    .modal-body {
        padding: 25px;
    }

    /* DETAIL MODAL */
    .detail-section {
        margin-bottom: 25px;
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
        margin-bottom: 25px;
    }

    .detail-title {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .detail-avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(45deg, var(--primary), var(--secondary));
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .detail-title h4 {
        margin: 0;
        color: var(--primary);
        font-size: 1.2rem;
    }

    .detail-title p {
        margin: 5px 0 0 0;
        color: #666;
        font-size: 0.9rem;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .detail-item label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #666;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .detail-item span {
        color: #333;
        font-size: 0.95rem;
    }

    .detail-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-top: 10px;
        border: 1px solid #e9ecef;
    }

    .detail-card p {
        margin: 0;
        line-height: 1.6;
        color: #333;
    }

    .detail-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    /* FORM */
    .form-section {
        margin-bottom: 25px;
    }

    .form-section h4 {
        color: var(--primary);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.1rem;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: border-color 0.3s;
        background: white;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-group small {
        display: block;
        margin-top: 5px;
        color: #888;
        font-size: 0.85rem;
    }

    /* BUTTONS */
    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        border: none;
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }

    .btn-secondary {
        background: #f8f9fa;
        color: #666;
        border: 1px solid #ddd;
    }

    .btn-secondary:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }

    .btn-danger {
        background: #e74c3c;
        color: white;
    }

    .btn-danger:hover:not(:disabled) {
        background: #c0392b;
        transform: translateY(-2px);
    }

    .btn-danger:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .modal-footer {
        padding: 20px 25px;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }

    /* Sertifikat Info */
    .sertifikat-info {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Action Button Colors */
    .action-btn.view-cert {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .action-btn.upload {
        background: #e3f2fd;
        color: #1976d2;
    }
    
    .action-btn.delete {
        background: #ffebee;
        color: #c62828;
    }
    
    /* File Upload */
    .file-upload-container {
        margin-top: 5px;
    }
    
    .file-input {
        display: none;
    }
    
    .file-upload-label {
        display: block;
        cursor: pointer;
    }
    
    .file-upload-content {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        transition: all 0.3s;
        background: #f8f9fa;
    }
    
    .file-upload-content:hover {
        border-color: var(--primary);
        background: #e3f2fd;
    }
    
    .file-upload-content i {
        font-size: 3rem;
        color: var(--primary);
        margin-bottom: 10px;
        display: block;
    }
    
    .file-upload-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    
    .file-upload-subtitle {
        font-size: 0.9rem;
        color: #666;
    }
    
    .file-name {
        margin-top: 10px;
        padding: 8px 12px;
        background: #f8f9fa;
        border-radius: 6px;
        font-size: 0.9rem;
        color: #666;
        text-align: center;
    }
    
    /* Sertifikat Status */
    .sertifikat-status {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    
    .sertifikat-status.success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
    }
    
    .sertifikat-status.warning {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
    }
    
    .sertifikat-status.info {
        background: #d1ecf1;
        border: 1px solid #bee5eb;
    }
    
    .sertifikat-status i {
        font-size: 1.5rem;
    }
    
    /* Sertifikat Grid */
    .sertifikat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    
    .sertifikat-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    
    .sertifikat-item.full {
        grid-column: 1 / -1;
    }
    
    .sertifikat-item label {
        font-size: 0.9rem;
        color: #666;
        font-weight: 500;
    }
    
    .sertifikat-item span {
        color: #333;
        font-size: 0.95rem;
    }
    
    .nomor-sertifikat {
        font-family: 'Courier New', monospace;
        background: #f8f9fa;
        padding: 4px 8px;
        border-radius: 4px;
        border: 1px solid #ddd;
    }
    
    /* PDF Preview */
    .pdf-preview {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 1px solid #e0e0e0;
    }
    
    .pdf-preview i {
        font-size: 3rem;
        color: #e74c3c;
    }
    
    .pdf-info {
        flex-grow: 1;
    }
    
    .pdf-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    
    .pdf-details {
        display: flex;
        gap: 15px;
        font-size: 0.85rem;
        color: #666;
    }
    
    .pdf-details span {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    /* Sertifikat Preview Note */
    .sertifikat-preview-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 15px;
        background: #d1ecf1;
        border-radius: 8px;
        margin-top: 20px;
        border: 1px solid #bee5eb;
    }
    
    .sertifikat-preview-note i {
        color: #0c5460;
        font-size: 1.2rem;
        flex-shrink: 0;
        margin-top: 2px;
    }
    
    .sertifikat-preview-note div {
        flex-grow: 1;
    }
    
    .sertifikat-preview-note strong {
        color: #0c5460;
        display: block;
        margin-bottom: 5px;
    }
    
    .sertifikat-preview-note p {
        color: #0c5460;
        margin: 0;
        font-size: 0.9rem;
    }
    
    /* Warning Box */
    .warning-box {
        background: #fff9e6;
        border: 1px solid #f1c40f;
        border-radius: 10px;
        padding: 15px;
        margin: 15px 0;
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }
    
    .warning-box i {
        color: #f1c40f;
        font-size: 1.5rem;
        flex-shrink: 0;
        margin-top: 2px;
    }
    
    .warning-box strong {
        color: #856404;
        display: block;
        margin-bottom: 5px;
    }
    
    .warning-box p {
        color: #856404;
        margin: 0;
        font-size: 0.9rem;
    }
    
    /* Responsive Design */
    @media (max-width: 1024px) {
        .detail-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .sertifikat-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .filter-actions {
            justify-content: flex-end;
        }

        .table-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .table-count {
            margin-top: 5px;
        }

        .detail-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .sertifikat-grid {
            grid-template-columns: 1fr;
        }

        .detail-actions {
            flex-direction: column;
        }

        .detail-actions .btn {
            width: 100%;
        }

        table {
            display: block;
            overflow-x: auto;
        }

        th, td {
            white-space: nowrap;
        }

        .modal-content {
            width: 95%;
            margin: 10px;
        }

        .modal-footer {
            flex-direction: column;
        }

        .modal-footer .btn {
            width: 100%;
        }

        th {
            padding: 15px;
        }

        td {
            padding: 15px;
        }

        .pdf-preview {
            flex-direction: column;
            text-align: center;
        }

        .pdf-details {
            flex-direction: column;
            gap: 5px;
        }

        .action-buttons {
            flex-wrap: wrap;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .pagination {
            flex-direction: column;
            gap: 15px;
            align-items: stretch;
        }

        .pagination-controls {
            justify-content: center;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .table-header {
            padding: 15px;
        }

        th {
            padding: 10px;
        }

        td {
            padding: 10px;
        }
    }
    
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
</script>
@endsection