@extends('layouts.mentor')

@section('title', 'Verifikasi Absensi & Logbook')

@section('content')

<!-- Tab Navigation -->
<div class="bg-white rounded-2xl shadow-sm mb-8">
    <div class="border-b border-gray-200">
        <nav class="flex overflow-x-auto" style="scrollbar-width: none;">
            <button id="tab-logbook" class="tab-btn active px-6 py-4 font-medium text-lg border-b-2 border-primary text-primary whitespace-nowrap">
                <i class='bx bx-book mr-2'></i> Logbook
                <span class="ml-2 bg-primary text-white text-xs px-2 py-1 rounded-full" id="tabLogbookCount">1</span>
            </button>
            <button id="tab-absensi" class="tab-btn px-6 py-4 font-medium text-lg border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">
                <i class='bx bx-calendar-check mr-2'></i> Absensi
                <span class="ml-2 bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full" id="tabAbsensiCount">2</span>
            </button>
        </nav>
    </div>
</div>

<!-- Search and Filter -->
<div class="form-card mb-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div>
            <label class="block text-gray-700 mb-2">Cari Kegiatan/Tanggal</label>
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Kegiatan atau tanggal..." 
                       class="w-full p-3 pl-10 border border-gray-300 rounded-lg">
                <i class='bx bx-search absolute left-3 top-3 text-gray-400'></i>
            </div>
        </div>
        
        <div>
            <label class="block text-gray-700 mb-2">Tanggal</label>
            <input type="date" id="dateFilter" class="w-full p-3 border border-gray-300 rounded-lg">
        </div>
    </div>
    
    <div class="flex gap-3">
        <button onclick="filterData()" class="btn btn-primary">
            <i class='bx bx-filter'></i> Terapkan Filter
        </button>
        <button onclick="resetFilter()" class="btn" style="background: #f8fafc; color: #666;">
            <i class='bx bx-reset'></i> Reset
        </button>
    </div>
</div>

<!-- Logbook Tab Content -->
<div id="logbook-content" class="tab-content active">
    <div class="form-card">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class='bx bx-book'></i> Logbook John Doe
            </h3>
            <span class="text-gray-600" id="logbookTotal">1 logbook menunggu</span>
        </div>
        
        <div id="logbook-list" class="space-y-4">
            <!-- Logbook items will be loaded here -->
        </div>
        
        <div class="mt-8 border-t pt-6">
            <h4 class="font-bold text-primary mb-4 flex items-center gap-2">
                <i class='bx bx-history'></i> Logbook Terverifikasi
            </h4>
            <div id="verified-logbook-list" class="space-y-3">
                <!-- Verified logbooks will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Absensi Tab Content -->
<div id="absensi-content" class="tab-content hidden">
    <div class="form-card">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class='bx bx-calendar-check'></i> Absensi John Doe
            </h3>
            <span class="text-gray-600" id="absensiTotal">2 absensi</span>
        </div>
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Waktu</th>
                        <th>Lokasi</th>
                        <th>Bukti</th>
                    </tr>
                </thead>
                <tbody id="absensi-table">
                    <!-- Absensi data will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Verifikasi Logbook -->
<div id="verificationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-primary" id="modalTitle">Verifikasi Logbook</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>
            
            <div id="modalContent">
                <!-- Modal content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
    // Data - Hanya untuk John Doe
    const logbookData = [
        {
            id: 1,
            peserta: 'John Doe',
            tanggal: '16 Mar 2024',
            kegiatan: 'Pengembangan dashboard admin',
            deskripsi: 'Mengembangkan fitur dashboard admin untuk monitoring magang dengan tambahan grafik statistik.',
            status: 'pending',
            waktu: '08:00 - 16:00',
            bukti: 'dashboard_screenshot.jpg',
            catatan: ''
        }
    ];
    
    const absensiData = [
        {
            id: 1,
            peserta: 'John Doe',
            tanggal: '16 Mar 2024',
            status: 'hadir',
            waktu: '08:15 - 16:30',
            lokasi: 'Dalam kantor',
            bukti: 'foto_kantor.jpg'
        },
        {
            id: 2,
            peserta: 'John Doe',
            tanggal: '15 Mar 2024',
            status: 'hadir',
            waktu: '08:30 - 17:00',
            lokasi: 'Dalam kantor',
            bukti: 'foto_kantor2.jpg'
        },
        {
            id: 3,
            peserta: 'John Doe',
            tanggal: '14 Mar 2024',
            status: 'hadir',
            waktu: '08:45 - 16:45',
            lokasi: 'Dalam kantor',
            bukti: 'foto_kantor3.jpg'
        }
    ];
    
    const verifiedLogbooks = [
        {
            id: 2,
            peserta: 'John Doe',
            tanggal: '15 Mar 2024',
            kegiatan: 'Analisis data statistik',
            status: 'approved',
            catatan: 'Analisis data sangat detail dan rapi'
        },
        {
            id: 3,
            peserta: 'John Doe',
            tanggal: '14 Mar 2024',
            kegiatan: 'Maintenance server',
            status: 'approved',
            catatan: 'Maintenance dilakukan dengan baik'
        }
    ];
    
    // Get query parameters from URL
    const urlParams = new URLSearchParams(window.location.search);
    const initialTab = urlParams.get('tab') || 'logbook';
    
    function setupTabs() {
        const tabs = document.querySelectorAll('.tab-btn');
        const contents = document.querySelectorAll('.tab-content');
        
        // Set active tab based on URL parameter
        let activeTabId = initialTab;
        if (!activeTabId || !['logbook', 'absensi'].includes(activeTabId)) {
            activeTabId = 'logbook';
        }
        
        // Remove active class from all
        tabs.forEach(t => {
            t.classList.remove('active');
            t.classList.add('text-gray-500', 'border-transparent');
            t.classList.remove('text-primary', 'border-primary');
        });
        
        contents.forEach(c => c.classList.add('hidden'));
        
        // Activate selected tab
        const activeTab = document.getElementById(`tab-${activeTabId}`);
        const activeContent = document.getElementById(`${activeTabId}-content`);
        
        if (activeTab) {
            activeTab.classList.remove('text-gray-500', 'border-transparent');
            activeTab.classList.add('text-primary', 'border-primary');
            activeTab.classList.add('active');
        }
        
        if (activeContent) {
            activeContent.classList.remove('hidden');
            activeContent.classList.add('active');
        }
        
        // Add click handlers
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs and contents
                tabs.forEach(t => {
                    t.classList.remove('active');
                    t.classList.add('text-gray-500', 'border-transparent');
                    t.classList.remove('text-primary', 'border-primary');
                });
                
                contents.forEach(c => c.classList.add('hidden'));
                
                // Add active class to clicked tab
                tab.classList.remove('text-gray-500', 'border-transparent');
                tab.classList.add('text-primary', 'border-primary');
                
                // Show corresponding content
                const tabId = tab.id.replace('tab-', '');
                const content = document.getElementById(`${tabId}-content`);
                if (content) {
                    content.classList.remove('hidden');
                }
                
                // Update URL without reloading
                const newUrl = `/mentor/verifikasi?tab=${tabId}`;
                window.history.replaceState({}, '', newUrl);
                
                // Update tab badge colors
                updateTabBadges();
                
                // Load data for active tab
                loadTabData(tabId);
            });
        });
        
        // Load initial data
        loadTabData(activeTabId);
    }
    
    function updateTabBadges() {
        const pendingLogbooks = logbookData.filter(l => l.status === 'pending').length;
        const totalAbsensi = absensiData.length;
        
        // Update counts
        document.getElementById('tabLogbookCount').textContent = pendingLogbooks;
        document.getElementById('tabAbsensiCount').textContent = totalAbsensi;
        
        // Update badge colors
        const activeTab = document.querySelector('.tab-btn.active').id;
        document.querySelectorAll('.tab-btn span').forEach(badge => {
            badge.classList.remove('bg-primary', 'text-white', 'bg-gray-200', 'text-gray-700');
        });
        
        if (activeTab === 'tab-logbook') {
            document.getElementById('tabLogbookCount').classList.add('bg-primary', 'text-white');
            document.getElementById('tabAbsensiCount').classList.add('bg-gray-200', 'text-gray-700');
        } else if (activeTab === 'tab-absensi') {
            document.getElementById('tabLogbookCount').classList.add('bg-gray-200', 'text-gray-700');
            document.getElementById('tabAbsensiCount').classList.add('bg-primary', 'text-white');
        }
    }
    
    // Load Data
    function loadTabData(tabId) {
        if (tabId === 'logbook') {
            renderLogbookList();
            renderVerifiedLogbooks();
            document.getElementById('logbookTotal').textContent = 
                `${logbookData.filter(l => l.status === 'pending').length} logbook menunggu`;
        } else if (tabId === 'absensi') {
            renderAbsensiTable();
            document.getElementById('absensiTotal').textContent = 
                `${absensiData.length} absensi`;
        }
    }
    
    function renderLogbookList() {
        const container = document.getElementById('logbook-list');
        const pendingLogbooks = logbookData.filter(l => l.status === 'pending');
        
        if (pendingLogbooks.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12">
                    <i class='bx bx-check-circle text-5xl text-green-500 mb-4'></i>
                    <h4 class="text-xl font-semibold text-gray-700 mb-2">Tidak ada logbook menunggu</h4>
                    <p class="text-gray-500">Semua logbook telah diverifikasi</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = pendingLogbooks.map(logbook => `
            <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition-shadow">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                    <div>
                        <h4 class="font-bold text-lg text-primary mb-1">${logbook.peserta}</h4>
                        <p class="text-gray-600 mb-2">${logbook.kegiatan}</p>
                        <div class="flex items-center gap-4 text-sm text-gray-500">
                            <span class="flex items-center gap-1">
                                <i class='bx bx-calendar'></i> ${logbook.tanggal}
                            </span>
                            <span class="flex items-center gap-1">
                                <i class='bx bx-time'></i> ${logbook.waktu}
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="viewDetail(${logbook.id}, 'logbook')" 
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                            <i class='bx bx-show'></i> Detail
                        </button>
                        <button onclick="openVerificationModal(${logbook.id}, 'logbook')" 
                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-800 transition">
                            <i class='bx bx-check'></i> Verifikasi
                        </button>
                    </div>
                </div>
                <div class="text-gray-600 line-clamp-2">${logbook.deskripsi}</div>
            </div>
        `).join('');
    }
    
    function renderVerifiedLogbooks() {
        const container = document.getElementById('verified-logbook-list');
        container.innerHTML = verifiedLogbooks.map(logbook => `
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class='bx bx-check text-xl text-green-600'></i>
                    </div>
                    <div>
                        <div class="font-medium text-gray-800">${logbook.peserta}</div>
                        <div class="text-sm text-gray-500">${logbook.kegiatan}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-medium text-gray-700">${logbook.tanggal}</div>
                    <div class="text-xs text-gray-500">${logbook.catatan}</div>
                </div>
            </div>
        `).join('');
    }
    
    function renderAbsensiTable() {
        const container = document.getElementById('absensi-table');
        
        container.innerHTML = absensiData.map(absensi => {
            const statusColor = absensi.status === 'hadir' ? 'status-approved' :
                              absensi.status === 'izin' ? 'status-waiting' : 'status-rejected';
            
            const statusText = absensi.status === 'hadir' ? 'HADIR' :
                             absensi.status === 'izin' ? 'IZIN' : 'SAKIT';
            
            return `
                <tr>
                    <td>${absensi.tanggal}</td>
                    <td>
                        <span class="status-badge ${statusColor}">
                            ${statusText}
                        </span>
                    </td>
                    <td>${absensi.waktu}</td>
                    <td>${absensi.lokasi}</td>
                    <td>
                        <button onclick="viewBukti('${absensi.bukti}')" 
                                class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                            <i class='bx ${absensi.bukti.includes('.pdf') ? 'bx-file' : 'bx-image'} mr-1'></i>
                            Lihat
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    }
    
    // Modal Functions
    function openVerificationModal(id, type) {
        let data;
        
        data = logbookData.find(l => l.id === id);
        const title = 'Verifikasi Logbook';
        const content = `
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 mb-1">Peserta</label>
                    <div class="font-medium">${data.peserta}</div>
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-1">Tanggal</label>
                    <div class="font-medium">${data.tanggal} • ${data.waktu}</div>
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-1">Kegiatan</label>
                    <div>${data.kegiatan}</div>
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-1">Deskripsi</label>
                    <div class="bg-gray-50 p-3 rounded-lg">${data.deskripsi}</div>
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">Catatan Evaluasi</label>
                    <textarea id="verificationNote" 
                              class="w-full p-3 border border-gray-300 rounded-lg" 
                              rows="3" 
                              placeholder="Berikan catatan atau masukan..."></textarea>
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">Status Verifikasi</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button onclick="setVerificationStatus('approved')" 
                                class="p-3 border-2 border-green-500 text-green-600 rounded-lg font-medium hover:bg-green-50 transition">
                            <i class='bx bx-check'></i> Setujui
                        </button>
                        <button onclick="setVerificationStatus('rejected')" 
                                class="p-3 border-2 border-red-500 text-red-600 rounded-lg font-medium hover:bg-red-50 transition">
                            <i class='bx bx-x'></i> Tolak
                        </button>
                    </div>
                </div>
                
                <input type="hidden" id="verificationId" value="${id}">
                <input type="hidden" id="verificationType" value="${type}">
            </div>
            
            <div class="mt-6 flex gap-3">
                <button onclick="submitVerification()" 
                        class="flex-1 bg-primary text-white py-3 rounded-lg font-medium hover:bg-blue-800 transition">
                    Simpan
                </button>
                <button onclick="closeModal()" 
                        class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-lg font-medium hover:bg-gray-200 transition">
                    Batal
                </button>
            </div>
        `;
        
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalContent').innerHTML = content;
        document.getElementById('verificationModal').classList.remove('hidden');
    }
    
    function setVerificationStatus(status) {
        const buttons = document.querySelectorAll('#modalContent button[onclick*="setVerificationStatus"]');
        buttons.forEach(btn => {
            btn.style.background = '';
            btn.style.color = btn.textContent.includes('Setujui') ? 
                '#10b981' : '#ef4444';
        });
        
        const selectedBtn = event.target.closest('button');
        selectedBtn.style.background = selectedBtn.textContent.includes('Setujui') ? 
            '#10b981' : '#ef4444';
        selectedBtn.style.color = 'white';
    }
    
    function submitVerification() {
        const id = parseInt(document.getElementById('verificationId').value);
        const type = document.getElementById('verificationType').value;
        const note = document.getElementById('verificationNote')?.value || '';
        
        const index = logbookData.findIndex(l => l.id === id);
        if (index !== -1) {
            logbookData[index].status = 'approved';
            logbookData[index].catatan = note;
            
            // Move to verified logbooks
            verifiedLogbooks.unshift({
                id: logbookData[index].id,
                peserta: logbookData[index].peserta,
                tanggal: logbookData[index].tanggal,
                kegiatan: logbookData[index].kegiatan,
                status: 'approved',
                catatan: note
            });
            
            // Remove from pending
            logbookData.splice(index, 1);
        }
        
        closeModal();
        
        // Show success message
        showNotification('Berhasil!', 'Logbook berhasil diverifikasi.', 'success');
        
        // Reload current tab
        const activeTab = document.querySelector('.tab-btn.active').id.replace('tab-', '');
        loadTabData(activeTab);
        updateTabBadges();
    }
    
    function closeModal() {
        document.getElementById('verificationModal').classList.add('hidden');
    }
    
    function viewDetail(id, type) {
        let data;
        let title;
        
        if (type === 'logbook') {
            data = logbookData.find(l => l.id === id);
            title = 'Detail Logbook';
        } else {
            data = absensiData.find(a => a.id === id);
            title = 'Detail Absensi';
        }
        
        alert(`${title}\n\nPeserta: ${data.peserta}\nTanggal: ${data.tanggal}\n${type === 'logbook' ? 'Kegiatan: ' + data.kegiatan : 'Status: ' + data.status}`);
    }
    
    // Filter Functions
    function filterData() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const dateFilter = document.getElementById('dateFilter').value;
        
        // Implementation depends on backend
        showNotification('Filter', 'Filter diterapkan.', 'info');
    }
    
    function resetFilter() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = 'all';
        document.getElementById('dateFilter').value = '';
        showNotification('Reset', 'Filter direset.', 'info');
    }
    
    // Utility Functions
    function showNotification(title, message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transform transition-transform duration-300 ${
            type === 'success' ? 'bg-green-500' :
            type === 'error' ? 'bg-red-500' :
            'bg-blue-500'
        } text-white`;
        
        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <i class='bx ${
                    type === 'success' ? 'bx-check-circle' :
                    type === 'error' ? 'bx-error' :
                    'bx-info-circle'
                } text-xl'></i>
                <div>
                    <div class="font-bold">${title}</div>
                    <div class="text-sm opacity-90">${message}</div>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Fungsi untuk melihat bukti absensi
    function viewBukti(filename) {
        const fileExt = filename.split('.').pop().toLowerCase();
        const imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        const pdfExtensions = ['pdf'];
        
        if (imageExtensions.includes(fileExt)) {
            // Simulasi modal untuk melihat gambar
            const modal = `
                <div class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                        <div class="flex justify-between items-center p-4 border-b">
                            <h3 class="text-lg font-bold text-primary">Preview Bukti Absensi</h3>
                            <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                <i class='bx bx-x text-2xl'></i>
                            </button>
                        </div>
                        <div class="p-4 overflow-auto">
                            <div class="text-center">
                                <img src="/images/dummy/${filename}" 
                                     alt="Bukti Absensi" 
                                     class="max-w-full h-auto rounded-lg mx-auto">
                                <div class="mt-4 text-gray-600 text-sm">
                                    <div class="font-medium">${filename}</div>
                                    <div>Bukti absensi John Doe</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modal);
        } else if (pdfExtensions.includes(fileExt)) {
            // Untuk file PDF, buka di tab baru atau tampilkan preview
            window.open(`/documents/${filename}`, '_blank');
        } else {
            alert(`Membuka file: ${filename}\n\nFile akan diunduh atau dibuka di aplikasi yang sesuai.`);
        }
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        setupTabs();
        loadTabData('logbook');
        updateTabBadges();
        
        // Add real-time search
        document.getElementById('searchInput').addEventListener('input', filterData);
        document.getElementById('statusFilter').addEventListener('change', filterData);
        document.getElementById('dateFilter').addEventListener('change', filterData);
        
        // Close modal on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });
    });
</script>

<style>
    /* Additional styles for the verification page */
    .tab-btn {
        transition: all 0.3s ease;
        position: relative;
    }
    
    .tab-btn.active {
        font-weight: 600;
    }
    
    .tab-content {
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Custom scrollbar for tables */
    .table-container::-webkit-scrollbar {
        height: 6px;
        width: 6px;
    }
    
    .table-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .table-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .table-container::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
</style>
@endsection