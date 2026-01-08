@extends('layouts.peserta')

@section('title', 'Pengajuan Magang')

@section('content')
<div class="form-card">
    <h2 style="color: var(--primary); margin-bottom: 30px; display: flex; align-items: center; gap: 10px;">
        <i class='bx bx-file'></i> Formulir Pengajuan Magang
    </h2>
    
    <!-- Status Pengajuan -->
    <div style="background: #f8fafc; padding: 20px; border-radius: 12px; margin-bottom: 30px; border-left: 4px solid var(--accent);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="color: var(--primary); margin-bottom: 5px;">Status Pengajuan Anda</h3>
                <p style="color: #666; font-size: 0.9rem;">Terakhir diperbarui: 12 Mar 2024, 14:30</p>
            </div>
            <span class="status-badge status-pending">MENUNGGU VERIFIKASI</span>
        </div>
        <div style="margin-top: 15px; display: flex; align-items: center; gap: 10px;">
            <div style="flex: 1; height: 6px; background: #e2e8f0; border-radius: 3px; overflow: hidden;">
                <div style="width: 75%; height: 100%; background: var(--accent);"></div>
            </div>
            <span style="font-size: 0.9rem; color: #666;">Step 3/4</span>
        </div>
    </div>
    
    <form id="formMagang" onsubmit="event.preventDefault(); submitPengajuan();">
        <!-- Data Pribadi -->
        <div class="form-section">
            <h3><i class='bx bx-user'></i> Data Pribadi</h3>
            
            <div class="form-group">
                <label for="nama">Nama Lengkap *</label>
                <input type="text" id="nama" value="John Doe" required disabled>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" value="john.doe@uns.ac.id" required disabled>
                </div>
                
                <div class="form-group">
                    <label for="no_telp">No. WhatsApp *</label>
                    <input type="tel" id="no_telp" value="0812-3456-7890" required>
                </div>
            </div>
        </div>
        
        <!-- Data Akademik -->
        <div class="form-section">
            <h3><i class='bx bx-graduation'></i> Data Akademik</h3>
            
            <div class="form-group">
                <label for="nim">NIM/NISN *</label>
                <input type="text" id="nim" placeholder="G123456789" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="universitas">Universitas/Sekolah *</label>
                    <input type="text" id="universitas" placeholder="Universitas Sebelas Maret" required>
                </div>
                
                <div class="form-group">
                    <label for="jurusan">Program Studi/Jurusan *</label>
                    <input type="text" id="jurusan" placeholder="Teknik Informatika" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="semester">Semester/Tingkat *</label>
                    <select id="semester" required>
                        <option value="">Pilih Semester</option>
                        <option value="6">Semester 1</option>
                        <option value="6">Semester 2</option>
                        <option value="6">Semester 3</option>
                        <option value="6">Semester 4</option>
                        <option value="5" selected>Semester 5</option>
                        <option value="6">Semester 6</option>
                        <option value="7">Semester 7</option>
                        <option value="8">Semester 8</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Pilihan Magang -->
        <div class="form-section">
            <h3><i class='bx bx-target-lock'></i> Pilihan Magang</h3>
            
            <div class="form-group">
                <label for="bidang_pilihan">Bidang yang diminati *</label>
                <select id="bidang_pilihan" required>
                    <option value="">Pilih Bidang Magang</option>
                    <option value="1">Statistik (Analisis Data)</option>
                    <option value="2" selected>Informatika (Programming & IT)</option>
                    <option value="3">Kesekretariatan (Administrasi)</option>
                    <option value="4">Penyelenggara (Event & Logistics)</option>
                </select>
                <small style="color: #666; display: block; margin-top: 5px;">Penempatan final ditentukan oleh Admin Bidang</small>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="tanggal_mulai">Tanggal Mulai *</label>
                    <input type="date" id="tanggal_mulai" required>
                </div>
                
                <div class="form-group">
                    <label for="tanggal_selesai">Tanggal Selesai *</label>
                    <input type="date" id="tanggal_selesai" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="alasan">Alasan memilih bidang ini *</label>
                <textarea id="alasan" rows="3" placeholder="Jelaskan mengapa Anda tertarik dengan bidang ini dan bagaimana keterkaitannya dengan studi Anda..." required></textarea>
            </div>
        </div>
        
        <!-- Upload Berkas -->
        <div class="form-section">
            <h3><i class='bx bx-paperclip'></i> Upload Berkas</h3>
            
            <div class="form-group">
                <label>CV/Resume *</label>
                <div class="upload-area" onclick="document.getElementById('cv_file').click()">
                    <div class="upload-icon">
                        <i class='bx bx-file'></i>
                    </div>
                    <div style="font-weight: 600; margin-bottom: 5px;">Unggah CV/Resume</div>
                    <small>Format PDF/DOC, max 2MB</small>
                    <input type="file" id="cv_file" accept=".pdf,.doc,.docx" style="display:none;" onchange="previewFile(this, 'cv_preview')">
                </div>
                <div id="cv_preview" style="margin-top: 10px;"></div>
            </div>
            
            <div class="form-group">
                <label>Surat Penempatan Magang *</label>
                <div class="upload-area" onclick="document.getElementById('surat_file').click()">
                    <div class="upload-icon">
                        <i class='bx bx-file-doc'></i>
                    </div>
                    <div style="font-weight: 600; margin-bottom: 5px;">Unggah Surat Penempatan</div>
                    <small>Format PDF, max 2MB</small>
                    <input type="file" id="surat_file" accept=".pdf" style="display:none;" onchange="previewFile(this, 'surat_preview')">
                </div>
                <div id="surat_preview" style="margin-top: 10px;"></div>
            </div>
        </div>
        
        <!-- Terms -->
        <div class="form-group">
            <label style="display: flex; align-items: flex-start; gap: 10px;">
                <input type="checkbox" id="terms" required style="margin-top: 3px;">
                <span>Saya menyatakan bahwa data yang saya berikan adalah benar dan siap bertanggung jawab jika ditemukan ketidaksesuaian. Saya juga setuju untuk mengikuti seluruh proses magang sesuai ketentuan Diskominfo SP Surakarta.</span>
            </label>
        </div>
        
        <!-- Submit Button -->
        <div style="display: flex; gap: 15px; margin-top: 30px;">
            <button type="submit" class="btn btn-primary" style="flex: 2;">
                <i class='bx bx-send'></i> Ajukan Magang
            </button>
            <button type="button" class="btn" onclick="window.location.href='/peserta/dashboard'" style="flex: 1; background: #f8fafc; color: #666;">
                Batal
            </button>
        </div>
    </form>
</div>
<script>
    // Set tanggal default
    const today = new Date();
    const nextMonth = new Date(today);
    nextMonth.setMonth(today.getMonth() + 3);
    
    document.getElementById('tanggal_mulai').value = today.toISOString().split('T')[0];
    document.getElementById('tanggal_selesai').value = nextMonth.toISOString().split('T')[0];
    
    // Validasi tanggal
    document.getElementById('tanggal_mulai').addEventListener('change', function() {
        const endDate = document.getElementById('tanggal_selesai');
        endDate.min = this.value;
        if (endDate.value < this.value) {
            endDate.value = this.value;
        }
    });
    
    // Preview file
    function previewFile(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);
            
            if (fileSize > 2) {
                alert('File terlalu besar! Maksimal 2MB.');
                input.value = '';
                preview.innerHTML = '';
                return;
            }
            
            preview.innerHTML = `
                <div style="background: #e6fff3; padding: 10px; border-radius: 8px; border: 1px solid #2ecc71; display: flex; align-items: center; gap: 10px;">
                    <i class='bx bx-check-circle' style="color: #2ecc71; font-size: 1.2rem;"></i>
                    <div>
                        <div style="font-weight: 600; color: var(--primary);">${fileName}</div>
                        <div style="font-size: 0.85rem; color: #666;">${fileSize} MB</div>
                    </div>
                </div>
            `;
        }
    }
    
    // Submit pengajuan
    function submitPengajuan() {
        const bidang = document.getElementById('bidang_pilihan').value;
        const tanggalMulai = document.getElementById('tanggal_mulai').value;
        const tanggalSelesai = document.getElementById('tanggal_selesai').value;
        
        if (!bidang || !tanggalMulai || !tanggalSelesai) {
            alert('Harap lengkapi semua field yang wajib diisi!');
            return;
        }
        
        if (!document.getElementById('terms').checked) {
            alert('Anda harus menyetujui pernyataan!');
            return;
        }
        
        // Simulasi loading
        const submitBtn = document.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bx bx-loader-circle bx-spin"></i> Mengirim...';
        submitBtn.disabled = true;
        
        setTimeout(() => {
            // Tampilkan modal sukses
            const modal = `
                <div style="position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.7); display:flex; align-items:center; justify-content:center; z-index:9999;">
                    <div style="background:white; padding:40px; border-radius:20px; text-align:center; max-width:500px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.3);">
                        <div style="font-size:4rem; color:#2ecc71; margin-bottom:20px;">🎉</div>
                        <h2 style="color:var(--primary); margin-bottom:15px;">Pengajuan Berhasil!</h2>
                        <p>Pengajuan magang Anda telah berhasil dikirim.</p>
                        <div style="background:#f8fafc; padding:20px; border-radius:12px; margin:20px 0; text-align:left;">
                            <p style="margin:0 0 10px 0;"><strong>Bidang:</strong> Informatika</p>
                            <p style="margin:0 0 10px 0;"><strong>Periode:</strong> ${new Date(tanggalMulai).toLocaleDateString('id-ID')} - ${new Date(tanggalSelesai).toLocaleDateString('id-ID')}</p>
                            <p style="margin:0;"><strong>Status:</strong> <span class="status-badge status-pending">MENUNGGU</span></p>
                        </div>
                        <p style="color:#666; margin-bottom:25px;">Tim Admin akan meninjau pengajuan Anda dalam 1-3 hari kerja.</p>
                        <div style="display:flex; gap:15px;">
                            <button onclick="window.location.href='/peserta/dashboard'" style="flex:1; background:var(--primary); color:white; border:none; padding:12px; border-radius:10px; font-weight:bold; cursor:pointer;">
                                Ke Dashboard
                            </button>
                            <button onclick="this.parentElement.parentElement.parentElement.remove()" style="flex:1; background:transparent; color:var(--primary); border:2px solid var(--primary); padding:12px; border-radius:10px; cursor:pointer;">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modal);
            
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 2000);
    }
</script>
@endsection