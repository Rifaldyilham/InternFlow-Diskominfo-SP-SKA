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
                <button onclick="downloadPenilaianFile()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-800 transition">
                    <i class='bx bx-download mr-2'></i> Unduh Penilaian
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
                <button onclick="downloadSertifikat()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class='bx bx-download mr-2'></i> Unduh Sertifikat
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

<script>
const API_CONFIG = {
    penilaianDetail: '/api/peserta/penilaian',
    penilaianDownload: '/api/peserta/penilaian/download',
    sertifikatDetail: '/api/peserta/sertifikat',
    sertifikatDownload: '/api/peserta/sertifikat/download'
};

const pesertaData = {
    penilaianMentor: {
        tersedia: false,
        nama: '-',
        ukuran: '-',
        tanggalUpload: '-',
        url: null
    },
    sertifikat: {
        tersedia: false,
        nama: '-',
        ukuran: '-',
        tanggalTerbit: '-',
        nomor: '-',
        url: null
    }
};

function formatDateOnly(value) {
    if (!value) return '-';
    return String(value).split(' ')[0].split('T')[0];
}

async function initializePage() {
    await loadPenilaian();
    await loadSertifikat();
    updateFileSections();
}

async function loadPenilaian() {
    try {
        const response = await fetch(API_CONFIG.penilaianDetail, {
            headers: { 'Accept': 'application/json' }
        });

        const data = await response.json();

        if (response.ok && data?.data?.tersedia) {
            pesertaData.penilaianMentor.tersedia = true;
            pesertaData.penilaianMentor.nama = data.data.nama || '-';
            pesertaData.penilaianMentor.ukuran = data.data.ukuran || '-';
            pesertaData.penilaianMentor.tanggalUpload = data.data.tanggal_upload || '-';
            pesertaData.penilaianMentor.url = data.data.url || API_CONFIG.penilaianDownload;
        } else {
            pesertaData.penilaianMentor.tersedia = false;
        }
    } catch (error) {
        console.error('Error loading penilaian:', error);
        pesertaData.penilaianMentor.tersedia = false;
    }
}

async function loadSertifikat() {
    try {
        const response = await fetch(API_CONFIG.sertifikatDetail, {
            headers: { 'Accept': 'application/json' }
        });

        const data = await response.json();

        if (response.ok && data?.data?.tersedia) {
            pesertaData.sertifikat.tersedia = true;
            pesertaData.sertifikat.nama = data.data.nama || '-';
            pesertaData.sertifikat.ukuran = data.data.ukuran || '-';
            pesertaData.sertifikat.tanggalTerbit = data.data.tanggal_terbit || '-';
            pesertaData.sertifikat.nomor = data.data.nomor_sertifikat || '-';
            pesertaData.sertifikat.url = data.data.url || API_CONFIG.sertifikatDownload;
        } else {
            pesertaData.sertifikat.tersedia = false;
        }
    } catch (error) {
        console.error('Error loading sertifikat:', error);
        pesertaData.sertifikat.tersedia = false;
    }
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
            `${pesertaData.sertifikat.ukuran} • Diterbitkan: ${formatDateOnly(pesertaData.sertifikat.tanggalTerbit)}`;
    } else {
        sertifikatFileSection.classList.add('hidden');
        sertifikatEmptyState.classList.remove('hidden');
        statusSertifikatBadge.innerHTML = '<i class="bx bx-time"></i> Belum Tersedia';
        statusSertifikatBadge.style.background = '#e2e8f0';
        statusSertifikatBadge.style.color = '#666';
    }
}

// Download penilaian file
function downloadPenilaianFile() {
    if (!pesertaData.penilaianMentor.tersedia) {
        showNotification('Info', 'File penilaian belum tersedia.', 'info');
        return;
    }

    const url = pesertaData.penilaianMentor.url || API_CONFIG.penilaianDownload;
    window.open(url, '_blank');
}

// Download sertifikat
function downloadSertifikat() {
    if (!pesertaData.sertifikat.tersedia) {
        showNotification('Info', 'Sertifikat belum tersedia.', 'info');
        return;
    }

    const url = pesertaData.sertifikat.url || API_CONFIG.sertifikatDownload;
    window.open(url, '_blank');
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

</style>
@endsection
