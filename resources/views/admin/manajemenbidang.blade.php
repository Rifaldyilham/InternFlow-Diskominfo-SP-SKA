@extends('layouts.admin')

@section('title', 'Manajemen Bidang')
@section('subtitle', 'Kelola Bidang Magang')

@section('content')
<div class="content-header">
    <div class="header-actions">
        <button class="btn btn-primary" onclick="showAddBidangModal()">
            <i class='bx bx-plus-circle'></i> Tambah Bidang
        </button>
    </div>
</div>

<!-- Bidang Table -->
<div class="table-container">
    <div class="table-header">
        <h3>Daftar Bidang</h3>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Nama Bidang</th>
                <th>Kuota</th>
                <th>Admin Bidang</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="bidangTableBody">
            <!-- Data akan dimuat via JavaScript -->
        </tbody>
    </table>
</div>

<!-- Modal Tambah/Edit Bidang -->
<div id="bidangModal" class="modal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header">
            <h3 id="modalBidangTitle">Tambah Bidang Baru</h3>
            <button class="modal-close" onclick="closeBidangModal()">&times;</button>
        </div>
        <form id="bidangForm">
            <div class="modal-body">
                <input type="hidden" id="editBidangId">
                
                <div class="form-section">
                    <h4><i class='bx bx-info-circle'></i> Informasi Bidang</h4>
                    <div class="form-group">
                        <label for="nama_bidang">Nama Bidang *</label>
                        <select id="nama_bidang" name="nama_bidang" required>
                            <option value="">Pilih Bidang</option>
                            <option value="Statistik">Statistik</option>
                            <option value="Informatika">Informatika</option>
                            <option value="Sekretariat">Sekretariat</option>
                            <option value="E-Goverment">E-Goverment</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="kuota_maksimal">Kuota Maksimal *</label>
                        <input type="number" id="kuota_maksimal" name="kuota_maksimal" 
                               required min="1" max="50" value="10">
                        <small>Jumlah maksimal peserta yang dapat ditampung</small>
                    </div>
                </div>
                
                <div class="form-section">
                    <h4><i class='bx bx-user-plus'></i> Akun Admin Bidang</h4>
                    <p class="section-description">Buat akun untuk admin bidang yang akan mengelola bidang ini</p>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="admin_nama">Nama Admin Bidang *</label>
                            <input type="text" id="admin_nama" name="admin_nama" required 
                                   placeholder="Nama lengkap admin bidang">
                        </div>
                        
                        <div class="form-group">
                            <label for="admin_email">Email Admin *</label>
                            <input type="email" id="admin_email" name="admin_email" required 
                                   placeholder="email@diskominfo.go.id">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="admin_password">Password *</label>
                            <div class="password-input">
                                <input type="password" id="admin_password" name="admin_password" required 
                                       placeholder="Minimal 8 karakter">
                                <button type="button" class="password-toggle" onclick="togglePassword('admin_password')">
                                    <i class='bx bx-show'></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="admin_password_confirmation">Konfirmasi Password *</label>
                            <div class="password-input">
                                <input type="password" id="admin_password_confirmation" name="admin_password_confirmation" 
                                       required placeholder="Ulangi password">
                                <button type="button" class="password-toggle" onclick="togglePassword('admin_password_confirmation')">
                                    <i class='bx bx-show'></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeBidangModal()">Batal</button>
                <button type="submit" class="btn btn-primary" id="modalBidangSubmitBtn">
                    <span id="submitBtnText">Simpan Bidang & Akun Admin</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Bidang -->
<div id="detailModal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3 id="detailBidangTitle">Detail Bidang</h3>
            <button class="modal-close" onclick="closeDetailModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="detail-section">
                <h4><i class='bx bx-info-circle'></i> Informasi Bidang</h4>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Nama Bidang:</label>
                        <span id="detailNama" class="bidang-name">-</span>
                    </div>
                    <div class="detail-item">
                        <label>Kuota:</label>
                        <div class="kuota-display">
                            <div class="kuota-info">
                                <span id="detailPesertaAktif">0</span> / <span id="detailKuotaMaksimal">0</span> peserta
                            </div>
                            <div class="kuota-progress">
                                <div class="progress-bar" id="detailKuotaProgress"></div>
                            </div>
                        </div>
                    </div>
                    <div class="detail-item">
                        <label>Kuota Tersedia:</label>
                        <span id="detailKuotaTersedia" class="kuota-available">0</span>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h4><i class='bx bx-user-circle'></i> Admin Bidang</h4>
                <div id="detailAdminInfo">
                    <!-- Admin info will be loaded here -->
                </div>
            </div>
            
            <div class="detail-section">
                <div class="section-header">
                    <h4><i class='bx bx-user'></i> Daftar Peserta</h4>
                    <button class="btn btn-small" onclick="managePeserta()">
                        <i class='bx bx-user-plus'></i> Kelola Peserta
                    </button>
                </div>
                <div id="detailPesertaList">
                    <!-- Peserta list will be loaded here -->
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeDetailModal()">Tutup</button>
        </div>
    </div>
</div>

<!-- Modal Hapus Bidang -->
<div id="deleteBidangModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Konfirmasi Hapus Bidang</h3>
            <button class="modal-close" onclick="closeDeleteBidangModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus bidang <strong id="deleteBidangName"></strong>?</p>
            
            <div class="warning-box">
                <i class='bx bx-error'></i>
                <div>
                    <strong>Peringatan!</strong>
                    <p>Data yang akan terpengaruh:</p>
                    <ul>
                        <li>Akun admin bidang akan dinonaktifkan</li>
                        <li>Peserta di bidang ini perlu dipindahkan</li>
                        <li>Semua data terkait bidang akan dihapus</li>
                    </ul>
                </div>
            </div>
            
            <div class="form-group">
                <label for="delete_confirmation">Ketik "HAPUS" untuk konfirmasi</label>
                <input type="text" id="delete_confirmation" name="delete_confirmation" 
                       placeholder="HAPUS" oninput="validateDeleteConfirmation()">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeDeleteBidangModal()">Batal</button>
            <button class="btn btn-danger" id="confirmDeleteBtn" onclick="confirmDeleteBidang()" disabled>
                Hapus Bidang
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let bidangList = [];
let currentBidangDetail = null;
let bidangToDelete = null;

// Inisialisasi data saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    fetchBidangData();
    document.getElementById('bidangForm').addEventListener('submit', handleBidangSubmit);
});

// Fetch daftar bidang
async function fetchBidangData() {
    try {
        // Mock data
        const mockData = {
            bidang: [
                {
                    id: 1,
                    nama: 'Statistik',
                    kuota_maksimal: 10,
                    peserta_aktif: 8,
                    admin_bidang: {
                        id: 101,
                        name: 'Dr. Budi Santoso',
                        email: 'budi.statistik@diskominfo.go.id',
                        last_login: '2026-01-16T14:30:00.000Z'
                    }
                },
                {
                    id: 2,
                    nama: 'Informatika',
                    kuota_maksimal: 15,
                    peserta_aktif: 12,
                    admin_bidang: {
                        id: 102,
                        name: 'Ir. Siti Aminah',
                        email: 'siti.informatika@diskominfo.go.id',
                        last_login: '2026-01-17T09:15:00.000Z'
                    }
                },
                {
                    id: 3,
                    nama: 'Sekretariat',
                    kuota_maksimal: 8,
                    peserta_aktif: 6,
                    admin_bidang: {
                        id: 103,
                        name: 'Muhammad Rizki',
                        email: 'rizki.sekretariat@diskominfo.go.id',
                        last_login: '2026-01-15T16:45:00.000Z'
                    }
                },
                {
                    id: 4,
                    nama: 'E-Goverment',
                    kuota_maksimal: 12,
                    peserta_aktif: 5,
                    admin_bidang: {
                        id: 104,
                        name: 'Dewi Lestari',
                        email: 'dewi.egov@diskominfo.go.id',
                        last_login: '2026-01-14T11:20:00.000Z'
                    }
                }
            ]
        };
        
        bidangList = mockData.bidang;
        renderBidangTable();
    } catch (error) {
        console.error('Error fetching bidang data:', error);
        showNotification('Gagal memuat data bidang', 'error');
    }
}

// Render table data
function renderBidangTable() {
    const tbody = document.getElementById('bidangTableBody');
    tbody.innerHTML = '';
    
    if (bidangList.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" style="text-align: center; padding: 40px; color: #888;">
                    <i class='bx bx-briefcase-alt' style="font-size: 3rem; margin-bottom: 10px; display: block;"></i>
                    Belum ada data bidang
                </td>
            </tr>
        `;
        return;
    }
    
    bidangList.forEach(bidang => {
        const kuotaPercent = Math.round((bidang.peserta_aktif / bidang.kuota_maksimal) * 100);
        const kuotaTersedia = bidang.kuota_maksimal - bidang.peserta_aktif;
        
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>
                <div style="font-weight: 600; color: var(--primary);">${bidang.nama}</div>
            </td>
            <td>
                <div class="kuota-container">
                    <div class="kuota-info">
                        <span class="kuota-current">${bidang.peserta_aktif}</span>
                        <span class="kuota-separator">/</span>
                        <span class="kuota-max">${bidang.kuota_maksimal}</span>
                        <span class="kuota-label">peserta</span>
                    </div>
                    <div class="kuota-progress-small">
                        <div class="progress-bar-small" style="width: ${kuotaPercent}%; 
                            background-color: ${kuotaPercent >= 100 ? '#ff4757' : 
                                            kuotaPercent >= 80 ? '#ffa502' : 
                                            kuotaPercent >= 60 ? '#2ed573' : '#3498db'};">
                        </div>
                    </div>
                    <div class="kuota-tersedia">
                        <i class='bx bx-user-plus'></i> ${kuotaTersedia} tersedia
                    </div>
                </div>
            </td>
            <td>
                ${bidang.admin_bidang ? `
                    <div class="admin-info">
                        <div class="admin-name">${bidang.admin_bidang.name}</div>
                        <div class="admin-email">${bidang.admin_bidang.email}</div>
                    </div>
                ` : `
                    <span class="no-admin">Belum ada admin</span>
                `}
            </td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn view" title="Lihat Detail" onclick="showDetailModal(${bidang.id})">
                        <i class='bx bx-show'></i>
                    </button>
                    <button class="action-btn edit" title="Edit" onclick="editBidang(${bidang.id})">
                        <i class='bx bx-edit'></i>
                    </button>
                    <button class="action-btn delete" title="Hapus" onclick="showDeleteBidangModal(${bidang.id}, '${bidang.nama}')">
                        <i class='bx bx-trash'></i>
                    </button>
                </div>
            </td>
        `;
        
        tbody.appendChild(row);
    });
}

// Modal functions
function showAddBidangModal() {
    document.getElementById('modalBidangTitle').textContent = 'Tambah Bidang Baru';
    document.getElementById('bidangForm').reset();
    document.getElementById('editBidangId').value = '';
    document.getElementById('kuota_maksimal').value = '10';
    document.getElementById('submitBtnText').textContent = 'Simpan Bidang & Akun Admin';
    openModal('bidangModal');
}

function editBidang(id) {
    const bidang = bidangList.find(b => b.id == id);
    if (!bidang) return;
    
    document.getElementById('modalBidangTitle').textContent = 'Edit Bidang';
    document.getElementById('editBidangId').value = bidang.id;
    document.getElementById('nama_bidang').value = bidang.nama;
    document.getElementById('kuota_maksimal').value = bidang.kuota_maksimal;
    
    if (bidang.admin_bidang) {
        document.getElementById('admin_nama').value = bidang.admin_bidang.name;
        document.getElementById('admin_email').value = bidang.admin_bidang.email;
        document.getElementById('admin_password').required = false;
        document.getElementById('admin_password_confirmation').required = false;
        document.getElementById('submitBtnText').textContent = 'Update Bidang';
    } else {
        document.getElementById('submitBtnText').textContent = 'Update Bidang & Tambah Admin';
    }
    
    openModal('bidangModal');
}

function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
}

function closeBidangModal() {
    document.getElementById('bidangModal').style.display = 'none';
}

// Form submission
function handleBidangSubmit(e) {
    e.preventDefault();
    
    const formData = {
        nama: document.getElementById('nama_bidang').value,
        kuota_maksimal: parseInt(document.getElementById('kuota_maksimal').value),
        admin_nama: document.getElementById('admin_nama').value,
        admin_email: document.getElementById('admin_email').value
    };
    
    const bidangId = document.getElementById('editBidangId').value;
    const isEdit = !!bidangId;
    
    // Add password only for new admin or if changed
    if (!isEdit || document.getElementById('admin_password').value) {
        const password = document.getElementById('admin_password').value;
        const confirmPassword = document.getElementById('admin_password_confirmation').value;
        
        if (password !== confirmPassword) {
            showNotification('Password tidak cocok', 'error');
            return;
        }
        
        if (password.length < 8) {
            showNotification('Password minimal 8 karakter', 'error');
            return;
        }
        
        formData.admin_password = password;
    }
    
    if (isEdit) {
        formData.id = bidangId;
        updateBidang(formData);
    } else {
        addBidang(formData);
    }
}

// API calls (simulated)
async function addBidang(data) {
    try {
        showNotification('Membuat bidang dan akun admin...', 'info');
        
        // Simulate success
        setTimeout(() => {
            showNotification('Bidang dan akun admin berhasil dibuat', 'success');
            closeBidangModal();
            fetchBidangData();
        }, 1000);
        
    } catch (error) {
        showNotification('Gagal membuat bidang', 'error');
    }
}

async function updateBidang(data) {
    try {
        showNotification('Mengupdate data bidang...', 'info');
        
        setTimeout(() => {
            showNotification('Data bidang berhasil diupdate', 'success');
            closeBidangModal();
            fetchBidangData();
        }, 1000);
        
    } catch (error) {
        showNotification('Gagal mengupdate bidang', 'error');
    }
}

// Detail Modal
function showDetailModal(id) {
    const bidang = bidangList.find(b => b.id == id);
    if (!bidang) return;
    
    currentBidangDetail = bidang;
    
    document.getElementById('detailBidangTitle').textContent = `Detail Bidang - ${bidang.nama}`;
    document.getElementById('detailNama').textContent = bidang.nama;
    document.getElementById('detailPesertaAktif').textContent = bidang.peserta_aktif;
    document.getElementById('detailKuotaMaksimal').textContent = bidang.kuota_maksimal;
    
    const kuotaTersedia = bidang.kuota_maksimal - bidang.peserta_aktif;
    const kuotaPercent = Math.round((bidang.peserta_aktif / bidang.kuota_maksimal) * 100);
    
    document.getElementById('detailKuotaTersedia').textContent = kuotaTersedia;
    document.getElementById('detailKuotaProgress').style.width = `${kuotaPercent}%`;
    document.getElementById('detailKuotaProgress').style.backgroundColor = 
        kuotaPercent >= 100 ? '#ff4757' : 
        kuotaPercent >= 80 ? '#ffa502' : 
        kuotaPercent >= 60 ? '#2ed573' : '#3498db';
    
    // Load admin info
    loadDetailAdminInfo(bidang);
    
    // Load peserta data
    loadDetailPeserta(bidang.id);
    
    openModal('detailModal');
}

function closeDetailModal() {
    document.getElementById('detailModal').style.display = 'none';
    currentBidangDetail = null;
}

function managePeserta() {
    if (currentBidangDetail) {
        showNotification(`Membuka manajemen peserta untuk ${currentBidangDetail.nama}`, 'info');
        // Redirect to peserta management with filter
        window.location.href = `/admin/manajemen-peserta?bidang=${currentBidangDetail.id}`;
    }
}

// Load detail data
function loadDetailAdminInfo(bidang) {
    const container = document.getElementById('detailAdminInfo');
    
    if (bidang.admin_bidang) {
        const lastLogin = new Date(bidang.admin_bidang.last_login).toLocaleString('id-ID');
        
        container.innerHTML = `
            <div class="admin-card">
                <div class="admin-avatar">${getInitials(bidang.admin_bidang.name)}</div>
                <div class="admin-details">
                    <div class="admin-name">${bidang.admin_bidang.name}</div>
                    <div class="admin-email">${bidang.admin_bidang.email}</div>
                    <div class="admin-meta">
                        <span class="meta-item">
                            <i class='bx bx-calendar'></i>
                            Terakhir login: ${lastLogin}
                        </span>
                    </div>
                </div>
            </div>
        `;
    } else {
        container.innerHTML = `
            <div class="no-admin-card">
                <i class='bx bx-user-x'></i>
                <div>
                    <h5>Belum ada Admin Bidang</h5>
                    <p>Bidang ini belum memiliki admin. Klik Edit untuk menambahkan admin.</p>
                </div>
            </div>
        `;
    }
}

async function loadDetailPeserta(bidangId) {
    // Mock peserta data
    const pesertaList = [
        { id: 1, name: 'Rifaldy', email: 'rifaldy@gmail.com', nim: 'M0521001' },
        { id: 2, name: 'Budi Santoso', email: 'budi@uns.ac.id', nim: 'M0521002' },
        { id: 3, name: 'Siti Aisyah', email: 'aisyah@ums.ac.id', nim: 'M0521003' }
    ];
    
    const container = document.getElementById('detailPesertaList');
    
    if (pesertaList.length === 0) {
        container.innerHTML = `
            <div class="no-data">
                <i class='bx bx-user-x'></i>
                <p>Belum ada peserta di bidang ini</p>
            </div>
        `;
        return;
    }
    
    let html = '<div class="peserta-table">';
    html += `
        <div class="table-header-small">
            <div>Nama</div>
            <div>NIM</div>
            <div>Email</div>
            <div>Aksi</div>
        </div>
    `;
    
    pesertaList.forEach(peserta => {
        html += `
            <div class="peserta-row">
                <div class="peserta-cell">
                    <div class="peserta-avatar-small">${getInitials(peserta.name)}</div>
                    <div class="peserta-info-small">
                        <div class="peserta-name">${peserta.name}</div>
                    </div>
                </div>
                <div class="peserta-cell">${peserta.nim}</div>
                <div class="peserta-cell">${peserta.email}</div>
                <div class="peserta-cell">
                    <button class="btn-remove-small" onclick="removePeserta(${peserta.id})">
                        <i class='bx bx-x'></i> Keluar
                    </button>
                </div>
            </div>
        `;
    });
    html += '</div>';
    
    container.innerHTML = html;
}

// Delete Modal
function showDeleteBidangModal(id, name) {
    bidangToDelete = id;
    document.getElementById('deleteBidangName').textContent = name;
    document.getElementById('delete_confirmation').value = '';
    document.getElementById('confirmDeleteBtn').disabled = true;
    openModal('deleteBidangModal');
}

function closeDeleteBidangModal() {
    document.getElementById('deleteBidangModal').style.display = 'none';
    bidangToDelete = null;
}

function validateDeleteConfirmation() {
    const input = document.getElementById('delete_confirmation').value;
    const btn = document.getElementById('confirmDeleteBtn');
    btn.disabled = input !== 'HAPUS';
}

async function confirmDeleteBidang() {
    if (!bidangToDelete) return;
    
    try {
        showNotification('Menghapus bidang...', 'info');
        
        setTimeout(() => {
            showNotification('Bidang berhasil dihapus', 'success');
            closeDeleteBidangModal();
            fetchBidangData();
        }, 1000);
        
    } catch (error) {
        showNotification('Gagal menghapus bidang', 'error');
    }
}

// Utility functions
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const toggleBtn = field.parentElement.querySelector('.password-toggle i');
    
    if (field.type === 'password') {
        field.type = 'text';
        toggleBtn.className = 'bx bx-hide';
    } else {
        field.type = 'password';
        toggleBtn.className = 'bx bx-show';
    }
}

function getInitials(name) {
    return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
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

// Add CSS styles
const style = document.createElement('style');
style.textContent = `
    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    
    .header-actions {
        display: flex;
        gap: 15px;
    }
    
    .kuota-container {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .kuota-info {
        display: flex;
        align-items: center;
        gap: 4px;
        font-weight: 600;
    }
    
    .kuota-current {
        color: var(--primary);
        font-size: 1.1rem;
    }
    
    .kuota-max {
        color: #666;
    }
    
    .kuota-label {
        color: #888;
        font-size: 0.9rem;
        margin-left: 4px;
    }
    
    .kuota-progress-small {
        height: 6px;
        background: #f0f0f0;
        border-radius: 3px;
        overflow: hidden;
    }
    
    .progress-bar-small {
        height: 100%;
        border-radius: 3px;
        transition: width 0.3s;
    }
    
    .kuota-tersedia {
        font-size: 0.85rem;
        color: #2ed573;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .admin-info {
        display: flex;
        flex-direction: column;
    }
    
    .admin-name {
        font-weight: 600;
        color: var(--primary);
        font-size: 0.9rem;
    }
    
    .admin-email {
        font-size: 0.8rem;
        color: #666;
    }
    
    .no-admin {
        color: #888;
        font-style: italic;
        font-size: 0.9rem;
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
    
    .form-section {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .form-section h4 {
        color: var(--primary);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.1rem;
    }
    
    .section-description {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 20px;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
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
    
    .password-input {
        position: relative;
    }
    
    .password-input input {
        padding-right: 45px;
        width: 100%;
    }
    
    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #888;
        cursor: pointer;
        font-size: 1.2rem;
        padding: 0;
    }
    
    .btn-danger {
        background: #e74c3c;
        color: white;
    }
    
    .btn-danger:hover {
        background: #c0392b;
    }
    
    .btn-danger:disabled {
        background: #f5b7b1;
        cursor: not-allowed;
    }
    
    .btn-small {
        padding: 6px 12px;
        font-size: 0.8rem;
    }
    
    /* Detail Modal Styles */
    .detail-section {
        margin-bottom: 30px;
    }
    
    .detail-section h4 {
        color: var(--primary);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.1rem;
    }
    
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .detail-item {
        display: flex;
        flex-direction: column;
    }
    
    .detail-item label {
        font-weight: 600;
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }
    
    .detail-item span, .detail-item div {
        color: #333;
        font-size: 0.95rem;
    }
    
    .bidang-name {
        font-weight: 600;
        color: var(--primary);
        font-size: 1.1rem;
    }
    
    .kuota-display {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .kuota-info {
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .kuota-progress {
        height: 10px;
        background: #f0f0f0;
        border-radius: 5px;
        overflow: hidden;
    }
    
    .progress-bar {
        height: 100%;
        border-radius: 5px;
        transition: width 0.3s;
    }
    
    .kuota-available {
        color: #2ed573;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .admin-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .admin-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(45deg, var(--primary), var(--secondary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    .admin-details {
        flex-grow: 1;
    }
    
    .admin-name {
        font-weight: 600;
        color: var(--primary);
        font-size: 1rem;
        margin-bottom: 5px;
    }
    
    .admin-email {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }
    
    .admin-meta {
        display: flex;
        gap: 15px;
    }
    
    .meta-item {
        font-size: 0.85rem;
        color: #888;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .no-admin-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }
    
    .no-admin-card i {
        font-size: 3rem;
        color: #ccc;
    }
    
    .no-admin-card h5 {
        color: var(--primary);
        margin: 0;
    }
    
    .no-admin-card p {
        color: #666;
        margin: 0;
        font-size: 0.9rem;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .peserta-table {
        background: #f8f9fa;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .table-header-small {
        display: grid;
        grid-template-columns: 2fr 1fr 2fr 1fr;
        background: var(--primary);
        color: white;
        padding: 12px 15px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .peserta-row {
        display: grid;
        grid-template-columns: 2fr 1fr 2fr 1fr;
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        align-items: center;
    }
    
    .peserta-row:last-child {
        border-bottom: none;
    }
    
    .peserta-cell {
        display: flex;
        align-items: center;
    }
    
    .peserta-avatar-small {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(45deg, var(--primary), var(--secondary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
        margin-right: 10px;
    }
    
    .peserta-info-small {
        flex-grow: 1;
    }
    
    .peserta-name {
        font-weight: 600;
        color: var(--primary);
        font-size: 0.9rem;
    }
    
    .btn-remove-small {
        background: none;
        border: 1px solid #e74c3c;
        color: #e74c3c;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.85rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .no-data {
        text-align: center;
        padding: 40px;
        color: #888;
    }
    
    .no-data i {
        font-size: 3rem;
        margin-bottom: 10px;
        display: block;
    }
    
    .warning-box {
        background: #fff9e6;
        border: 1px solid #f1c40f;
        border-radius: 10px;
        padding: 15px;
        margin: 15px 0;
    }
    
    .warning-box i {
        color: #f1c40f;
        font-size: 1.5rem;
        float: left;
        margin-right: 10px;
    }
    
    .warning-box ul {
        margin: 10px 0 0 0;
        padding-left: 20px;
        color: #666;
    }
    
    .warning-box li {
        margin-bottom: 5px;
        font-size: 0.9rem;
    }
    
    .alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 15px;
        border-radius: 10px;
        margin-top: 15px;
    }
    
    .alert-info {
        background: #d1ecf1;
        border: 1px solid #bee5eb;
        color: #0c5460;
    }
    
    .alert i {
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    
    .alert div {
        flex-grow: 1;
    }
    
    .alert strong {
        display: block;
        margin-bottom: 5px;
    }
    
    .alert p {
        margin: 0;
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
        .form-row,
        .detail-grid {
            grid-template-columns: 1fr;
        }
        
        .modal-content {
            width: 95%;
            margin: 10px;
        }
        
        .table-header-small,
        .peserta-row {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .peserta-cell {
            justify-content: flex-start;
        }
        
        .content-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .action-buttons {
            flex-wrap: wrap;
        }
    }
    
    @media (max-width: 480px) {
        .modal-footer {
            flex-direction: column;
        }
        
        .modal-footer button {
            width: 100%;
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection