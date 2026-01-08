@extends('layouts.peserta')

@section('title', 'Penilaian & Sertifikat')

@section('content')

<!-- File Penilaian dari Mentor -->
<div class="form-card mb-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-primary flex items-center gap-2">
            <i class='bx bx-file-pdf'></i> File Penilaian dari Mentor
        </h3>
        <span id="statusPenilaianBadge" class="status-badge" style="background: #fff9e6; color: #f1c40f;">
            <i class='bx bx-time'></i> Belum Tersedia
        </span>
    </div>
    
    <div id="penilaianFileSection" class="bg-yellow-50 p-6 rounded-xl border border-yellow-200 mb-6 hidden">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class='bx bx-file-pdf text-3xl text-yellow-600'></i>
                </div>
                <div>
                    <div id="penilaianFileName" class="font-bold text-primary text-lg">Penilaian_John_Doe.pdf</div>
                    <div id="penilaianFileInfo" class="text-gray-600 text-sm">2.4 MB • Diupload: 28 Mar 2024</div>
                    <div class="text-xs text-yellow-600 mt-1">
                        <i class='bx bx-info-circle'></i> File penilaian final dari mentor pembimbing
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                <button onclick="previewPenilaianFile()" class="px-4 py-2 bg-white border border-blue-500 text-blue-500 rounded-lg hover:bg-blue-50 transition">
                    <i class='bx bx-show mr-2'></i> Preview
                </button>
                <button onclick="downloadPenilaianFile()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-800 transition">
                    <i class='bx bx-download mr-2'></i> Unduh
                </button>
            </div>
        </div>
    </div>
    
    <div id="penilaianEmptyState" class="bg-gray-50 p-8 rounded-xl text-center">
        <i class='bx bx-file text-5xl text-gray-300 mb-4'></i>
        <h4 class="text-xl font-bold text-primary mb-2">File Penilaian Belum Tersedia</h4>
        <p class="text-gray-600 mb-6">File penilaian akan diupload oleh mentor setelah masa magang selesai dan proses penilaian final disetujui.</p>
        <div class="inline-block bg-white p-6 rounded-xl shadow-sm">
            <div class="text-left text-gray-600">
                <div class="font-bold text-primary mb-3">Tahapan Penilaian:</div>
                <ol class="list-decimal pl-5 space-y-2">
                    <li>Review dan penilaian final oleh mentor</li>
                    <li>File penilaian di Upload oleh mentor</li>
                    <li>File tersedia untuk diunduh</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Sertifikat Magang -->
<div class="form-card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-primary flex items-center gap-2">
            <i class='bx bx-certification'></i> Sertifikat Magang
        </h3>
        <span id="statusSertifikatBadge" class="status-badge" style="background: #e2e8f0; color: #666;">
            <i class='bx bx-time'></i> Belum Tersedia
        </span>
    </div>
    
    <div id="sertifikatFileSection" class="bg-green-50 p-6 rounded-xl border border-green-200 mb-6 hidden">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class='bx bx-certificate text-3xl text-green-600'></i>
                </div>
                <div>
                    <div id="sertifikatFileName" class="font-bold text-primary text-lg">Sertifikat_Magang_John_Doe.pdf</div>
                    <div id="sertifikatFileInfo" class="text-gray-600 text-sm">1.8 MB • Diterbitkan: 2 Apr 2024</div>
                    <div class="text-xs text-green-600 mt-1">
                        <i class='bx bx-info-circle'></i> Sertifikat resmi dari Diskominfo SP Surakarta
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                <button onclick="viewSertifikat()" class="px-4 py-2 bg-white border border-green-500 text-green-500 rounded-lg hover:bg-green-50 transition">
                    <i class='bx bx-show mr-2'></i> Lihat
                </button>
                <button onclick="downloadSertifikat()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class='bx bx-download mr-2'></i> Unduh Sertifikat
                </button>
                <button onclick="printSertifikat()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-800 transition">
                    <i class='bx bx-printer mr-2'></i> Print
                </button>
            </div>
        </div>
    </div>
    
    <div id="sertifikatEmptyState" class="bg-gray-50 p-8 rounded-xl text-center">
        <i class='bx bx-time-five text-5xl text-gray-300 mb-4'></i>
        <h4 class="text-xl font-bold text-primary mb-2">Sertifikat Belum Tersedia</h4>
        <p class="text-gray-600 mb-6">Sertifikat akan diterbitkan oleh bagian kepegawaian setelah proses penilaian selesai dan semua administrasi lengkap.</p>
        <div class="inline-block bg-white p-6 rounded-xl shadow-sm">
            <div class="text-left text-gray-600">
                <div class="font-bold text-primary mb-3">Persyaratan Sertifikat:</div>
                <ol class="list-decimal pl-5 space-y-2">
                    <li>Menyelesaikan seluruh masa magang (90 hari)</li>
                    <li>Memiliki file penilaian final dari mentor</li>
                    <li>Dokumen administrasi lengkap</li>
                    <li>Verifikasi oleh admin Diskominfo</li>
                    <li>Penerbitan oleh bagian kepegawaian</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview File -->
<div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 hidden">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-primary" id="previewTitle">Preview File</h3>
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
// Data status peserta
const pesertaData = {
    id: 1,
    nama: "John Doe",
    nim: "1234567890",
    universitas: "Universitas Sebelas Maret",
    prodi: "Teknik Informatika",
    periode: "1 Jan - 30 Mar 2024",
    statusMagang: "berjalan", // berjalan, selesai, belum
    progress: 85,
    hariTersisa: 13,
    tanggalSelesai: "2024-03-30",
    
    // File penilaian dari mentor
    penilaianMentor: {
        tersedia: false,
        nama: "Penilaian_John_Doe.pdf",
        ukuran: "2.4 MB",
        tanggalUpload: "2024-03-28",
        url: "#"
    },
    
    // File sertifikat dari kepegawaian
    sertifikat: {
        tersedia: false,
        nama: "Sertifikat_Magang_John_Doe.pdf",
        ukuran: "1.8 MB",
        tanggalTerbit: "2024-04-02",
        nomor: "DINKOM/2024/SERT/00456",
        url: "#"
    }
};

// Initialize page
function initializePage() {
    updateStatusMagang();
    updateFileSections();
    updateProgressSteps();
    checkAutoUpdate();
}

// Update status magang
function updateStatusMagang() {
    const magangStatus = document.getElementById('magangStatus');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const hariTersisa = document.getElementById('hariTersisa');
    
    if (pesertaData.statusMagang === 'berjalan') {
        magangStatus.innerHTML = '<i class="bx bx-time"></i> Sedang Berjalan';
        magangStatus.className = 'status-badge status-active';
        progressBar.style.width = `${pesertaData.progress}%`;
        progressText.textContent = `${pesertaData.progress}% Selesai`;
        hariTersisa.textContent = pesertaData.hariTersisa;
    } else if (pesertaData.statusMagang === 'selesai') {
        magangStatus.innerHTML = '<i class="bx bx-check-circle"></i> Selesai';
        magangStatus.className = 'status-badge status-approved';
        progressBar.style.width = '100%';
        progressBar.className = 'h-full bg-gradient-to-r from-green-400 to-green-600';
        progressText.textContent = '100% Selesai';
        hariTersisa.textContent = '0';
    } else {
        magangStatus.innerHTML = '<i class="bx bx-x-circle"></i> Belum Dimulai';
        magangStatus.className = 'status-badge status-rejected';
        progressBar.style.width = '0%';
        progressText.textContent = '0% Selesai';
    }
    
    // Update status text
    document.getElementById('statusPenilaian').textContent = pesertaData.penilaianMentor.tersedia ? 'Tersedia' : 'Menunggu';
    document.getElementById('statusSertifikat').textContent = pesertaData.sertifikat.tersedia ? 'Tersedia' : 'Belum';
}

// Update file sections
function updateFileSections() {
    const penilaianFileSection = document.getElementById('penilaianFileSection');
    const penilaianEmptyState = document.getElementById('penilaianEmptyState');
    const sertifikatFileSection = document.getElementById('sertifikatFileSection');
    const sertifikatEmptyState = document.getElementById('sertifikatEmptyState');
    const statusPenilaianBadge = document.getElementById('statusPenilaianBadge');
    const statusSertifikatBadge = document.getElementById('statusSertifikatBadge');
    
    // Update penilaian section
    if (pesertaData.penilaianMentor.tersedia) {
        penilaianFileSection.classList.remove('hidden');
        penilaianEmptyState.classList.add('hidden');
        statusPenilaianBadge.innerHTML = '<i class="bx bx-check-circle"></i> Tersedia';
        statusPenilaianBadge.style.background = '#e6fff3';
        statusPenilaianBadge.style.color = '#2ecc71';
        
        document.getElementById('penilaianFileName').textContent = pesertaData.penilaianMentor.nama;
        document.getElementById('penilaianFileInfo').textContent = 
            `${pesertaData.penilaianMentor.ukuran} • Diupload: ${pesertaData.penilaianMentor.tanggalUpload}`;
    } else {
        penilaianFileSection.classList.add('hidden');
        penilaianEmptyState.classList.remove('hidden');
        statusPenilaianBadge.innerHTML = '<i class="bx bx-time"></i> Belum Tersedia';
        statusPenilaianBadge.style.background = '#fff9e6';
        statusPenilaianBadge.style.color = '#f1c40f';
    }
    
    // Update sertifikat section
    if (pesertaData.sertifikat.tersedia) {
        sertifikatFileSection.classList.remove('hidden');
        sertifikatEmptyState.classList.add('hidden');
        statusSertifikatBadge.innerHTML = '<i class="bx bx-check-circle"></i> Tersedia';
        statusSertifikatBadge.style.background = '#e6fff3';
        statusSertifikatBadge.style.color = '#2ecc71';
        
        document.getElementById('sertifikatFileName').textContent = pesertaData.sertifikat.nama;
        document.getElementById('sertifikatFileInfo').textContent = 
            `${pesertaData.sertifikat.ukuran} • Diterbitkan: ${pesertaData.sertifikat.tanggalTerbit}`;
    } else {
        sertifikatFileSection.classList.add('hidden');
        sertifikatEmptyState.classList.remove('hidden');
        statusSertifikatBadge.innerHTML = '<i class="bx bx-time"></i> Belum Tersedia';
        statusSertifikatBadge.style.background = '#e2e8f0';
        statusSertifikatBadge.style.color = '#666';
    }
}

// Update progress steps
function updateProgressSteps() {
    const stepMentor = document.getElementById('stepMentor');
    const stepAdmin = document.getElementById('stepAdmin');
    const stepKepegawaian = document.getElementById('stepKepegawaian');
    
    if (pesertaData.statusMagang === 'selesai') {
        stepMentor.className = 'w-3 h-3 bg-green-500 rounded-full mx-auto';
        if (pesertaData.penilaianMentor.tersedia) {
            stepAdmin.className = 'w-3 h-3 bg-green-500 rounded-full mx-auto';
            if (pesertaData.sertifikat.tersedia) {
                stepKepegawaian.className = 'w-3 h-3 bg-green-500 rounded-full mx-auto';
            } else {
                stepKepegawaian.className = 'w-3 h-3 bg-yellow-500 rounded-full mx-auto';
            }
        } else {
            stepAdmin.className = 'w-3 h-3 bg-yellow-500 rounded-full mx-auto';
        }
    }
}

// Preview penilaian file
function previewPenilaianFile() {
    if (!pesertaData.penilaianMentor.tersedia) {
        showNotification('Info', 'File penilaian belum tersedia.', 'info');
        return;
    }
    
    document.getElementById('previewTitle').textContent = `Preview: ${pesertaData.penilaianMentor.nama}`;
    
    const previewContent = `
        <div class="space-y-6">
            <div class="bg-gray-50 p-4 rounded-xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class='bx bx-file-pdf text-3xl text-blue-600'></i>
                        </div>
                        <div>
                            <div class="font-bold text-primary text-lg">${pesertaData.penilaianMentor.nama}</div>
                            <div class="text-sm text-gray-600">
                                ${pesertaData.penilaianMentor.ukuran} • Upload: ${pesertaData.penilaianMentor.tanggalUpload}
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="downloadPenilaianFile()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-800 transition">
                            <i class='bx bx-download mr-2'></i> Download
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="border rounded-xl p-4">
                <div class="text-center">
                    <div class="text-gray-500 mb-4">
                        <i class='bx bx-file-pdf text-6xl mb-4"></i>
                        <div class="font-medium">File PDF tidak dapat dipreview secara langsung</div>
                        <div class="text-sm">Silakan download file untuk melihat isi penilaian</div>
                    </div>
                    <div class="text-sm text-gray-600">
                        File: ${pesertaData.penilaianMentor.nama}<br>
                        Ukuran: ${pesertaData.penilaianMentor.ukuran}<br>
                        Diupload: ${pesertaData.penilaianMentor.tanggalUpload}<br>
                        Oleh: Mentor Pembimbing
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3 pt-4 border-t">
                <button onclick="downloadPenilaianFile()" class="flex-1 bg-primary text-white py-3 rounded-lg font-bold hover:bg-blue-800 transition">
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

// Download penilaian file
function downloadPenilaianFile() {
    if (!pesertaData.penilaianMentor.tersedia) {
        showNotification('Info', 'File penilaian belum tersedia.', 'info');
        return;
    }
    
    // Simulate download
    showNotification('Info', `Memulai download ${pesertaData.penilaianMentor.nama}...`, 'info');
    
    // Create temporary download link
    const link = document.createElement('a');
    link.href = '#';
    link.download = pesertaData.penilaianMentor.nama;
    link.click();
    
    // Show success message after delay
    setTimeout(() => {
        showNotification('Berhasil!', `File ${pesertaData.penilaianMentor.nama} berhasil diunduh.`, 'success');
    }, 1000);
}

// View sertifikat
function viewSertifikat() {
    if (!pesertaData.sertifikat.tersedia) {
        showNotification('Info', 'Sertifikat belum tersedia.', 'info');
        return;
    }
    
    const previewContent = `
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-8 rounded-xl relative overflow-hidden border border-gray-300">
            <!-- Watermark -->
            <div class="absolute inset-0 flex items-center justify-center opacity-5">
                <div class="text-8xl font-black text-primary whitespace-nowrap">INTERNFLOW</div>
            </div>
            
            <!-- Certificate Content -->
            <div class="relative z-10 text-center">
                <div class="mb-8">
                    <div class="text-sm text-primary font-bold mb-2">
                        PEMERINTAH KOTA SURAKARTA<br>
                        DINAS KOMUNIKASI DAN INFORMATIKA
                    </div>
                </div>
                
                <div class="mb-8">
                    <div class="text-4xl font-black text-primary mb-2 tracking-wider">SERTIFIKAT</div>
                    <div class="text-lg text-gray-600 font-bold">Magang & Pelatihan Kerja</div>
                    <div class="h-1 w-20 bg-accent mx-auto my-4"></div>
                </div>
                
                <div class="mb-8">
                    <div class="text-3xl font-bold text-primary mb-2">${pesertaData.nama}</div>
                    <div class="text-gray-600">
                        ${pesertaData.universitas}<br>
                        Program Studi: ${pesertaData.prodi}
                    </div>
                </div>
                
                <div class="mb-8 max-w-2xl mx-auto">
                    <div class="text-gray-600 leading-relaxed">
                        Dengan ini dinyatakan telah menyelesaikan program Magang di<br>
                        <span class="font-bold text-primary">Dinas Komunikasi dan Informatika Kota Surakarta</span><br>
                        pada Bidang Informatika, selama periode<br>
                        <span class="font-bold text-primary">${pesertaData.periode}</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-12 mt-12">
                    <div>
                        <div class="h-px bg-gray-600 mb-4"></div>
                        <div class="font-bold text-primary">Dr. Ahmad Fauzi, M.Kom.</div>
                        <div class="text-gray-600 text-sm">Mentor Pembimbing</div>
                    </div>
                    <div>
                        <div class="h-px bg-gray-600 mb-4"></div>
                        <div class="font-bold text-primary">Kepala Diskominfo SP Surakarta</div>
                        <div class="text-gray-600 text-sm">Bagian Kepegawaian</div>
                    </div>
                </div>
                
                <!-- Certificate Number -->
                <div class="mt-8 text-xs text-gray-600">
                    No: ${pesertaData.sertifikat.nomor}
                </div>
            </div>
        </div>
        
        <div class="flex gap-3 mt-6">
            <button onclick="downloadSertifikat()" class="flex-1 bg-primary text-white py-3 rounded-lg font-bold hover:bg-blue-800 transition">
                <i class='bx bx-download'></i> UNDUH SERTIFIKAT
            </button>
            <button onclick="closePreviewModal()" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition">
                Tutup
            </button>
        </div>
    `;
    
    document.getElementById('previewTitle').textContent = 'Preview Sertifikat';
    document.getElementById('previewContent').innerHTML = previewContent;
    document.getElementById('previewModal').classList.remove('hidden');
}

// Download sertifikat
function downloadSertifikat() {
    if (!pesertaData.sertifikat.tersedia) {
        showNotification('Info', 'Sertifikat belum tersedia.', 'info');
        return;
    }
    
    // Simulate download
    showNotification('Info', `Memulai download ${pesertaData.sertifikat.nama}...`, 'info');
    
    // Create temporary download link
    const link = document.createElement('a');
    link.href = '#';
    link.download = pesertaData.sertifikat.nama;
    link.click();
    
    // Show success message after delay
    setTimeout(() => {
        showNotification('Berhasil!', `Sertifikat ${pesertaData.sertifikat.nama} berhasil diunduh.`, 'success');
    }, 1000);
}

// Print sertifikat
function printSertifikat() {
    if (!pesertaData.sertifikat.tersedia) {
        showNotification('Info', 'Sertifikat belum tersedia.', 'info');
        return;
    }
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Sertifikat ${pesertaData.nama}</title>
                <style>
                    @page { margin: 0.5in; }
                    body { 
                        font-family: 'Times New Roman', Times, serif;
                        text-align: center;
                    }
                    .certificate-container {
                        padding: 50px;
                        position: relative;
                    }
                    .watermark {
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        font-size: 120px;
                        opacity: 0.05;
                        color: #213448;
                        font-weight: 900;
                        white-space: nowrap;
                    }
                    .header {
                        margin-bottom: 30px;
                    }
                    .title {
                        font-size: 40px;
                        font-weight: 900;
                        color: #213448;
                        margin: 20px 0;
                        letter-spacing: 4px;
                    }
                    .subtitle {
                        font-size: 18px;
                        color: #666;
                        font-weight: bold;
                        margin-bottom: 30px;
                    }
                    .name {
                        font-size: 36px;
                        font-weight: bold;
                        color: #213448;
                        margin: 20px 0;
                    }
                    .info {
                        font-size: 16px;
                        color: #666;
                        line-height: 1.6;
                        margin: 20px 0;
                    }
                    .separator {
                        width: 100px;
                        height: 2px;
                        background: #94B4C1;
                        margin: 20px auto;
                    }
                    .signature {
                        display: flex;
                        justify-content: space-between;
                        margin-top: 80px;
                        padding: 0 50px;
                    }
                    .signature-item {
                        text-align: center;
                    }
                    .signature-line {
                        width: 200px;
                        height: 1px;
                        background: #333;
                        margin: 0 auto 10px;
                    }
                    .certificate-number {
                        margin-top: 30px;
                        font-size: 12px;
                        color: #666;
                    }
                </style>
            </head>
            <body>
                <div class="certificate-container">
                    <div class="watermark">INTERNFLOW</div>
                    
                    <div class="header">
                        <div style="font-size: 14px; font-weight: bold; color: #213448;">
                            PEMERINTAH KOTA SURAKARTA<br>
                            DINAS KOMUNIKASI DAN INFORMATIKA
                        </div>
                    </div>
                    
                    <div class="title">SERTIFIKAT</div>
                    <div class="subtitle">Magang & Pelatihan Kerja</div>
                    <div class="separator"></div>
                    
                    <div class="name">${pesertaData.nama}</div>
                    <div style="color: #666;">
                        ${pesertaData.universitas}<br>
                        Program Studi: ${pesertaData.prodi}
                    </div>
                    
                    <div class="info" style="max-width: 600px; margin: 30px auto;">
                        Dengan ini dinyatakan telah menyelesaikan program Magang di<br>
                        <b>Dinas Komunikasi dan Informatika Kota Surakarta</b><br>
                        pada Bidang Informatika, selama periode<br>
                        <b>${pesertaData.periode}</b>
                    </div>
                    
                    <div class="signature">
                        <div class="signature-item">
                            <div class="signature-line"></div>
                            <div style="font-weight: bold; color: #213448;">Dr. Ahmad Fauzi, M.Kom.</div>
                            <div style="color: #666; font-size: 14px;">Mentor Pembimbing</div>
                        </div>
                        <div class="signature-item">
                            <div class="signature-line"></div>
                            <div style="font-weight: bold; color: #213448;">Kepala Diskominfo SP Surakarta</div>
                            <div style="color: #666; font-size: 14px;">Bagian Kepegawaian</div>
                        </div>
                    </div>
                    
                    <div class="certificate-number">
                        No: ${pesertaData.sertifikat.nomor}
                    </div>
                </div>
                
                <script>
                    window.onload = function() {
                        window.print();
                    };
                <\/script>
            </body>
        </html>
    `);
    printWindow.document.close();
}

// Close preview modal
function closePreviewModal() {
    document.getElementById('previewModal').classList.add('hidden');
}

// Check for auto-update (simulating real-time updates)
function checkAutoUpdate() {
    // Simulate checking for new files
    setInterval(() => {
        // In a real app, this would make an API call
        const today = new Date().toISOString().split('T')[0];
        
        // Check if magang is finished
        if (pesertaData.statusMagang === 'berjalan' && today > pesertaData.tanggalSelesai) {
            pesertaData.statusMagang = 'selesai';
            pesertaData.progress = 100;
            pesertaData.hariTersisa = 0;
            updateStatusMagang();
            
            // Simulate mentor upload after 2 days
            if (!pesertaData.penilaianMentor.tersedia) {
                setTimeout(() => {
                    pesertaData.penilaianMentor.tersedia = true;
                    updateFileSections();
                    updateProgressSteps();
                    showNotification('Info', 'File penilaian baru tersedia dari mentor!', 'info');
                }, 2000);
            }
            
            // Simulate sertifikat after 5 days
            if (!pesertaData.sertifikat.tersedia) {
                setTimeout(() => {
                    pesertaData.sertifikat.tersedia = true;
                    updateFileSections();
                    updateProgressSteps();
                    showNotification('Info', 'Sertifikat magang sudah tersedia!', 'info');
                }, 5000);
            }
        }
    }, 30000); // Check every 30 seconds
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

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializePage();
});
</script>

<style>
/* Custom styles for file sections */
#penilaianFileSection, #sertifikatFileSection {
    transition: all 0.3s ease;
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

#previewModal > div {
    animation: modalSlideIn 0.3s ease;
}

/* Print styles for certificate */
@media print {
    .sidebar, .header, .menu-toggle, button, .form-card:not(:last-child) {
        display: none !important;
    }
    
    .main-content {
        margin-left: 0 !important;
    }
    
    .content-wrapper {
        padding: 0 !important;
    }
    
    #previewModal {
        position: static !important;
        background: transparent !important;
        display: block !important;
        width: 100% !important;
        height: auto !important;
        max-width: none !important;
        max-height: none !important;
    }
    
    #previewModal > div {
        box-shadow: none !important;
        animation: none !important;
        margin: 0 !important;
    }
}
</style>
@endsection