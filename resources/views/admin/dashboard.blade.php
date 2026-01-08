@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('subtitle', 'Overview Sistem Monitoring Magang')

@section('content')
<div class="stats-grid">
    <!-- Card 1 -->
    <div class="stat-card border-blue">
        <div class="stat-header">
            <div>
                <div class="stat-value">13</div>
                <div class="stat-label">Total Peserta Magang</div>
                <div class="stat-change positive">
                    <i class='bx bx-up-arrow-alt'></i> 12% dari bulan lalu
                </div>
            </div>
            <div class="stat-icon blue">
                <i class='bx bx-user'></i>
            </div>
        </div>
    </div>
    
    <!-- Card 2 -->
    <div class="stat-card border-green">
        <div class="stat-header">
            <div>
                <div class="stat-value">24</div>
                <div class="stat-label">Pendaftar Baru</div>
                <div class="stat-change positive">
                    <i class='bx bx-up-arrow-alt'></i> 5 menunggu verifikasi
                </div>
            </div>
            <div class="stat-icon green">
                <i class='bx bx-user-plus'></i>
            </div>
        </div>
    </div>
    
    <!-- Card 3 -->
    <div class="stat-card border-orange">
        <div class="stat-header">
            <div>
                <div class="stat-value">4</div>
                <div class="stat-label">Bidang Aktif</div>
                <div class="stat-change positive">
                    <i class='bx bx-check'></i> Semua beroperasi
                </div>
            </div>
            <div class="stat-icon orange">
                <i class='bx bx-briefcase'></i>
            </div>
        </div>
    </div>
    
    <!-- Card 4 -->
    <div class="stat-card border-purple">
        <div class="stat-header">
            <div>
                <div class="stat-value">10</div>
                <div class="stat-label">Mentor Terdaftar</div>
                <div class="stat-change negative">
                    <i class='bx bx-down-arrow-alt'></i> 2 perlu penambahan
                </div>
            </div>
            <div class="stat-icon purple">
                <i class='bx bx-user-voice'></i>
            </div>
        </div>
    </div>
</div>


<!-- Recent Pendaftar -->
<div class="table-container">
    <div class="table-header">
        <h3>Pendaftar Terbaru</h3>
        <div class="table-actions">
            <button class="btn btn-secondary">
                <i class='bx bx-refresh'></i> Refresh
            </button>
            <button class="btn btn-primary">
                <i class='bx bx-download'></i> Export
            </button>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Universitas</th>
                <th>Bidang Minat</th>
                <th>Tanggal Daftar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div class="avatar" style="width: 35px; height: 35px; font-size: 0.9rem;">RD</div>
                        <div>
                            <div style="font-weight: 600;">Rizky Darmawan</div>
                            <div style="font-size: 0.8rem; color: #888;">rizky@uns.ac.id</div>
                        </div>
                    </div>
                </td>
                <td>UNS</td>
                <td>Informatika</td>
                <td>12 Mar 2024</td>
                <td><span class="status-badge status-pending">Menunggu</span></td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view" title="Lihat">
                            <i class='bx bx-show'></i>
                        </button>
                        <button class="action-btn edit" title="Edit">
                            <i class='bx bx-edit'></i>
                        </button>
                        <button class="action-btn delete" title="Hapus">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div class="avatar" style="width: 35px; height: 35px; font-size: 0.9rem;">SA</div>
                        <div>
                            <div style="font-weight: 600;">Siti Aisyah</div>
                            <div style="font-size: 0.8rem; color: #888;">aisyah@ums.ac.id</div>
                        </div>
                    </div>
                </td>
                <td>UMS</td>
                <td>Statistik</td>
                <td>11 Mar 2024</td>
                <td><span class="status-badge status-approved">Disetujui</span></td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view">
                            <i class='bx bx-show'></i>
                        </button>
                        <button class="action-btn edit">
                            <i class='bx bx-edit'></i>
                        </button>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div class="avatar" style="width: 35px; height: 35px; font-size: 0.9rem;">RI</div>
                        <div>
                            <div style="font-weight: 600;">Rifaldy Ilham Nasrulloh</div>
                            <div style="font-size: 0.8rem; color: #888;">rifaldy@uns.ac.id</div>
                        </div>
                    </div>
                </td>
                <td>UNS</td>
                <td>Informatika</td>
                <td>08 Des 2025</td>
                <td><span class="status-badge status-approved">Disetujui</span></td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view">
                            <i class='bx bx-show'></i>
                        </button>
                        <button class="action-btn edit">
                            <i class='bx bx-edit'></i>
                        </button>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div class="avatar" style="width: 35px; height: 35px; font-size: 0.9rem;">MD</div>
                        <div>
                            <div style="font-weight: 600;">Maya Dewi</div>
                            <div style="font-size: 0.8rem; color: #888;">maya@its.ac.id</div>
                        </div>
                    </div>
                </td>
                <td>ITS</td>
                <td>Penyelenggara</td>
                <td>9 Mar 2024</td>
                <td><span class="status-badge status-pending">Menunggu</span></td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view">
                            <i class='bx bx-show'></i>
                        </button>
                        <button class="action-btn edit">
                            <i class='bx bx-edit'></i>
                        </button>
                        <button class="action-btn delete">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div style="padding: 20px 25px; text-align: center; border-top: 1px solid #eee;">
        <a href="{{ route('admin.peserta') }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">
            Lihat Semua Pendaftar → 
        </a>
    </div>
</div>

<!-- Bidang dengan Kuota -->
<div class="table-container">
    <div class="table-header">
        <h3>Kuota Magang per Bidang</h3>
        <div class="table-actions">
            <button class="btn btn-primary">
                <i class='bx bx-cog'></i> Atur Kuota
            </button>
        </div>
    </div>
    
    <div style="padding: 25px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                    <div>
                        <h4 style="color: var(--primary); margin-bottom: 5px;">Statistik</h4>
                        <p style="color: #666; font-size: 0.9rem;">Data & Analisis</p>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 1.8rem; font-weight: 700; color: var(--primary);">8/10</div>
                        <div style="color: #666; font-size: 0.9rem;">Peserta</div>
                    </div>
                </div>
                <div style="height: 10px; background: #e0e0e0; border-radius: 5px; overflow: hidden;">
                    <div style="width: 80%; height: 100%; background: var(--primary);"></div>
                </div>
                <div style="margin-top: 10px; font-size: 0.85rem; color: #666;">
                    <i class='bx bx-info-circle'></i> 2 slot tersisa
                </div>
            </div>
            
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                    <div>
                        <h4 style="color: var(--primary); margin-bottom: 5px;">Informatika</h4>
                        <p style="color: #666; font-size: 0.9rem;">IT & Programming</p>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 1.8rem; font-weight: 700; color: var(--primary);">12/15</div>
                        <div style="color: #666; font-size: 0.9rem;">Peserta</div>
                    </div>
                </div>
                <div style="height: 10px; background: #e0e0e0; border-radius: 5px; overflow: hidden;">
                    <div style="width: 80%; height: 100%; background: #2ed573;"></div>
                </div>
                <div style="margin-top: 10px; font-size: 0.85rem; color: #666;">
                    <i class='bx bx-info-circle'></i> 3 slot tersisa
                </div>
            </div>
            
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                    <div>
                        <h4 style="color: var(--primary); margin-bottom: 5px;">Kesekretariatan</h4>
                        <p style="color: #666; font-size: 0.9rem;">Administrasi</p>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 1.8rem; font-weight: 700; color: var(--primary);">6/8</div>
                        <div style="color: #666; font-size: 0.9rem;">Peserta</div>
                    </div>
                </div>
                <div style="height: 10px; background: #e0e0e0; border-radius: 5px; overflow: hidden;">
                    <div style="width: 75%; height: 100%; background: #ffa502;"></div>
                </div>
                <div style="margin-top: 10px; font-size: 0.85rem; color: #666;">
                    <i class='bx bx-info-circle'></i> 2 slot tersisa
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Refresh data setiap 30 detik
    setInterval(() => {
        console.log('Refreshing dashboard data...');
    }, 30000);
</script>
@endsection