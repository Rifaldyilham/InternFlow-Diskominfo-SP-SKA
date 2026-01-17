@extends('layouts.admin')

@section('title', 'Manajemen Akun')
@section('subtitle', 'Kelola Semua Akun Terdaftar')

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
            <input type="text" id="searchInput" placeholder="Cari berdasarkan nama...">
        </div>
    </div>
</div>

<!-- Tabel Akun -->
<div class="table-container">
    <div class="table-header">
        <h3>Daftar Akun</h3>
        <div class="table-actions">
            <button class="btn btn-secondary" onclick="refreshData()">
                <i class='bx bx-refresh'></i> Refresh
            </button>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Tanggal Daftar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="pesertaTableBody">
            <!-- Data akan dimuat via JavaScript -->
        </tbody>
    </table>
    
    <div class="table-footer">
        <div class="pagination-info">
            Menampilkan <span id="startRow">1</span>-<span id="endRow">10</span> dari <span id="totalRows">0</span> akun
        </div>
        <div class="pagination-controls">
            <button class="pagination-btn" onclick="prevPage()" disabled>
                <i class='bx bx-chevron-left'></i>
            </button>
            <span class="page-info">Halaman <span id="currentPage">1</span></span>
            <button class="pagination-btn" onclick="nextPage()" disabled>
                <i class='bx bx-chevron-right'></i>
            </button>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Akun -->
<div id="userModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Tambah Peserta Baru</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="userForm">
            <div class="modal-body">
                <input type="hidden" id="editUserId">
                
                <div class="form-group">
                    <label for="name">Nama Lengkap *</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password" required>
                        <small>Minimal 8 karakter</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="id_role">Role *</label>
                    <select id="id_role" name="id_role" required>
                        <option value="">Pilih Role</option>
                        <!-- Options akan diisi via JavaScript -->
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn btn-primary" id="modalSubmitBtn">Simpan</button>
            </div>
        </form>
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
            <p>Apakah Anda yakin ingin menghapus akun <strong id="deleteUserName"></strong>?</p>
            <p class="text-warning">Data yang dihapus tidak dapat dikembalikan.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
            <button class="btn btn-danger" onclick="confirmDelete()">Hapus</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentPage = 1;
let totalPages = 1;
let perPage = 10;
let users = [];
let roles = [];
let userToDelete = null;

// Inisialisasi data saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    fetchRoles();
    fetchUsers();
    
    // Event listener untuk form
    document.getElementById('userForm').addEventListener('submit', handleSubmit);
    document.getElementById('searchInput').addEventListener('input', debounce(searchUsers, 300));
});

// Fetch daftar role dari database
async function fetchRoles() {
    try {
        // In production, replace with actual API call
        // const response = await fetch('/api/roles');
        // roles = await response.json();
        
        // Mock data based on your database
        roles = [
            { id_roles: 1, nama_role: 'Admin Kepegawaian' },
            { id_roles: 2, nama_role: 'Admin Bidang' },
            { id_roles: 3, nama_role: 'Mentor' },
            { id_roles: 4, nama_role: 'Peserta Magang' }
        ];
        
        populateRoleSelect();
    } catch (error) {
        console.error('Error fetching roles:', error);
        showNotification('Gagal memuat data role', 'error');
    }
}

// Populate role select options
function populateRoleSelect() {
    const select = document.getElementById('id_role');
    select.innerHTML = '<option value="">Pilih Role</option>';
    
    roles.forEach(role => {
        const option = document.createElement('option');
        option.value = role.id_roles;
        option.textContent = role.nama_role;
        select.appendChild(option);
    });
}

// Fetch daftar akun
async function fetchUsers() {
    try {
        // In production, replace with actual API call
        // const response = await fetch('/api/users?page=' + currentPage);
        // const data = await response.json();
        
        // Mock data - in real app this comes from backend
        const mockData = {
            users: [
                {
                    id_user: 1,
                    name: 'Rifaldy',
                    email: 'rifaldy@gmail.com',
                    id_role: 4,
                    status_aktif: 1,
                    created_at: '2026-01-14T04:57:26.000Z',
                    role: { nama_role: 'Peserta Magang' }
                },
                {
                    id_user: 2,
                    name: 'Admin Kepegawaian',
                    email: 'admin@diskominfo.surakarta.go.id',
                    id_role: 1,
                    status_aktif: 1,
                    created_at: '2026-01-15T10:30:00.000Z',
                    role: { nama_role: 'Admin Kepegawaian' }
                },
                {
                    id_user: 3,
                    name: 'Budi Santoso',
                    email: 'budi@uns.ac.id',
                    id_role: 4,
                    status_aktif: 1,
                    created_at: '2026-01-16T08:15:00.000Z',
                    role: { nama_role: 'Peserta Magang' }
                },
                {
                    id_user: 4,
                    name: 'Siti Rahma',
                    email: 'siti@ui.ac.id',
                    id_role: 4,
                    status_aktif: 1,
                    created_at: '2026-01-16T09:30:00.000Z',
                    role: { nama_role: 'Peserta Magang' }
                },
                {
                    id_user: 5,
                    name: 'Admin Bidang Informatika',
                    email: 'admin-informatika@diskominfo.surakarta.go.id',
                    id_role: 2,
                    status_aktif: 1,
                    created_at: '2026-01-15T11:00:00.000Z',
                    role: { nama_role: 'Admin Bidang' }
                },
                {
                    id_user: 6,
                    name: 'Mentor Informatika 1',
                    email: 'mentor1@diskominfo.surakarta.go.id',
                    id_role: 3,
                    status_aktif: 1,
                    created_at: '2026-01-15T12:00:00.000Z',
                    role: { nama_role: 'Mentor' }
                }
            ],
            total: 6,
            current_page: 1,
            per_page: 10
        };
        
        users = mockData.users;
        totalPages = Math.ceil(mockData.total / perPage);
        
        renderTable();
        updatePagination();
    } catch (error) {
        console.error('Error fetching users:', error);
        showNotification('Gagal memuat data akun', 'error');
    }
}

// Render table data
function renderTable() {
    const tbody = document.getElementById('pesertaTableBody');
    tbody.innerHTML = '';
    
    if (users.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: #888;">
                    <i class='bx bx-user-x' style="font-size: 3rem; margin-bottom: 10px; display: block;"></i>
                    Tidak ada data peserta
                </td>
            </tr>
        `;
        return;
    }
    
    users.forEach(user => {
        const role = roles.find(r => r.id_roles == user.id_role) || { nama_role: 'Unknown' };
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>${user.id_user}</td>
            <td>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div class="avatar" style="width: 35px; height: 35px; font-size: 0.9rem;">
                        ${getInitials(user.name)}
                    </div>
                    <div>
                        <div style="font-weight: 600;">${user.name}</div>
                    </div>
                </div>
            </td>
            <td>${user.email}</td>
            <td><span class="role-badge-small">${role.nama_role}</span></td>
            <td>${formatDate(user.created_at)}</td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn edit" title="Edit" onclick="editUser(${user.id_user})">
                        <i class='bx bx-edit'></i>
                    </button>
                    <button class="action-btn delete" title="Hapus" onclick="showDeleteModal(${user.id_user}, '${user.name}')">
                        <i class='bx bx-trash'></i>
                    </button>
                </div>
            </td>
        `;
        
        tbody.appendChild(row);
    });
}

// Helper functions
function getInitials(name) {
    return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
}

// Modal functions
function showAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Akun Baru';
    document.getElementById('userForm').reset();
    document.getElementById('editUserId').value = '';
    document.getElementById('password').required = true;
    document.getElementById('password_confirmation').required = true;
    openModal();
}

function editUser(id) {
    const user = users.find(u => u.id_user == id);
    if (!user) return;
    
    document.getElementById('modalTitle').textContent = 'Edit Akun';
    document.getElementById('editUserId').value = user.id_user;
    document.getElementById('name').value = user.name;
    document.getElementById('email').value = user.email;
    document.getElementById('id_role').value = user.id_role;
    
    // Password tidak wajib untuk edit
    document.getElementById('password').required = false;
    document.getElementById('password_confirmation').required = false;
    
    openModal();
}

function openModal() {
    document.getElementById('userModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('userModal').style.display = 'none';
}

// Form submission
function handleSubmit(e) {
    e.preventDefault();
    
    const formData = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        id_role: document.getElementById('id_role').value,
    };
    
    const userId = document.getElementById('editUserId').value;
    const isEdit = !!userId;
    
    // Add password only for new user or if changed
    if (!isEdit || document.getElementById('password').value) {
        formData.password = document.getElementById('password').value;
        formData.password_confirmation = document.getElementById('password_confirmation').value;
    }
    
    if (isEdit) {
        formData.id_user = userId;
        updateUser(formData);
    } else {
        addUser(formData);
    }
}

// API calls (simulated)
async function addUser(data) {
    try {
        // Simulate API call
        showNotification('Menambahkan akun...', 'info');
        
        // In production:
        // const response = await fetch('/api/users', {
        //     method: 'POST',
        //     headers: { 'Content-Type': 'application/json' },
        //     body: JSON.stringify(data)
        // });
        
        // Simulate success
        setTimeout(() => {
            showNotification('Akun berhasil ditambahkan', 'success');
            closeModal();
            fetchUsers();
        }, 1000);
        
    } catch (error) {
        showNotification('Gagal menambahkan akun', 'error');
    }
}

async function updateUser(data) {
    try {
        showNotification('Mengupdate data akun...', 'info');
        
        // In production:
        // const response = await fetch('/api/users/' + data.id_user, {
        //     method: 'PUT',
        //     headers: { 'Content-Type': 'application/json' },
        //     body: JSON.stringify(data)
        // });
        
        setTimeout(() => {
            showNotification('Data akun berhasil diupdate', 'success');
            closeModal();
            fetchUsers();
        }, 1000);
        
    } catch (error) {
        showNotification('Gagal mengupdate akun', 'error');
    }
}

// Delete functions
function showDeleteModal(id, name) {
    userToDelete = id;
    document.getElementById('deleteUserName').textContent = name;
    document.getElementById('deleteModal').style.display = 'flex';
}

function closeDeleteModal() {
    userToDelete = null;
    document.getElementById('deleteModal').style.display = 'none';
}

async function confirmDelete() {
    if (!userToDelete) return;
    
    try {
        showNotification('Menghapus akun...', 'info');
        
        // In production:
        // await fetch('/api/users/' + userToDelete, { method: 'DELETE' });
        
        setTimeout(() => {
            showNotification('Akun berhasil dihapus', 'success');
            closeDeleteModal();
            fetchUsers();
        }, 1000);
        
    } catch (error) {
        showNotification('Gagal menghapus akun', 'error');
    }
}

// Search and filter
function searchUsers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    
    const filteredUsers = users.filter(user => 
        user.name.toLowerCase().includes(searchTerm)
    );
    
    const tbody = document.getElementById('pesertaTableBody');
    tbody.innerHTML = '';
    
    if (filteredUsers.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: #888;">
                    <i class='bx bx-user-x' style="font-size: 3rem; margin-bottom: 10px; display: block;"></i>
                    Tidak ada akun yang cocok dengan pencarian
                </td>
            </tr>
        `;
        return;
    }
    
    filteredUsers.forEach(user => {
        const role = roles.find(r => r.id_roles == user.id_role) || { nama_role: 'Unknown' };
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>${user.id_user}</td>
            <td>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div class="avatar" style="width: 35px; height: 35px; font-size: 0.9rem;">
                        ${getInitials(user.name)}
                    </div>
                    <div>
                        <div style="font-weight: 600;">${user.name}</div>
                    </div>
                </div>
            </td>
            <td>${user.email}</td>
            <td><span class="role-badge-small">${role.nama_role}</span></td>
            <td>${formatDate(user.created_at)}</td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn edit" title="Edit" onclick="editUser(${user.id_user})">
                        <i class='bx bx-edit'></i>
                    </button>
                    <button class="action-btn delete" title="Hapus" onclick="showDeleteModal(${user.id_user}, '${user.name}')">
                        <i class='bx bx-trash'></i>
                    </button>
                </div>
            </td>
        `;
        
        tbody.appendChild(row);
    });
    
    updatePagination();
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

function updatePagination() {
    document.getElementById('currentPage').textContent = currentPage;
    document.getElementById('totalRows').textContent = users.length;
    document.getElementById('startRow').textContent = ((currentPage - 1) * perPage) + 1;
    document.getElementById('endRow').textContent = Math.min(currentPage * perPage, users.length);
    
    // Update button states
    const prevBtn = document.querySelector('.pagination-controls .pagination-btn:first-child');
    const nextBtn = document.querySelector('.pagination-controls .pagination-btn:last-child');
    
    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPages;
}

// Utility functions
function refreshData() {
    showNotification('Memperbarui data...', 'info');
    fetchUsers();
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification-toast notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class='bx ${type === 'success' ? 'bx-check-circle' : type === 'error' ? 'bx-error-circle' : 'bx-info-circle'}'></i>
            <span>${message}</span>
        </div>
        <button onclick="this.parentElement.remove()">&times;</button>
    `;
    
    // Add styles
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
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Add CSS for modal
const style = document.createElement('style');
style.textContent = `
    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .header-actions {
        display: flex;
        gap: 15px;
    }
    
    .search-filter {
        display: flex;
        gap: 15px;
        align-items: center;
    }
    
    .search-box {
        position: relative;
    }
    
    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
    }
    
    .search-box input {
        padding: 10px 15px 10px 45px;
        border: 1px solid #ddd;
        border-radius: 10px;
        width: 250px;
        font-size: 0.9rem;
    }
    
    .filter-select {
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background: white;
        font-size: 0.9rem;
    }
    
    .table-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 25px;
        border-top: 1px solid #eee;
    }
    
    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .pagination-btn {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        border: 1px solid #ddd;
        background: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        transition: all 0.3s;
    }
    
    .pagination-btn:hover:not(:disabled) {
        background: #f5f5f5;
    }
    
    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .page-info {
        font-size: 0.9rem;
        color: #666;
    }
    
    .pagination-info {
        font-size: 0.9rem;
        color: #666;
    }
    
    .role-badge-small {
        padding: 4px 10px;
        background: rgba(33, 52, 72, 0.1);
        color: var(--primary);
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }
    
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
    }
    
    .modal-content {
        background: white;
        border-radius: 16px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
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
        padding: 0;
        line-height: 1;
    }
    
    .modal-body {
        padding: 25px;
    }
    
    .modal-footer {
        padding: 20px 25px;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
    }
    
    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: border-color 0.3s;
    }
    
    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--primary);
    }
    
    .form-group small {
        display: block;
        margin-top: 5px;
        color: #888;
        font-size: 0.8rem;
    }
    
    .btn-danger {
        background: #e74c3c;
        color: white;
    }
    
    .btn-danger:hover {
        background: #c0392b;
    }
    
    .text-warning {
        color: #e74c3c;
        font-size: 0.9rem;
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
    
    @media (max-width: 768px) {
        .content-header {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-filter {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-box input {
            width: 100%;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .modal-content {
            width: 95%;
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection