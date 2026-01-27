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
                    <th style="width: 70px;">ID</th>
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
                        <small>Minimal 8 karakter dengan huruf dan angka</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password">
                    </div>
                </div>

                <!-- ERROR MESSAGE FULL WIDTH -->
                <small id="passwordError" class="password-error">
                    Password harus minimal 8 karakter dan mengandung huruf serta angka
                </small>

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
                            <!-- Options akan diisi via JavaScript --   
                    </div>

                    <!-- Bidang (hanya untuk Admin Bidang & Mentor) -->
                    <div class="form-group" id="bidangWrapper" style="display: none;">
                        <label for="bidang">Bidang *</label>
                        <select id="bidang" name="bidang">
                            <option value="">Pilih Bidang</option>
                            <option value="Teknologi_Informatika">Teknologi & Informatika</option>
                            <option value="statistika">Statistika</option>
                            <option value="komunikasi_publik">Komunikasi Publik dan Media</option>
                            <option value="sekretariat">Sekretariat</option>
                        </select>
                    </div>

                <div class="form-group">
                    <label for="status_aktif">Status Aktif *</label>
                    <select id="status_aktif" name="status_aktif" required>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>

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
            <h3>Detail Akun</h3>
            <button class="modal-close" onclick="closeDetailModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="user-detail">
                <div class="user-avatar-large">
                    <div class="avatar" id="detailAvatar">AK</div>
                </div>
                <div class="user-info-detail">
                    <h4 id="detailName">-</h4>
                    <p id="detailEmail">-</p>
                    <div class="detail-info">
                        <div class="info-row">
                            <span class="info-label">Role:</span>
                            <span class="info-value" id="detailRole">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status:</span>
                            <span class="info-value" id="detailStatus">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Bergabung:</span>
                            <span class="info-value" id="detailJoinDate">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Terakhir Login:</span>
                            <span class="info-value" id="detailLastLogin">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeDetailModal()">Tutup</button>
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
            <button class="btn btn-danger" onclick="confirmDelete()">
                <i class='bx bx-trash'></i> Hapus
            </button>
        </div>
    </div>
</div>
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
let currentStatusFilter = '';
let userToDelete = null;
let currentDetailUser = null;

// Inisialisasi
// document.addEventListener('DOMContentLoaded', function () {
//     const roleSelect = document.getElementById('id_role');
//     const bidangWrapper = document.getElementById('bidangWrapper');
//     const nipWrapper = document.getElementById('nipWrapper');
//     const nipInput = document.getElementById('nip');

//     if (!roleSelect) return;

//     roleSelect.addEventListener('change', function () {
//         const roleId = this.value;

//         // BIDANG: Admin Bidang & Mentor
//         if (roleId === '2' || roleId === '3') {
//             bidangWrapper.style.display = 'block';
//         } else {
//             bidangWrapper.style.display = 'none';
//             document.getElementById('bidang').value = '';
//         }

//         // NIP: Admin Kepegawaian & Admin Bidang
//         if (roleId === '1' || roleId === '2') {
//             nipWrapper.style.display = 'block';
//         } else {
//             nipWrapper.style.display = 'none';
//             nipInput.value = '';
//         }
//     });
// });

document.addEventListener('DOMContentLoaded', function() {
    fetchRoles()
    fetchUsers();
    
    // Event listeners
    document.getElementById('userForm').addEventListener('submit', handleSubmit);
    document.getElementById('searchInput').addEventListener('input', debounce(searchUsers, 300));
    document.getElementById('roleFilter').addEventListener('change', filterUsers);
    document.getElementById('statusFilter').addEventListener('change', filterUsers);
    
    setupCSRF();
});

Setup CSRF
function setupCSRF() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    if (token) {
        window.csrfToken = token;
    }
}

// Fetch roles
async function fetchRoles() {
    try {
        const response = await fetch(API_CONFIG.endpoints.roles, {
            headers: { 'Accept': 'application/json' }
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
        if (currentStatusFilter) params.push(`status=${currentStatusFilter}`);
        
        if (params.length > 0) {
            url += `&${params.join('&')}`;
        }
        
        const response = await fetch(url, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (!response.ok) throw new Error('Failed to fetch users');
        
        const data = await response.json();
        renderTable(data.data || data);
        updatePagination(data);
        document.getElementById('totalUsers').textContent = data.meta?.total || data.total || 0;
        
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
    
    if (!users || users.length === 0) {
        renderEmptyTable('Tidak ada data akun ditemukan');
        return;
    }
    
    tbody.innerHTML = '';
    
    users.forEach(user => {
        const row = document.createElement('tr');
        const roleClass = getRoleClass(user.id_role || user.role_id);
        const roleName = user.role?.name || user.role?.nama_role || getRoleName(user.id_role || user.role_id);
        
        row.innerHTML = `
            <td><strong>#${user.id || user.id_user}</strong></td>
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
                    <button class="action-btn view" title="Detail" onclick="showDetail(${user.id || user.id_user})">
                        <i class='bx bx-show'></i>
                    </button>
                    <button class="action-btn edit" title="Edit" onclick="editUser(${user.id || user.id_user})">
                        <i class='bx bx-edit'></i>
                    </button>
                    <button class="action-btn delete" title="Hapus" onclick="showDeleteModal(${user.id || user.id_user}, '${user.name}')">
                        <i class='bx bx-trash'></i>
                    </button>
                </div>
            </td>
        `;
        
        tbody.appendChild(row);
    });
}

// Helper functions
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
    return name
        .split(' ')
        .map(word => word.charAt(0).toUpperCase())
        .join('')
        .substring(0, 2);
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
}

function formatTime(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    });
}

function renderEmptyTable(message) {
    const tbody = document.getElementById('pesertaTableBody');
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

// document.addEventListener('DOMContentLoaded', function () {
//     const passwordInput = document.getElementById('password');
//     const confirmInput = document.getElementById('password_confirmation');

//     const passwordError = document.getElementById('passwordError');
//     const confirmError = document.getElementById('passwordConfirmError');

//     if (!passwordInput || !confirmInput) return;

//     function validatePassword() {
//         const password = passwordInput.value;
//         const regex = /^(?=.*[A-Za-z])(?=.*\d).{8,}$/;

//         if (password.length === 0) {
//             passwordError.style.display = 'none';
//             return false;
//         }

//         if (!regex.test(password)) {
//             passwordError.style.display = 'block';
//             return false;
//         } else {
//             passwordError.style.display = 'none';
//             return true;
//         }
//     }

//     function validateConfirmPassword() {
//         if (confirmInput.value.length === 0) {
//             confirmError.style.display = 'none';
//             return false;
//         }

//         if (confirmInput.value !== passwordInput.value) {
//             confirmError.style.display = 'block';
//             return false;
//         } else {
//             confirmError.style.display = 'none';
//             return true;
//         }
//     }

//     passwordInput.addEventListener('input', validatePassword);
//     confirmInput.addEventListener('input', validateConfirmPassword);
// });

// Modal functions
function showAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Akun Baru';
    document.getElementById('userForm').reset();
    document.getElementById('editUserId').value = '';
    document.getElementById('status_aktif').value = '1';
    document.getElementById('password').required = true;
    document.getElementById('password_confirmation').required = true;
    document.getElementById('bidangWrapper').style.display = 'none';
    document.getElementById('bidang').value = '';
    document.getElementById('nipWrapper').style.display = 'none';
    document.getElementById('nip').value = '';

    openModal('userModal');
}

async function editUser(id) {
    try {
        showLoading(true);
        
        const response = await fetch(`${API_CONFIG.endpoints.users}/${id}`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (!response.ok) throw new Error('Failed to fetch user data');
        
        const user = await response.json();
        
        document.getElementById('modalTitle').textContent = 'Edit Akun';
        document.getElementById('editUserId').value = user.id || user.id_user;
        document.getElementById('name').value = user.name;
        document.getElementById('email').value = user.email;
        document.getElementById('status_aktif').value = user.status_aktif || '1';
        document.getElementById('id_role').value = user.role_id || user.id_role;
        // Tampilkan bidang jika role Admin Bidang / Mentor saat edit
        const bidangWrapper = document.getElementById('bidangWrapper');
        const roleId = user.role_id || user.id_role;

        if (roleId == 2 || roleId == 3) {
            bidangWrapper.style.display = 'block';
        } else {
            bidangWrapper.style.display = 'none';
        }
        document.getElementById('status_aktif').value = user.status_aktif || '1';
        
        // Password tidak wajib untuk edit
        document.getElementById('password').required = false;
        document.getElementById('password_confirmation').required = false;
        
        openModal('userModal');
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat data pengguna', 'error');
    } finally {
        showLoading(false);
    }
}

async function showDetail(id) {
    try {
        const response = await fetch(`${API_CONFIG.endpoints.users}/${id}`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (!response.ok) throw new Error('Failed to fetch user data');
        
        const user = await response.json();
        currentDetailUser = user;
        
        // Update detail modal
        document.getElementById('detailAvatar').textContent = getInitials(user.name);
        document.getElementById('detailName').textContent = user.name;
        document.getElementById('detailEmail').textContent = user.email;
        document.getElementById('detailRole').textContent = user.role?.name || user.role?.nama_role || getRoleName(user.role_id || user.id_role);
        document.getElementById('detailStatus').innerHTML = user.status_aktif == 1 
            ? '<span class="status-active">Aktif</span>'
            : '<span class="status-inactive">Nonaktif</span>';
        document.getElementById('detailJoinDate').textContent = formatDate(user.created_at);
        document.getElementById('detailLastLogin').textContent = user.last_login_at 
            ? `${formatDate(user.last_login_at)} ${formatTime(user.last_login_at)}`
            : 'Belum pernah login';
        
        // Update edit button
        document.getElementById('detailEditBtn').onclick = function() {
            closeDetailModal();
            editUser(user.id || user.id_user);
        };
        
        openModal('detailModal');
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat detail pengguna', 'error');
    }
}

function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
}

function closeModal() {
    document.getElementById('userModal').style.display = 'none';
}

function closeDetailModal() {
    document.getElementById('detailModal').style.display = 'none';
}

// Tampilkan bidang jika role Admin Bidang atau Mentor
// document.getElementById('id_role').addEventListener('change', function () {
//     const bidangWrapper = document.getElementById('bidangWrapper');
//     const roleId = this.value;

//     if (roleId === '2' || roleId === '3') {
//         bidangWrapper.style.display = 'block';
//     } else {
//         bidangWrapper.style.display = 'none';
//         document.getElementById('bidang').value = '';
//     }
// });
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('id_role');
    const bidangWrapper = document.getElementById('bidangWrapper');
    const bidangSelect = document.getElementById('bidang');

    if (!roleSelect) return;

    roleSelect.addEventListener('change', function () {
        const roleId = this.value;

        if (roleId === '2' || roleId === '3') {
            bidangWrapper.style.display = 'block';
        } else {
            bidangWrapper.style.display = 'none';
            bidangSelect.value = '';
        }
    });
});


// Form submission
async function handleSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const userId = document.getElementById('editUserId').value;
    const isEdit = !!userId;
    
    try {
        showSubmitLoading(true);
        
        const url = isEdit 
            ? `${API_CONFIG.endpoints.users}/${userId}`
            : API_CONFIG.endpoints.users;
        
        const method = isEdit ? 'PUT' : 'POST';
        
        const jsonData = {};
        formData.forEach((value, key) => {
            jsonData[key] = value;
        });
        
        // Add CSRF token
        // jsonData._token = window.csrfToken;
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify(jsonData)
        });

        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(jsonData)
        });
        
        const data = await response.json();
        
        if (!response.ok) {
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
        showNotification(error.message || 'Gagal menyimpan data', 'error');
    } finally {
        showSubmitLoading(false);
    }
}

if (jsonData.password && jsonData.password !== jsonData.password_confirmation) {
    showNotification('Password dan konfirmasi tidak sama', 'error');
    showSubmitLoading(false);
    return;
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

function closeDeleteModal() {
    userToDelete = null;
    document.getElementById('deleteModal').style.display = 'none';
}

async function confirmDelete() {
    if (!userToDelete) return;
    
    try {
        const deleteBtn = document.querySelector('#deleteModal .btn-danger');
        deleteBtn.disabled = true;
        deleteBtn.innerHTML = '<i class="bx bx-loader-circle bx-spin"></i> Menghapus...';
        
        const response = await fetch(`${API_CONFIG.endpoints.users}/${userToDelete}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to delete user');
        }
        
        showNotification('Akun berhasil dihapus', 'success');
        closeDeleteModal();
        fetchUsers();
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal menghapus akun', 'error');
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
    currentStatusFilter = document.getElementById('statusFilter').value;
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

function updatePagination(data) {
    const meta = data.meta || {
        current_page: currentPage,
        per_page: perPage,
        total: data.total || data.length || 0
    };
    
    totalPages = Math.ceil(meta.total / meta.per_page);
    currentPage = meta.current_page;
    
    document.getElementById('currentPage').textContent = currentPage;
    document.getElementById('totalPages').textContent = totalPages;
    document.getElementById('totalRows').textContent = meta.total;
    document.getElementById('startRow').textContent = ((currentPage - 1) * perPage) + 1;
    document.getElementById('endRow').textContent = Math.min(currentPage * perPage, meta.total);
    
    // Update button states
    document.getElementById('prevBtn').disabled = currentPage <= 1;
    document.getElementById('nextBtn').disabled = currentPage >= totalPages;
}

// Utility functions
function refreshData() {
    currentSearch = '';
    currentRoleFilter = '';
    currentStatusFilter = '';
    
    document.getElementById('searchInput').value = '';
    document.getElementById('roleFilter').value = '';
    document.getElementById('statusFilter').value = '';
    
    currentPage = 1;
    fetchUsers();
    showNotification('Data berhasil diperbarui', 'success');
}


function showNotification(message, type = 'info') {
    // Existing notification code...
    // You can keep your existing showNotification function
}

// Add additional styles for user detail
const detailStyle = document.createElement('style');
detailStyle.textContent = `
    .user-detail {
        text-align: center;
        padding: 20px 0;
    }
    
    .user-avatar-large {
        margin-bottom: 20px;
    }
    
    .user-avatar-large .avatar {
        width: 80px;
        height: 80px;
        font-size: 1.5rem;
        margin: 0 auto;
    }
    
    .user-info-detail h4 {
        font-size: 1.4rem;
        margin-bottom: 5px;
        color: var(--primary);
    }
    
    .user-info-detail p {
        color: #666;
        margin-bottom: 20px;
    }
    
    .detail-info {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #555;
    }
    
    .info-value {
        color: #333;
    }
    
    .warning-icon {
        text-align: center;
        margin-bottom: 10px;
    }
    
    .table-info {
        font-size: 0.9rem;
        color: #666;
        padding: 8px 12px;
        background: #f8f9fa;
        border-radius: 6px;
        border: 1px solid #dee2e6;
    }
    
    .page-numbers {
        font-weight: 600;
        color: var(--primary);
        padding: 0 15px;
    }
`;
//event listeners
document.addEventListener('DOMContentLoaded', function () {
    setupCSRF();
    fetchUsers();

    const form = document.getElementById('userForm');
    if (form) {
        form.addEventListener('submit', handleSubmit);
    }
});

document.head.appendChild(detailStyle);
</script>
@endsection