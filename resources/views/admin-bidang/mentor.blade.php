@extends('layouts.admin-bidang')

@section('title', 'Manajemen Mentor')
@section('subtitle', 'Kelola mentor di bidang Anda')

@section('content')

<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <button onclick="showAddMentorModal()" class="btn btn-primary">
        <i class='bx bx-user-plus'></i> Tambah Mentor Baru
    </button>
</div>



<!-- Filter dan Pencarian -->
<div class="form-card mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium mb-2 text-gray-700">Status Mentor</label>
            <select id="filterStatus" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                <option value="all">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium mb-2 text-gray-700">Kapasitas</label>
            <select id="filterKapasitas" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                <option value="all">Semua Kapasitas</option>
                <option value="available">Masih Ada Kuota</option>
                <option value="full">Kuota Penuh</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium mb-2 text-gray-700">Cari Mentor</label>
            <div class="relative">
                <input type="text" id="searchMentor" placeholder="Nama, email, atau jabatan..." 
                       class="w-full p-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                <i class='bx bx-search absolute left-3 top-3.5 text-gray-400'></i>
            </div>
        </div>
    </div>
    
    <div class="mt-4 flex justify-between items-center">
        <div class="text-sm text-gray-600">
            <span id="filterResultCount">0</span> mentor ditemukan
        </div>
        <button onclick="resetFilters()" class="text-primary hover:underline flex items-center gap-1">
            <i class='bx bx-reset'></i> Reset Filter
        </button>
    </div>
</div>

<!-- Daftar Mentor -->
<div class="form-card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-primary flex items-center gap-2">
            <i class='bx bx-list-ul'></i> Daftar Mentor Bidang
        </h3>
    </div>
    
    <!-- Tabel Mentor -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="w-16">No.</th>
                    <th>Nama Mentor</th>
                    <th>Email</th>
                    <th>Jabatan</th>
                    <th>Kapasitas Bimbingan</th>
                    <th>Status</th>
                    <th class="w-40">Aksi</th>
                </tr>
            </thead>
            <tbody id="mentorTableBody">
                <!-- Data akan dimuat via JavaScript -->
                <tr>
                    <td colspan="7" class="text-center py-12">
                        <div class="flex flex-col items-center">
                            <i class='bx bx-loader-circle bx-spin text-3xl text-primary mb-3'></i>
                            <p class="text-gray-500">Memuat data mentor...</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Empty State -->
    <div id="emptyState" class="hidden text-center py-12">
        <i class='bx bx-group text-4xl text-gray-300 mb-4'></i>
        <h4 class="text-lg font-medium text-gray-500 mb-2">Belum ada mentor</h4>
        <p class="text-gray-400 mb-6">Tidak ada mentor yang sesuai dengan filter yang dipilih</p>
        <button onclick="resetFilters()" class="btn btn-primary mr-3">
            <i class='bx bx-reset'></i> Reset Filter
        </button>
        <button onclick="showAddMentorModal()" class="btn btn-secondary">
            <i class='bx bx-user-plus'></i> Tambah Mentor
        </button>
    </div>
    
    <!-- Pagination -->
    <div class="mt-6 flex justify-between items-center">
        <div class="text-gray-600 text-sm">
            Menampilkan <span id="startRow">1</span>-<span id="endRow">10</span> dari <span id="totalRows">0</span> mentor
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



<!-- Modal Tambah Mentor -->
<div id="addMentorModal" class="modal">
    <div class="modal-content max-w-2xl">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Mentor Baru</h3>
            <button class="modal-close" onclick="closeModal('addMentorModal')">&times;</button>
        </div>
        <form id="mentorForm" onsubmit="saveMentor(event)">
            <div class="modal-body">
                <div class="mb-6">
                    <p class="text-gray-600">Mentor baru akan ditambahkan ke bidang <strong id="modalBidangName">Informatika</strong>.</p>
                </div>
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700">Nama Lengkap *</label>
                            <input type="text" id="mentorNama" required
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Nama lengkap mentor">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700">Jabatan *</label>
                            <input type="text" id="mentorJabatan" required
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Jabatan di bidang">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700">Email *</label>
                            <input type="email" id="mentorEmail" required
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="email@diskominfo.go.id">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700">Nomor Telepon</label>
                            <input type="tel" id="mentorTelepon"
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="0812-3456-7890">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700">Kapasitas Bimbingan *</label>
                            <input type="number" id="mentorKapasitas" required min="1" max="10" value="5"
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-2">Jumlah maksimal peserta yang dapat dibimbing (1-10)</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700">Status *</label>
                            <select id="mentorStatus" required
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700">Catatan (Opsional)</label>
                        <textarea id="mentorCatatan" rows="3"
                                  class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                  placeholder="Catatan tambahan tentang mentor"></textarea>
                    </div>
                    
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start gap-3">
                            <i class='bx bx-info-circle text-blue-600 text-xl mt-0.5'></i>
                            <div>
                                <h4 class="font-medium text-blue-800 mb-1">Informasi Akun</h4>
                                <p class="text-sm text-blue-700">
                                    Mentor akan menerima email berisi informasi akun untuk login ke sistem.
                                    Pastikan email yang dimasukkan valid.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addMentorModal')">Batal</button>
                <button type="submit" class="btn btn-primary" id="saveMentorBtn">
                    <i class='bx bx-save'></i> Simpan Mentor
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Mentor -->
<div id="editMentorModal" class="modal">
    <div class="modal-content max-w-2xl">
        <div class="modal-header">
            <h3 class="modal-title">Edit Data Mentor</h3>
            <button class="modal-close" onclick="closeModal('editMentorModal')">&times;</button>
        </div>
        <form id="editMentorForm" onsubmit="updateMentor(event)">
            <input type="hidden" id="editMentorId">
            <div class="modal-body">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700">Nama Lengkap *</label>
                            <input type="text" id="editMentorNama" required
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700">Jabatan *</label>
                            <input type="text" id="editMentorJabatan" required
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700">Email *</label>
                            <input type="email" id="editMentorEmail" required
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700">Nomor Telepon</label>
                            <input type="tel" id="editMentorTelepon"
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700">Kapasitas Bimbingan *</label>
                            <input type="number" id="editMentorKapasitas" required min="1" max="10"
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <div class="mt-2 text-sm text-gray-600">
                                <span id="editBimbinganAktif">0</span> peserta sedang dibimbing
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700">Status *</label>
                            <select id="editMentorStatus" required
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700">Catatan</label>
                        <textarea id="editMentorCatatan" rows="3"
                                  class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                    </div>
                    
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg" id="editWarning">
                        <!-- Warning message will be inserted here -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editMentorModal')">Batal</button>
                <button type="submit" class="btn btn-primary" id="updateMentorBtn">
                    <i class='bx bx-save'></i> Update Mentor
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Mentor -->
<div id="detailMentorModal" class="modal">
    <div class="modal-content max-w-3xl">
        <div class="modal-header">
            <h3 class="modal-title">Detail Mentor</h3>
            <button class="modal-close" onclick="closeModal('detailMentorModal')">&times;</button>
        </div>
        <div class="modal-body" id="detailMentorContent">
            <!-- Content will be loaded via JavaScript -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('detailMentorModal')">Tutup</button>
        </div>
    </div>
</div>

<!-- Modal Hapus Mentor -->
<div id="deleteMentorModal" class="modal">
    <div class="modal-content max-w-md">
        <div class="modal-header">
            <h3 class="modal-title text-red-600">Konfirmasi Hapus Mentor</h3>
            <button class="modal-close" onclick="closeModal('deleteMentorModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="text-center mb-6">
                <i class='bx bx-error text-5xl text-red-500 mb-4'></i>
                <p>Apakah Anda yakin ingin menghapus mentor <strong id="deleteMentorName"></strong>?</p>
            </div>
            
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg mb-6">
                <div class="flex items-start gap-3">
                    <i class='bx bx-error-circle text-red-600 text-xl mt-0.5'></i>
                    <div>
                        <h4 class="font-medium text-red-800 mb-1">Peringatan!</h4>
                        <p class="text-sm text-red-700">
                            Mentor yang sedang membimbing peserta <strong>tidak dapat dihapus</strong>.
                            Jika mentor sedang membimbing peserta, Anda dapat menonaktifkan status mentor terlebih dahulu.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="text-sm text-gray-600" id="deleteWarningInfo">
                <!-- Warning info will be inserted here -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('deleteMentorModal')">Batal</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn" onclick="confirmDeleteMentor()" disabled>
                <i class='bx bx-trash'></i> Hapus Mentor
            </button>
        </div>
    </div>
</div>

<script>
// Data global
let allMentor = [];
let filteredMentor = [];
let currentMentorPage = 1;
const itemsPerPage = 10;
let mentorToDelete = null;

// Data contoh (dalam implementasi real, ini dari API)
const mockMentorData = [
    {
        id: 1,
        nama: "Dr. Ahmad Fauzi, M.Kom.",
        email: "ahmad.fauzi@diskominfo.go.id",
        jabatan: "Kepala Sub Bidang Informatika",
        telepon: "08111222333",
        kapasitas: 6,
        bimbingan_aktif: 2,
        status: "active",
        bidang_id: 1,
        bidang_nama: "Informatika",
        tanggal_ditambahkan: "2024-01-15",
        catatan: "Ahli dalam pengembangan web dan mobile",
        peserta_dibimbing: [
            { id: 3, nama: "Siti Nurhaliza", status: "active" },
            { id: 8, nama: "Joko Santoso", status: "active" }
        ]
    },
    {
        id: 2,
        nama: "Dra. Siti Rahma, M.Si.",
        email: "siti.rahma@diskominfo.go.id",
        jabatan: "Analis Data",
        telepon: "08111222334",
        kapasitas: 5,
        bimbingan_aktif: 1,
        status: "active",
        bidang_id: 1,
        bidang_nama: "Informatika",
        tanggal_ditambahkan: "2024-02-10",
        catatan: "Spesialis data analytics",
        peserta_dibimbing: [
            { id: 5, nama: "Dewi Lestari", status: "active" }
        ]
    },
    {
        id: 3,
        nama: "Ir. Bambang Sudarsono, M.T.",
        email: "bambang.s@diskominfo.go.id",
        jabatan: "Pengembang Sistem",
        telepon: "08111222335",
        kapasitas: 4,
        bimbingan_aktif: 0,
        status: "active",
        bidang_id: 1,
        bidang_nama: "Informatika",
        tanggal_ditambahkan: "2024-02-20",
        catatan: "Ahli jaringan dan sistem",
        peserta_dibimbing: []
    },
    {
        id: 4,
        nama: "Dr. Rina Dewi, M.Si.",
        email: "rina.dewi@diskominfo.go.id",
        jabatan: "Koordinator Bidang",
        telepon: "08111222336",
        kapasitas: 3,
        bimbingan_aktif: 3,
        status: "active",
        bidang_id: 1,
        bidang_nama: "Informatika",
        tanggal_ditambahkan: "2023-12-01",
        catatan: "Pembimbing senior",
        peserta_dibimbing: [
            { id: 10, nama: "Andi Wijaya", status: "active" },
            { id: 11, nama: "Maya Indah", status: "active" },
            { id: 12, nama: "Rudi Hartono", status: "active" }
        ]
    },
    {
        id: 5,
        nama: "Prof. Budi Santoso, Ph.D.",
        email: "budi.santoso@diskominfo.go.id",
        jabatan: "Peneliti Senior",
        telepon: "08111222337",
        kapasitas: 4,
        bimbingan_aktif: 0,
        status: "inactive",
        bidang_id: 1,
        bidang_nama: "Informatika",
        tanggal_ditambahkan: "2023-11-15",
        catatan: "Sedang cuti penelitian",
        peserta_dibimbing: []
    }
];

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    initMentorData();
    setupEventListeners();
    renderAllMentor();
});

// Inisialisasi data mentor
function initMentorData() {
    allMentor = [...mockMentorData];
    filteredMentor = [...allMentor];
    
    // Update statistik
    updateMentorStatistics();
    updateSummary();
}

// Setup event listeners
function setupEventListeners() {
    // Filter listeners
    document.getElementById('filterStatus').addEventListener('change', filterMentor);
    document.getElementById('filterKapasitas').addEventListener('change', filterMentor);
    document.getElementById('searchMentor').addEventListener('input', debounce(filterMentor, 300));
    
    // Pagination listeners
    document.getElementById('prevPage').addEventListener('click', prevMentorPage);
    document.getElementById('nextPage').addEventListener('click', nextMentorPage);
    
    // Form submission
    document.getElementById('mentorForm').addEventListener('submit', saveMentor);
    document.getElementById('editMentorForm').addEventListener('submit', updateMentor);
}

// Render semua komponen mentor
function renderAllMentor() {
    renderMentorTable();
    updateMentorPagination();
}

// Render tabel mentor
function renderMentorTable() {
    const tbody = document.getElementById('mentorTableBody');
    const emptyState = document.getElementById('emptyState');
    
    if (filteredMentor.length === 0) {
        tbody.innerHTML = '';
        emptyState.classList.remove('hidden');
        return;
    }
    
    emptyState.classList.add('hidden');
    
    // Hitung data untuk halaman saat ini
    const startIndex = (currentMentorPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const pageData = filteredMentor.slice(startIndex, endIndex);
    
    let html = '';
    
    pageData.forEach((mentor, index) => {
        const no = startIndex + index + 1;
        const kuotaTersedia = mentor.kapasitas - mentor.bimbingan_aktif;
        const kuotaPersen = Math.round((mentor.bimbingan_aktif / mentor.kapasitas) * 100);
        const kuotaColor = kuotaPersen >= 100 ? 'bg-red-500' : 
                          kuotaPersen >= 80 ? 'bg-orange-500' : 
                          kuotaPersen >= 60 ? 'bg-yellow-500' : 'bg-green-500';
        
        html += `
            <tr class="hover:bg-gray-50">
                <td class="text-center text-gray-500">${no}</td>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-user-circle text-blue-600'></i>
                        </div>
                        <div>
                            <div class="font-medium">${mentor.nama}</div>
                            <div class="text-sm text-gray-500">ID: ${mentor.id}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="text-sm">${mentor.email}</div>
                    <div class="text-xs text-gray-500">${mentor.telepon || '-'}</div>
                </td>
                <td>${mentor.jabatan}</td>
                <td>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">${mentor.bimbingan_aktif}/${mentor.kapasitas}</span>
                            <span class="font-medium ${kuotaTersedia > 0 ? 'text-green-600' : 'text-red-600'}">
                                ${kuotaTersedia} tersedia
                            </span>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full ${kuotaColor}" style="width: ${kuotaPersen}%"></div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="status-badge ${mentor.status === 'active' ? 'status-active' : 'status-pending'}">
                        ${mentor.status === 'active' ? 'Aktif' : 'Nonaktif'}
                    </span>
                </td>
                <td>
                    <div class="flex gap-2">
                        <button onclick="showDetailMentor(${mentor.id})" 
                                class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">
                            <i class='bx bx-show'></i>
                        </button>
                        <button onclick="showEditMentorModal(${mentor.id})" 
                                class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-sm hover:bg-blue-100">
                            <i class='bx bx-edit'></i>
                        </button>
                        <button onclick="showDeleteMentorModal(${mentor.id})" 
                                class="px-3 py-1 bg-red-50 text-red-600 rounded-lg text-sm hover:bg-red-100"
                                ${mentor.bimbingan_aktif > 0 ? 'disabled' : ''}>
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

// Filter mentor
function filterMentor() {
    const statusFilter = document.getElementById('filterStatus').value;
    const kapasitasFilter = document.getElementById('filterKapasitas').value;
    const searchQuery = document.getElementById('searchMentor').value.toLowerCase();
    
    filteredMentor = allMentor.filter(mentor => {
        // Filter status
        if (statusFilter !== 'all' && mentor.status !== statusFilter) return false;
        
        // Filter kapasitas
        if (kapasitasFilter !== 'all') {
            const kuotaTersedia = mentor.kapasitas - mentor.bimbingan_aktif;
            if (kapasitasFilter === 'available' && kuotaTersedia <= 0) return false;
            if (kapasitasFilter === 'full' && kuotaTersedia > 0) return false;
        }
        
        // Search filter
        if (searchQuery) {
            const searchIn = `${mentor.nama} ${mentor.email} ${mentor.jabatan} ${mentor.telepon}`.toLowerCase();
            if (!searchIn.includes(searchQuery)) return false;
        }
        
        return true;
    });
    
    currentMentorPage = 1;
    renderAllMentor();
    updateFilterResultCount();
}

// Update statistik mentor
function updateMentorStatistics() {
    const totalMentor = allMentor.length;
    const mentorAktif = allMentor.filter(m => m.status === 'active').length;
    const availableMentor = allMentor.filter(m => m.status === 'active' && m.bimbingan_aktif < m.kapasitas).length;
    const totalKapasitas = allMentor.reduce((sum, m) => sum + m.kapasitas, 0);
    const kapasitasTerisi = allMentor.reduce((sum, m) => sum + m.bimbingan_aktif, 0);
    const avgPeserta = mentorAktif > 0 ? (kapasitasTerisi / mentorAktif).toFixed(1) : 0;
    
    document.getElementById('totalMentor').textContent = totalMentor;
    document.getElementById('mentorAktif').textContent = mentorAktif;
    document.getElementById('availableMentor').textContent = availableMentor;
    document.getElementById('totalKapasitas').textContent = totalKapasitas;
    document.getElementById('kapasitasTerisi').textContent = kapasitasTerisi;
    document.getElementById('avgPeserta').textContent = avgPeserta;
}

// Update ringkasan
function updateSummary() {
    const totalKapasitas = allMentor.reduce((sum, m) => sum + m.kapasitas, 0);
    const kapasitasTerisi = allMentor.reduce((sum, m) => sum + m.bimbingan_aktif, 0);
    const kapasitasTersedia = totalKapasitas - kapasitasTerisi;
    const kuotaTerisiPersen = totalKapasitas > 0 ? Math.round((kapasitasTerisi / totalKapasitas) * 100) : 0;
    
    document.getElementById('summaryKapasitasTersedia').textContent = kapasitasTersedia;
    document.getElementById('summaryKuotaTerisi').textContent = `${kuotaTerisiPersen}%`;
    
    document.getElementById('kapasitasTersediaBar').style.width = `${(kapasitasTersedia / totalKapasitas) * 100}%`;
    document.getElementById('kuotaTerisiBar').style.width = `${kuotaTerisiPersen}%`;
}

// Update filter result count
function updateFilterResultCount() {
    document.getElementById('filterResultCount').textContent = filteredMentor.length;
}

// Pagination mentor
function updateMentorPagination() {
    const totalPages = Math.ceil(filteredMentor.length / itemsPerPage);
    const startRow = ((currentMentorPage - 1) * itemsPerPage) + 1;
    const endRow = Math.min(currentMentorPage * itemsPerPage, filteredMentor.length);
    
    document.getElementById('startRow').textContent = startRow;
    document.getElementById('endRow').textContent = endRow;
    document.getElementById('totalRows').textContent = filteredMentor.length;
    document.getElementById('currentPage').textContent = currentMentorPage;
    document.getElementById('totalPages').textContent = totalPages;
    
    document.getElementById('prevPage').disabled = currentMentorPage === 1;
    document.getElementById('nextPage').disabled = currentMentorPage === totalPages || totalPages === 0;
}

function prevMentorPage() {
    if (currentMentorPage > 1) {
        currentMentorPage--;
        renderMentorTable();
        updateMentorPagination();
    }
}

function nextMentorPage() {
    const totalPages = Math.ceil(filteredMentor.length / itemsPerPage);
    if (currentMentorPage < totalPages) {
        currentMentorPage++;
        renderMentorTable();
        updateMentorPagination();
    }
}

// Modal functions
function showAddMentorModal() {
    document.getElementById('modalBidangName').textContent = 'Informatika';
    document.getElementById('mentorForm').reset();
    document.getElementById('mentorKapasitas').value = 5;
    document.getElementById('mentorStatus').value = 'active';
    openModal('addMentorModal');
}

function showEditMentorModal(mentorId) {
    const mentor = allMentor.find(m => m.id === mentorId);
    if (!mentor) return;
    
    document.getElementById('editMentorId').value = mentor.id;
    document.getElementById('editMentorNama').value = mentor.nama;
    document.getElementById('editMentorEmail').value = mentor.email;
    document.getElementById('editMentorJabatan').value = mentor.jabatan;
    document.getElementById('editMentorTelepon').value = mentor.telepon || '';
    document.getElementById('editMentorKapasitas').value = mentor.kapasitas;
    document.getElementById('editMentorStatus').value = mentor.status;
    document.getElementById('editMentorCatatan').value = mentor.catatan || '';
    document.getElementById('editBimbinganAktif').textContent = mentor.bimbingan_aktif;
    
    // Update warning message
    const warningDiv = document.getElementById('editWarning');
    if (mentor.bimbingan_aktif > 0) {
        warningDiv.innerHTML = `
            <div class="flex items-start gap-3">
                <i class='bx bx-error text-orange-600 text-xl mt-0.5'></i>
                <div>
                    <h4 class="font-medium text-orange-800 mb-1">Perhatian!</h4>
                    <p class="text-sm text-orange-700">
                        Mentor ini sedang membimbing ${mentor.bimbingan_aktif} peserta.
                        Mengurangi kapasitas di bawah ${mentor.bimbingan_aktif} akan menyebabkan kesalahan.
                    </p>
                </div>
            </div>
        `;
    } else {
        warningDiv.innerHTML = '';
    }
    
    openModal('editMentorModal');
}

function showDetailMentor(mentorId) {
    const mentor = allMentor.find(m => m.id === mentorId);
    if (!mentor) return;
    
    const kuotaTersedia = mentor.kapasitas - mentor.bimbingan_aktif;
    const kuotaPersen = Math.round((mentor.bimbingan_aktif / mentor.kapasitas) * 100);
    const tanggalDitambahkan = new Date(mentor.tanggal_ditambahkan).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });
    
    const detailContent = document.getElementById('detailMentorContent');
    detailContent.innerHTML = `
        <div class="space-y-6">
            <!-- Header Info -->
            <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-lg">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class='bx bx-user-circle text-blue-600 text-3xl'></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-lg">${mentor.nama}</h4>
                    <div class="text-gray-600">${mentor.jabatan}</div>
                    <div class="flex items-center gap-4 mt-2">
                        <span class="status-badge ${mentor.status === 'active' ? 'status-active' : 'status-pending'}">
                            ${mentor.status === 'active' ? 'Aktif' : 'Nonaktif'}
                        </span>
                        <span class="text-sm text-gray-500">
                            <i class='bx bx-calendar'></i> Ditambahkan: ${tanggalDitambahkan}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Contact Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h5 class="font-medium text-gray-700 mb-3">Informasi Kontak</h5>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-500">Email</label>
                            <div class="font-medium">${mentor.email}</div>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Telepon</label>
                            <div class="font-medium">${mentor.telepon || '-'}</div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h5 class="font-medium text-gray-700 mb-3">Kapasitas Bimbingan</h5>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Kapasitas:</span>
                                <span class="font-medium">${mentor.bimbingan_aktif}/${mentor.kapasitas}</span>
                            </div>
                            <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full ${kuotaPersen >= 100 ? 'bg-red-500' : kuotaPersen >= 80 ? 'bg-orange-500' : kuotaPersen >= 60 ? 'bg-yellow-500' : 'bg-green-500'}" 
                                     style="width: ${kuotaPersen}%"></div>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <div class="text-sm">
                                <span class="text-gray-600">Kuota Tersedia:</span>
                                <span class="font-medium ml-2 ${kuotaTersedia > 0 ? 'text-green-600' : 'text-red-600'}">
                                    ${kuotaTersedia}
                                </span>
                            </div>
                            <div class="text-sm">
                                <span class="text-gray-600">Terisi:</span>
                                <span class="font-medium ml-2">${kuotaPersen}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Catatan -->
            ${mentor.catatan ? `
                <div>
                    <h5 class="font-medium text-gray-700 mb-3">Catatan</h5>
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <p class="text-gray-600">${mentor.catatan}</p>
                    </div>
                </div>
            ` : ''}
            
            <!-- Daftar Peserta Dibimbing -->
            <div>
                <h5 class="font-medium text-gray-700 mb-3">Peserta yang Dibimbing</h5>
                ${mentor.peserta_dibimbing.length > 0 ? `
                    <div class="space-y-2">
                        ${mentor.peserta_dibimbing.map(peserta => `
                            <div class="flex items-center justify-between p-3 border rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                        <i class='bx bx-user text-gray-600'></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">${peserta.nama}</div>
                                        <div class="text-sm text-gray-500">Status: ${peserta.status === 'active' ? 'Aktif' : 'Selesai'}</div>
                                    </div>
                                </div>
                                <button onclick="window.location.href='/admin-bidang/penempatan?peserta=${peserta.id}'" 
                                        class="text-sm text-primary hover:underline">
                                    Lihat Detail
                                </button>
                            </div>
                        `).join('')}
                    </div>
                ` : `
                    <div class="text-center py-6 border border-dashed border-gray-300 rounded-lg">
                        <i class='bx bx-user-x text-3xl text-gray-300 mb-2'></i>
                        <p class="text-gray-400">Belum ada peserta yang dibimbing</p>
                    </div>
                `}
            </div>
        </div>
    `;
    
    openModal('detailMentorModal');
}

function showDeleteMentorModal(mentorId) {
    const mentor = allMentor.find(m => m.id === mentorId);
    if (!mentor) return;
    
    mentorToDelete = mentorId;
    document.getElementById('deleteMentorName').textContent = mentor.nama;
    document.getElementById('confirmDeleteBtn').disabled = mentor.bimbingan_aktif > 0;
    
    // Update warning info
    const warningInfo = document.getElementById('deleteWarningInfo');
    if (mentor.bimbingan_aktif > 0) {
        warningInfo.innerHTML = `
            <div class="text-center p-3 bg-red-50 rounded-lg">
                <i class='bx bx-error text-red-500 mb-2'></i>
                <p class="text-red-600 font-medium">
                    Mentor ini sedang membimbing ${mentor.bimbingan_aktif} peserta.
                    Tidak dapat dihapus. Nonaktifkan terlebih dahulu.
                </p>
            </div>
        `;
    } else {
        warningInfo.innerHTML = `
            <div class="text-center p-3 bg-yellow-50 rounded-lg">
                <i class='bx bx-info-circle text-yellow-500 mb-2'></i>
                <p class="text-yellow-600">
                    Mentor tidak sedang membimbing peserta. Dapat dihapus dengan aman.
                </p>
            </div>
        `;
    }
    
    openModal('deleteMentorModal');
}

// Form handling
function saveMentor(event) {
    event.preventDefault();
    
    const newMentor = {
        id: allMentor.length > 0 ? Math.max(...allMentor.map(m => m.id)) + 1 : 1,
        nama: document.getElementById('mentorNama').value,
        email: document.getElementById('mentorEmail').value,
        jabatan: document.getElementById('mentorJabatan').value,
        telepon: document.getElementById('mentorTelepon').value || null,
        kapasitas: parseInt(document.getElementById('mentorKapasitas').value),
        bimbingan_aktif: 0,
        status: document.getElementById('mentorStatus').value,
        bidang_id: 1,
        bidang_nama: "Informatika",
        tanggal_ditambahkan: new Date().toISOString().split('T')[0],
        catatan: document.getElementById('mentorCatatan').value || null,
        peserta_dibimbing: []
    };
    
    // Validasi email unik
    if (allMentor.some(m => m.email === newMentor.email)) {
        showNotification('Email mentor sudah terdaftar', 'error');
        return;
    }
    
    // Add to data
    allMentor.push(newMentor);
    filteredMentor = [...allMentor];
    
    // Update UI
    closeModal('addMentorModal');
    renderAllMentor();
    updateMentorStatistics();
    updateSummary();
    
    // Show success message
    showNotification(`Mentor ${newMentor.nama} berhasil ditambahkan`, 'success');
    
    // Simulasi pengiriman email
    setTimeout(() => {
        showNotification(`Email konfirmasi telah dikirim ke ${newMentor.email}`, 'info');
    }, 1000);
}

function updateMentor(event) {
    event.preventDefault();
    
    const mentorId = parseInt(document.getElementById('editMentorId').value);
    const mentor = allMentor.find(m => m.id === mentorId);
    
    if (!mentor) return;
    
    // Validasi kapasitas
    const newKapasitas = parseInt(document.getElementById('editMentorKapasitas').value);
    if (newKapasitas < mentor.bimbingan_aktif) {
        showNotification(`Kapasitas tidak boleh kurang dari ${mentor.bimbingan_aktif} (peserta yang sedang dibimbing)`, 'error');
        return;
    }
    
    // Update data mentor
    mentor.nama = document.getElementById('editMentorNama').value;
    mentor.email = document.getElementById('editMentorEmail').value;
    mentor.jabatan = document.getElementById('editMentorJabatan').value;
    mentor.telepon = document.getElementById('editMentorTelepon').value || null;
    mentor.kapasitas = newKapasitas;
    mentor.status = document.getElementById('editMentorStatus').value;
    mentor.catatan = document.getElementById('editMentorCatatan').value || null;
    
    // Update UI
    closeModal('editMentorModal');
    renderAllMentor();
    updateMentorStatistics();
    updateSummary();
    
    showNotification(`Data mentor ${mentor.nama} berhasil diperbarui`, 'success');
}

function confirmDeleteMentor() {
    if (!mentorToDelete) return;
    
    const mentor = allMentor.find(m => m.id === mentorToDelete);
    if (!mentor) return;
    
    // Hanya bisa hapus jika tidak ada peserta yang dibimbing
    if (mentor.bimbingan_aktif > 0) {
        showNotification('Tidak dapat menghapus mentor yang sedang membimbing peserta', 'error');
        closeModal('deleteMentorModal');
        return;
    }
    
    // Remove from data
    allMentor = allMentor.filter(m => m.id !== mentorToDelete);
    filteredMentor = [...allMentor];
    
    // Update UI
    closeModal('deleteMentorModal');
    renderAllMentor();
    updateMentorStatistics();
    updateSummary();
    
    showNotification(`Mentor ${mentor.nama} berhasil dihapus`, 'success');
    
    mentorToDelete = null;
}

// Utility functions
function resetFilters() {
    document.getElementById('filterStatus').value = 'all';
    document.getElementById('filterKapasitas').value = 'all';
    document.getElementById('searchMentor').value = '';
    
    filterMentor();
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
        notification.style.opacity = '0';
        notification.style.transition = 'opacity 0.3s';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Placeholder functions
function exportMentorData() {
    showNotification('Fitur export data mentor akan segera tersedia', 'info');
}

function printMentorList() {
    showNotification('Fitur cetak daftar mentor akan segera tersedia', 'info');
}

function showCapacityReport() {
    showNotification('Fitur laporan kapasitas akan segera tersedia', 'info');
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

/* Danger Button */
.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
}

.btn-danger:disabled {
    background: #fca5a5;
    cursor: not-allowed;
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
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection