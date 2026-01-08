@extends('layouts.mentor')

@section('title', 'Input Penilaian Peserta')

@section('content')


<!-- Filter dan Pencarian -->
<div class="form-card mb-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="md:col-span-2">
            <label class="block text-gray-700 mb-2">Cari Peserta</label>
            <div class="relative">
                <input type="text" id="searchPeserta" placeholder="Nama peserta, NIM, atau universitas..." 
                       class="w-full p-3 pl-10 border border-gray-300 rounded-lg">
                <i class='bx bx-search absolute left-3 top-3 text-gray-400'></i>
            </div>
        </div>
        
        <div>
            <label class="block text-gray-700 mb-2">Status Penilaian</label>
            <select id="statusFilter" class="w-full p-3 border border-gray-300 rounded-lg">
                <option value="all">Semua</option>
                <option value="sudah">Sudah Dinilai</option>
                <option value="belum">Belum Dinilai</option>
            </select>
        </div>
    </div>
    
    <div class="flex gap-3">
        <button onclick="filterPeserta()" class="btn btn-primary">
            <i class='bx bx-filter'></i> Terapkan Filter
        </button>
        <button onclick="resetFilter()" class="btn" style="background: #f8fafc; color: #666;">
            <i class='bx bx-reset'></i> Reset
        </button>
    </div>
</div>

<!-- Daftar Peserta -->
<div class="form-card mb-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-primary flex items-center gap-2">
            <i class='bx bx-list-ul'></i> Daftar Peserta Magang
        </h3>
        <span class="text-gray-600" id="pesertaCount">12 peserta ditemukan</span>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Peserta</th>
                    <th>Universitas</th>
                    <th>Periode</th>
                    <th>Status</th>
                    <th>File Penilaian</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="pesertaTable">
                <!-- Data akan di-load oleh JavaScript -->
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between mt-4">
        <div class="text-sm text-gray-600" id="pageInfo">Menampilkan 1 - 5 dari 6 peserta</div>
        <div class="flex items-center gap-2">
            <button id="prevPageBtn" onclick="prevPage()" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition disabled:opacity-50" disabled>
                <i class='bx bx-chevron-up'></i>
            </button>
            <button id="nextPageBtn" onclick="nextPage()" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                <i class='bx bx-chevron-down'></i>
            </button>
        </div>
    </div>
</div>

<!-- Modal Upload File Penilaian -->
<div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 hidden">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-primary" id="modalTitle">Upload File Penilaian</h3>
                <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>
            
            <div id="modalContent">
                <!-- Form upload akan di-load di sini -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview File -->
<div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 hidden">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-primary" id="previewTitle">Preview File Penilaian</h3>
                <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-600">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>
            
            <div id="previewContent" class="text-center">
                <!-- Preview file akan di-load di sini -->
            </div>
        </div>
    </div>
</div>

<script>
// Data peserta magang
const pesertaData = [
    {
        id: 1,
        nama: "John Doe",
        nim: "1234567890",
        universitas: "Universitas Sebelas Maret",
        prodi: "Teknik Informatika",
        periode: "1 Jan - 30 Mar 2024",
        status: "selesai",
        sudahDinilai: true,
        tanggalSelesai: "2024-03-30",
        foto: "JD",
        filePenilaian: {
            nama: "Penilaian_John_Doe.pdf",
            ukuran: "2.4 MB",
            tanggalUpload: "2024-03-28",
            url: "#"
        }
    },
    {
        id: 2,
        nama: "Jane Smith",
        nim: "0987654321",
        universitas: "Universitas Gadjah Mada",
        prodi: "Ilmu Komunikasi",
        periode: "1 Jan - 30 Mar 2024",
        status: "selesai",
        sudahDinilai: false,
        tanggalSelesai: "2024-03-30",
        foto: "JS",
        filePenilaian: null
    },
    {
        id: 3,
        nama: "Budi Santoso",
        nim: "1122334455",
        universitas: "Universitas Diponegoro",
        prodi: "Sistem Informasi",
        periode: "1 Jan - 30 Mar 2024",
        status: "selesai",
        sudahDinilai: true,
        tanggalSelesai: "2024-03-30",
        foto: "BS",
        filePenilaian: {
            nama: "Nilai_Budi_Santoso.pdf",
            ukuran: "1.8 MB",
            tanggalUpload: "2024-03-27",
            url: "#"
        }
    },
    {
        id: 4,
        nama: "Siti Rahma",
        nim: "5566778899",
        universitas: "Universitas Indonesia",
        prodi: "Administrasi Bisnis",
        periode: "1 Jan - 30 Mar 2024",
        status: "selesai",
        sudahDinilai: true,
        tanggalSelesai: "2024-03-30",
        foto: "SR",
        filePenilaian: {
            nama: "Penilaian_Siti_Rahma.docx",
            ukuran: "3.1 MB",
            tanggalUpload: "2024-03-29",
            url: "#"
        }
    },
    {
        id: 5,
        nama: "Ahmad Rizki",
        nim: "6677889900",
        universitas: "Institut Teknologi Bandung",
        prodi: "Teknik Informatika",
        periode: "1 Jan - 30 Mar 2024",
        status: "selesai",
        sudahDinilai: false,
        tanggalSelesai: "2024-03-30",
        foto: "AR",
        filePenilaian: null
    },
    {
        id: 6,
        nama: "Rina Dewi",
        nim: "3344556677",
        universitas: "Universitas Airlangga",
        prodi: "Komunikasi",
        periode: "1 Jan - 30 Mar 2024",
        status: "selesai",
        sudahDinilai: true,
        tanggalSelesai: "2024-03-30",
        foto: "RD",
        filePenilaian: {
            nama: "Evaluasi_Rina_Dewi.pdf",
            ukuran: "2.7 MB",
            tanggalUpload: "2024-03-26",
            url: "#"
        }
    }
];

// Pagination configuration
const itemsPerPage = 5; // tampilkan maksimal 5 baris per halaman
let currentPage = 1;    // halaman saat ini (1-based)

// Load daftar peserta (dengan pagination)
function loadPesertaTable() {
    const container = document.getElementById('pesertaTable');
    const filteredData = filterPesertaData();
    const totalItems = filteredData.length;
    const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));

    if (currentPage > totalPages) currentPage = totalPages;

    if (totalItems === 0) {
        container.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-8">
                    <div class="text-gray-500">
                        <i class='bx bx-search-alt text-4xl mb-3 block'></i>
                        <div class="font-medium">Tidak ada peserta ditemukan</div>
                        <div class="text-sm">Coba dengan filter yang berbeda</div>
                    </div>
                </td>
            </tr>
        `;
        document.getElementById('pesertaCount').textContent = `0 peserta ditemukan`;
        document.getElementById('pageInfo').textContent = `Menampilkan 0 - 0 dari 0 peserta`;
        document.getElementById('prevPageBtn').disabled = true;
        document.getElementById('nextPageBtn').disabled = true;
        return;
    }

    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = filteredData.slice(start, end);

    container.innerHTML = pageData.map(peserta => {
        let statusColor = 'bg-gray-100 text-gray-800';
        let statusText = 'Belum Selesai';

        if (peserta.status === 'selesai') {
            statusColor = peserta.sudahDinilai ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
            statusText = peserta.sudahDinilai ? 'Sudah Dinilai' : 'Belum Dinilai';
        } else if (peserta.status === 'berjalan') {
            statusColor = 'bg-blue-100 text-blue-800';
            statusText = 'Sedang Berjalan';
        }

        const fileInfo = peserta.filePenilaian ? `
            <div class="flex items-center gap-2">
                <i class='bx bx-file text-primary'></i>
                <div>
                    <div class="font-medium text-primary text-sm">${peserta.filePenilaian.nama}</div>
                    <div class="text-xs text-gray-600">${peserta.filePenilaian.ukuran} • ${peserta.filePenilaian.tanggalUpload}</div>
                </div>
            </div>
        ` : `<span class="text-gray-400 italic">Belum ada file</span>`;

        return `
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-accent rounded-full flex items-center justify-center text-primary font-bold">
                            ${peserta.foto}
                        </div>
                        <div>
                            <div class="font-bold text-primary">${peserta.nama}</div>
                            <div class="text-sm text-gray-600">${peserta.nim}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="font-medium">${peserta.universitas}</div>
                    <div class="text-sm text-gray-600">${peserta.prodi}</div>
                </td>
                <td class="text-gray-600">${peserta.periode}</td>
                <td>
                    <span class="px-3 py-1 rounded-full text-xs font-medium ${statusColor}">
                        ${statusText}
                    </span>
                </td>
                <td>
                    ${fileInfo}
                </td>
                <td>
                    <div class="flex gap-2">
                        ${peserta.filePenilaian ? `
                            <button onclick="previewFile(${peserta.id})" 
                                    class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-sm">
                                <i class='bx bx-show'></i>
                            </button>
                            <button onclick="downloadFile(${peserta.id})" 
                                    class="px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition text-sm">
                                <i class='bx bx-download'></i>
                            </button>
                        ` : ''}
                        <button onclick="${peserta.filePenilaian ? `editFile(${peserta.id})` : `uploadFile(${peserta.id})`}" 
                                class="px-3 py-1 ${peserta.filePenilaian ? 'bg-yellow-100 text-yellow-700' : 'bg-primary text-white'} rounded-lg hover:opacity-90 transition text-sm">
                            <i class='bx ${peserta.filePenilaian ? 'bx-edit' : 'bx-upload'} mr-1'></i>
                            ${peserta.filePenilaian ? 'Edit' : 'Upload'}
                        </button>
                        ${peserta.filePenilaian ? `
                            <button onclick="deleteFile(${peserta.id})" 
                                    class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-sm">
                                <i class='bx bx-trash'></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `;
    }).join('');

    // Update count and pagination display
    document.getElementById('pesertaCount').textContent = `${totalItems} peserta ditemukan`;
    document.getElementById('pageInfo').textContent = `Menampilkan ${start + 1} - ${Math.min(end, totalItems)} dari ${totalItems} peserta`;
    document.getElementById('prevPageBtn').disabled = currentPage === 1;
    document.getElementById('nextPageBtn').disabled = currentPage === totalPages;
}

// Filter data peserta
function filterPesertaData() {
    const searchTerm = document.getElementById('searchPeserta').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    
    return pesertaData.filter(peserta => {
        // Search filter
        const searchMatch = !searchTerm || 
            peserta.nama.toLowerCase().includes(searchTerm) ||
            peserta.nim.includes(searchTerm) ||
            peserta.universitas.toLowerCase().includes(searchTerm) ||
            peserta.prodi.toLowerCase().includes(searchTerm);
        
        // Status filter (berdasarkan status penilaian)
        let statusMatch = true;
        if (statusFilter === 'sudah') {
            statusMatch = peserta.sudahDinilai;
        } else if (statusFilter === 'belum') {
            statusMatch = !peserta.sudahDinilai;
        }
        
        return searchMatch && statusMatch;
    });
}

// Fungsi untuk terapkan filter
function filterPeserta() {
    currentPage = 1;
    loadPesertaTable();
    updateStats();
}

// Reset filter
function resetFilter() {
    document.getElementById('searchPeserta').value = '';
    document.getElementById('statusFilter').value = 'all';
    currentPage = 1;
    loadPesertaTable();
    updateStats();
}

// Upload file penilaian
function uploadFile(pesertaId) {
    const peserta = pesertaData.find(p => p.id === pesertaId);
    if (!peserta) return;
    
    document.getElementById('modalTitle').textContent = `Upload File Penilaian - ${peserta.nama}`;
    
    const formContent = `
        <div class="space-y-6">
            <!-- Peserta Info -->
            <div class="bg-gray-50 p-4 rounded-xl mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Nama Peserta</div>
                        <div class="font-bold text-primary">${peserta.nama}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">NIM</div>
                        <div class="font-medium">${peserta.nim}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Universitas</div>
                        <div class="font-medium">${peserta.universitas}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Periode Magang</div>
                        <div class="font-medium">${peserta.periode}</div>
                    </div>
                </div>
            </div>
            
            <!-- Form Upload -->
            <div>
                <h4 class="font-bold text-primary mb-4">Upload File Penilaian</h4>
                <div class="space-y-4"> 
                    <div>
                        <label class="block text-gray-700 mb-2">File Penilaian *</label>
                        <div id="dropZone" class="border-2 border-dashed border-accent rounded-xl p-8 text-center cursor-pointer hover:bg-gray-50 transition"
                             ondragover="event.preventDefault(); this.classList.add('border-primary', 'bg-blue-50');"
                             ondragleave="this.classList.remove('border-primary', 'bg-blue-50');"
                             ondrop="handleDrop(event)">
                            <i class='bx bx-cloud-upload text-5xl text-accent mb-4'></i>
                            <div class="font-bold text-primary mb-2">Seret dan lepas file di sini</div>
                            <div class="text-gray-600 mb-4">atau</div>
                            <button type="button" onclick="document.getElementById('fileInput').click()" 
                                    class="px-6 py-3 bg-primary text-white rounded-lg font-medium hover:bg-blue-800 transition">
                                <i class='bx bx-folder-open mr-2'></i> Pilih File
                            </button>
                            <div class="text-sm text-gray-500 mt-4">
                                Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG<br>
                                Ukuran maksimal: 10MB
                            </div>
                        </div>
                        <input type="file" id="fileInput" class="hidden" onchange="handleFileSelect(this)">
                        <div id="filePreview" class="mt-4 hidden">
                            <!-- Preview file akan muncul di sini -->
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <!-- Tombol Aksi -->
            <div class="flex gap-3 pt-4 border-t">
                <button onclick="processUpload(${peserta.id})" 
                        class="flex-1 bg-primary text-white py-3 rounded-lg font-bold hover:bg-blue-800 transition text-lg">
                    <i class='bx bx-upload'></i> UPLOAD FILE
                </button>
                <button onclick="closeUploadModal()" 
                        class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition">
                    Batal
                </button>
            </div>
            
            <input type="hidden" id="currentPesertaId" value="${peserta.id}">
        </div>
    `;
    
    document.getElementById('modalContent').innerHTML = formContent;
    document.getElementById('uploadModal').classList.remove('hidden');
}

// Edit file penilaian
function editFile(pesertaId) {
    const peserta = pesertaData.find(p => p.id === pesertaId);
    if (!peserta || !peserta.filePenilaian) return;
    
    uploadFile(pesertaId);
    document.getElementById('modalTitle').textContent = `Edit File Penilaian - ${peserta.nama}`;
    document.querySelector('button[onclick^="processUpload"]').innerHTML = '<i class="bx bx-save"></i> UPDATE FILE';
}

// Handle file select
function handleFileSelect(input) {
    const file = input.files[0];
    if (!file) return;
    
    handleFile(file);
}

// Handle drop
function handleDrop(event) {
    event.preventDefault();
    const dropZone = document.getElementById('dropZone');
    dropZone.classList.remove('border-primary', 'bg-blue-50');
    
    const file = event.dataTransfer.files[0];
    if (file) {
        handleFile(file);
    }
}

// Handle file
function handleFile(file) {
    // Validasi ukuran file
    if (file.size > 10 * 1024 * 1024) { // 10MB
        showNotification('Error', 'Ukuran file terlalu besar. Maksimal 10MB.', 'error');
        return;
    }
    
    // Validasi tipe file
    const allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'image/jpeg',
        'image/png'
    ];
    
    if (!allowedTypes.includes(file.type)) {
        showNotification('Error', 'Format file tidak didukung.', 'error');
        return;
    }
    
    // Tampilkan preview
    const previewDiv = document.getElementById('filePreview');
    const fileSize = (file.size / 1024 / 1024).toFixed(2);
    const fileIcon = getFileIcon(file.type);
    
    previewDiv.innerHTML = `
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class='bx ${fileIcon} text-2xl text-green-600'></i>
                    </div>
                    <div>
                        <div class="font-bold text-primary">${file.name}</div>
                        <div class="text-sm text-gray-600">${fileSize} MB • ${file.type}</div>
                    </div>
                </div>
                <button onclick="removeFile()" class="text-red-500 hover:text-red-700">
                    <i class='bx bx-trash text-xl'></i>
                </button>
            </div>
            <div class="mt-3">
                <div class="text-sm font-medium text-gray-700 mb-1">Progress Upload:</div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="uploadProgress" class="bg-green-500 h-2 rounded-full" style="width: 0%"></div>
                </div>
            </div>
        </div>
    `;
    
    previewDiv.classList.remove('hidden');
}

// Get file icon based on type
function getFileIcon(fileType) {
    if (fileType.includes('pdf')) return 'bx-file-pdf';
    if (fileType.includes('word') || fileType.includes('document')) return 'bx-file-doc';
    if (fileType.includes('excel') || fileType.includes('sheet')) return 'bx-file-xls';
    if (fileType.includes('image')) return 'bx-image';
    return 'bx-file';
}

// Remove selected file
function removeFile() {
    document.getElementById('fileInput').value = '';
    document.getElementById('filePreview').classList.add('hidden');
    document.getElementById('filePreview').innerHTML = '';
}

// Process upload
function processUpload(pesertaId) {
    const peserta = pesertaData.find(p => p.id === pesertaId);
    if (!peserta) return;
    
    const fileInput = document.getElementById('fileInput');
    if (!fileInput.files[0]) {
        showNotification('Peringatan', 'Pilih file terlebih dahulu.', 'warning');
        return;
    }
    
    const file = fileInput.files[0];
    const jenisFile = document.getElementById('jenisFile').value;
    const keterangan = document.getElementById('keterangan').value;
    
    // Simulate upload progress
    const progressBar = document.getElementById('uploadProgress');
    let progress = 0;
    const interval = setInterval(() => {
        progress += 10;
        progressBar.style.width = `${progress}%`;
        
        if (progress >= 100) {
            clearInterval(interval);
            
            // Update peserta data
            peserta.filePenilaian = {
                nama: file.name,
                ukuran: (file.size / 1024 / 1024).toFixed(1) + ' MB',
                tanggalUpload: new Date().toISOString().split('T')[0],
                url: URL.createObjectURL(file),
                jenis: jenisFile,
                keterangan: keterangan
            };
            peserta.sudahDinilai = true;
            
            // Close modal and update UI
            setTimeout(() => {
                closeUploadModal();
                loadPesertaTable();
                updateStats();
                showNotification('Berhasil!', `File penilaian untuk ${peserta.nama} telah diupload.`, 'success');
            }, 500);
        }
    }, 100);
}

// Preview file
function previewFile(pesertaId) {
    const peserta = pesertaData.find(p => p.id === pesertaId);
    if (!peserta || !peserta.filePenilaian) return;
    
    document.getElementById('previewTitle').textContent = `Preview - ${peserta.filePenilaian.nama}`;
    
    const previewContent = `
        <div class="space-y-6">
            <div class="bg-gray-50 p-4 rounded-xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class='bx ${getFileIconByExt(peserta.filePenilaian.nama)} text-3xl text-blue-600'></i>
                        </div>
                        <div>
                            <div class="font-bold text-primary text-lg">${peserta.filePenilaian.nama}</div>
                            <div class="text-sm text-gray-600">
                                ${peserta.filePenilaian.ukuran} • Upload: ${peserta.filePenilaian.tanggalUpload}
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="downloadFile(${peserta.id})" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-800 transition">
                            <i class='bx bx-download mr-2'></i> Download
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="border rounded-xl p-4">
                <div class="text-center">
                    <div class="text-gray-500 mb-4">
                        <i class='bx bx-file text-6xl mb-4"></i>
                        <div class="font-medium">File tidak dapat dipreview secara langsung</div>
                        <div class="text-sm">Silakan download file untuk melihat isinya</div>
                    </div>
                    <div class="text-sm text-gray-600">
                        File: ${peserta.filePenilaian.nama}<br>
                        Ukuran: ${peserta.filePenilaian.ukuran}<br>
                        Diupload: ${peserta.filePenilaian.tanggalUpload}
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3 pt-4 border-t">
                <button onclick="downloadFile(${peserta.id})" class="flex-1 bg-primary text-white py-3 rounded-lg font-bold hover:bg-blue-800 transition">
                    <i class='bx bx-download'></i> DOWNLOAD FILE
                </button>
                <button onclick="closePreviewModal()" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition">
                    Tutup
                </button>
            </div>
        </div>
    `;
    
    document.getElementById('previewContent').innerHTML = previewContent;
    document.getElementById('previewModal').classList.remove('hidden');
}

// Get file icon by extension
function getFileIconByExt(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    if (ext === 'pdf') return 'bx-file-pdf';
    if (['doc', 'docx'].includes(ext)) return 'bx-file-doc';
    if (['xls', 'xlsx'].includes(ext)) return 'bx-file-xls';
    if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) return 'bx-image';
    return 'bx-file';
}

// Download file
function downloadFile(pesertaId) {
    const peserta = pesertaData.find(p => p.id === pesertaId);
    if (!peserta || !peserta.filePenilaian) return;
    
    // Create temporary download link
    const link = document.createElement('a');
    link.href = peserta.filePenilaian.url;
    link.download = peserta.filePenilaian.nama;
    link.click();
    
    showNotification('Info', `Download ${peserta.filePenilaian.nama} dimulai.`, 'info');
}

// Delete file
function deleteFile(pesertaId) {
    const peserta = pesertaData.find(p => p.id === pesertaId);
    if (!peserta || !peserta.filePenilaian) return;
    
    if (confirm(`Apakah Anda yakin ingin menghapus file penilaian ${peserta.nama}?`)) {
        peserta.filePenilaian = null;
        peserta.sudahDinilai = false;
        loadPesertaTable();
        updateStats();
        showNotification('Berhasil', `File penilaian untuk ${peserta.nama} telah dihapus.`, 'success');
    }
}

// Close upload modal
function closeUploadModal() {
    document.getElementById('uploadModal').classList.add('hidden');
    removeFile(); // Reset file input
}

// Close preview modal
function closePreviewModal() {
    document.getElementById('previewModal').classList.add('hidden');
}

// Update stats
function updateStats() {
    const totalPeserta = pesertaData.length;
    const pending = pesertaData.filter(p => p.status === 'selesai' && !p.sudahDinilai).length;
    const completed = pesertaData.filter(p => p.sudahDinilai).length;
    const totalFiles = pesertaData.filter(p => p.filePenilaian).length;
    
    document.getElementById('pendingCount').textContent = pending;
    document.getElementById('completedCount').textContent = completed;
    document.getElementById('totalFiles').textContent = totalFiles;
}

// Download all files
function downloadAllFiles() {
    const files = pesertaData.filter(p => p.filePenilaian);
    
    if (files.length === 0) {
        showNotification('Info', 'Belum ada file penilaian untuk didownload', 'info');
        return;
    }
    
    if (confirm(`Download ${files.length} file penilaian?`)) {
        // Simulate batch download
        showNotification('Info', `Memulai download ${files.length} file...`, 'info');
        
        // In real implementation, this would download a zip file
        files.forEach((peserta, index) => {
            setTimeout(() => {
                downloadFile(peserta.id);
            }, index * 1000);
        });
    }
}

// Show notification
function showNotification(title, message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transform transition-transform duration-300 ${
        type === 'success' ? 'bg-green-500' :
        type === 'error' ? 'bg-red-500' :
        type === 'warning' ? 'bg-yellow-500' :
        'bg-blue-500'
    } text-white`;
    
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <i class='bx ${
                type === 'success' ? 'bx-check-circle' :
                type === 'error' ? 'bx-error' :
                type === 'warning' ? 'bx-alarm-exclamation' :
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

// Pagination functions
function nextPage() {
    const filteredData = filterPesertaData();
    const totalPages = Math.max(1, Math.ceil(filteredData.length / itemsPerPage));
    if (currentPage < totalPages) {
        currentPage++;
        loadPesertaTable();
    }
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        loadPesertaTable();
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadPesertaTable();
    updateStats();
    
    // Search as you type
    document.getElementById('searchPeserta').addEventListener('input', filterPeserta);
    document.getElementById('statusFilter').addEventListener('change', filterPeserta);
    
    // Close modal on ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeUploadModal();
            closePreviewModal();
        }
    });
});
</script>

<style>
/* Custom styles for upload zone */
#dropZone {
    transition: all 0.3s ease;
}

#dropZone.drag-over {
    border-color: var(--primary);
    background-color: rgba(33, 52, 72, 0.05);
}

/* Animation for modal */
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

#uploadModal > div,
#previewModal > div {
    animation: modalSlideIn 0.3s ease;
}

/* File icon colors */
.bx-file-pdf {
    color: #FF6B6B;
}

.bx-file-doc {
    color: #2B579A;
}

.bx-file-xls {
    color: #217346;
}

.bx-image {
    color: #FFA726;
}

/* Print styles */
@media print {
    .sidebar, .header, .menu-toggle, button {
        display: none !important;
    }
    
    .main-content {
        margin-left: 0 !important;
    }
    
    .content-wrapper {
        padding: 0 !important;
    }
}
</style>
@endsection