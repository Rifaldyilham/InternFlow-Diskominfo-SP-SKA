@extends('layouts.peserta')

@section('title', 'Dashboard Peserta')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/peserta/peserta.css') }}">
@endsection

@section('content')

<!-- Status Magang -->
<div class="form-card">
    <h2 style="color: var(--primary); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <i class='bx bx-info-circle'></i> Status Magang Anda
    </h2>
    
    <!-- Loading State -->
    <div id="loadingState" style="text-align: center; padding: 40px 0;">
        <i class='bx bx-loader-circle bx-spin' style="font-size: 3rem; color: var(--primary);"></i>
        <div style="margin-top: 15px; color: #666;">Memuat data magang...</div>
    </div>
    
    <!-- Empty State (Belum Ada Pengajuan) -->
    <div id="emptyState" class="hidden">
        <div style="background: #f8fafc; padding: 40px; border-radius: 16px; text-align: center; border: 2px dashed #ddd;">
            <div style="font-size: 4rem; color: #ccc; margin-bottom: 20px;">📋</div>
            <h3 style="color: var(--primary); margin-bottom: 10px;">Belum Ada Pengajuan Magang</h3>
            <p style="color: #666; margin-bottom: 25px; max-width: 500px; margin-left: auto; margin-right: auto;">
                Anda belum memiliki pengajuan magang. Mulai dengan mengajukan permohonan magang untuk mengikuti program magang di Diskominfo SP Surakarta.
            </p>
            <a href="{{ route('peserta.pendaftaran') }}" class="btn btn-primary" style="padding: 12px 30px; font-size: 1rem;">
                <i class='bx bx-plus'></i> Ajukan Magang
            </a>
        </div>
    </div>
    
    <!-- Active Magang State -->
    <div id="activeMagangState" class="hidden">
        <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 30px; flex-wrap: wrap;">
            <div style="flex-grow: 1;">
                <h3 style="color: var(--primary); margin-bottom: 5px;" id="bidangNama">-</h3>
                <p style="color: #666;" id="mentorInfo">Mentor: -</p>
                <p style="color: #666; font-size: 0.9rem;" id="periodeInfo">Periode: -</p>
            </div>
            <div>
                <span id="statusMagangBadge" class="status-badge">-</span>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div class="dashboard-stat-card">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                    <div class="stat-icon-box" style="background: rgba(33, 52, 72, 0.1); color: var(--primary);">
                        <i class='bx bx-file'></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary);">Pengajuan</div>
                        <div style="font-size: 0.9rem; color: #666;">Status</div>
                    </div>
                </div>
                <span id="pengajuanStatusBadge" class="status-badge status-pending">-</span>
            </div>

            <div class="dashboard-stat-card">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                    <div class="stat-icon-box" style="background: rgba(113, 88, 226, 0.1); color: #7158e2;">
                        <i class='bx bx-certification'></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary);">Sertifikat</div>
                        <div style="font-size: 0.9rem; color: #666;">Status</div>
                    </div>
                </div>
                <span id="sertifikatStatusBadge" class="status-badge status-pending">-</span>
            </div>

            <div class="dashboard-stat-card">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                    <div class="stat-icon-box" style="background: rgba(46, 204, 113, 0.1); color: #2ecc71;">
                        <i class='bx bx-calendar'></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary);">Progress</div>
                        <div style="font-size: 0.9rem; color: #666;">Hari Tersisa</div>
                    </div>
                </div>
                <div>
                    <div style="height: 6px; background: #e2e8f0; border-radius: 3px; margin-bottom: 5px; overflow: hidden;">
                        <div id="progressBar" style="width: 0%; height: 100%; background: #2ecc71; transition: width 0.5s ease;"></div>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: #666;">
                        <span id="progressText">0% Selesai</span>
                        <span id="hariTersisaText">- hari</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Sertifikat -->
<div id="sertifikatProgressSection" class="form-card hidden">
    <h2 style="color: var(--primary); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <i class='bx bx-time'></i> Progress Sertifikat
    </h2>
    
    <div style="background: #f8fafc; padding: 25px; border-radius: 12px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
            <div>
                <div style="font-weight: 600; color: var(--primary); margin-bottom: 5px;">Proses Penerbitan Sertifikat</div>
                <div style="color: #666; font-size: 0.9rem;" id="sertifikatProgressText">-</div>
            </div>
            <div id="sertifikatStepBadge" class="status-badge">-</div>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px;">
            <div class="progress-step" id="step1">
                <div class="step-circle">1</div>
                <div class="step-label">Verifikasi Mentor</div>
                <div class="step-description">Penilaian mentor</div>
            </div>
            
            <div class="progress-step" id="step2">
                <div class="step-circle">2</div>
                <div class="step-label">Admin Bidang</div>
                <div class="step-description">Konfirmasi berkas</div>
            </div>
            
            <div class="progress-step" id="step3">
                <div class="step-circle">3</div>
                <div class="step-label">Kepegawaian</div>
                <div class="step-description">Terbit sertifikat</div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div id="quickActionsSection" class="form-card hidden">
    <h2 style="color: var(--primary); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <i class='bx bx-bolt-circle'></i> Tindakan Cepat
    </h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <a href="{{ route('peserta.logbook') }}" class="quick-action-card">
            <div class="quick-action-icon" style="background: rgba(33, 52, 72, 0.1);">
                <i class='bx bx-book' style="color: var(--primary);"></i>
            </div>
            <div>
                <div style="font-weight: 600; color: var(--primary);">Logbook Harian</div>
                <div style="font-size: 0.85rem; color: #666;">Isi kegiatan hari ini</div>
            </div>
        </a>
        
        <a href="{{ route('peserta.absensi') }}" class="quick-action-card">
            <div class="quick-action-icon" style="background: rgba(52, 152, 219, 0.1);">
                <i class='bx bx-calendar-check' style="color: #3498db;"></i>
            </div>
            <div>
                <div style="font-weight: 600; color: var(--primary);">Absensi</div>
                <div style="font-size: 0.85rem; color: #666;">Absen masuk/pulang</div>
            </div>
        </a>
        
        <a href="{{ route('peserta.penilaian-sertifikat') }}" class="quick-action-card">
            <div class="quick-action-icon" style="background: rgba(46, 204, 113, 0.1);">
                <i class='bx bx-certification' style="color: #2ecc71;"></i>
            </div>
            <div>
                <div style="font-weight: 600; color: var(--primary);">Sertifikat</div>
                <div style="font-size: 0.85rem; color: #666;">Lihat status sertifikat</div>
            </div>
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
});

// Load dashboard data
async function loadDashboardData() {
    try {
        const response = await fetch('/api/peserta/dashboard', {
            headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) throw new Error('Gagal memuat data');

        const data = await response.json();

        if (data.hasPengajuan) {
            renderDashboardData(data.pengajuan);
        } else {
            simulateEmptyState();
        }
        
    } catch (error) {
        console.error('Error loading dashboard:', error);
        simulateEmptyState();
    }
}

// Simulasi empty state (belum ada pengajuan)
function simulateEmptyState() {
    // Hide loading
    document.getElementById('loadingState').style.display = 'none';
    
    // Show empty state
    document.getElementById('emptyState').classList.remove('hidden');
}

// Render dashboard dengan status verifikasi
function renderDashboardData(pengajuan) {
    // Hide loading state
    document.getElementById('loadingState').style.display = 'none';
    
    // Show active magang state
    const activeMagangState = document.getElementById('activeMagangState');
    activeMagangState.classList.remove('hidden');
    
    // Update pengajuan status badge
    const pengajuanStatusBadge = document.getElementById('pengajuanStatusBadge');
    const statusConfig = getStatusConfig(pengajuan.status);
    pengajuanStatusBadge.textContent = statusConfig.text;
    pengajuanStatusBadge.className = `status-badge ${statusConfig.class}`;
    
    // Show quick actions
    document.getElementById('quickActionsSection').classList.remove('hidden');
}

// Get status configuration
function getStatusConfig(status) {
    const configs = {
        'pending': { text: 'MENUNGGU VERIFIKASI', class: 'status-pending' },
        'terverifikasi': { text: 'TERVERIFIKASI', class: 'status-approved' },
        'ditolak': { text: 'DITOLAK', class: 'status-rejected' }
    };
    return configs[status] || { text: 'MENUNGGU VERIFIKASI', class: 'status-pending' };
}

// Helper function to format date
function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
}

// CSS utility class
const style = document.createElement('style');
style.textContent = `
    .hidden { display: none !important; }
    
    .dashboard-stat-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.3s ease;
    }
    
    .dashboard-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .stat-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    
    .progress-step {
        text-align: center;
    }
    
    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #666;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin: 0 auto 10px;
        transition: all 0.3s ease;
    }
    
    .step-circle.step-completed {
        background: #2ecc71;
        color: white;
    }
    
    .step-circle.step-active {
        background: var(--primary);
        color: white;
        box-shadow: 0 0 0 4px rgba(33, 52, 72, 0.2);
    }
    
    .step-label {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 5px;
    }
    
    .step-description {
        font-size: 0.85rem;
        color: #666;
    }
    
    .quick-action-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .quick-action-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: var(--primary);
    }
    
    .quick-action-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .status-review {
        background: rgba(52, 152, 219, 0.1);
        color: #2980b9;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
    }
    
    .btn-primary {
        background: var(--primary);
        color: white;
    }
    
    .btn-primary:hover {
        background: #1a2938;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(33, 52, 72, 0.2);
    }
    
    .btn-secondary {
        background: #f8f9fa;
        color: #495057;
        border: 1px solid #dee2e6;
    }
    
    .btn-secondary:hover {
        background: #e9ecef;
    }
`;
document.head.appendChild(style);
</script>
@endsection