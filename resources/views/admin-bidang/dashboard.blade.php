@extends('layouts.admin-bidang')

@section('title', 'Dashboard Admin Bidang')

@section('content')

<!-- Stats Overview -->
<div class="stats-grid mb-8">
    <div class="stat-card border-blue">
        <div class="stat-header">
            <div>
                <div class="stat-value">25</div>
                <div class="stat-label">Total Peserta</div>
                <div class="text-xs text-gray-600 mt-1">
                    18 aktif, 7 selesai
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
                <div class="stat-value">12</div>
                <div class="stat-label">Bidang Informatika</div>
                <div class="text-xs text-gray-600 mt-1">
                    80% kapasitas
                </div>
            </div>
            <div class="stat-icon teal">
                <i class='bx bx-laptop'></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card border-cyan">
        <div class="stat-header">
            <div>
                <div class="stat-value">8</div>
                <div class="stat-label">Bidang Statistik</div>
                <div class="text-xs text-gray-600 mt-1">
                    70% kapasitas
                </div>
            </div>
            <div class="stat-icon cyan">
                <i class='bx bx-stats'></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card border-orange">
        <div class="stat-header">
            <div>
                <div class="stat-value">5</div>
                <div class="stat-label">Lainnya</div>
                <div class="text-xs text-gray-600 mt-1">
                    Sekretariat & E-Gov
                </div>
            </div>
            <div class="stat-icon orange">
                <i class='bx bx-building'></i>
            </div>
        </div>
    </div>
</div>

<!-- Distribusi Peserta per Bidang -->
<div class="form-card mb-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-primary flex items-center gap-2">
            <i class='bx bx-pie-chart'></i> Distribusi Peserta per Bidang
        </h3>
        <div class="text-gray-600">
            <select id="periodeFilter" class="p-2 border border-gray-300 rounded-lg text-sm">
                <option value="all">Semua Periode</option>
                <option value="2024-1">Jan-Mar 2024</option>
                <option value="2023-4">Okt-Des 2023</option>
                <option value="2023-3">Jul-Sep 2023</option>
            </select>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Pie Chart -->
        <div class="flex justify-center">
            <div id="pieChartContainer" class="w-full max-w-md"></div>
        </div>
        
        <!-- Detail Bidang -->
        <div>
            <div class="space-y-4">
                @foreach([
                    ['nama' => 'Bidang Informatika', 'jumlah' => 12, 'warna' => 'bg-blue-500', 'persentase' => '48%', 'mentor' => 4, 'kapasitas' => 15],
                    ['nama' => 'Bidang Statistik', 'jumlah' => 8, 'warna' => 'bg-teal-500', 'persentase' => '32%', 'mentor' => 3, 'kapasitas' => 10],
                    ['nama' => 'Kesekretariatan', 'jumlah' => 3, 'warna' => 'bg-purple-500', 'persentase' => '12%', 'mentor' => 1, 'kapasitas' => 5],
                    ['nama' => 'E-Goverment', 'jumlah' => 2, 'warna' => 'bg-orange-500', 'persentase' => '8%', 'mentor' => 1, 'kapasitas' => 5]
                ] as $bidang)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 {{ $bidang['warna'] }} rounded-full"></div>
                            <div class="font-medium text-gray-800">{{ $bidang['nama'] }}</div>
                        </div>
                        <div class="text-lg font-bold text-primary">{{ $bidang['jumlah'] }}</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full {{ $bidang['warna'] }}" style="width: {{ $bidang['persentase'] }}"></div>
                        </div>
                        <span class="text-sm text-gray-600">{{ $bidang['persentase'] }}</span>
                    </div>
                    <div class="mt-3 flex justify-between text-sm text-gray-500">
                        <span>Mentor: {{ $bidang['mentor'] }}</span>
                        <span>Kapasitas: {{ $bidang['jumlah'] }}/{{ $bidang['kapasitas'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Peserta Baru -->
<div class="form-card mb-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-primary flex items-center gap-2">
            <i class='bx bx-user-plus'></i> Peserta Baru Perlu Penempatan
        </h3>
        <a href="/admin-bidang/peserta" class="text-primary font-medium hover:underline">
            Lihat Semua →
        </a>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Peserta</th>
                    <th>Universitas</th>
                    <th>Program Studi</th>
                    <th>Tanggal Daftar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach([
                    ['nama' => 'Rina Dewi', 'univ' => 'UNS', 'prodi' => 'Teknik Informatika', 'tanggal' => '15 Mar 2024', 'status' => 'pending'],
                    ['nama' => 'Ahmad Fauzi', 'univ' => 'UGM', 'prodi' => 'Sistem Informasi', 'tanggal' => '14 Mar 2024', 'status' => 'pending'],
                    ['nama' => 'Siti Nurhaliza', 'univ' => 'UNDIP', 'prodi' => 'Statistika', 'tanggal' => '13 Mar 2024', 'status' => 'assigned'],
                    ['nama' => 'Bambang Pamungkas', 'univ' => 'ITB', 'prodi' => 'Teknik Komputer', 'tanggal' => '12 Mar 2024', 'status' => 'pending']
                ] as $peserta)
                <tr>
                    <td class="font-medium">{{ $peserta['nama'] }}</td>
                    <td>{{ $peserta['univ'] }}</td>
                    <td>{{ $peserta['prodi'] }}</td>
                    <td>{{ $peserta['tanggal'] }}</td>
                    <td>
                        <span class="status-badge {{ $peserta['status'] === 'pending' ? 'status-pending' : 'status-waiting' }}">
                            {{ $peserta['status'] === 'pending' ? 'PERLU PENEMPATAN' : 'SUDAH DITEMPATKAN' }}
                        </span>
                    </td>
                    <td>
                        <div class="flex gap-2">
                            <button onclick="placeParticipant('{{ $peserta['nama'] }}')" class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-sm hover:bg-blue-100">
                                <i class='bx bx-transfer-alt'></i> Tempatkan
                            </button>
                            <button onclick="viewParticipant('{{ $peserta['nama'] }}')" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">
                                <i class='bx bx-show'></i> Detail
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-6 text-center">
        <button onclick="window.location.href='/admin-bidang/peserta/baru'" class="btn btn-primary">
            <i class='bx bx-user-plus'></i> Tambah Peserta Manual
        </button>
    </div>
</div>

<!-- Status Mentor -->
<div class="form-card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-primary flex items-center gap-2">
            <i class='bx bx-group'></i> Status Mentor per Bidang
        </h3>
        <a href="/admin-bidang/mentor" class="text-primary font-medium hover:underline">
            Kelola Mentor →
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach([
            ['bidang' => 'Bidang Informatika', 'mentors' => [
                ['nama' => 'Dr. Ahmad Fauzi, M.Kom.', 'status' => 'active', 'bimbingan' => 5],
                ['nama' => 'Dra. Siti Rahma, M.Si.', 'status' => 'active', 'bimbingan' => 4],
                ['nama' => 'Ir. Bambang Sudarsono, M.T.', 'status' => 'active', 'bimbingan' => 3]
            ]],
            ['bidang' => 'Bidang Statistik', 'mentors' => [
                ['nama' => 'Dr. Rina Dewi, M.Si.', 'status' => 'active', 'bimbingan' => 4],
                ['nama' => 'Prof. Budi Santoso, Ph.D.', 'status' => 'active', 'bimbingan' => 3],
                ['nama' => 'Dra. Maya Indah, M.Si.', 'status' => 'inactive', 'bimbingan' => 0]
            ]]
        ] as $bidang)
        <div class="border border-gray-200 rounded-xl p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 {{ $bidang['bidang'] === 'Bidang Informatika' ? 'bg-blue-100' : 'bg-teal-100' }} rounded-lg flex items-center justify-center {{ $bidang['bidang'] === 'Bidang Informatika' ? 'text-blue-600' : 'text-teal-600' }}">
                    <i class='bx {{ $bidang['bidang'] === 'Bidang Informatika' ? 'bx-laptop' : 'bx-stats' }}'></i>
                </div>
                <div>
                    <div class="font-bold text-primary">{{ $bidang['bidang'] }}</div>
                    <div class="text-sm text-gray-600">{{ count($bidang['mentors']) }} mentor</div>
                </div>
            </div>
            
            <div class="space-y-3">
                @foreach($bidang['mentors'] as $mentor)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 {{ $mentor['status'] === 'active' ? 'bg-green-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center {{ $mentor['status'] === 'active' ? 'text-green-600' : 'text-gray-600' }}">
                            <i class='bx bx-user-check'></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800 text-sm">{{ $mentor['nama'] }}</div>
                            <div class="text-xs text-gray-500">{{ $mentor['bimbingan'] }} peserta</div>
                        </div>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full {{ $mentor['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $mentor['status'] === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
                @endforeach
            </div>
            
            <div class="mt-4">
                <button onclick="manageMentors('{{ $bidang['bidang'] }}')" class="btn btn-secondary w-full text-sm">
                    <i class='bx bx-edit'></i> Kelola Mentor {{ $bidang['bidang'] }}
                </button>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <button onclick="window.location.href='/admin-bidang/mentor/tambah'" class="btn btn-primary">
            <i class='bx bx-user-plus'></i> Tambah Mentor Baru
        </button>
        <button onclick="window.location.href='/admin-bidang/mentor/penempatan'" class="btn btn-accent">
            <i class='bx bx-transfer'></i> Penempatan Peserta ke Mentor
        </button>
    </div>
</div>

<script>
// Data for pie chart
const bidangData = [
    { name: 'Informatika', value: 12, color: '#3b82f6' },
    { name: 'Statistik', value: 8, color: '#14b8a6' },
    { name: 'Kesekretariatan', value: 3, color: '#8b5cf6' },
    { name: 'E-Goverment', value: 2, color: '#f59e0b' }
];

// Initialize pie chart
function initPieChart() {
    const container = document.getElementById('pieChartContainer');
    if (!container) return;
    
    const total = bidangData.reduce((sum, item) => sum + item.value, 0);
    const size = 300;
    const radius = size / 2;
    
    const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    svg.setAttribute("width", "100%");
    svg.setAttribute("height", "300");
    svg.setAttribute("viewBox", `0 0 ${size} ${size}`);
    
    let cumulativeAngle = 0;
    const centerX = size / 2;
    const centerY = size / 2;
    
    bidangData.forEach((item, index) => {
        const percentage = item.value / total;
        const angle = percentage * 360;
        const startAngle = cumulativeAngle;
        const endAngle = startAngle + angle;
        
        // Calculate points for arc
        const startAngleRad = (startAngle - 90) * Math.PI / 180;
        const endAngleRad = (endAngle - 90) * Math.PI / 180;
        
        const x1 = centerX + radius * Math.cos(startAngleRad);
        const y1 = centerY + radius * Math.sin(startAngleRad);
        const x2 = centerX + radius * Math.cos(endAngleRad);
        const y2 = centerY + radius * Math.sin(endAngleRad);
        
        // Create path
        const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
        const largeArcFlag = angle > 180 ? 1 : 0;
        
        const d = [
            `M ${centerX} ${centerY}`,
            `L ${x1} ${y1}`,
            `A ${radius} ${radius} 0 ${largeArcFlag} 1 ${x2} ${y2}`,
            "Z"
        ].join(" ");
        
        path.setAttribute("d", d);
        path.setAttribute("fill", item.color);
        path.setAttribute("stroke", "white");
        path.setAttribute("stroke-width", "2");
        path.setAttribute("class", "cursor-pointer hover:opacity-90 transition-opacity");
        path.setAttribute("data-index", index);
        path.setAttribute("onclick", `showBidangDetail(${index})`);
        
        svg.appendChild(path);
        
        cumulativeAngle = endAngle;
    });
    
    // Add center circle
    const centerCircle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
    centerCircle.setAttribute("cx", centerX);
    centerCircle.setAttribute("cy", centerY);
    centerCircle.setAttribute("r", radius * 0.3);
    centerCircle.setAttribute("fill", "white");
    svg.appendChild(centerCircle);
    
    // Add total text
    const totalText = document.createElementNS("http://www.w3.org/2000/svg", "text");
    totalText.setAttribute("x", centerX);
    totalText.setAttribute("y", centerY - 5);
    totalText.setAttribute("text-anchor", "middle");
    totalText.setAttribute("dominant-baseline", "middle");
    totalText.setAttribute("fill", "#1a3a5f");
    totalText.setAttribute("font-size", "24");
    totalText.setAttribute("font-weight", "bold");
    totalText.textContent = total;
    svg.appendChild(totalText);
    
    const totalLabel = document.createElementNS("http://www.w3.org/2000/svg", "text");
    totalLabel.setAttribute("x", centerX);
    totalLabel.setAttribute("y", centerY + 15);
    totalLabel.setAttribute("text-anchor", "middle");
    totalLabel.setAttribute("dominant-baseline", "middle");
    totalLabel.setAttribute("fill", "#666");
    totalLabel.setAttribute("font-size", "12");
    totalLabel.textContent = "Total Peserta";
    svg.appendChild(totalLabel);
    
    container.innerHTML = '';
    container.appendChild(svg);
}

// Show bidang detail
function showBidangDetail(index) {
    const bidang = bidangData[index];
    const modal = `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: ${bidang.color}20; color: ${bidang.color}">
                        <i class='bx bx-buildings text-2xl'></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-primary">Detail ${bidang.name}</h3>
                        <p class="text-gray-600">${bidang.value} peserta</p>
                    </div>
                </div>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jumlah Peserta:</span>
                        <span class="font-medium">${bidang.value}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Persentase:</span>
                        <span class="font-medium">${Math.round(bidang.value / bidangData.reduce((s, i) => s + i.value, 0) * 100)}%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Warna:</span>
                        <span class="font-medium" style="color: ${bidang.color}">${bidang.color}</span>
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <button onclick="window.location.href='/admin-bidang/bidang/${bidang.name.toLowerCase().replace('bidang ', '')}'" class="flex-1 bg-primary text-white py-3 rounded-lg font-medium hover:bg-blue-800">
                        <i class='bx bx-show'></i> Lihat Detail
                    </button>
                    <button onclick="this.closest('.fixed').remove()" class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-lg font-medium hover:bg-gray-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modal);
}

// Place participant
function placeParticipant(name) {
    const modal = `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6">
                <h3 class="font-bold text-lg text-primary mb-4">Penempatan Peserta</h3>
                <p class="text-gray-600 mb-6">Tempatkan <strong>${name}</strong> ke bidang dan mentor yang sesuai</p>
                
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-gray-700 mb-2">Pilih Bidang</label>
                        <select class="w-full p-3 border border-gray-300 rounded-lg">
                            <option value="">-- Pilih Bidang --</option>
                            <option value="informatika">Bidang Informatika</option>
                            <option value="statistik">Bidang Statistik</option>
                            <option value="sekretariat">Kesekretariatan</option>
                            <option value="egov">E-Goverment</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Pilih Mentor</label>
                        <select class="w-full p-3 border border-gray-300 rounded-lg">
                            <option value="">-- Pilih Mentor --</option>
                            <option value="1">Dr. Ahmad Fauzi, M.Kom.</option>
                            <option value="2">Dra. Siti Rahma, M.Si.</option>
                            <option value="3">Ir. Bambang Sudarsono, M.T.</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <button onclick="submitPlacement('${name}')" class="flex-1 bg-primary text-white py-3 rounded-lg font-medium hover:bg-blue-800">
                        <i class='bx bx-check'></i> Simpan Penempatan
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

function submitPlacement(name) {
    alert(`Peserta ${name} berhasil ditempatkan!`);
    const modal = document.querySelector('.fixed');
    if (modal) modal.remove();
}

function viewParticipant(name) {
    alert(`Melihat detail peserta: ${name}\n\nAkan diarahkan ke halaman detail peserta.`);
}

function manageMentors(bidang) {
    alert(`Mengelola mentor untuk: ${bidang}\n\nAkan diarahkan ke halaman pengelolaan mentor.`);
}

// Filter data by periode
document.getElementById('periodeFilter')?.addEventListener('change', function() {
    const periode = this.value;
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-blue-500 text-white p-4 rounded-lg shadow-lg z-50';
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <i class='bx bx-check'></i>
            <div>
                <div class="font-bold">Filter Diterapkan</div>
                <div class="text-sm opacity-90">Menampilkan data periode: ${periode}</div>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transition = 'opacity 0.3s';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    initPieChart();
    
    // Update stats periodically (simulated)
    setInterval(() => {
        // Simulate real-time updates
        const activeElement = document.querySelector('.stat-value');
        if (activeElement) {
            // Random fluctuation for demo
            const fluctuation = Math.random() > 0.5 ? 1 : -1;
            const newValue = Math.max(20, Math.min(30, parseInt(activeElement.textContent) + fluctuation));
            activeElement.textContent = newValue;
        }
    }, 15000);
});
</script>

<style>
/* Custom styles for pie chart */
#pieChartContainer svg {
    max-width: 100%;
    height: auto;
}

/* Hover effects for cards */
.border-blue:hover { border-color: #3b82f6; }
.border-teal:hover { border-color: #14b8a6; }
.border-cyan:hover { border-color: #06b6d4; }
.border-orange:hover { border-color: #f59e0b; }

/* Animation for stats */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stat-card {
    animation: fadeInUp 0.5s ease-out;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }

/* Responsive adjustments */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .form-card {
        padding: 20px;
    }
    
    .data-table th,
    .data-table td {
        padding: 12px 8px;
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .btn {
        padding: 10px 15px;
        font-size: 0.85rem;
    }
}
</style>
@endsection