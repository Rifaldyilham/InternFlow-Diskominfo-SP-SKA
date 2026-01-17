@extends('layouts.admin-bidang')

@section('title', 'Dashboard Admin Bidang')

@section('content')

<!-- Statistik Bidang -->
<div class="stats-grid">
    <div class="stat-card border-blue">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="totalPeserta">0</div>
                <div class="stat-label">Total Peserta di Bidang</div>
                <div class="text-xs text-gray-600 mt-1">
                    <span id="pesertaAktif">0</span> aktif
                </div>
            </div>
            <div class="stat-icon blue">
                <i class='bx bx-user-circle'></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card border-teal">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="totalMentor">0</div>
                <div class="stat-label">Jumlah Mentor</div>
                <div class="text-xs text-gray-600 mt-1">
                    <span id="mentorAktif">0</span> aktif
                </div>
            </div>
            <div class="stat-icon teal">
                <i class='bx bx-group'></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card border-orange">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="kuotaTersedia">0</div>
                <div class="stat-label">Kuota Tersedia</div>
                <div class="text-xs text-gray-600 mt-1">
                    dari <span id="kuotaMaksimal">0</span> total kapasitas
                </div>
            </div>
            <div class="stat-icon orange">
                <i class='bx bx-pie-chart-alt'></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card border-purple">
        <div class="stat-header">
            <div>
                <div class="stat-value" id="needPlacement">0</div>
                <div class="stat-label">Perlu Penempatan</div>
                <div class="text-xs text-gray-600 mt-1">
                    peserta belum punya mentor
                </div>
            </div>
            <div class="stat-icon purple">
                <i class='bx bx-user-plus'></i>
            </div>
        </div>
    </div>
</div>




<script>
// Data untuk dashboard
let dashboardData = {
    bidang: {
        id: 1,
        nama: 'Informatika',
        kuota_maksimal: 15,
        peserta_aktif: 12,
        peserta_selesai: 3,
        kuota_tersedia: 0
    },
    mentor: [
        { id: 1, nama: 'Dr. Ahmad Fauzi, M.Kom.', status: 'active', bimbingan: 5, kapasitas: 6 },
        { id: 2, nama: 'Dra. Siti Rahma, M.Si.', status: 'active', bimbingan: 4, kapasitas: 5 },
        { id: 3, nama: 'Ir. Bambang Sudarsono, M.T.', status: 'active', bimbingan: 3, kapasitas: 4 }
    ],
    peserta: [
        { id: 1, nama: 'Rina Dewi', nim: 'M0521001', universitas: 'UNS', 
          prodi: 'Teknik Informatika', tanggal_masuk: '2024-03-15', status: 'assigned', mentor_id: 1 },
        { id: 2, nama: 'Ahmad Fauzi', nim: 'M0521002', universitas: 'UGM', 
          prodi: 'Sistem Informasi', tanggal_masuk: '2024-03-14', status: 'unassigned', mentor_id: null },
        { id: 3, nama: 'Siti Nurhaliza', nim: 'M0521003', universitas: 'UNDIP', 
          prodi: 'Statistika', tanggal_masuk: '2024-03-13', status: 'assigned', mentor_id: 1 },
        { id: 4, nama: 'Bambang Pamungkas', nim: 'M0521004', universitas: 'ITB', 
          prodi: 'Teknik Komputer', tanggal_masuk: '2024-03-12', status: 'unassigned', mentor_id: null },
        { id: 5, nama: 'Dewi Lestari', nim: 'M0521005', universitas: 'UI', 
          prodi: 'Ilmu Komputer', tanggal_masuk: '2024-03-11', status: 'assigned', mentor_id: 2 },
        { id: 6, nama: 'Rizky Pratama', nim: 'M0521006', universitas: 'UNS', 
          prodi: 'Teknik Informatika', tanggal_masuk: '2024-03-10', status: 'assigned', mentor_id: 2 },
        { id: 7, nama: 'Maya Sari', nim: 'M0521007', universitas: 'UGM', 
          prodi: 'Sistem Informasi', tanggal_masuk: '2024-03-09', status: 'assigned', mentor_id: 3 },
        { id: 8, nama: 'Joko Santoso', nim: 'M0521008', universitas: 'UNDIP', 
          prodi: 'Statistika', tanggal_masuk: '2024-03-08', status: 'assigned', mentor_id: 3 }
    ]
};

// Fungsi inisialisasi dashboard
function initDashboard() {
    // Hitung statistik
    const pesertaPerluPenempatan = dashboardData.peserta.filter(p => p.status === 'unassigned').length;
    const mentorAktif = dashboardData.mentor.filter(m => m.status === 'active').length;
    const kuotaTersedia = dashboardData.bidang.kuota_maksimal - dashboardData.bidang.peserta_aktif;
    const pesertaDitempatkan = dashboardData.peserta.filter(p => p.status === 'assigned').length;
    const totalBimbingan = dashboardData.mentor.reduce((sum, m) => sum + m.bimbingan, 0);
    const rataPesertaPerMentor = mentorAktif > 0 ? (totalBimbingan / mentorAktif).toFixed(1) : 0;
    
    // Update stat cards
    document.getElementById('totalPeserta').textContent = dashboardData.peserta.length;
    document.getElementById('pesertaAktif').textContent = dashboardData.bidang.peserta_aktif;
    document.getElementById('totalMentor').textContent = dashboardData.mentor.length;
    document.getElementById('mentorAktif').textContent = mentorAktif;
    document.getElementById('kuotaTersedia').textContent = kuotaTersedia;
    document.getElementById('kuotaMaksimal').textContent = dashboardData.bidang.kuota_maksimal;
    document.getElementById('needPlacement').textContent = pesertaPerluPenempatan;
    
    // Update ringkasan status
    document.getElementById('pesertaDitempatkan').textContent = `${pesertaDitempatkan} peserta`;
    document.getElementById('pesertaBelumDitempatkan').textContent = `${pesertaPerluPenempatan} peserta`;
    document.getElementById('rataPesertaPerMentor').textContent = `${rataPesertaPerMentor} peserta`;
    
    // Render distribusi chart
    renderDistribusiChart();
}

// Render chart distribusi
function renderDistribusiChart() {
    const container = document.getElementById('distribusiChart');
    
    // Filter mentor aktif saja
    const mentorAktif = dashboardData.mentor.filter(m => m.status === 'active');
    
    if (mentorAktif.length === 0) {
        container.innerHTML = `
            <div class="text-center py-6">
                <i class='bx bx-user-x text-3xl text-gray-300 mb-3'></i>
                <p class="text-gray-500">Belum ada mentor aktif</p>
            </div>
        `;
        return;
    }
    
    // Hitung distribusi
    const distribusi = {};
    mentorAktif.forEach(mentor => {
        // Potong nama jika terlalu panjang
        const namaSingkat = mentor.nama.length > 20 ? mentor.nama.substring(0, 20) + '...' : mentor.nama;
        distribusi[namaSingkat] = mentor.bimbingan;
    });
    
    const chartData = Object.entries(distribusi);
    const max = Math.max(...Object.values(distribusi));
    
    let html = '<div class="space-y-3">';
    chartData.forEach(([nama, jumlah]) => {
        const persen = max > 0 ? (jumlah / max) * 100 : 0;
        
        html += `
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium text-gray-700 truncate">${nama}</span>
                    <span class="font-bold text-primary">${jumlah}</span>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 rounded-full transition-all duration-1000" 
                         style="width: ${persen}%"></div>
                </div>
            </div>
        `;
    });
    html += '</div>';
    
    container.innerHTML = html;
}

// Fungsi notifikasi (untuk demo)
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <i class='bx bx-${type === 'success' ? 'check-circle' : type === 'error' ? 'error-circle' : 'info-circle'}'></i>
            <div>
                <div class="font-bold">${type === 'success' ? 'Berhasil' : type === 'error' ? 'Error' : 'Info'}</div>
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

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    initDashboard();
    
    // Simulasi update data real-time (untuk demo)
    setInterval(() => {
        // Random update untuk demo statistik
        if (Math.random() > 0.7) {
            const fluctuation = Math.floor(Math.random() * 3) - 1; // -1, 0, atau 1
            const currentValue = parseInt(document.getElementById('needPlacement').textContent);
            const newValue = Math.max(0, currentValue + fluctuation);
            document.getElementById('needPlacement').textContent = newValue;
            
            // Update ringkasan status
            const totalPeserta = parseInt(document.getElementById('totalPeserta').textContent);
            const pesertaDitempatkan = totalPeserta - newValue;
            document.getElementById('pesertaDitempatkan').textContent = `${pesertaDitempatkan} peserta`;
            document.getElementById('pesertaBelumDitempatkan').textContent = `${newValue} peserta`;
        }
    }, 10000);
});
</script>

<style>
.border-blue { border-color: #3b82f6; }
.border-teal { border-color: #14b8a6; }
.border-orange { border-color: #f59e0b; }
.border-purple { border-color: #8b5cf6; }

.stat-icon.blue { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
.stat-icon.teal { background: rgba(20, 184, 166, 0.1); color: #14b8a6; }
.stat-icon.orange { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
.stat-icon.purple { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }

/* Responsive adjustments */
@media (max-width: 768px) {
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