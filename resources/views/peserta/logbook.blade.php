@extends('layouts.peserta')

@section('title', 'Logbook Harian')

@section('content')
<div class="form-card">
    <h2 style="color: var(--primary); margin-bottom: 30px; display: flex; align-items: center; gap: 10px;">
        <i class='bx bx-book'></i> Logbook Harian Magang
    </h2>
    
    <!-- Statistik Logbook -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, rgba(33, 52, 72, 0.1) 0%, rgba(84, 119, 146, 0.1) 100%); padding: 20px; border-radius: 12px;">
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary);">15</div>
            <div style="color: #666;">Total Hari</div>
        </div>
        <div style="background: linear-gradient(135deg, rgba(46, 213, 115, 0.1) 0%, rgba(39, 174, 96, 0.1) 100%); padding: 20px; border-radius: 12px;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #2ecc71;">8</div>
            <div style="color: #666;">Sudah Diisi</div>
        </div>
        <div style="background: linear-gradient(135deg, rgba(241, 196, 15, 0.1) 0%, rgba(243, 156, 18, 0.1) 100%); padding: 20px; border-radius: 12px;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #f1c40f;">1</div>
            <div style="color: #666;">Menunggu</div>
        </div>
        <div style="background: linear-gradient(135deg, rgba(231, 76, 60, 0.1) 0%, rgba(192, 57, 43, 0.1) 100%); padding: 20px; border-radius: 12px;">
            <div style="font-size: 2.5rem; font-weight: 700; color: #e74c3c;">6</div>
            <div style="color: #666;">Belum Diisi</div>
        </div>
    </div>
    
    <!-- Form Isi Logbook -->
    <div id="logbookForm" style="background: #f8fafc; padding: 30px; border-radius: 16px; margin-bottom: 30px; border: 2px solid var(--accent);">
        <div class="flex justify-between items-start mb-6">
            <h3 style="color: var(--primary); display: flex; align-items: center; gap: 10px;">
                <i class='bx bx-edit'></i> Isi Logbook
            </h3>
            <div class="text-right">
                <div class="text-sm text-gray-600 mb-1">Status Magang:</div>
                <span class="status-badge status-active">Sedang Berjalan</span>
            </div>
        </div>
        
        <!-- Tanggal Picker -->
        <div style="background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #e2e8f0;">
            <h4 style="color: var(--primary); margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                <i class='bx bx-calendar'></i> Pilih Tanggal Kegiatan
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 mb-2">Tanggal Kegiatan *</label>
                    <div class="relative">
                        <input type="date" id="tanggalKegiatan" class="w-full p-3 pl-10 border border-gray-300 rounded-lg"
                               min="2024-01-01" max="2024-12-31"
                               onchange="updateTanggalInfo()">
                        <i class='bx bx-calendar absolute left-3 top-3 text-gray-400'></i>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">Pilih tanggal untuk mengisi logbook</div>
                    
                    <!-- Calendar Quick Select -->
                    <div class="mt-4">
                        <div class="text-sm text-gray-600 mb-2">Pilih cepat:</div>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="setTanggalHariIni()" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm hover:bg-blue-200">
                                Hari Ini
                            </button>
                            <button onclick="setTanggalKemarin()" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200">
                                Kemarin
                            </button>
                            <button onclick="setTanggalBesok()" class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm hover:bg-purple-200">
                                Besok
                            </button>
                        </div>
                    </div>
                </div>
                
                <div id="tanggalInfo" style="background: #f0f9ff; padding: 15px; border-radius: 10px; border-left: 4px solid #3b82f6;">
                    <div class="font-semibold text-primary text-lg" id="selectedDateText">Sabtu, 16 Maret 2024</div>
                    <div class="text-sm text-gray-600 mt-1" id="dayInfo">Hari ke-16 dari 90 hari magang</div>
                    <div class="text-xs text-gray-500 mt-2" id="dateStatus">
                        <i class='bx bx-info-circle'></i> Pilih tanggal untuk melihat status
                    </div>
                    
                    <!-- Status Logbook untuk tanggal terpilih -->
                    <div id="logbookStatusInfo" class="mt-3 p-3 rounded-lg hidden">
                        <!-- Info status logbook akan muncul di sini -->
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Status Kehadiran -->
        <div id="statusSection" style="background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #e2e8f0; display: none;">
            <h4 style="color: var(--primary); margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                <i class='bx bx-user-check'></i> Status Kehadiran
            </h4>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <button onclick="setKehadiranStatus('hadir')" 
                        id="btnHadir"
                        class="p-4 border-2 border-green-200 rounded-lg text-left hover:bg-green-50 transition-colors">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class='bx bx-check-circle text-xl text-green-600'></i>
                        </div>
                        <div>
                            <div class="font-bold text-primary">Hadir</div>
                            <div class="text-sm text-gray-600">Bekerja di kantor</div>
                        </div>
                    </div>
                </button>
                
                <button onclick="setKehadiranStatus('izin')" 
                        id="btnIzin"
                        class="p-4 border-2 border-yellow-200 rounded-lg text-left hover:bg-yellow-50 transition-colors">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class='bx bx-alarm-exclamation text-xl text-yellow-600'></i>
                        </div>
                        <div>
                            <div class="font-bold text-primary">Izin</div>
                            <div class="text-sm text-gray-600">Tidak masuk kantor</div>
                        </div>
                    </div>
                </button>
                
                <button onclick="setKehadiranStatus('sakit')" 
                        id="btnSakit"
                        class="p-4 border-2 border-red-200 rounded-lg text-left hover:bg-red-50 transition-colors">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class='bx bx-plus-medical text-xl text-red-600'></i>
                        </div>
                        <div>
                            <div class="font-bold text-primary">Sakit</div>
                            <div class="text-sm text-gray-600">Tidak sehat</div>
                        </div>
                    </div>
                </button>
            </div>
            
            <div class="mt-4" id="selectedStatusInfo">
                <!-- Status info will be shown here -->
            </div>
        </div>
        
        <!-- Form Logbook (HADIR) -->
        <div id="logbookHadirForm" style="display: none;">
            <form id="formLogbookHadir" onsubmit="event.preventDefault(); submitLogbookHadir();">
                <div class="form-group">
                    <label for="kegiatan">Kegiatan Utama Hari Ini *</label>
                    <input type="text" id="kegiatan" placeholder="Contoh: Meeting tim IT, pengembangan fitur dashboard" required>
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi Kegiatan *</label>
                    <textarea id="deskripsi" rows="4" placeholder="Jelaskan secara detail apa yang Anda kerjakan hari ini, kendala yang dihadapi, dan solusinya..." required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="hasil">Hasil/Pencapaian</label>
                    <textarea id="hasil" rows="3" placeholder="Apa yang berhasil diselesaikan atau dipelajari hari ini?"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Tantangan/Kendala</label>
                    <textarea id="tantangan" rows="2" placeholder="Kendala teknis atau non-teknis yang dihadapi"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="waktu_mulai">Waktu Mulai</label>
                        <input type="time" id="waktu_mulai" value="08:00">
                    </div>
                    
                    <div class="form-group">
                        <label for="waktu_selesai">Waktu Selesai</label>
                        <input type="time" id="waktu_selesai" value="16:00">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Upload Bukti Kegiatan (Opsional)</label>
                    <div class="upload-area" onclick="document.getElementById('bukti_file').click()">
                        <div class="upload-icon">
                            <i class='bx bx-image-add'></i>
                        </div>
                        <div style="font-weight: 600; margin-bottom: 5px;">Unggah Foto/Dokumen</div>
                        <small>Format JPG/PNG/PDF, max 5MB</small>
                        <input type="file" id="bukti_file" accept=".jpg,.jpeg,.png,.pdf" style="display:none;" onchange="previewBukti(this)">
                    </div>
                    <div id="bukti_preview" style="margin-top: 10px;"></div>
                </div>
                
                <div style="display: flex; gap: 15px; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary" style="flex: 2;">
                        <i class='bx bx-save'></i> Simpan Logbook
                    </button>
                    <button type="button" class="btn" onclick="resetForm()" style="flex: 1; background: #f8fafc; color: #666;">
                        Reset
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Form Izin -->
        <div id="logbookIzinForm" style="display: none;">
            <div style="background: white; padding: 20px; border-radius: 12px; margin-top: 20px; border: 1px solid #f1c40f;">
                <h4 style="color: var(--primary); margin-bottom: 15px;">Form Izin Tidak Masuk</h4>
                
                <form id="formIzin" onsubmit="event.preventDefault(); submitIzin();">
                    <div class="form-group">
                        <label>Alasan Izin *</label>
                        <textarea id="alasanIzin" rows="3" placeholder="Jelaskan alasan izin secara detail..." required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tanggalMulaiIzin">Tanggal Mulai Izin</label>
                            <input type="date" id="tanggalMulaiIzin" min="2024-01-01" max="2024-12-31">
                        </div>
                        
                        <div class="form-group">
                            <label for="tanggalSelesaiIzin">Tanggal Selesai Izin</label>
                            <input type="date" id="tanggalSelesaiIzin" min="2024-01-01" max="2024-12-31">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Upload Surat Izin (Opsional)</label>
                        <div class="upload-area" onclick="document.getElementById('suratIzin').click()">
                            <div class="upload-icon">
                                <i class='bx bx-file'></i>
                            </div>
                            <div>Unggah Surat Izin</div>
                            <small>Format PDF/JPG, max 2MB</small>
                            <input type="file" id="suratIzin" style="display:none;">
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 15px; margin-top: 20px;">
                        <button type="submit" class="btn btn-primary" style="flex: 2;">
                            <i class='bx bx-send'></i> Kirim Izin
                        </button>
                        <button type="button" class="btn" onclick="resetForm()" style="flex: 1; background: #f8fafc; color: #666;">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Form Sakit -->
        <div id="logbookSakitForm" style="display: none;">
            <div style="background: white; padding: 20px; border-radius: 12px; margin-top: 20px; border: 1px solid #e74c3c;">
                <h4 style="color: var(--primary); margin-bottom: 15px;">Form Laporan Sakit</h4>
                
                <form id="formSakit" onsubmit="event.preventDefault(); submitSakit();">
                    <div class="form-group">
                        <label>Jenis Sakit *</label>
                        <input type="text" id="jenisSakit" placeholder="Contoh: Demam, Flu, Sakit Kepala, dll" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Deskripsi Kondisi *</label>
                        <textarea id="deskripsiSakit" rows="3" placeholder="Jelaskan kondisi kesehatan Anda, gejala yang dirasakan..." required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tanggalMulaiSakit">Tanggal Mulai Sakit</label>
                            <input type="date" id="tanggalMulaiSakit" min="2024-01-01" max="2024-12-31">
                        </div>
                        
                        <div class="form-group">
                            <label for="tanggalSelesaiSakit">Tanggal Selesai Sakit</label>
                            <input type="date" id="tanggalSelesaiSakit" min="2024-01-01" max="2024-12-31">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Upload Surat Dokter (Opsional)</label>
                        <div class="upload-area" onclick="document.getElementById('suratSakit').click()">
                            <div class="upload-icon">
                                <i class='bx bx-file-medical'></i>
                            </div>
                            <div>Unggah Surat Dokter</div>
                            <small>Format PDF/JPG, max 2MB</small>
                            <input type="file" id="suratSakit" style="display:none;">
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 15px; margin-top: 20px;">
                        <button type="submit" class="btn btn-primary" style="flex: 2;">
                            <i class='bx bx-send'></i> Kirim Laporan Sakit
                        </button>
                        <button type="button" class="btn" onclick="resetForm()" style="flex: 1; background: #f8fafc; color: #666;">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Daftar Logbook -->
    <div class="form-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="color: var(--primary); display: flex; align-items: center; gap: 10px;">
                <i class='bx bx-history'></i> Riwayat Logbook
            </h2>
            <div style="display: flex; gap: 10px;">
                <select id="filterBulan" onchange="filterLogbook()" style="padding: 8px 15px; border: 2px solid #e2e8f0; border-radius: 8px; background: white;">
                    <option value="all">Semua Bulan</option>
                    <option value="3" selected>Maret 2024</option>
                    <option value="2">Februari 2024</option>
                    <option value="1">Januari 2024</option>
                </select>
                <select id="filterStatus" onchange="filterLogbook()" style="padding: 8px 15px; border: 2px solid #e2e8f0; border-radius: 8px; background: white;">
                    <option value="all">Semua Status</option>
                    <option value="verified">Diverifikasi</option>
                    <option value="pending">Menunggu</option>
                    <option value="izin">Izin/Sakit</option>
                </select>
            </div>
        </div>
        
        <div id="logbookList">
            <!-- Logbook items will be loaded here -->
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <button onclick="loadMore()" style="background: var(--accent); color: var(--primary); border: none; padding: 12px 30px; border-radius: 10px; font-weight: 600; cursor: pointer;">
                <i class='bx bx-refresh'></i> Muat Lebih Banyak
            </button>
        </div>
    </div>
</div>

<script>
    let currentStatus = null;
    let selectedDate = null;
    let lastNotifiedDate = null; // added
    let isEditing = false; // added
    let logbookData = [
        {
            id: 1,
            tanggal: '2024-03-15',
            tanggalDisplay: '15 Mar 2024',
            kegiatan: 'Analisis data statistik pengunjung website',
            deskripsi: 'Melakukan analisis data pengunjung website Diskominfo menggunakan Google Analytics dan membuat laporan bulanan.',
            status: 'verified',
            jenis: 'hadir',
            catatan: 'Kerja bagus! Data sangat detail.',
            waktu: '08:00 - 16:00'
        },
        {
            id: 2,
            tanggal: '2024-03-14',
            tanggalDisplay: '14 Mar 2024',
            kegiatan: 'Maintenance server dan backup data',
            deskripsi: 'Melakukan maintenance server utama dan backup data ke cloud storage.',
            status: 'pending',
            jenis: 'hadir',
            catatan: '',
            waktu: '08:30 - 17:00'
        },
        {
            id: 3,
            tanggal: '2024-03-13',
            tanggalDisplay: '13 Mar 2024',
            kegiatan: 'Izin tidak masuk',
            deskripsi: 'Izin tidak masuk karena sakit demam dan batuk.',
            status: 'verified',
            jenis: 'sakit',
            catatan: 'Cepat sembuh dan jaga kesehatan.',
            waktu: '-'
        },
        {
            id: 4,
            tanggal: '2024-03-12',
            tanggalDisplay: '12 Mar 2024',
            kegiatan: 'Pengembangan fitur dashboard admin',
            deskripsi: 'Mengembangkan fitur export data dan filter pada dashboard admin InternFlow.',
            status: 'verified',
            jenis: 'hadir',
            catatan: 'Progress baik, lanjutkan!',
            waktu: '09:00 - 16:30'
        },
        {
            id: 5,
            tanggal: '2024-03-11',
            tanggalDisplay: '11 Mar 2024',
            kegiatan: 'Training database management',
            deskripsi: 'Mengikuti training pengelolaan database MySQL dan optimasi query.',
            status: 'verified',
            jenis: 'hadir',
            catatan: 'Pemahaman bagus.',
            waktu: '08:00 - 15:00'
        }
    ];
    
    // Initialize date picker with today's date or latest unfilled date
    document.addEventListener('DOMContentLoaded', function() {
        // pilih tanggal terbaru yang BELUM diisi (mundur dari hari ini)
        const defaultDate = getLatestUnfilledDate() || new Date().toISOString().split('T')[0];
        document.getElementById('tanggalKegiatan').value = defaultDate;
        selectedDate = defaultDate;
        
        // Set default dates for izin/sakit forms (keperluan internal)
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0];
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        const tomorrowStr = tomorrow.toISOString().split('T')[0];
        
        document.getElementById('tanggalMulaiIzin') && (document.getElementById('tanggalMulaiIzin').value = formattedDate);
        document.getElementById('tanggalSelesaiIzin') && (document.getElementById('tanggalSelesaiIzin').value = formattedDate);
        document.getElementById('tanggalMulaiSakit') && (document.getElementById('tanggalMulaiSakit').value = formattedDate);
        document.getElementById('tanggalSelesaiSakit') && (document.getElementById('tanggalSelesaiSakit').value = formattedDate);
        
        updateTanggalInfo();
        renderLogbook();
    });
    
    // Set tanggal to today
    function setTanggalHariIni() {
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0];
        document.getElementById('tanggalKegiatan').value = formattedDate;
        selectedDate = formattedDate;
        updateTanggalInfo();
    }
    
    // Set tanggal to yesterday
    function setTanggalKemarin() {
        const yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);
        const formattedDate = yesterday.toISOString().split('T')[0];
        document.getElementById('tanggalKegiatan').value = formattedDate;
        selectedDate = formattedDate;
        updateTanggalInfo();
    }
    
    // Set tanggal to tomorrow
    function setTanggalBesok() {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const formattedDate = tomorrow.toISOString().split('T')[0];
        document.getElementById('tanggalKegiatan').value = formattedDate;
        selectedDate = formattedDate;
        updateTanggalInfo();
    }
    
    // Cari tanggal terbaru yang BELUM diisi (mundur dari hari ini)
    function getLatestUnfilledDate() {
        const today = new Date();
        const startDate = new Date('2024-01-01');
        for (let d = new Date(today); d >= startDate; d.setDate(d.getDate() - 1)) {
            const dateStr = d.toISOString().split('T')[0];
            const exists = logbookData.some(log => log.tanggal === dateStr);
            if (!exists) return dateStr;
        }
        return today.toISOString().split('T')[0];
    }
    
    // Update tanggal info when date changes
    function updateTanggalInfo() {
        const dateInput = document.getElementById('tanggalKegiatan');
        selectedDate = dateInput.value;
        
        if (!selectedDate) {
            document.getElementById('statusSection').style.display = 'none';
            document.getElementById('selectedDateText').textContent = 'Pilih tanggal terlebih dahulu';
            document.getElementById('dayInfo').textContent = '';
            document.getElementById('dateStatus').innerHTML = '<i class="bx bx-info-circle"></i> Pilih tanggal untuk melihat status';
            document.getElementById('logbookStatusInfo').classList.add('hidden');
            return;
        }
        
        const dateObj = new Date(selectedDate);
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateText = dateObj.toLocaleDateString('id-ID', options);
        
        document.getElementById('selectedDateText').textContent = dateText;
        
        // Calculate day number (assuming magang started on 2024-01-01)
        const startDate = new Date('2024-01-01');
        const diffTime = Math.abs(dateObj - startDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        
        document.getElementById('dayInfo').textContent = `Hari ke-${diffDays} dari 90 hari magang`;
        
        // Check if date is in the past or future
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const selected = new Date(selectedDate);
        selected.setHours(0, 0, 0, 0);
        
        let statusInfo = '';
        if (selected > today) {
            statusInfo = '<span class="text-blue-600"><i class="bx bx-calendar-plus"></i> Tanggal di masa depan</span>';
        } else if (selected < today) {
            statusInfo = '<span class="text-green-600"><i class="bx bx-calendar-check"></i> Tanggal di masa lalu</span>';
        } else {
            statusInfo = '<span class="text-primary"><i class="bx bx-calendar-star"></i> Hari ini</span>';
        }
        
        // Check if logbook already exists for this date
        const existingLogbook = logbookData.find(log => log.tanggal === selectedDate);
        const logbookStatusInfo = document.getElementById('logbookStatusInfo');
        
        if (existingLogbook) {
            statusInfo += '<br><span class="text-orange-600 text-sm"><i class="bx bx-notepad"></i> Logbook sudah ada</span>';
            
            logbookStatusInfo.classList.remove('hidden');
            let statusClass = '';
            let statusIcon = '';
            
            if (existingLogbook.status === 'verified') {
                statusClass = 'bg-green-100 text-green-800';
                statusIcon = 'bx-check-circle';
            } else if (existingLogbook.status === 'pending') {
                statusClass = 'bg-yellow-100 text-yellow-800';
                statusIcon = 'bx-time';
            } else {
                statusClass = 'bg-red-100 text-red-800';
                statusIcon = 'bx-x-circle';
            }
            
            logbookStatusInfo.innerHTML = `
                <div class="${statusClass} p-3 rounded-lg">
                    <div class="flex items-center gap-2">
                        <i class='bx ${statusIcon}'></i>
                        <span class="font-semibold">Logbook untuk tanggal ini sudah ada</span>
                    </div>
                    <div class="text-sm mt-1">
                        Status: ${existingLogbook.status === 'verified' ? 'Diverifikasi' : 
                                existingLogbook.status === 'pending' ? 'Menunggu Verifikasi' : 'Ditolak'}
                        ${existingLogbook.jenis === 'izin' ? ' (Izin)' : existingLogbook.jenis === 'sakit' ? ' (Sakit)' : ''}
                    </div>
                    <button onclick="editLogbook('${existingLogbook.id}')" class="mt-2 text-sm text-blue-600 hover:text-blue-800">
                        <i class='bx bx-edit'></i> Edit Logbook
                    </button>
                </div>
            `;
            
            // Tampilkan notifikasi sekali saat memilih tanggal yang sudah diisi
            if (lastNotifiedDate !== selectedDate) {
                showNotification('info', 'Logbook sudah ada', `Anda telah mengisi logbook untuk ${dateText}.`);
                lastNotifiedDate = selectedDate;
            }
        } else {
            logbookStatusInfo.classList.add('hidden');
        }
        
        document.getElementById('dateStatus').innerHTML = statusInfo;
        
        // Show status section
        document.getElementById('statusSection').style.display = 'block';
        
        // Reset status selection
        resetKehadiranStatus();
    }
    
    // Edit existing logbook
    function editLogbook(logbookId) {
        const logbook = logbookData.find(log => log.id == logbookId);
        if (!logbook) return;
        
        // Set tanggal
        document.getElementById('tanggalKegiatan').value = logbook.tanggal;
        selectedDate = logbook.tanggal;
        updateTanggalInfo();
        
        // Prevent auto-submit while editing
        isEditing = true;
        setKehadiranStatus(logbook.jenis);
        
        // Fill form with existing data
        if (logbook.jenis === 'hadir') {
            document.getElementById('kegiatan').value = logbook.kegiatan;
            document.getElementById('deskripsi').value = logbook.deskripsi;
            
            if (logbook.waktu && logbook.waktu !== '-') {
                const [start, end] = logbook.waktu.split(' - ');
                document.getElementById('waktu_mulai').value = start;
                document.getElementById('waktu_selesai').value = end;
            }
        }
        
        showNotification('info', 'Mengedit logbook', 'Anda sedang mengedit logbook yang sudah ada.');
        isEditing = false;
    }
    
    // Set kehadiran status
    function setKehadiranStatus(status) {
        currentStatus = status;
        
        // Reset all buttons
        resetKehadiranStatus();
        
        // Highlight selected button
        const btn = document.getElementById(`btn${status.charAt(0).toUpperCase() + status.slice(1)}`);
        if (status === 'hadir') {
            btn.style.background = '#e6fff3';
            btn.style.borderColor = '#2ecc71';
            document.getElementById('selectedStatusInfo').innerHTML = `
                <div style="background: #e6fff3; padding: 15px; border-radius: 10px; border-left: 4px solid #2ecc71;">
                    <div class="flex items-center gap-3">
                        <i class='bx bx-check-circle text-2xl text-green-600'></i>
                        <div>
                            <div class="font-bold text-green-700">Status: Hadir</div>
                            <div class="text-sm text-green-600">Silakan isi logbook kegiatan hari ini</div>
                        </div>
                    </div>
                </div>
            `;
        } else if (status === 'izin') {
            btn.style.background = '#fff9e6';
            btn.style.borderColor = '#f1c40f';
            document.getElementById('selectedStatusInfo').innerHTML = `
                <div style="background: #fff9e6; padding: 15px; border-radius: 10px; border-left: 4px solid #f1c40f;">
                    <div class="flex items-center gap-3">
                        <i class='bx bx-alarm-exclamation text-2xl text-yellow-600'></i>
                        <div>
                            <div class="font-bold text-yellow-700">Status: Izin</div>
                            <div class="text-sm text-yellow-600">Izin dikirim otomatis</div>
                        </div>
                    </div>
                </div>
            `;
            // Auto-submit izin tanpa form input (hanya jika bukan sedang edit)
            if (!isEditing) setTimeout(() => { submitIzin(); }, 300);
            return;
        } else {
            btn.style.background = '#ffe6e6';
            btn.style.borderColor = '#e74c3c';
            document.getElementById('selectedStatusInfo').innerHTML = `
                <div style="background: #ffe6e6; padding: 15px; border-radius: 10px; border-left: 4px solid #e74c3c;">
                    <div class="flex items-center gap-3">
                        <i class='bx bx-plus-medical text-2xl text-red-600'></i>
                        <div>
                            <div class="font-bold text-red-700">Status: Sakit</div>
                            <div class="text-sm text-red-600">Laporan sakit dikirim otomatis</div>
                        </div>
                    </div>
                </div>
            `;
            // Auto-submit sakit tanpa form input (hanya jika bukan sedang edit)
            if (!isEditing) setTimeout(() => { submitSakit(); }, 300);
            return;
        }
        
        // Show appropriate form only for 'hadir'
        document.getElementById('logbookHadirForm').style.display = status === 'hadir' ? 'block' : 'none';
    }
    
    function resetKehadiranStatus() {
        // Reset all buttons
        const buttons = ['Hadir', 'Izin', 'Sakit'];
        buttons.forEach(status => {
            const btn = document.getElementById(`btn${status}`);
            if (btn) {
                btn.style.background = '';
                btn.style.borderColor = status === 'Hadir' ? '#d1fae5' : 
                                      status === 'Izin' ? '#fef3c7' : 
                                      '#fee2e2';
            }
        });
        
        // Hide hadir form
        document.getElementById('logbookHadirForm').style.display = 'none';
        
        document.getElementById('selectedStatusInfo').innerHTML = '';
        currentStatus = null;
    }
    
    // Preview uploaded file
    function previewBukti(input) {
        const preview = document.getElementById('bukti_preview');
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);
            
            if (fileSize > 5) {
                alert('File terlalu besar! Maksimal 5MB.');
                input.value = '';
                preview.innerHTML = '';
                return;
            }
            
            preview.innerHTML = `
                <div style="background: #e6f2ff; padding: 10px; border-radius: 8px; border: 1px solid #3498db; display: flex; align-items: center; gap: 10px;">
                    <i class='bx bx-image' style="color: #3498db; font-size: 1.2rem;"></i>
                    <div>
                        <div style="font-weight: 600; color: var(--primary);">${fileName}</div>
                        <div style="font-size: 0.85rem; color: #666;">${fileSize} MB</div>
                    </div>
                    <button onclick="removeFile()" class="ml-auto text-red-500 hover:text-red-700">
                        <i class='bx bx-trash'></i>
                    </button>
                </div>
            `;
        }
    }
    
    function removeFile() {
        document.getElementById('bukti_file').value = '';
        document.getElementById('bukti_preview').innerHTML = '';
    }
    
    // Submit logbook for "Hadir"
    function submitLogbookHadir() {
        if (!selectedDate || !currentStatus) {
            alert('Harap pilih tanggal dan status terlebih dahulu!');
            return;
        }
        
        const kegiatan = document.getElementById('kegiatan').value;
        const deskripsi = document.getElementById('deskripsi').value;
        
        if (!kegiatan || !deskripsi) {
            alert('Harap isi kegiatan dan deskripsi!');
            return;
        }
        
        // Simulasi submit
        const submitBtn = document.querySelector('#formLogbookHadir button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bx bx-loader-circle bx-spin"></i> Menyimpan...';
        submitBtn.disabled = true;
        
        setTimeout(() => {
            // Tampilkan notifikasi
            showNotification('success', 'Logbook berhasil disimpan!', 'Logbook telah disimpan dan menunggu verifikasi mentor.');
            
            // Reset form
            resetForm();
            
            // Update button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            // Tambahkan ke daftar
            const dateObj = new Date(selectedDate);
            const dateDisplay = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            
            const newLogbook = {
                id: logbookData.length + 1,
                tanggal: selectedDate,
                tanggalDisplay: dateDisplay,
                kegiatan: kegiatan,
                deskripsi: deskripsi,
                status: 'pending',
                jenis: currentStatus,
                catatan: '',
                waktu: document.getElementById('waktu_mulai').value + ' - ' + document.getElementById('waktu_selesai').value
            };
            
            logbookData.unshift(newLogbook);
            renderLogbook();
        }, 1500);
    }
    
    // Submit izin (otomatis, tanpa form input)
    function submitIzin() {
        if (!selectedDate) {
            alert('Harap pilih tanggal terlebih dahulu!');
            return;
        }
        
        const submitBtnPlaceholder = null;
        setTimeout(() => {
            showNotification('success', 'Izin berhasil dikirim!', 'Status: MENUNGGU VERIFIKASI');
            resetForm();
            
            const dateObj = new Date(selectedDate);
            const dateDisplay = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            
            const newLogbook = {
                id: logbookData.length + 1,
                tanggal: selectedDate,
                tanggalDisplay: dateDisplay,
                kegiatan: 'Izin (dikirim otomatis)',
                deskripsi: `Izin otomatis dikirim oleh peserta pada ${selectedDate}.`,
                status: 'pending',
                jenis: 'izin',
                catatan: '',
                waktu: '-'
            };
            
            logbookData.unshift(newLogbook);
            renderLogbook();
        }, 800);
    }
    
    // Submit sakit (otomatis, tanpa form input)
    function submitSakit() {
        if (!selectedDate) {
            alert('Harap pilih tanggal terlebih dahulu!');
            return;
        }
        
        setTimeout(() => {
            showNotification('success', 'Laporan sakit berhasil dikirim!', 'Status: MENUNGGU VERIFIKASI');
            resetForm();
            
            const dateObj = new Date(selectedDate);
            const dateDisplay = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            
            const newLogbook = {
                id: logbookData.length + 1,
                tanggal: selectedDate,
                tanggalDisplay: dateDisplay,
                kegiatan: 'Sakit (laporan otomatis)',
                deskripsi: `Laporan sakit otomatis dikirim oleh peserta pada ${selectedDate}.`,
                status: 'pending',
                jenis: 'sakit',
                catatan: '',
                waktu: '-'
            };
            
            logbookData.unshift(newLogbook);
            renderLogbook();
        }, 800);
    }
    
    // Reset form
    function resetForm() {
        document.getElementById('kegiatan').value = '';
        document.getElementById('deskripsi').value = '';
        document.getElementById('hasil').value = '';
        document.getElementById('tantangan').value = '';
        document.getElementById('waktu_mulai').value = '08:00';
        document.getElementById('waktu_selesai').value = '16:00';
        document.getElementById('bukti_preview').innerHTML = '';
        document.getElementById('alasanIzin').value = '';
        document.getElementById('jenisSakit').value = '';
        document.getElementById('deskripsiSakit').value = '';
        
        // Reset tanggal to today
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0];
        document.getElementById('tanggalKegiatan').value = formattedDate;
        selectedDate = formattedDate;
        
        updateTanggalInfo();
        resetKehadiranStatus();
    }
    
    // Show notification
    function showNotification(type, title, message) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-sm ${
            type === 'success' ? 'bg-green-100 border-l-4 border-green-500 text-green-700' :
            type === 'error' ? 'bg-red-100 border-l-4 border-red-500 text-red-700' :
            'bg-blue-100 border-l-4 border-blue-500 text-blue-700'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class='bx ${
                    type === 'success' ? 'bx-check-circle text-xl' :
                    type === 'error' ? 'bx-x-circle text-xl' :
                    'bx-info-circle text-xl'
                } mr-3'></i>
                <div>
                    <div class="font-bold">${title}</div>
                    <div class="text-sm">${message}</div>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
    
    function renderLogbook() {
        const container = document.getElementById('logbookList');
        const bulan = document.getElementById('filterBulan').value;
        const status = document.getElementById('filterStatus').value;
        
        let filteredData = logbookData;
        
        if (status !== 'all') {
            filteredData = filteredData.filter(item => item.status === status);
        }
        
        if (bulan !== 'all') {
            // Filter berdasarkan bulan
            filteredData = filteredData.filter(item => {
                const date = new Date(item.tanggal);
                return date.getMonth() + 1 == bulan;
            });
        }
        
        container.innerHTML = '';
        
        filteredData.forEach(item => {
            const statusBadge = item.status === 'verified' ? 
                '<span class="status-badge status-approved">DIVERIFIKASI</span>' : 
                '<span class="status-badge status-pending">MENUNGGU</span>';
            
            const jenisBadge = item.jenis === 'hadir' ? 
                '<span style="background:#e6fff3; color:#2ecc71; padding:4px 12px; border-radius:20px; font-size:0.8rem;">Hadir</span>' :
                item.jenis === 'izin' ?
                '<span style="background:#fff9e6; color:#f1c40f; padding:4px 12px; border-radius:20px; font-size:0.8rem;">Izin</span>' :
                '<span style="background:#ffe6e6; color:#e74c3c; padding:4px 12px; border-radius:20px; font-size:0.8rem;">Sakit</span>';
            
            const logbookItem = `
                <div style="background: white; border-radius: 12px; padding: 20px; margin-bottom: 15px; border: 1px solid #eee; transition: all 0.3s;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                        <div>
                            <div style="font-weight: 600; color: var(--primary); font-size: 1.1rem;">${item.tanggalDisplay}</div>
                            <div style="color: #666; font-size: 0.9rem; margin-top: 5px;">${item.waktu}</div>
                        </div>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            ${jenisBadge}
                            ${statusBadge}
                            <button onclick="editLogbook('${item.id}')" class="text-blue-600 hover:text-blue-800">
                                <i class='bx bx-edit'></i>
                            </button>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <div style="font-weight: 600; color: var(--primary); margin-bottom: 5px;">${item.kegiatan}</div>
                        <div style="color: #666; line-height: 1.6;">${item.deskripsi}</div>
                    </div>
                    
                    ${item.catatan ? `
                    <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border-left: 4px solid var(--accent); margin-top: 15px;">
                        <div style="font-weight: 600; color: var(--primary); margin-bottom: 5px;">Catatan Mentor:</div>
                        <div style="color: #666;">${item.catatan}</div>
                    </div>
                    ` : ''}
                </div>
            `;
            
            container.innerHTML += logbookItem;
        });
        
        if (filteredData.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; padding: 40px; color: #666;">
                    <i class='bx bx-book-open' style="font-size: 3rem; opacity: 0.3; margin-bottom: 15px;"></i>
                    <p>Tidak ada logbook ditemukan</p>
                </div>
            `;
        }
    }
    
    function filterLogbook() {
        renderLogbook();
    }
    
    function loadMore() {
        // Simulasi load more
        showNotification('info', 'Memuat data', 'Memuat data logbook lebih banyak...');
    }
</script>

<style>
    .grid {
        display: grid;
    }
    .grid-cols-7 {
        grid-template-columns: repeat(7, minmax(0, 1fr));
    }
    .grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
    @media (min-width: 768px) {
        .md\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    .gap-1 { gap: 0.25rem; }
    .gap-2 { gap: 0.5rem; }
    .gap-3 { gap: 0.75rem; }
    .gap-4 { gap: 1rem; }
    .relative { position: relative; }
    .absolute { position: absolute; }
    .fixed { position: fixed; }
    .top-4 { top: 1rem; }
    .right-4 { right: 1rem; }
    .left-3 { left: 0.75rem; }
    .top-3 { top: 0.75rem; }
    .left-1\/2 { left: 50%; }
    .-bottom-1 { bottom: -0.25rem; }
    .transform { transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y)); }
    .-translate-x-1\/2 { --tw-translate-x: -50%; }
    .z-50 { z-index: 50; }
    .ml-auto { margin-left: auto; }
    .mt-1 { margin-top: 0.25rem; }
    .mt-2 { margin-top: 0.5rem; }
    .mt-3 { margin-top: 0.75rem; }
    .mt-4 { margin-top: 1rem; }
    .mb-1 { margin-bottom: 0.25rem; }
    .mb-2 { margin-bottom: 0.5rem; }
    .mb-3 { margin-bottom: 0.75rem; }
    .mb-4 { margin-bottom: 1rem; }
    .mb-6 { margin-bottom: 1.5rem; }
    .mr-3 { margin-right: 0.75rem; }
    .flex { display: flex; }
    .hidden { display: none; }
    .items-center { align-items: center; }
    .justify-center { justify-content: center; }
    .justify-between { justify-content: space-between; }
    .flex-wrap { flex-wrap: wrap; }
    .rounded-lg { border-radius: 0.5rem; }
    .rounded-full { border-radius: 9999px; }
    .h-10 { height: 2.5rem; }
    .w-10 { width: 2.5rem; }
    .w-2 { width: 0.5rem; }
    .h-2 { height: 0.5rem; }
    .max-w-sm { max-width: 24rem; }
    .p-2 { padding: 0.5rem; }
    .p-3 { padding: 0.75rem; }
    .p-4 { padding: 1rem; }
    .py-1 { padding-top: 0.25rem; padding-bottom: 0.25rem; }
    .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
    .pl-10 { padding-left: 2.5rem; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .text-lg { font-size: 1.125rem; line-height: 1.75rem; }
    .text-xl { font-size: 1.25rem; line-height: 1.75rem; }
    .text-2xl { font-size: 1.5rem; line-height: 2rem; }
    .text-sm { font-size: 0.875rem; line-height: 1.25rem; }
    .text-xs { font-size: 0.75rem; line-height: 1rem; }
    .font-semibold { font-weight: 600; }
    .font-medium { font-weight: 500; }
    .font-bold { font-weight: 700; }
    .text-gray-500 { color: #6b7280; }
    .text-gray-600 { color: #4b5563; }
    .text-gray-700 { color: #374151; }
    .text-blue-600 { color: #2563eb; }
    .text-blue-700 { color: #1d4ed8; }
    .text-green-600 { color: #16a34a; }
    .text-green-700 { color: #15803d; }
    .text-green-800 { color: #166534; }
    .text-yellow-600 { color: #ca8a04; }
    .text-yellow-700 { color: #a16207; }
    .text-yellow-800 { color: #854d0e; }
    .text-red-600 { color: #dc2626; }
    .text-red-700 { color: #b91c1c; }
    .text-red-800 { color: #991b1b; }
    .text-orange-600 { color: #ea580c; }
    .text-purple-700 { color: #7c3aed; }
    .bg-green-100 { background-color: #dcfce7; }
    .bg-green-500 { background-color: #22c55e; }
    .bg-yellow-100 { background-color: #fef3c7; }
    .bg-yellow-500 { background-color: #eab308; }
    .bg-red-100 { background-color: #fee2e2; }
    .bg-blue-100 { background-color: #dbeafe; }
    .bg-gray-100 { background-color: #f3f4f6; }
    .bg-purple-100 { background-color: #f3e8ff; }
    .hover\:bg-gray-100:hover { background-color: #f3f4f6; }
    .hover\:bg-blue-200:hover { background-color: #bfdbfe; }
    .hover\:bg-gray-200:hover { background-color: #e5e7eb; }
    .hover\:bg-purple-200:hover { background-color: #e9d5ff; }
    .hover\:bg-green-50:hover { background-color: #f0fdf4; }
    .hover\:bg-yellow-50:hover { background-color: #fefce8; }
    .hover\:bg-red-50:hover { background-color: #fef2f2; }
    .border { border-width: 1px; }
    .border-2 { border-width: 2px; }
    .border-l-4 { border-left-width: 4px; }
    .border-gray-300 { border-color: #d1d5db; }
    .border-green-200 { border-color: #bbf7d0; }
    .border-yellow-200 { border-color: #fde68a; }
    .border-red-200 { border-color: #fecaca; }
    .border-green-500 { border-color: #22c55e; }
    .border-blue-500 { border-color: #3b82f6; }
    .border-red-500 { border-color: #ef4444; }
    .ring-2 { --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color); --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color); box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000); }
    .ring-primary { --tw-ring-color: var(--primary); }
    .shadow-lg { --tw-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow); }
    .transition-colors { transition-property: background-color, border-color, color, fill, stroke; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }
    .overflow-x-auto { overflow-x: auto; }
    .cursor-pointer { cursor: pointer; }
</style>
@endsection