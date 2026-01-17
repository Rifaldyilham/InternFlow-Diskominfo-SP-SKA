@extends('layouts.admin')

@section('title', 'Verifikasi Berkas Peserta')
@section('subtitle', 'Kelola Pengajuan Magang Peserta')

@section('content')
<div class="content-header">
    <div class="header-actions">
        <!-- Tombol download semua dihapus -->
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card border-blue">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="pendingCount">0</div>
                <div class="stat-label">Menunggu</div>
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
                <div class="stat-label">Diterima</div>
            </div>
            <div class="stat-icon green">
                <i class='bx bx-check-circle'></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card border-orange">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="rejectedCount">0</div>
                <div class="stat-label">Ditolak</div>
            </div>
            <div class="stat-icon orange">
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
            <input type="text" id="searchInput" placeholder="Nama, NIM, universitas..." 
                   class="filter-input">
        </div>
        
        <div class="filter-group">
            <label for="statusFilter" class="filter-label">
                <i class='bx bx-filter-alt'></i> Status
            </label>
            <select id="statusFilter" class="filter-select">
                <option value="all">Semua Status</option>
                <option value="pending">Menunggu</option>
                <option value="accepted">Diterima</option>
                <option value="rejected">Ditolak</option>
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
            <button onclick="filterPengajuan()" class="btn btn-primary">
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
        <h3>Daftar Pengajuan Magang</h3>
        <span class="table-count" id="pengajuanCount">0 pengajuan</span>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Peserta</th>
                <th>Universitas</th>
                <th>Bidang Pilihan</th>
                <th>Tanggal Pengajuan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="pengajuanTable">
            <!-- Data akan dimuat via JavaScript -->
        </tbody>
    </table>
    
    <!-- Pagination -->
    <div class="pagination">
        <div class="pagination-info" id="pageInfo">Menampilkan 0 - 0 dari 0 pengajuan</div>
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

<!-- Modal Detail Pengajuan -->
<div id="detailModal" class="modal">
    <div class="modal-content" style="max-width: 900px;">
        <div class="modal-header">
            <h3 id="modalTitle">Detail Pengajuan Magang</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="modalContent">
                <!-- Konten akan dimuat via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi -->
<div id="verifikasiModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3 id="verifikasiTitle">Verifikasi Pengajuan</h3>
            <button class="modal-close" onclick="closeVerifikasiModal()">&times;</button>
        </div>
        <form id="verifikasiForm" onsubmit="event.preventDefault(); submitVerifikasi();">
            <div class="modal-body">
                <input type="hidden" id="verifikasiId">
                
                <div class="form-section">
                    <h4><i class='bx bx-check-shield'></i> Status Verifikasi</h4>
                    <div class="form-group">
                        <label for="statusVerifikasi">Status *</label>
                        <select id="statusVerifikasi" required>
                            <option value="">Pilih Status</option>
                            <option value="accepted">Diterima</option>
                            <option value="rejected">Ditolak</option>
                            <option value="pending">Tunda</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="catatanVerifikasi">Catatan/Keterangan</label>
                        <textarea id="catatanVerifikasi" rows="4" 
                                  placeholder="Berikan catatan atau alasan verifikasi..."></textarea>
                        <small>Catatan akan dikirimkan ke peserta via email</small>
                    </div>
                </div>
                
                <div class="form-section">
                    <h4><i class='bx bx-briefcase'></i> Penempatan Final</h4>
                    <div class="form-group">
                        <label for="bidangFinal">Bidang Penempatan</label>
                        <select id="bidangFinal">
                            <option value="">Pilih Bidang (Opsional)</option>
                            <option value="Statistik">Statistik</option>
                            <option value="Informatika">Informatika</option>
                            <option value="Sekretariat">Sekretariat</option>
                            <option value="E-Goverment">E-Goverment</option>
                        </select>
                        <small>Isi jika ingin menempatkan ke bidang yang berbeda dengan pilihan peserta</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeVerifikasiModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Verifikasi</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let pengajuanData = [];
let currentPage = 1;
const itemsPerPage = 10;

// Inisialisasi data
document.addEventListener('DOMContentLoaded', function() {
    fetchPengajuanData();
    document.getElementById('searchInput').addEventListener('input', filterPengajuan);
    document.getElementById('statusFilter').addEventListener('change', filterPengajuan);
    document.getElementById('bidangFilter').addEventListener('change', filterPengajuan);
});

// Data dummy pengajuan
function fetchPengajuanData() {
    pengajuanData = [
        {
            id: 1,
            nama: "John Doe",
            nim: "G123456789",
            email: "john.doe@uns.ac.id",
            no_telp: "081234567890",
            universitas: "Universitas Sebelas Maret",
            jurusan: "Teknik Informatika",
            semester: "6",
            bidang_pilihan: "Informatika",
            tanggal_mulai: "2024-01-01",
            tanggal_selesai: "2024-03-30",
            alasan: "Saya tertarik dengan bidang informatika karena sesuai dengan program studi saya dan ingin mengembangkan skill programming.",
            tanggal_pengajuan: "2024-12-01",
            status: "pending",
            berkas: {
                cv: { nama: "CV_John_Doe.pdf", ukuran: "1.2 MB" },
                surat: { nama: "Surat_Magang_Uns.pdf", ukuran: "0.8 MB" }
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
            semester: "7",
            bidang_pilihan: "Sekretariat",
            tanggal_mulai: "2024-02-01",
            tanggal_selesai: "2024-04-30",
            alasan: "Ingin mengembangkan skill administrasi dan organisasi di bidang kesekretariatan.",
            tanggal_pengajuan: "2024-12-02",
            status: "accepted",
            catatan: "Peserta memenuhi syarat administrasi dan akademik.",
            tanggal_verifikasi: "2024-12-03",
            verifikator: "Admin Bidang Sekretariat",
            berkas: {
                cv: { nama: "CV_Jane_Smith.pdf", ukuran: "1.5 MB" },
                surat: { nama: "Surat_UGM.pdf", ukuran: "1.0 MB" }
            }
        },
        {
            id: 3,
            nama: "Budi Santoso",
            nim: "M112233445",
            email: "budi@polines.ac.id",
            no_telp: "082134567890",
            universitas: "Politeknik Negeri Semarang",
            jurusan: "Sistem Informasi",
            semester: "5",
            bidang_pilihan: "Statistik",
            tanggal_mulai: "2024-01-15",
            tanggal_selesai: "2024-04-15",
            alasan: "Ingin mempelajari analisis data statistik untuk mendukung penelitian skripsi.",
            tanggal_pengajuan: "2024-12-03",
            status: "rejected",
            catatan: "Berkas tidak lengkap, surat penempatan tidak sesuai format.",
            tanggal_verifikasi: "2024-12-04",
            verifikator: "Admin Bidang Statistik",
            berkas: {
                cv: { nama: "CV_Budi_Santoso.pdf", ukuran: "1.0 MB" },
                surat: { nama: "Surat_Polines.docx", ukuran: "1.8 MB" }
            }
        },
        {
            id: 4,
            nama: "Siti Rahma",
            nim: "U556677889",
            email: "siti@ui.ac.id",
            no_telp: "081312345678",
            universitas: "Universitas Indonesia",
            jurusan: "Administrasi Bisnis",
            semester: "6",
            bidang_pilihan: "E-Goverment",
            tanggal_mulai: "2024-02-15",
            tanggal_selesai: "2024-05-15",
            alasan: "Tertarik dengan implementasi e-government di pemerintah daerah.",
            tanggal_pengajuan: "2024-12-04",
            status: "pending",
            berkas: {
                cv: { nama: "CV_Siti_Rahma.pdf", ukuran: "1.3 MB" },
                surat: { nama: "Surat_UI.pdf", ukuran: "0.9 MB" }
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
            semester: "8",
            bidang_pilihan: "Informatika",
            tanggal_mulai: "2024-03-01",
            tanggal_selesai: "2024-06-01",
            alasan: "Ingin mendapatkan pengalaman kerja nyata di bidang IT pemerintahan.",
            tanggal_pengajuan: "2024-12-05",
            status: "accepted",
            catatan: "Peserta memiliki portofolio yang baik di bidang web development.",
            tanggal_verifikasi: "2024-12-06",
            verifikator: "Admin Bidang Informatika",
            berkas: {
                cv: { nama: "CV_Ahmad_Rizki.pdf", ukuran: "2.0 MB" },
                surat: { nama: "Surat_UNNES.pdf", ukuran: "1.1 MB" }
            }
        }
    ];
    
    filterPengajuan();
}

// Filter pengajuan
function filterPengajuan() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const bidangFilter = document.getElementById('bidangFilter').value;
    
    let filtered = pengajuanData;
    
    // Filter pencarian
    if (searchTerm) {
        filtered = filtered.filter(p => 
            p.nama.toLowerCase().includes(searchTerm) ||
            p.nim.toLowerCase().includes(searchTerm) ||
            p.universitas.toLowerCase().includes(searchTerm) ||
            p.jurusan.toLowerCase().includes(searchTerm)
        );
    }
    
    // Filter status
    if (statusFilter !== 'all') {
        filtered = filtered.filter(p => p.status === statusFilter);
    }
    
    // Filter bidang
    if (bidangFilter !== 'all') {
        filtered = filtered.filter(p => p.bidang_pilihan === bidangFilter);
    }
    
    // Sort by tanggal pengajuan (terbaru pertama)
    filtered.sort((a, b) => new Date(b.tanggal_pengajuan) - new Date(a.tanggal_pengajuan));
    
    renderTable(filtered);
    updateStats(filtered);
}

// Reset filter
function resetFilter() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('bidangFilter').value = 'all';
    currentPage = 1;
    filterPengajuan();
}

// Render tabel
function renderTable(data) {
    const container = document.getElementById('pengajuanTable');
    const totalItems = data.length;
    const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
    
    if (currentPage > totalPages) currentPage = totalPages;
    
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = data.slice(start, end);
    
    if (pageData.length === 0) {
        container.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: #888;">
                    <i class='bx bx-search-alt' style="font-size: 3rem; margin-bottom: 10px; display: block;"></i>
                    <div style="font-weight: 500;">Tidak ada pengajuan ditemukan</div>
                    <div style="font-size: 0.9rem;">Coba dengan filter yang berbeda</div>
                </td>
            </tr>
        `;
    } else {
        container.innerHTML = pageData.map(pengajuan => {
            const statusConfig = getStatusConfig(pengajuan.status);
            const tanggal = new Date(pengajuan.tanggal_pengajuan).toLocaleDateString('id-ID');
            
            return `
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="user-avatar">${getInitials(pengajuan.nama)}</div>
                            <div>
                                <div style="font-weight: 600; color: var(--primary);">${pengajuan.nama}</div>
                                <div style="font-size: 0.85rem; color: #666;">${pengajuan.nim}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 500;">${pengajuan.universitas}</div>
                        <div style="font-size: 0.85rem; color: #666;">${pengajuan.jurusan}</div>
                    </td>
                    <td>
                        <span class="bidang-badge">${pengajuan.bidang_pilihan}</span>
                    </td>
                    <td>
                        <div style="color: #666;">${tanggal}</div>
                    </td>
                    <td>
                        <span class="status-badge ${statusConfig.class}">${statusConfig.text}</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn view" onclick="showDetail(${pengajuan.id})" title="Lihat Detail">
                                <i class='bx bx-show'></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }
    
    // Update info
    document.getElementById('pengajuanCount').textContent = `${totalItems} pengajuan`;
    document.getElementById('pageInfo').textContent = `Menampilkan ${start + 1} - ${Math.min(end, totalItems)} dari ${totalItems}`;
    document.getElementById('prevPageBtn').disabled = currentPage === 1;
    document.getElementById('nextPageBtn').disabled = currentPage === totalPages;
}

// Update statistik
function updateStats(data) {
    const pending = data.filter(p => p.status === 'pending').length;
    const accepted = data.filter(p => p.status === 'accepted').length;
    const rejected = data.filter(p => p.status === 'rejected').length;
    const total = data.length;
    
    document.getElementById('pendingCount').textContent = pending;
    document.getElementById('acceptedCount').textContent = accepted;
    document.getElementById('rejectedCount').textContent = rejected;
    document.getElementById('totalCount').textContent = total;
}

// Tampilkan detail pengajuan
function showDetail(id) {
    const pengajuan = pengajuanData.find(p => p.id == id);
    if (!pengajuan) return;
    
    const statusConfig = getStatusConfig(pengajuan.status);
    const tanggalMulai = new Date(pengajuan.tanggal_mulai).toLocaleDateString('id-ID');
    const tanggalSelesai = new Date(pengajuan.tanggal_selesai).toLocaleDateString('id-ID');
    const tanggalPengajuan = new Date(pengajuan.tanggal_pengajuan).toLocaleDateString('id-ID');
    const tanggalVerifikasi = pengajuan.tanggal_verifikasi ? 
        new Date(pengajuan.tanggal_verifikasi).toLocaleDateString('id-ID') : '-';
    
    document.getElementById('modalTitle').textContent = `Detail Pengajuan - ${pengajuan.nama}`;
    
    document.getElementById('modalContent').innerHTML = `
        <div class="detail-section">
            <div class="detail-header">
                <div class="detail-title">
                    <div class="detail-avatar">${getInitials(pengajuan.nama)}</div>
                    <div>
                        <h4>${pengajuan.nama}</h4>
                        <p>${pengajuan.nim} • ${pengajuan.universitas}</p>
                    </div>
                </div>
                <span class="status-badge ${statusConfig.class}">${statusConfig.text}</span>
            </div>
        </div>
        
        <div class="detail-grid">
            <div class="detail-item">
                <label><i class='bx bx-envelope'></i> Email</label>
                <span>${pengajuan.email}</span>
            </div>
            <div class="detail-item">
                <label><i class='bx bx-phone'></i> No. Telepon</label>
                <span>${pengajuan.no_telp}</span>
            </div>
            <div class="detail-item">
                <label><i class='bx bx-book'></i> Program Studi</label>
                <span>${pengajuan.jurusan} - Semester ${pengajuan.semester}</span>
            </div>
            <div class="detail-item">
                <label><i class='bx bx-briefcase'></i> Bidang Pilihan</label>
                <span class="bidang-badge">${pengajuan.bidang_pilihan}</span>
            </div>
            <div class="detail-item">
                <label><i class='bx bx-calendar'></i> Periode Magang</label>
                <span>${tanggalMulai} - ${tanggalSelesai}</span>
            </div>
            <div class="detail-item">
                <label><i class='bx bx-time'></i> Tanggal Pengajuan</label>
                <span>${tanggalPengajuan}</span>
            </div>
        </div>
        
        <div class="detail-section">
            <h4><i class='bx bx-message'></i> Alasan Memilih Bidang</h4>
            <div class="detail-card">
                <p>${pengajuan.alasan}</p>
            </div>
        </div>
        
        <div class="detail-section">
            <h4><i class='bx bx-file'></i> Berkas Pendaftaran</h4>
            <div class="berkas-grid">
                <div class="berkas-card">
                    <div class="berkas-header">
                        <i class='bx bx-file-pdf'></i>
                        <div>
                            <div class="berkas-name">${pengajuan.berkas.cv.nama}</div>
                            <div class="berkas-size">${pengajuan.berkas.cv.ukuran}</div>
                        </div>
                    </div>
                    <button onclick="downloadBerkas(${pengajuan.id}, 'cv')" class="berkas-btn">
                        <i class='bx bx-download'></i> Download CV
                    </button>
                </div>
                
                <div class="berkas-card">
                    <div class="berkas-header">
                        <i class='bx bx-file'></i>
                        <div>
                            <div class="berkas-name">${pengajuan.berkas.surat.nama}</div>
                            <div class="berkas-size">${pengajuan.berkas.surat.ukuran}</div>
                        </div>
                    </div>
                    <button onclick="downloadBerkas(${pengajuan.id}, 'surat')" class="berkas-btn">
                        <i class='bx bx-download'></i> Download Surat
                    </button>
                </div>
            </div>
        </div>
        
        ${pengajuan.status !== 'pending' ? `
            <div class="detail-section">
                <h4><i class='bx bx-check-shield'></i> Informasi Verifikasi</h4>
                <div class="detail-card">
                    <div class="verifikasi-grid">
                        <div class="verifikasi-item">
                            <label>Tanggal Verifikasi</label>
                            <span>${tanggalVerifikasi}</span>
                        </div>
                        <div class="verifikasi-item">
                            <label>Verifikator</label>
                            <span>${pengajuan.verifikator || '-'}</span>
                        </div>
                        <div class="verifikasi-item full">
                            <label>Catatan</label>
                            <span>${pengajuan.catatan || '-'}</span>
                        </div>
                    </div>
                </div>
            </div>
        ` : ''}
        
        <div class="detail-actions">
            <button onclick="showVerifikasiModal(${pengajuan.id})" class="btn btn-primary">
                <i class='bx bx-check'></i> Verifikasi Pengajuan
            </button>
            <button onclick="closeModal()" class="btn btn-secondary">Tutup</button>
        </div>
    `;
    
    document.getElementById('detailModal').style.display = 'flex';
}

// Tampilkan modal verifikasi
function showVerifikasiModal(id) {
    const pengajuan = pengajuanData.find(p => p.id == id);
    if (!pengajuan) return;
    
    document.getElementById('verifikasiId').value = id;
    document.getElementById('verifikasiTitle').textContent = `Verifikasi - ${pengajuan.nama}`;
    document.getElementById('statusVerifikasi').value = pengajuan.status === 'pending' ? '' : pengajuan.status;
    document.getElementById('catatanVerifikasi').value = pengajuan.catatan || '';
    document.getElementById('bidangFinal').value = pengajuan.bidang_final || pengajuan.bidang_pilihan;
    
    document.getElementById('verifikasiModal').style.display = 'flex';
}

// Submit verifikasi
function submitVerifikasi() {
    const id = document.getElementById('verifikasiId').value;
    const status = document.getElementById('statusVerifikasi').value;
    const catatan = document.getElementById('catatanVerifikasi').value;
    const bidangFinal = document.getElementById('bidangFinal').value;
    
    if (!status) {
        showNotification('Pilih status verifikasi terlebih dahulu!', 'error');
        return;
    }
    
    const pengajuan = pengajuanData.find(p => p.id == id);
    if (pengajuan) {
        pengajuan.status = status;
        pengajuan.catatan = catatan;
        pengajuan.tanggal_verifikasi = new Date().toISOString().split('T')[0];
        pengajuan.verifikator = "Admin Utama";
        if (bidangFinal) pengajuan.bidang_final = bidangFinal;
        
        showNotification(`Pengajuan ${pengajuan.nama} telah diverifikasi`, 'success');
        closeVerifikasiModal();
        closeModal();
        filterPengajuan();
    }
}

// Download berkas
function downloadBerkas(id, type) {
    const pengajuan = pengajuanData.find(p => p.id == id);
    if (!pengajuan) return;
    
    const berkas = type === 'cv' ? pengajuan.berkas.cv : type === 'surat' ? pengajuan.berkas.surat : null;
    if (!berkas) return;
    
    // Simulasi download
    showNotification(`Mendownload ${berkas.nama}...`, 'info');
    
    // Buat link download dummy
    const link = document.createElement('a');
    link.href = '#';
    link.download = berkas.nama;
    link.click();
}

// Modal functions
function closeModal() {
    document.getElementById('detailModal').style.display = 'none';
}

function closeVerifikasiModal() {
    document.getElementById('verifikasiModal').style.display = 'none';
    document.getElementById('verifikasiForm').reset();
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

// Utility functions
function getStatusConfig(status) {
    const configs = {
        'pending': { class: 'status-pending', text: 'MENUNGGU' },
        'accepted': { class: 'status-approved', text: 'DITERIMA' },
        'rejected': { class: 'status-rejected', text: 'DITOLAK' }
    };
    return configs[status] || { class: 'status-pending', text: 'MENUNGGU' };
}

function getInitials(name) {
    return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
}

function getFilteredData() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const bidangFilter = document.getElementById('bidangFilter').value;
    
    let filtered = pengajuanData;
    
    if (searchTerm) {
        filtered = filtered.filter(p => 
            p.nama.toLowerCase().includes(searchTerm) ||
            p.nim.toLowerCase().includes(searchTerm) ||
            p.universitas.toLowerCase().includes(searchTerm)
        );
    }
    
    if (statusFilter !== 'all') {
        filtered = filtered.filter(p => p.status === statusFilter);
    }
    
    if (bidangFilter !== 'all') {
        filtered = filtered.filter(p => p.bidang_pilihan === bidangFilter);
    }
    
    return filtered;
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
    /* Reset and Base Styles */
    .content-header {
        margin-bottom: 30px;
    }
    
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
    
    /* Filter Container */
    .filter-container {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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
        font-size: 0.9rem;
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
    
    /* Button Styles */
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
        background: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }
    
    .btn-secondary {
        background: #f8f9fa;
        color: #666;
        border: 1px solid #ddd;
    }
    
    .btn-secondary:hover {
        background: #e2e6ea;
        transform: translateY(-2px);
    }
    
    /* Table Styles */
    .table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .table-header {
        padding: 20px;
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
        font-size: 0.9rem;
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
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #dee2e6;
    }
    
    td {
        padding: 15px;
        border-bottom: 1px solid #eee;
    }
    
    tr:hover {
        background: #f8f9fa;
    }
    
    /* User Avatar */
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
    
    /* Bidang Badge */
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
    
    /* Status Badge */
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
    
    /* Action Buttons */
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
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    /* Pagination */
    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        border-top: 1px solid #eee;
    }
    
    .pagination-info {
        font-size: 0.9rem;
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
        transform: none;
    }
    
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
    
    /* Detail Modal Components */
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
    }
    
    .detail-card p {
        margin: 0;
        line-height: 1.6;
        color: #333;
    }
    
    .berkas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        margin-top: 10px;
    }
    
    .berkas-card {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .berkas-header {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .berkas-header i {
        font-size: 2rem;
        color: #e74c3c;
    }
    
    .berkas-card:nth-child(2) .berkas-header i {
        color: #3498db;
    }
    
    .berkas-name {
        font-weight: 600;
        color: #333;
    }
    
    .berkas-size {
        font-size: 0.85rem;
        color: #666;
    }
    
    .berkas-btn {
        padding: 10px 15px;
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 8px;
        color: #333;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .berkas-btn:hover {
        background: #e9ecef;
    }
    
    .verifikasi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    
    .verifikasi-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    
    .verifikasi-item.full {
        grid-column: 1 / -1;
    }
    
    .verifikasi-item label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #666;
    }
    
    .verifikasi-item span {
        color: #333;
    }
    
    .detail-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    
    /* Form Section */
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
    
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: border-color 0.3s;
        background: white;
    }
    
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
    }
    
    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }
    
    .form-group small {
        display: block;
        margin-top: 5px;
        color: #888;
        font-size: 0.8rem;
    }
    
    .modal-footer {
        padding: 20px 25px;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }
    
    /* Responsive Design */
    @media (max-width: 1024px) {
        .filter-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .detail-grid {
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
        
        .berkas-grid {
            grid-template-columns: 1fr;
        }
        
        .verifikasi-grid {
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
    }
    
    /* Notification Animation */
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