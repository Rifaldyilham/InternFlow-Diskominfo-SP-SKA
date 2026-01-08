@extends('layouts.mentor')

@section('title', 'Daftar Bimbingan')

@section('content')

<!-- Search and Filter -->
<div class="form-card mb-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div>
            <label class="block text-gray-700 mb-2">Cari Peserta</label>
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Nama, NIM, atau Universitas..." 
                       class="w-full p-3 pl-10 border border-gray-300 rounded-lg">
                <i class='bx bx-search absolute left-3 top-3 text-gray-400'></i>
            </div>
        </div>
        
        <div>
            <label class="block text-gray-700 mb-2">Status</label>
            <select id="statusFilter" class="w-full p-3 border border-gray-300 rounded-lg">
                <option value="all">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="completed">Selesai</option>
                <option value="pending">Menunggu</option>
            </select>
        </div>
        
        <div>
            <label class="block text-gray-700 mb-2">Bidang</label>
            <select id="bidangFilter" class="w-full p-3 border border-gray-300 rounded-lg">
                <option value="all">Semua Bidang</option>
                <option value="informatika">Informatika</option>
                <option value="statistik">Statistik</option>
                <option value="kesekretariatan">Kesekretariatan</option>
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

<!-- Peserta Table -->
<div class="form-card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-mentor-primary flex items-center gap-2">
            <i class='bx bx-list-ul'></i> Data Peserta Bimbingan
        </h3>
        <span class="text-gray-600" id="totalPeserta">Total: 5 peserta</span>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Peserta</th>
                    <th>Universitas</th>
                    <th>Program Studi</th>
                    <th>Periode</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="pesertaTable">
                @foreach([
                    ['name' => 'John Doe', 'nim' => 'G12345678', 'univ' => 'Universitas Sebelas Maret', 
                     'prodi' => 'Teknik Informatika', 'periode' => '1 Jan - 30 Mar 2024', 
                     'status' => 'active', 'bidang' => 'informatika'],
                    ['name' => 'Jane Smith', 'nim' => 'S98765432', 'univ' => 'Politeknik Negeri Semarang', 
                     'prodi' => 'Sistem Informasi', 'periode' => '1 Feb - 30 Apr 2024', 
                     'status' => 'active', 'bidang' => 'statistik'],
                    ['name' => 'Budi Santoso', 'nim' => 'D87654321', 'univ' => 'Universitas Diponegoro', 
                     'prodi' => 'Ilmu Komputer', 'periode' => '1 Mar - 30 Mei 2024', 
                     'status' => 'active', 'bidang' => 'informatika'],
                    ['name' => 'Siti Rahma', 'nim' => 'U76543210', 'univ' => 'Universitas Gadjah Mada', 
                     'prodi' => 'Teknologi Informasi', 'periode' => '1 Jan - 30 Mar 2024', 
                     'status' => 'completed', 'bidang' => 'kesekretariatan'],
                    ['name' => 'Ahmad Rizki', 'nim' => 'N65432109', 'univ' => 'Universitas Negeri Semarang', 
                     'prodi' => 'Manajemen Informatika', 'periode' => '1 Des 2023 - 29 Feb 2024', 
                     'status' => 'completed', 'bidang' => 'informatika']
                ] as $peserta)
                <tr>
                    <td>
                        <div class="font-medium text-mentor-primary">{{ $peserta['name'] }}</div>
                        <div class="text-sm text-gray-500">{{ $peserta['nim'] }}</div>
                    </td>
                    <td>{{ $peserta['univ'] }}</td>
                    <td>{{ $peserta['prodi'] }}</td>
                    <td>{{ $peserta['periode'] }}</td>
                    <td>
                        <span class="status-badge {{ $peserta['status'] === 'active' ? 'status-active' : 'status-approved' }}">
                            {{ $peserta['status'] === 'active' ? 'AKTIF' : 'SELESAI' }}
                        </span>
                    </td>
                    <td>
                        <div class="flex gap-2">
                            <button onclick="viewDetail('{{ $peserta['name'] }}')" 
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" 
                                    title="Detail">
                                <i class='bx bx-show'></i>
                            </button>
                            <button onclick="viewLogbook('{{ $peserta['name'] }}')" 
                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg" 
                                    title="Logbook">
                                <i class='bx bx-book'></i>
                            </button>
                            <button onclick="viewAbsensi('{{ $peserta['name'] }}')" 
                                    class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg" 
                                    title="Absensi">
                                <i class='bx bx-calendar-check'></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="flex justify-between items-center mt-6">
        <div class="text-gray-600 text-sm">
            Menampilkan 5 dari 5 peserta
        </div>
        <div class="flex gap-2">
            <button class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                <i class='bx bx-chevron-left'></i>
            </button>
            <button class="px-3 py-2 bg-mentor-primary text-white rounded-lg">1</button>
            <button class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                <i class='bx bx-chevron-right'></i>
            </button>
        </div>
    </div>
</div>

<script>
    let pesertaData = [
        @foreach([
            ['name' => 'John Doe', 'nim' => 'G12345678', 'univ' => 'Universitas Sebelas Maret', 
             'prodi' => 'Teknik Informatika', 'periode' => '1 Jan - 30 Mar 2024', 
             'status' => 'active', 'bidang' => 'informatika'],
            ['name' => 'Jane Smith', 'nim' => 'S98765432', 'univ' => 'Politeknik Negeri Semarang', 
             'prodi' => 'Sistem Informasi', 'periode' => '1 Feb - 30 Apr 2024', 
             'status' => 'active', 'bidang' => 'statistik'],
            ['name' => 'Budi Santoso', 'nim' => 'D87654321', 'univ' => 'Universitas Diponegoro', 
             'prodi' => 'Ilmu Komputer', 'periode' => '1 Mar - 30 Mei 2024', 
             'status' => 'active', 'bidang' => 'informatika'],
            ['name' => 'Siti Rahma', 'nim' => 'U76543210', 'univ' => 'Universitas Gadjah Mada', 
             'prodi' => 'Teknologi Informasi', 'periode' => '1 Jan - 30 Mar 2024', 
             'status' => 'completed', 'bidang' => 'kesekretariatan'],
            ['name' => 'Ahmad Rizki', 'nim' => 'N65432109', 'univ' => 'Universitas Negeri Semarang', 
             'prodi' => 'Manajemen Informatika', 'periode' => '1 Des 2023 - 29 Feb 2024', 
             'status' => 'completed', 'bidang' => 'informatika']
        ] as $peserta)
        {
            name: "{{ $peserta['name'] }}",
            nim: "{{ $peserta['nim'] }}",
            univ: "{{ $peserta['univ'] }}",
            prodi: "{{ $peserta['prodi'] }}",
            periode: "{{ $peserta['periode'] }}",
            status: "{{ $peserta['status'] }}",
            bidang: "{{ $peserta['bidang'] }}"
        },
        @endforeach
    ];
    
    function filterPeserta() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const bidangFilter = document.getElementById('bidangFilter').value;
        
        const filtered = pesertaData.filter(peserta => {
            const matchesSearch = searchTerm === '' || 
                peserta.name.toLowerCase().includes(searchTerm) ||
                peserta.nim.toLowerCase().includes(searchTerm) ||
                peserta.univ.toLowerCase().includes(searchTerm) ||
                peserta.prodi.toLowerCase().includes(searchTerm);
            
            const matchesStatus = statusFilter === 'all' || peserta.status === statusFilter;
            const matchesBidang = bidangFilter === 'all' || peserta.bidang === bidangFilter;
            
            return matchesSearch && matchesStatus && matchesBidang;
        });
        
        renderTable(filtered);
        document.getElementById('totalPeserta').textContent = `Total: ${filtered.length} peserta`;
    }
    
    function resetFilter() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = 'all';
        document.getElementById('bidangFilter').value = 'all';
        renderTable(pesertaData);
        document.getElementById('totalPeserta').textContent = `Total: ${pesertaData.length} peserta`;
    }
    
    function renderTable(data) {
        const tableBody = document.getElementById('pesertaTable');
        tableBody.innerHTML = '';
        
        data.forEach(peserta => {
            const row = `
                <tr>
                    <td>
                        <div class="font-medium text-mentor-primary">${peserta.name}</div>
                        <div class="text-sm text-gray-500">${peserta.nim}</div>
                    </td>
                    <td>${peserta.univ}</td>
                    <td>${peserta.prodi}</td>
                    <td>${peserta.periode}</td>
                    <td>
                        <span class="status-badge ${peserta.status === 'active' ? 'status-active' : 'status-approved'}">
                            ${peserta.status === 'active' ? 'AKTIF' : 'SELESAI'}
                        </span>
                    </td>
                    <td>
                        <div class="flex gap-2">
                            <button onclick="viewDetail('${peserta.name}')" 
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" 
                                    title="Detail">
                                <i class='bx bx-show'></i>
                            </button>
                            <button onclick="viewLogbook('${peserta.name}')" 
                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg" 
                                    title="Logbook">
                                <i class='bx bx-book'></i>
                            </button>
                            <button onclick="viewAbsensi('${peserta.name}')" 
                                    class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg" 
                                    title="Absensi">
                                <i class='bx bx-calendar-check'></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    }
    
    function viewDetail(name) {
        const peserta = pesertaData.find(p => p.name === name);
        if (!peserta) return;
        
        const modal = `
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-mentor-primary">Detail Peserta</h3>
                            <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                <i class='bx bx-x text-2xl'></i>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="space-y-4">
                                <div>
                                    <div class="text-sm text-gray-500">Nama Lengkap</div>
                                    <div class="font-medium text-lg">${peserta.name}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">NIM</div>
                                    <div class="font-medium">${peserta.nim}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Universitas</div>
                                    <div class="font-medium">${peserta.univ}</div>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <div class="text-sm text-gray-500">Program Studi</div>
                                    <div class="font-medium">${peserta.prodi}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Periode Magang</div>
                                    <div class="font-medium">${peserta.periode}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Status</div>
                                    <span class="status-badge ${peserta.status === 'active' ? 'status-active' : 'status-approved'}">
                                        ${peserta.status === 'active' ? 'AKTIF' : 'SELESAI'}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t pt-6">
                            <h4 class="font-bold text-mentor-primary mb-4">Statistik Magang</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="text-2xl font-bold text-mentor-primary">${peserta.status === 'completed' ? '100' : '85'}%</div>
                                    <div class="text-sm text-gray-600">Progress Magang</div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="text-2xl font-bold text-mentor-primary">${peserta.status === 'completed' ? '85' : 'N/A'}</div>
                                    <div class="text-sm text-gray-600">Nilai Akhir</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t p-6">
                        <div class="flex gap-3">
                            <button onclick="viewLogbook('${peserta.name}')" class="flex-1 btn btn-primary">
                                <i class='bx bx-book'></i> Lihat Logbook
                            </button>
                            <button onclick="viewAbsensi('${peserta.name}')" class="flex-1 btn" style="background: #f8fafc; color: #666;">
                                <i class='bx bx-calendar-check'></i> Lihat Absensi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modal);
    }
    
    function viewLogbook(name) {
        // Redirect to verification page with logbook tab and filtered by peserta
        window.location.href = `/mentor/verifikasi?tab=logbook&peserta=${encodeURIComponent(name)}`;
    }
    
    function viewAbsensi(name) {
        // Redirect to verification page with absensi tab and filtered by peserta
        window.location.href = `/mentor/verifikasi?tab=absensi&peserta=${encodeURIComponent(name)}`;
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Add real-time search
        document.getElementById('searchInput').addEventListener('input', filterPeserta);
        document.getElementById('statusFilter').addEventListener('change', filterPeserta);
        document.getElementById('bidangFilter').addEventListener('change', filterPeserta);
    });
</script>
@endsection