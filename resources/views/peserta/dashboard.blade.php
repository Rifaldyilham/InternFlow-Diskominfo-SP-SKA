@extends('layouts.peserta')

@section('title', 'Dashboard Peserta')

@section('content')

<!-- Status Magang -->
<div class="form-card">
    <h2 style="color: var(--primary); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <i class='bx bx-info-circle'></i> Status Magang Anda
    </h2>
    
    <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 30px;">
        <div>
            <h3 style="color: var(--primary); margin-bottom: 5px;">Bidang Informatika</h3>
            <p style="color: #666;">Mentor: Dr. Ahmad Fauzi, M.Kom.</p>
            <p style="color: #666; font-size: 0.9rem;">Periode: 1 Jan 2024 - 30 Mar 2024</p>
        </div>
        <div style="margin-left: auto;">
            <span class="status-badge status-active">AKTIF</span>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <div style="background: #f8fafc; padding: 20px; border-radius: 12px;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                <div style="width: 40px; height: 40px; background: rgba(33, 52, 72, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                    <i class='bx bx-file'></i>
                </div>
                <div>
                    <div style="font-weight: 600; color: var(--primary);">Pengajuan</div>
                    <div style="font-size: 0.9rem; color: #666;">Status</div>
                </div>
            </div>
            <span class="status-badge status-approved">DISETUJUI</span>
        </div>
        
    <div style="background: #f8fafc; padding: 20px; border-radius: 12px;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                <div style="width: 40px; height: 40px; background: rgba(113, 88, 226, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #7158e2;">
                    <i class='bx bx-file'></i>
                </div>
                <div>
                    <div style="font-weight: 600; color: var(--primary);">Penilaian</div>
                    <div style="font-size: 0.9rem; color: #666;">Status</div>
                </div>
            </div>
            <span class="status-badge status-pending">BELUM SELESAI</span>
        </div>

        <div style="background: #f8fafc; padding: 20px; border-radius: 12px;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                <div style="width: 40px; height: 40px; background: rgba(113, 88, 226, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #7158e2;">
                    <i class='bx bx-certification'></i>
                </div>
                <div>
                    <div style="font-weight: 600; color: var(--primary);">Sertifikat</div>
                    <div style="font-size: 0.9rem; color: #666;">Status</div>
                </div>
            </div>
            <span class="status-badge status-pending">BELUM SELESAI</span>
        </div>
    </div>
</div>
@endsection