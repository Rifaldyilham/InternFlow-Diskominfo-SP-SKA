@extends('layouts.admin')

@section('title', 'Manajemen Akun')
@section('subtitle', 'Kelola Semua Akun Terdaftar')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endsection

@section('content')
<div class="content-header">
    <div class="header-actions">
        <button class="btn btn-primary" onclick="showAddModal()">
            <i class='bx bx-user-plus'></i> Tambah Akun
        </button>
    </div>
    
    <div class="search-filter">
        <div class="search-box">
            <i class='bx bx-search'></i>
            <input type="text" id="searchInput" placeholder="Cari nama atau email...">
        </div>
        <select class="filter-select" id="roleFilter">
            <option value="">Semua Role</option>
            <option value="1">Admin Kepegawaian</option>
            <option value="2">Admin Bidang</option>
            <option value="3">Mentor</option>
        </select>
    </div>
</div>

<!-- Tabel Akun -->
<div class="table-container">
    <div class="table-header">
        <h3>Daftar Akun Pengguna</h3>
        <div class="table-actions">
            <div class="table-info">
                Total: <span id="totalUsers">0</span> akun
            </div>
            <button class="btn btn-secondary btn-sm" onclick="refreshData()">
                <i class='bx bx-refresh'></i> Refresh
            </button>
        </div>
    </div>
    
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Tanggal Bergabung</th>
                    <th style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="pesertaTableBody">
                <!-- Data akan dimuat via AJAX -->
                <tr id="loadingRow">
                    <td colspan="5" style="text-align: center; padding: 50px 20px;">
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
    
    <div class="table-footer">
        <div class="pagination-info">
            Menampilkan <span id="startRow">0</span>-<span id="endRow">0</span> dari <span id="totalRows">0</span> akun
        </div>
        <div class="pagination-controls">
            <button class="pagination-btn" onclick="prevPage()" id="prevBtn" disabled>
                <i class='bx bx-chevron-left'></i>
            </button>
            <div class="page-numbers">
                <span id="currentPage">1</span> / <span id="totalPages">1</span>
            </div>
            <button class="pagination-btn" onclick="nextPage()" id="nextBtn" disabled>
                <i class='bx bx-chevron-right'></i>
            </button>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Akun -->
<div id="userModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Tambah Akun Baru</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="userForm">
            @csrf
            <div class="modal-body">
                <input type="hidden" id="editUserId">
                
                <div class="form-group">
                    <label for="name">Nama Lengkap *</label>
                    <input type="text" id="name" name="name" required placeholder="Masukkan nama lengkap">
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required placeholder="contoh@email.com">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password" required placeholder="Minimal 8 karakter">
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password">
                    </div>
                </div>

                <!-- ERROR MESSAGE -->
                <div class="password-error-container" id="passwordErrorContainer" style="display: none;">
                    <div class="password-error" id="passwordError">
                        <!-- Pesan error akan ditampilkan di sini -->
                    </div>
                </div>

                <div class="form-group" id="nipWrapper" style="display: none;">
                    <label for="nip">NIP Pegawai *</label>
                    <input type="text" id="nip" name="nip">
                </div>

                
                <div class="form-row">
                    <div class="form-group">
                        <label for="id_role">Role *</label>
                        <select id="id_role" name="id_role" required>
                            <option value="">Pilih Role</option>
                            <option value="1">Admin Kepegawaian</option>
                            <option value="2">Admin Bidang</option>
                            <option value="3">Mentor</option>
                        </select>
                    </div>

                    <!-- Bidang (hanya untuk Admin Bidang & Mentor) -->
                    <div class="form-group" id="bidangWrapper" style="display: none;">
                        <label for="bidang">Bidang *</label>
                        <select id="bidang" name="id_bidang">
                            <option value="">Pilih Bidang</option>
                            <option value="1">Teknologi & Informatika</option>
                            <option value="2">Statistika</option>
                            <option value="3">Komunikasi Publik dan Media</option>
                            <option value="4">Sekretariat</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status_aktif">Status Aktif *</label>
                    <select id="status_aktif" name="status_aktif" required>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn btn-primary" id="modalSubmitBtn">
                    <i class='bx bx-save'></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Akun -->
<div id="detailModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title">Detail Akun</h3>
            <button class="modal-close" onclick="closeDetailModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="space-y-6">
                <!-- Header dengan avatar -->
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg profile-header">
                    <div class="w-16 h-16 bg-primary text-white rounded-full flex items-center justify-center text-xl font-bold" id="detailAvatar">
                        AK
                    </div>
                    <div class="profile-text">
                        <h4 class="font-bold text-lg" id="detailName">-</h4>
                        <div class="text-gray-600" id="detailEmail">-</div>
                    </div>
                </div>
                
                <!-- Informasi Akun -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-sm text-gray-500 block">Role</label>
                        <div id="detailRole" class="font-medium">-</div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm text-gray-500 block">NIP</label>
                        <div id="detailNip" class="font-medium">-</div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm text-gray-500 block">Bidang</label>
                        <div id="detailBidang" class="font-medium">-</div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm text-gray-500 block">Status</label>
                        <div id="detailStatus">-</div>
                    </div>
                </div>
                
                <!-- Informasi Tambahan -->
                <div>
                    <h5 class="font-semibold mb-3 text-primary">Informasi Lainnya</h5>
                    <div class="p-4 bg-gray-50 rounded-lg space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Tanggal Bergabung</span>
                            <span class="font-medium" id="detailJoinDate">-</span>
                        </div>
                    </div>
                </div>
                
                <!-- Status Badge yang lebih baik -->
                <div id="statusBadgeContainer">
                    <!-- Status akan ditambahkan oleh JavaScript -->
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeDetailModal()">
                <i class='bx bx-x'></i> Tutup
            </button>
            <button class="btn btn-primary" id="detailEditBtn">
                <i class='bx bx-edit'></i> Edit Akun
            </button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header">
            <h3>Konfirmasi Hapus</h3>
            <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="warning-icon">
                <i class='bx bx-error-circle' style="font-size: 3rem; color: #e74c3c;"></i>
            </div>
            <p style="text-align: center; margin: 20px 0;">
                Apakah Anda yakin ingin menghapus akun <strong id="deleteUserName"></strong>?
            </p>
            <p class="text-warning" style="text-align: center;">
                <i class='bx bx-info-circle'></i> Data yang dihapus tidak dapat dikembalikan.
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
            <button class="btn btn-danger" onclick="confirmDelete()" id="deleteConfirmBtn">
                <i class='bx bx-trash'></i> Hapus
            </button>
        </div>
    </div>
</div>

<!-- Notification Container -->
<div id="notificationContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;"></div>
@endsection

@section('scripts')
<script>
// Konfigurasi
const API_CONFIG = {
    baseUrl: window.location.origin,
    endpoints: {
        users: '/api/admin/users',
        roles: '/api/admin/roles'
    }
};

let currentPage = 1;
let totalPages = 1;
let perPage = 10;
let currentSearch = '';
let currentRoleFilter = '';
let userToDelete = null;
let currentDetailUser = null;

// Inisialisasi
document.addEventListener('DOMContentLoaded', function () {
    fetchRoles();
    fetchUsers();
    setupCSRF();
    
    // Event listeners
    const userForm = document.getElementById('userForm');
    if (userForm) {
        userForm.addEventListener('submit', handleSubmit);
    }
    
    document.getElementById('searchInput').addEventListener('input', debounce(searchUsers, 300));
    document.getElementById('roleFilter').addEventListener('change', filterUsers);
    
    // Password validation
    setupPasswordValidation();
    
    // Role change handler
    setupRoleChangeHandler();
});

// Setup CSRF
function setupCSRF() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    if (token) {
        window.csrfToken = token;
    }
}

// Setup password validation
function setupPasswordValidation() {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    
    if (passwordInput && confirmInput) {
        passwordInput.addEventListener('input', validatePassword);
        confirmInput.addEventListener('input', validateConfirmPassword);
    }
}

function validatePassword() {
    const password = document.getElementById('password').value;
    const passwordErrorContainer = document.getElementById('passwordErrorContainer');
    const passwordError = document.getElementById('passwordError');
    const regex = /^(?=.*[A-Za-z])(?=.*\d).{8,}$/;
    
    if (password.length === 0) {
        passwordErrorContainer.style.display = 'none';
        passwordError.textContent = '';
        return false;
    }
    
    if (!regex.test(password)) {
        passwordErrorContainer.style.display = 'block';
        passwordError.textContent = 'Password harus minimal 8 karakter dan mengandung huruf serta angka';
        passwordError.style.color = '#e74c3c';
        return false;
    } else {
        passwordErrorContainer.style.display = 'none';
        return true;
    }
}

function validateConfirmPassword() {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirmation').value;
    const passwordErrorContainer = document.getElementById('passwordErrorContainer');
    const passwordError = document.getElementById('passwordError');
    
    if (confirm.length === 0) {
        passwordErrorContainer.style.display = 'none';
        return false;
    }
    
    if (password !== confirm) {
        passwordErrorContainer.style.display = 'block';
        passwordError.textContent = 'Password dan konfirmasi password tidak cocok';
        passwordError.style.color = '#e74c3c';
        return false;
    } else {
        passwordErrorContainer.style.display = 'none';
        return true;
    }
}

// Setup role change handler
function setupRoleChangeHandler() {
    const roleSelect = document.getElementById('id_role');
    const bidangWrapper = document.getElementById('bidangWrapper');
    const nipWrapper = document.getElementById('nipWrapper');
    
    if (!roleSelect) return;
    
    roleSelect.addEventListener('change', function () {
        const roleId = this.value;
        
        // BIDANG: Admin Bidang & Mentor
        if (roleId === '2' || roleId === '3') {
            bidangWrapper.style.display = 'block';
            nipWrapper.style.display = 'block';
        } else {
            bidangWrapper.style.display = 'none';
            nipWrapper.style.display = 'none';
            document.getElementById('bidang').value = '';
            document.getElementById('nip').value = '';
        }
    });
}

// Fetch roles
async function fetchRoles() {
    try {
        const response = await fetch(API_CONFIG.endpoints.roles, {
            headers: { 
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken 
            }
        });
        
        if (!response.ok) throw new Error('Failed to fetch roles');
        
        const data = await response.json();
        populateRoleSelect(data.data || data);
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat data role', 'error');
    }
}

// Populate role select
function populateRoleSelect(roles) {
    const select = document.getElementById('id_role');
    if (!select) return;
    
    select.innerHTML = '<option value="">Pilih Role</option>';
    
    roles.forEach(role => {
        const option = document.createElement('option');
        option.value = role.id || role.id_roles;
        option.textContent = role.name || role.nama_role;
        select.appendChild(option);
    });
}

// Fetch users
async function fetchUsers() {
    try {
        showLoading(true);
        
        let url = `${API_CONFIG.endpoints.users}?page=${currentPage}&per_page=${perPage}`;
        
        const params = [];
        if (currentSearch) params.push(`search=${encodeURIComponent(currentSearch)}`);
        if (currentRoleFilter) params.push(`role=${currentRoleFilter}`);

        if (params.length > 0) {
            url += `&${params.join('&')}`;
        }
        
        const response = await fetch(url, {
            headers: { 
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken 
            }
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Failed to fetch users');
        }
        
        const data = await response.json();
        
        // Perbaiki struktur data untuk kompatibilitas
        const users = data.data || data;
        const meta = data.meta || {
            current_page: currentPage,
            per_page: perPage,
            total: data.total || users.length || 0,
            last_page: Math.ceil((data.total || users.length || 0) / perPage)
        };
        
        renderTable(users);
        updatePagination(meta);
        document.getElementById('totalUsers').textContent = meta.total || 0;
        
    } catch (error) {
        console.error('Error:', error);
        renderEmptyTable('Terjadi kesalahan saat memuat data');
        showNotification('Gagal memuat data akun', 'error');
    } finally {
        showLoading(false);
    }
}

// Render table
function renderTable(users) {
    const tbody = document.getElementById('pesertaTableBody');
    
    if (!tbody) return;
    
    if (!users || users.length === 0) {
        renderEmptyTable('Tidak ada data akun ditemukan');
        return;
    }
    
    tbody.innerHTML = '';
    
    users.forEach(user => {
        const row = document.createElement('tr');
        const roleClass = getRoleClass(user.id_role);
        const roleName = getRoleName(user.id_role);
        const userId = user.id_user || user.id;
        
        row.innerHTML = `
            <td>
                <div class="user-cell">
                    <div class="avatar">${getInitials(user.name)}</div>
                    <div class="user-info-cell">
                        <div class="user-name">${user.name}</div>
                        <div class="user-email">${user.email}</div>
                    </div>
                </div>
            </td>
            <td>
                <span class="role-badge ${roleClass}">${roleName}</span>
            </td>
            <td>
                <span class="status-badge ${user.status_aktif == 1 ? 'status-active' : 'status-inactive'}">
                    ${user.status_aktif == 1 ? 'Aktif' : 'Nonaktif'}
                </span>
            </td>
            <td>
                <div>${formatDate(user.created_at)}</div>
                <small style="color: #888; font-size: 0.85rem;">${formatTime(user.created_at)}</small>
            </td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn view" title="Detail" onclick="showDetail(${userId})">
                        <i class='bx bx-show'></i>
                    </button>
                    <button class="action-btn edit" title="Edit" onclick="editUser(${userId})">
                        <i class='bx bx-edit'></i>
                    </button>
                    <button class="action-btn delete" title="Hapus" onclick="showDeleteModal(${userId}, '${escapeHtml(user.name)}')">
                        <i class='bx bx-trash'></i>
                    </button>
                </div>
            </td>
        `;
        
        tbody.appendChild(row);
    });
}

// Helper functions
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

function getRoleClass(roleId) {
    switch(parseInt(roleId)) {
        case 1: return 'role-admin';
        case 2: return 'role-admin-bidang';
        case 3: return 'role-mentor';
        case 4: return 'role-peserta';
        default: return '';
    }
}

function getRoleName(roleId) {
    switch(parseInt(roleId)) {
        case 1: return 'Admin Kepegawaian';
        case 2: return 'Admin Bidang';
        case 3: return 'Mentor';
        case 4: return 'Peserta Magang';
        default: return 'Unknown';
    }
}

function getInitials(name) {
    if (!name) return '??';
    return name
        .split(' ')
        .map(word => word.charAt(0).toUpperCase())
        .join('')
        .substring(0, 2);
}

function formatDate(dateString) {
    if (!dateString) return '-';
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    } catch (e) {
        return '-';
    }
}

function formatTime(dateString) {
    if (!dateString) return '';
    try {
        const date = new Date(dateString);
        return date.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (e) {
        return '';
    }
}

function renderEmptyTable(message) {
    const tbody = document.getElementById('pesertaTableBody');
    if (!tbody) return;
    
    tbody.innerHTML = `
        <tr>
            <td colspan="6">
                <div class="empty-state">
                    <i class='bx bx-user-x'></i>
                    <h4>${message}</h4>
                    <p>Coba perbaiki pencarian atau filter yang Anda gunakan</p>
                    <button class="btn btn-secondary btn-sm" onclick="refreshData()" style="margin-top: 15px;">
                        <i class='bx bx-refresh'></i> Muat Ulang Data
                    </button>
                </div>
            </td>
        </tr>
    `;
}

function showLoading(show) {
    const loadingRow = document.getElementById('loadingRow');
    if (loadingRow) {
        loadingRow.style.display = show ? 'table-row' : 'none';
    }
}

// Modal functions
function showAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Akun Baru';
    document.getElementById('userForm').reset();
    document.getElementById('editUserId').value = '';
    document.getElementById('status_aktif').value = '1';
    document.getElementById('password').required = true;
    document.getElementById('password_confirmation').required = true;
    document.getElementById('passwordErrorContainer').style.display = 'none';
    
    // Reset bidang dan NIP
    const bidangWrapper = document.getElementById('bidangWrapper');
    const nipWrapper = document.getElementById('nipWrapper');
    if (bidangWrapper) bidangWrapper.style.display = 'none';
    if (nipWrapper) nipWrapper.style.display = 'none';
    
    openModal('userModal');
}

async function editUser(id) {
    try {
        showLoading(true);
        
        const response = await fetch(`${API_CONFIG.endpoints.users}/${id}`, {
            headers: { 
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken 
            }
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Failed to fetch user data');
        }
        
        const user = await response.json();
        
        document.getElementById('modalTitle').textContent = 'Edit Akun';
        document.getElementById('editUserId').value = user.id_user || user.id;
        document.getElementById('name').value = user.name || '';
        document.getElementById('email').value = user.email || '';
        document.getElementById('nip').value = user.pegawai?.nip || '';
        document.getElementById('bidang').value = user.pegawai?.id_bidang || '';
        document.getElementById('status_aktif').value = user.status_aktif || '1';
        document.getElementById('id_role').value = user.id_role || '';
        document.getElementById('passwordErrorContainer').style.display = 'none';
        
        // Tampilkan bidang jika role Admin Bidang / Mentor saat edit
        const bidangWrapper = document.getElementById('bidangWrapper');
        const nipWrapper = document.getElementById('nipWrapper');
        const roleId = user.id_role;

        if (roleId == 2 || roleId == 3) {
            if (bidangWrapper) bidangWrapper.style.display = 'block';
            if (nipWrapper) nipWrapper.style.display = 'block';
        } else {
            if (bidangWrapper) bidangWrapper.style.display = 'none';
            if (nipWrapper) nipWrapper.style.display = 'none';
        }
        
        // Password tidak wajib untuk edit
        document.getElementById('password').required = false;
        document.getElementById('password_confirmation').required = false;
        document.getElementById('password').placeholder = 'Masukkan password';
        document.getElementById('password_confirmation').placeholder = 'Masukkan ulang password';
        
        openModal('userModal');
        
    } catch (error) {
        console.error('Error:', error);
        showNotification(error.message || 'Gagal memuat data pengguna', 'error');
    } finally {
        showLoading(false);
    }
}

async function showDetail(id) {
    try {
        showLoading(true);
        
        const response = await fetch(`${API_CONFIG.endpoints.users}/${id}`, {
            headers: { 
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken 
            }
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Failed to fetch user data');
        }
        
        const user = await response.json();
        currentDetailUser = user;
        
        // Update detail modal
        document.getElementById('detailAvatar').textContent = getInitials(user.name);
        document.getElementById('detailName').textContent = user.name || '-';
        document.getElementById('detailEmail').textContent = user.email || '-';
        document.getElementById('detailRole').textContent = getRoleName(user.id_role);
        document.getElementById('detailStatus').innerHTML = user.status_aktif == 1 
            ? '<span class="status-active">Aktif</span>'
            : '<span class="status-inactive">Nonaktif</span>';
        document.getElementById('detailNip').textContent = user.pegawai?.nip || '-';
        const bidangElement = document.getElementById('detailBidang');
        if (user.pegawai?.bidang?.nama_bidang) {
            bidangElement.textContent = user.pegawai.bidang.nama_bidang;
        } else if (user.pegawai?.id_bidang) {
            // Jika hanya dapat ID, konversi ke nama
            bidangElement.textContent = convertBidangIdToName(user.pegawai.id_bidang);
        } else {
            bidangElement.textContent = '-';
        }
        document.getElementById('detailJoinDate').textContent = formatDate(user.created_at);
        
        // Update edit button
        const editBtn = document.getElementById('detailEditBtn');
        if (editBtn) {
            editBtn.onclick = function() {
                closeDetailModal();
                editUser(user.id_user || user.id);
            };
        }
        
        openModal('detailModal');
        
    } catch (error) {
        console.error('Error:', error);
        showNotification(error.message || 'Gagal memuat detail pengguna', 'error');
    } finally {
        showLoading(false);
    }
}

function convertBidangIdToName(id) {
    const bidangMap = {
        '1': 'Teknologi & Informatika',
        '2': 'Statistika',
        '3': 'Komunikasi Publik dan Media',
        '4': 'Sekretariat'
    };
    return bidangMap[id] || `Bidang ${id}`;
}

function openModal(modalId) {
    closeAllModals();
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        modal.style.animation = 'modalSlideIn 0.3s ease-out';
    }
}

function closeModal() {
    const modal = document.getElementById('userModal');
    if (modal) {
        modal.style.display = 'none';
    }
    // Reset password fields
    document.getElementById('password').value = '';
    document.getElementById('password_confirmation').value = '';
    document.getElementById('passwordErrorContainer').style.display = 'none';
}

function closeDetailModal() {
    const modal = document.getElementById('detailModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.style.display = 'none';
    }
    userToDelete = null;
    document.getElementById('deleteConfirmBtn').disabled = false;
    document.getElementById('deleteConfirmBtn').innerHTML = '<i class="bx bx-trash"></i> Hapus';
}

function closeAllModals() {
    document.querySelectorAll('.modal').forEach(m => {
        m.style.display = 'none';
    });
}

// Form submission
async function handleSubmit(e) {
    e.preventDefault();
    
    const userId = document.getElementById('editUserId').value;
    const isEdit = !!userId;
    
    // Validasi password untuk tambah baru
    if (!isEdit) {
        const passwordValid = validatePassword();
        const confirmValid = validateConfirmPassword();
        
        if (!passwordValid || !confirmValid) {
            return;
        }
    }
    
    // Validasi password untuk edit jika diisi
    if (isEdit) {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;
        
        if (password || confirm) {
            if (password !== confirm) {
                document.getElementById('passwordErrorContainer').style.display = 'block';
                document.getElementById('passwordError').textContent = 'Password dan konfirmasi password tidak cocok';
                document.getElementById('passwordError').style.color = '#e74c3c';
                return;
            }
            
            if (password && !/^(?=.*[A-Za-z])(?=.*\d).{8,}$/.test(password)) {
                document.getElementById('passwordErrorContainer').style.display = 'block';
                document.getElementById('passwordError').textContent = 'Password harus minimal 8 karakter dan mengandung huruf serta angka';
                document.getElementById('passwordError').style.color = '#e74c3c';
                return;
            }
        }
    }
    
    try {
        showSubmitLoading(true);
        
        const url = isEdit 
            ? `${API_CONFIG.endpoints.users}/${userId}`
            : API_CONFIG.endpoints.users;
        
        const method = isEdit ? 'PUT' : 'POST';
        
        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            id_role: document.getElementById('id_role').value,
            status_aktif: document.getElementById('status_aktif').value,
            nip: document.getElementById('nip').value,
            id_bidang: document.getElementById('bidang').value
        };
        
        // Tambahkan password hanya jika diisi (untuk edit) atau wajib (untuk tambah)
        if (!isEdit || document.getElementById('password').value) {
            formData.password = document.getElementById('password').value;
            formData.password_confirmation = document.getElementById('password_confirmation').value;
        }
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            // Handle validation errors
            if (response.status === 422 && data.errors) {
                let errorMessage = 'Validasi gagal: ';
                Object.values(data.errors).forEach(errors => {
                    if (Array.isArray(errors)) {
                        errorMessage += errors.join(', ') + ' ';
                    }
                });
                throw new Error(errorMessage);
            }
            throw new Error(data.message || 'Request failed');
        }
        
        showNotification(
            isEdit ? 'Data akun berhasil diupdate' : 'Akun berhasil ditambahkan',
            'success'
        );
        
        closeModal();
        fetchUsers();
        
    } catch (error) {
        console.error('Error:', error);
        
        // Show error in password error area
        document.getElementById('passwordErrorContainer').style.display = 'block';
        document.getElementById('passwordError').textContent = error.message;
        document.getElementById('passwordError').style.color = '#e74c3c';
        
    } finally {
        showSubmitLoading(false);
    }
}

function showSubmitLoading(show) {
    const btn = document.getElementById('modalSubmitBtn');
    if (btn) {
        btn.disabled = show;
        btn.innerHTML = show 
            ? '<i class="bx bx-loader-circle bx-spin"></i> Memproses...'
            : '<i class="bx bx-save"></i> Simpan';
    }
}

// Delete functions
function showDeleteModal(id, name) {
    userToDelete = id;
    document.getElementById('deleteUserName').textContent = name;
    openModal('deleteModal');
}

async function confirmDelete() {
    if (!userToDelete) return;
    
    try {
        const deleteBtn = document.getElementById('deleteConfirmBtn');
        if (deleteBtn) {
            deleteBtn.disabled = true;
            deleteBtn.innerHTML = '<i class="bx bx-loader-circle bx-spin"></i> Menghapus...';
        }
        
        const response = await fetch(`${API_CONFIG.endpoints.users}/${userToDelete}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Failed to delete user');
        }
        
        showNotification('Akun berhasil dihapus', 'success');
        closeDeleteModal();
        fetchUsers();
        
    } catch (error) {
        console.error('Error:', error);
        showNotification(error.message || 'Gagal menghapus akun', 'error');
        
        // Reset delete button
        const deleteBtn = document.getElementById('deleteConfirmBtn');
        if (deleteBtn) {
            deleteBtn.disabled = false;
            deleteBtn.innerHTML = '<i class="bx bx-trash"></i> Hapus';
        }
    }
}

// Search and filter
function searchUsers() {
    currentSearch = document.getElementById('searchInput').value.trim();
    currentPage = 1;
    fetchUsers();
}

function filterUsers() {
    currentRoleFilter = document.getElementById('roleFilter').value;
    currentPage = 1;
    fetchUsers();
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
    if (currentPage < totalPages) {
        currentPage++;
        fetchUsers();
    }
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        fetchUsers();
    }
} 

function updatePagination(meta) {
    if (!meta) return;
    
    totalPages = meta.last_page || Math.ceil(meta.total / meta.per_page);
    currentPage = meta.current_page || currentPage;
    
    document.getElementById('currentPage').textContent = currentPage;
    document.getElementById('totalPages').textContent = totalPages;
    document.getElementById('totalRows').textContent = meta.total || 0;
    
    const startRow = ((currentPage - 1) * perPage) + 1;
    const endRow = Math.min(currentPage * perPage, meta.total || 0);
    
    document.getElementById('startRow').textContent = startRow;
    document.getElementById('endRow').textContent = endRow;
    
    // Update button states
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    if (prevBtn) prevBtn.disabled = currentPage <= 1;
    if (nextBtn) nextBtn.disabled = currentPage >= totalPages;
}

// Utility functions
function refreshData() {
    currentSearch = '';
    currentRoleFilter = '';
    
    document.getElementById('searchInput').value = '';
    document.getElementById('roleFilter').value = '';
    
    currentPage = 1;
    fetchUsers();
    showNotification('Data berhasil diperbarui', 'success');
}

function showNotification(message, type = 'info') {
    const container = document.getElementById('notificationContainer');
    if (!container) return;
    
    // Hapus notifikasi lama setelah 3 detik
    const notifications = container.querySelectorAll('.notification');
    notifications.forEach(notification => {
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }
        }, 3000);
    });
    
    // Buat notifikasi baru
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 10px;">
            <i class='bx ${type === 'success' ? 'bx-check-circle' : type === 'error' ? 'bx-error-circle' : 'bx-info-circle'}' 
               style="font-size: 1.5rem; color: ${type === 'success' ? '#2ed573' : type === 'error' ? '#e74c3c' : '#3498db'}"></i>
            <span style="flex-grow: 1;">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" 
                    style="background: none; border: none; cursor: pointer; color: #888;">
                <i class='bx bx-x'></i>
            </button>
        </div>
    `;
    
    container.appendChild(notification);
    
    // Auto remove setelah 5 detik
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }
    }, 5000);
}

// Tambahkan style untuk notification dan modal animation
const customStyle = document.createElement('style');
customStyle.textContent = `
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
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .notification {
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 10px;
        background: white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-left: 4px solid;
        animation: slideIn 0.3s ease-out;
    }
    
    .notification-success {
        border-left-color: #2ed573;
        background: #f0f9f0;
    }
    
    .notification-error {
        border-left-color: #e74c3c;
        background: #fdf2f2;
    }
    
    .notification-info {
        border-left-color: #3498db;
        background: #f0f8ff;
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
    
    .password-error-container {
        margin-top: 10px;
        padding: 10px;
        border-radius: 6px;
        background: #fdf2f2;
        border: 1px solid #f8d7da;
    }
    
    .password-error {
        color: #e74c3c;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .password-error:before {
        content: '⚠';
        font-size: 1rem;
    }
    
    .modal {
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
`;
document.head.appendChild(customStyle);
</script>
@endsection
