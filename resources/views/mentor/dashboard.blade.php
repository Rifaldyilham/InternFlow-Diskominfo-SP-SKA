@extends('layouts.mentor')

@section('title', 'Dashboard Mentor')

@section('content')
<!-- Stats Overview -->
<div class="stats-grid mb-8">
    <div class="stat-card border-blue">
        <div class="stat-header">
            <div>
                <div class="stat-value">5</div>
                <div class="stat-label">Peserta Bimbingan</div>
                <div class="text-xs text-gray-600 mt-1">
                    3 aktif, 2 selesai
                </div>
            </div>
            <div class="stat-icon blue">
                <i class='bx bx-group'></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card border-teal">
        <div class="stat-header">
            <div>
                <div class="stat-value">3</div>
                <div class="stat-label">Logbook Pending</div>
                <div class="text-xs text-gray-600 mt-1">
                    Perlu verifikasi
                </div>
            </div>
            <div class="stat-icon teal">
                <i class='bx bx-book'></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card border-orange">
        <div class="stat-header">
            <div>
                <div class="stat-value">1</div>
                <div class="stat-label">Perlu Penilaian</div>
                <div class="text-xs text-gray-600 mt-1">
                    Masa magang selesai
                </div>
            </div>
            <div class="stat-icon orange">
                <i class='bx bx-star'></i>
            </div>
        </div>
    </div>
</div>


<!-- Logbook Pending -->
<div class="form-card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-mentor-primary flex items-center gap-2">
            <i class='bx bx-book'></i> Logbook Menunggu Verifikasi
        </h3>
        <button onclick="window.location.href='/mentor/logbook'" class="text-mentor-primary font-medium hover:underline">
            Lihat Semua →
        </button>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Peserta</th>
                    <th>Tanggal</th>
                    <th>Kegiatan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                $logbooks = [
                    ['peserta' => 'John Doe', 'tanggal' => '16 Mar 2024', 'kegiatan' => 'Pengembangan dashboard admin', 'status' => 'pending'],
                    ['peserta' => 'Jane Smith', 'tanggal' => '15 Mar 2024', 'kegiatan' => 'Analisis data statistik', 'status' => 'pending'],
                    ['peserta' => 'Budi Santoso', 'tanggal' => '15 Mar 2024', 'kegiatan' => 'Maintenance server', 'status' => 'pending']
                ];
                @endphp
                
                @foreach($logbooks as $logbook)
                <tr>
                    <td class="font-medium">{{ $logbook['peserta'] }}</td>
                    <td>{{ $logbook['tanggal'] }}</td>
                    <td>{{ $logbook['kegiatan'] }}</td>
                    <td>
                        <span class="status-badge status-pending">MENUNGGU</span>
                    </td>
                    <td>
                        <div class="flex gap-2">
                            <button onclick="viewLogbook('{{ $logbook['peserta'] }}')" class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-sm hover:bg-blue-100">
                                <i class='bx bx-show'></i> Lihat
                            </button>
                            <button onclick="verifyLogbook('{{ $logbook['peserta'] }}')" class="px-3 py-1 bg-green-50 text-green-600 rounded-lg text-sm hover:bg-green-100">
                                <i class='bx bx-check'></i> Verifikasi
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function viewPeserta(name) {
        alert(`Melihat detail peserta: ${name}\n\nAkan diarahkan ke halaman detail peserta.`);
        // In real app: window.location.href = `/mentor/bimbingan/${encodeURIComponent(name)}`;
    }
    
    function verifikasiPeserta(name) {
        alert(`Verifikasi peserta: ${name}\n\nMembuka modal verifikasi.`);
    }
    
    function viewLogbook(name) {
        alert(`Melihat logbook: ${name}\n\nMenampilkan detail logbook.`);
    }
    
    function verifyLogbook(name) {
        const modal = `
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-2xl max-w-md w-full p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class='bx bx-check-circle text-2xl text-blue-600'></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-mentor-primary">Verifikasi Logbook</h3>
                            <p class="text-gray-600">${name}</p>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 mb-2">Catatan Evaluasi</label>
                        <textarea class="w-full p-3 border border-gray-300 rounded-lg" rows="3" placeholder="Berikan catatan atau masukan..."></textarea>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 mb-2">Status Verifikasi</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button onclick="setVerificationStatus('approved')" class="p-3 border-2 border-green-500 text-green-600 rounded-lg font-medium hover:bg-green-50">
                                <i class='bx bx-check'></i> Setujui
                            </button>
                            <button onclick="setVerificationStatus('rejected')" class="p-3 border-2 border-red-500 text-red-600 rounded-lg font-medium hover:bg-red-50">
                                <i class='bx bx-x'></i> Tolak
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button onclick="submitVerification()" class="flex-1 bg-mentor-primary text-white py-3 rounded-lg font-medium hover:bg-blue-800">
                            Simpan
                        </button>
                        <button onclick="this.closest('.fixed').remove()" class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-lg font-medium hover:bg-gray-200">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modal);
    }
    
    function setVerificationStatus(status) {
        // Highlight selected button
        const buttons = document.querySelectorAll('[onclick^="setVerificationStatus"]');
        buttons.forEach(btn => {
            btn.style.background = '';
            if (btn.textContent.includes('Setujui')) {
                btn.style.color = '#10b981';
            } else {
                btn.style.color = '#ef4444';
            }
        });
        
        const selectedBtn = event.target.closest('button');
        if (selectedBtn.textContent.includes('Setujui')) {
            selectedBtn.style.background = '#10b981';
        } else {
            selectedBtn.style.background = '#ef4444';
        }
        selectedBtn.style.color = 'white';
    }
    
    function submitVerification() {
        const note = document.querySelector('textarea').value;
        if (!note.trim()) {
            alert('Harap isi catatan evaluasi!');
            return;
        }
        
        alert('Logbook berhasil diverifikasi!');
        const modal = document.querySelector('.fixed');
        if (modal) modal.remove();
        // In real app: reload or update table
    }
</script>
@endsection