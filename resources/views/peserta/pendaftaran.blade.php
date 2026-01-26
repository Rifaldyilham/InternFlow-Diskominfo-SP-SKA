@extends('layouts.peserta')

@section('title', 'Pengajuan Magang')

@section('content')
<div class="form-card">
    <h2 class="form-title">
        <i class='bx bx-file'></i> Formulir Pengajuan Magang
    </h2>
    
    <!-- Info Alert -->
    <div class="info-alert">
        <i class='bx bx-info-circle'></i>
        <div>
            <strong>Informasi Penting</strong>
            <p>Pastikan data yang diisi benar dan lengkap. Pengajuan akan diverifikasi oleh Admin Kepegawaian dalam 1-3 hari kerja.</p>
        </div>
    </div>
    
    <!-- Loading State -->
    <div id="loadingState" class="text-center py-8">
        <i class='bx bx-loader-circle bx-spin text-4xl text-primary'></i>
        <div class="text-gray-600 mt-4">Memuat data...</div>
    </div>
    
    <!-- Already Applied State -->
    <div id="alreadyAppliedState" class="hidden">
        <div class="applied-status-card">
            <div class="applied-status-header">
                <div>
                    <h3 class="text-primary font-bold text-xl mb-2">Pengajuan Sudah Diajukan</h3>
                    <p class="text-gray-600">Anda sudah memiliki pengajuan magang yang sedang diproses</p>
                </div>
                <div id="appliedStatusBadge" class="status-badge status-pending">MENUNGGU</div>
            </div>
            
            <div class="applied-details">
                <div class="detail-item">
                    <label>Bidang:</label>
                    <span id="appliedBidang">-</span>
                </div>
                <div class="detail-item">
                    <label>Periode:</label>
                    <span id="appliedPeriode">-</span>
                </div>
                <div class="detail-item">
                    <label>Tanggal Pengajuan:</label>
                    <span id="appliedTanggal">-</span>
                </div>
            </div>
            
            <div class="mt-6 text-center">
                <a href="{{ route('peserta.dashboard') }}" class="btn btn-primary">
                    <i class='bx bx-home'></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
    
    <!-- Form Pengajuan -->
    <form id="formPengajuan" class="hidden" enctype="multipart/form-data">
        <!-- Data Pribadi -->
        <div class="form-section">
            <h3 class="section-title">
                <i class='bx bx-user'></i> Data Pribadi
            </h3>
            
            <div class="form-group">
                <label for="nama">Nama Lengkap *</label>
                <input type="text" id="nama" name="nama" value="{{ Auth::user()->name ?? '' }}" required readonly>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" value="{{ Auth::user()->email ?? '' }}" required readonly>
                </div>
                
                <div class="form-group">
                    <label for="no_telp">No. WhatsApp *</label>
                    <input type="tel" id="no_telp" name="no_telp" placeholder="0812-3456-7890" required>
                </div>
            </div>
        </div>
        
        <!-- Data Akademik -->
        <div class="form-section">
            <h3 class="section-title">
                <i class='bx bx-graduation'></i> Data Akademik
            </h3>
            
            <div class="form-group">
                <label for="nim">NIM/NISN *</label>
                <input type="text" id="nim" name="nim" placeholder="G123456789" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="universitas">Universitas/Sekolah *</label>
                    <input type="text" id="universitas" name="universitas" placeholder="Universitas Sebelas Maret" required>
                </div>
                
                <div class="form-group">
                    <label for="jurusan">Program Studi/Jurusan *</label>
                    <input type="text" id="jurusan" name="jurusan" placeholder="Teknik Informatika" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="semester">Semester/Tingkat *</label>
                    <select id="semester" name="semester" required>
                        <option value="">Pilih Semester</option>
                        <option value="1">Semester 1</option>
                        <option value="2">Semester 2</option>
                        <option value="3">Semester 3</option>
                        <option value="4">Semester 4</option>
                        <option value="5">Semester 5</option>
                        <option value="6">Semester 6</option>
                        <option value="7">Semester 7</option>
                        <option value="8">Semester 8</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ipk">IPK/Nilai (Opsional)</label>
                    <input type="number" id="ipk" name="ipk" step="0.01" min="0" max="4" placeholder="3.50">
                </div>
            </div>
        </div>
        
        <!-- Pilihan Magang -->
        <div class="form-section">
            <h3 class="section-title">
                <i class='bx bx-target-lock'></i> Pilihan Magang
            </h3>
            
            <div class="form-group">
                <label for="bidang_pilihan">Bidang yang diminati *</label>
                <select id="bidang_pilihan" name="bidang_pilihan" required>
                    <option value="">Pilih Bidang Magang</option>
                    <option value="statistik">Statistik</option>
                    <option value="informatika">Informatika</option>
                    <option value="sekretariat">Kesekretariatan</option>
                    <option value="e-goverment">E-Goverment</option>
                </select>
                <small class="form-hint">Penempatan final akan ditentukan oleh Admin Bidang berdasarkan ketersediaan dan kesesuaian</small>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="tanggal_mulai">Tanggal Mulai *</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" required>
                </div>
                
                <div class="form-group">
                    <label for="tanggal_selesai">Tanggal Selesai *</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="alasan">Alasan memilih bidang ini *</label>
                <textarea id="alasan" name="alasan" rows="4" 
                    placeholder="Jelaskan mengapa Anda tertarik dengan bidang ini, keterkaitannya dengan studi Anda, dan tujuan yang ingin dicapai selama magang..." required></textarea>
                <small class="form-hint">Minimal 150 karakter</small>
                <div class="char-counter">
                    <span id="charCount">0</span> / 500 karakter
                </div>
            </div>
        </div>
        
        <!-- Upload Berkas -->
        <div class="form-section">
            <h3 class="section-title">
                <i class='bx bx-paperclip'></i> Upload Berkas
            </h3>
            
            <div class="warning-alert">
                <i class='bx bx-error-circle'></i>
                <div>
                    <strong>Format File</strong>
                    <p>Pastikan file dalam format PDF. Maksimal ukuran file: 2MB per dokumen.</p>
                </div>
            </div>
            
            <div class="form-group">
                <label>CV/Resume *</label>
                <div class="file-upload-area" id="cvUploadArea">
                    <input type="file" id="cv_file" name="cv_file" accept=".pdf" class="file-input" required>
                    <div class="upload-content">
                        <i class='bx bx-cloud-upload'></i>
                        <div class="upload-text">
                            <div class="upload-title">Unggah CV/Resume</div>
                            <div class="upload-subtitle">Format PDF, maksimal 2MB</div>
                        </div>
                    </div>
                    <div class="file-name" id="cvFileName">Belum ada file dipilih</div>
                </div>
                <div id="cvError" class="file-error"></div>
            </div>
            
            <div class="form-group">
                <label>Surat Pengantar/Penempatan *</label>
                <div class="file-upload-area" id="suratUploadArea">
                    <input type="file" id="surat_file" name="surat_file" accept=".pdf" class="file-input" required>
                    <div class="upload-content">
                        <i class='bx bx-cloud-upload'></i>
                        <div class="upload-text">
                            <div class="upload-title">Unggah Surat Pengantar</div>
                            <div class="upload-subtitle">Format PDF, maksimal 2MB</div>
                        </div>
                    </div>
                    <div class="file-name" id="suratFileName">Belum ada file dipilih</div>
                </div>
                <div id="suratError" class="file-error"></div>
            </div>
        </div>
        
        <!-- Terms & Conditions -->
        <div class="form-section">
            <div class="terms-box">
                <h4 class="terms-title">
                    <i class='bx bx-check-shield'></i> Pernyataan dan Persetujuan
                </h4>
                <div class="terms-content">
                    <p>Saya menyatakan bahwa data dan informasi yang saya berikan dalam formulir ini adalah benar dan dapat dipertanggungjawabkan.</p>
                    <p>Saya bersedia untuk:</p>
                    <ul>
                        <li>Mengikuti seluruh aturan dan tata tertib selama magang di Diskominfo SP Surakarta</li>
                        <li>Hadir tepat waktu dan mengikuti kegiatan magang sesuai jadwal</li>
                        <li>Menyelesaikan tugas dan tanggung jawab yang diberikan oleh mentor/pembimbing</li>
                        <li>Menjaga kerahasiaan informasi internal organisasi</li>
                        <li>Mengembalikan semua aset organisasi yang dipinjamkan setelah magang selesai</li>
                    </ul>
                    <p>Diskominfo SP Surakarta berhak untuk:</p>
                    <ul>
                        <li>Memverifikasi dan memvalidasi data yang diberikan</li>
                        <li>Menentukan penempatan bidang sesuai kebutuhan dan ketersediaan</li>
                        <li>Membatalkan penerimaan jika ditemukan ketidaksesuaian data</li>
                        <li>Memberikan evaluasi dan penilaian selama masa magang</li>
                    </ul>
                </div>
                
                <div class="terms-agreement">
                    <label class="terms-checkbox">
                        <input type="checkbox" id="terms" name="terms" required>
                        <span class="checkmark"></span>
                        <span class="terms-text">
                            Saya telah membaca, memahami, dan menyetujui semua pernyataan di atas
                        </span>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class='bx bx-send'></i> Ajukan Magang
            </button>
            <a href="{{ route('peserta.dashboard') }}" class="btn btn-secondary">
                <i class='bx bx-x'></i> Batal
            </a>
        </div>
    </form>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal hidden">
    <div class="modal-content">
        <div class="modal-success-icon">
            <i class='bx bx-check-circle'></i>
        </div>
        <div class="modal-body">
            <h3 class="modal-title">Pengajuan Berhasil!</h3>
            <p>Pengajuan magang Anda telah berhasil dikirim dan akan diproses oleh tim admin.</p>
            
            <div class="success-details">
                <div class="detail-item">
                    <label>Status:</label>
                    <span class="status-badge status-pending">MENUNGGU VERIFIKASI</span>
                </div>
                <div class="detail-item">
                    <label>Bidang:</label>
                    <span id="modalBidang">-</span>
                </div>
                <div class="detail-item">
                    <label>Periode:</label>
                    <span id="modalPeriode">-</span>
                </div>
                <div class="detail-item">
                    <label>Estimasi:</label>
                    <span>Proses verifikasi 1-3 hari kerja</span>
                </div>
            </div>
            
            <p class="text-gray-600 text-sm mt-4">
                Anda dapat memantau status pengajuan di halaman Dashboard. Notifikasi akan dikirim via email jika ada pembaruan.
            </p>
        </div>
        <div class="modal-footer">
            <a href="{{ route('peserta.dashboard') }}" class="btn btn-primary">
                <i class='bx bx-home'></i> Ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Konfigurasi API
const API_CONFIG = {
    endpoints: {
        check: '/api/peserta/pengajuan/status',
        submit: '/api/peserta/pengajuan',
        bidang: '/api/bidang'
    }
};

// State management
let state = {
    hasExistingPengajuan: false,
    existingPengajuan: null,
    bidangList: [],
    formData: {
        user_id: {{ Auth::id() ?? 'null' }}
    }
};

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    initializeForm();
    checkExistingPengajuan();
    setupEventListeners();
    setupDateValidation();
});

// Initialize form
function initializeForm() {
    // Set tanggal default (mulai 1 bulan dari sekarang, selesai 3 bulan kemudian)
    const today = new Date();
    const startDate = new Date(today);
    startDate.setMonth(today.getMonth() + 1);
    startDate.setDate(1); // Tanggal 1 bulan depan
    
    const endDate = new Date(startDate);
    endDate.setMonth(startDate.getMonth() + 3);
    
    document.getElementById('tanggal_mulai').value = formatDateForInput(startDate);
    document.getElementById('tanggal_selesai').value = formatDateForInput(endDate);
    
    // Set min date untuk tanggal mulai (besok)
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('tanggal_mulai').min = formatDateForInput(tomorrow);
    
    // Update user email and name from Laravel Auth
    const userEmail = document.getElementById('email');
    const userName = document.getElementById('nama');
    
    if (userEmail && userName) {
        userEmail.value = '{{ Auth::user()->email ?? "" }}';
        userName.value = '{{ Auth::user()->name ?? "" }}';
    }
}

// Check existing pengajuan
async function checkExistingPengajuan() {
    try {
        showLoading(true);
        
        // Simulasi API call - nanti ganti dengan fetch
        // const response = await fetch(API_CONFIG.endpoints.check);
        // const data = await response.json();
        
        // Untuk sekarang, simulasi belum ada pengajuan
        setTimeout(() => {
            showLoading(false);
            
            // Jika sudah ada pengajuan
            // if (data.hasPengajuan) {
            //     state.hasExistingPengajuan = true;
            //     state.existingPengajuan = data.pengajuan;
            //     showAlreadyApplied(data.pengajuan);
            // } else {
            //     showForm();
            // }
            
            showForm(); // Tampilkan form karena belum ada pengajuan
        }, 1000);
        
    } catch (error) {
        console.error('Error checking existing pengajuan:', error);
        showLoading(false);
        showError('Gagal memuat data. Silakan refresh halaman.');
    }
}

// Setup event listeners
function setupEventListeners() {
    // Character counter for alasan textarea
    const alasanTextarea = document.getElementById('alasan');
    const charCount = document.getElementById('charCount');
    
    alasanTextarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
    
    // File upload preview
    setupFileUpload('cv_file', 'cvUploadArea', 'cvFileName', 'cvError');
    setupFileUpload('surat_file', 'suratUploadArea', 'suratFileName', 'suratError');
    
    // Form submission
    const form = document.getElementById('formPengajuan');
    form.addEventListener('submit', handleFormSubmit);
    
    // Modal close on backdrop click
    const modal = document.getElementById('successModal');
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeSuccessModal();
        }
    });
}

// Setup date validation
function setupDateValidation() {
    const startDateInput = document.getElementById('tanggal_mulai');
    const endDateInput = document.getElementById('tanggal_selesai');
    
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
        
        // Validate end date
        if (endDateInput.value && endDateInput.value < this.value) {
            endDateInput.value = this.value;
        }
        
        // Validate duration (min 1 month, max 6 months)
        validateDuration();
    });
    
    endDateInput.addEventListener('change', validateDuration);
}

// Validate duration between dates
function validateDuration() {
    const startDate = new Date(document.getElementById('tanggal_mulai').value);
    const endDate = new Date(document.getElementById('tanggal_selesai').value);
    
    if (!startDate || !endDate) return;
    
    const diffTime = Math.abs(endDate - startDate);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    const diffMonths = diffDays / 30;
    
    if (diffMonths < 1) {
        showFieldError('tanggal_selesai', 'Durasi magang minimal 1 bulan');
    } else if (diffMonths > 6) {
        showFieldError('tanggal_selesai', 'Durasi magang maksimal 6 bulan');
    } else {
        clearFieldError('tanggal_selesai');
    }
}

// Setup file upload
function setupFileUpload(inputId, areaId, fileNameId, errorId = null) {
    const input = document.getElementById(inputId);
    const area = document.getElementById(areaId);
    const fileName = document.getElementById(fileNameId);
    
    if (!input || !area || !fileName) return;
    
    // Click area to trigger file input
    area.addEventListener('click', function(e) {
        if (e.target !== input) {
            input.click();
        }
    });
    
    // Handle file selection
    input.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            
            // Validate file
            if (!validateFile(file, errorId)) {
                this.value = '';
                fileName.textContent = 'Belum ada file dipilih';
                return;
            }
            
            fileName.textContent = file.name;
            
            // Show preview (for PDF, we can't preview directly)
            if (file.type === 'application/pdf') {
                clearFileError(errorId);
            }
        }
    });
    
    // Prevent drag and drop default behavior
    area.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.add('dragover');
    });
    
    area.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('dragover');
    });
    
    area.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('dragover');
        
        if (e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            input.dispatchEvent(new Event('change'));
        }
    });
}

// Validate file
function validateFile(file, errorId) {
    // Check file size (max 2MB)
    if (file.size > 2 * 1024 * 1024) {
        if (errorId) {
            showFileError(errorId, 'Ukuran file terlalu besar. Maksimal 2MB.');
        } else {
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
        }
        return false;
    }
    
    // Check file type
    if (!file.type.includes('pdf')) {
        if (errorId) {
            showFileError(errorId, 'File harus dalam format PDF.');
        } else {
            alert('File harus dalam format PDF.');
        }
        return false;
    }
    
    return true;
}

// Handle form submission
async function handleFormSubmit(e) {
    e.preventDefault();
    
    if (!validateForm()) {
        return;
    }
    
    try {
        // Show loading state
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bx bx-loader-circle bx-spin"></i> Mengirim...';
        submitBtn.disabled = true;
        
        // Prepare form data
        const formData = new FormData(e.target);
        
        // Simulasi API call - nanti ganti dengan fetch
        // const response = await fetch(API_CONFIG.endpoints.submit, {
        //     method: 'POST',
        //     headers: {
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        //         'Accept': 'application/json'
        //     },
        //     body: formData
        // });
        
        // Simulate API delay
        setTimeout(() => {
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            // Show success modal
            showSuccessModal({
                bidang: document.getElementById('bidang_pilihan').selectedOptions[0].text,
                tanggal_mulai: document.getElementById('tanggal_mulai').value,
                tanggal_selesai: document.getElementById('tanggal_selesai').value
            });
            
            // Reset form
            e.target.reset();
            initializeForm();
            
        }, 2000);
        
    } catch (error) {
        console.error('Error submitting form:', error);
        showError('Gagal mengirim pengajuan. Silakan coba lagi.');
    }
}

// Validate form
function validateForm() {
    let isValid = true;
    
    // Clear all errors
    clearAllErrors();
    
    // Validate required fields
    const requiredFields = ['no_telp', 'nim', 'universitas', 'jurusan', 'semester', 'bidang_pilihan', 'alasan'];
    
    requiredFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (!field.value.trim()) {
            showFieldError(fieldId, 'Field ini wajib diisi');
            isValid = false;
        }
    });
    
    // Validate alasan length
    const alasan = document.getElementById('alasan');
    if (alasan.value.length < 150) {
        showFieldError('alasan', 'Alasan minimal 150 karakter');
        isValid = false;
    }
    
    // Validate dates
    const startDate = document.getElementById('tanggal_mulai');
    const endDate = document.getElementById('tanggal_selesai');
    
    if (!startDate.value || !endDate.value) {
        if (!startDate.value) showFieldError('tanggal_mulai', 'Tanggal mulai wajib diisi');
        if (!endDate.value) showFieldError('tanggal_selesai', 'Tanggal selesai wajib diisi');
        isValid = false;
    }
    
    // Validate files
    const cvFile = document.getElementById('cv_file');
    const suratFile = document.getElementById('surat_file');
    
    if (!cvFile.files.length) {
        showFileError('cvError', 'CV/Resume wajib diupload');
        isValid = false;
    }
    
    if (!suratFile.files.length) {
        showFileError('suratError', 'Surat pengantar wajib diupload');
        isValid = false;
    }
    
    // Validate terms
    const terms = document.getElementById('terms');
    if (!terms.checked) {
        showFieldError('terms', 'Anda harus menyetujui pernyataan');
        isValid = false;
    }
    
    return isValid;
}

// Show already applied state
function showAlreadyApplied(pengajuan) {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('alreadyAppliedState').classList.remove('hidden');
    
    // Update pengajuan details
    document.getElementById('appliedBidang').textContent = pengajuan.bidang || '-';
    document.getElementById('appliedPeriode').textContent = 
        `${formatDate(pengajuan.tanggal_mulai)} - ${formatDate(pengajuan.tanggal_selesai)}`;
    document.getElementById('appliedTanggal').textContent = formatDate(pengajuan.created_at);
    
    // Update status badge
    const badge = document.getElementById('appliedStatusBadge');
    const status = pengajuan.status || 'pending';
    
    badge.textContent = getStatusText(status);
    badge.className = `status-badge ${getStatusClass(status)}`;
}

// Show form
function showForm() {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('formPengajuan').classList.remove('hidden');
}

// Show success modal
function showSuccessModal(data) {
    document.getElementById('modalBidang').textContent = data.bidang;
    document.getElementById('modalPeriode').textContent = 
        `${formatDate(data.tanggal_mulai)} - ${formatDate(data.tanggal_selesai)}`;
    
    document.getElementById('successModal').classList.remove('hidden');
}

// Close success modal
function closeSuccessModal() {
    document.getElementById('successModal').classList.add('hidden');
}

// Show loading
function showLoading(show) {
    const loading = document.getElementById('loadingState');
    if (show) {
        loading.classList.remove('hidden');
    } else {
        loading.classList.add('hidden');
    }
}

// Error handling
function showError(message) {
    // Show error notification
    const notification = document.createElement('div');
    notification.className = 'error-notification';
    notification.innerHTML = `
        <i class='bx bx-error-circle'></i>
        <span>${message}</span>
    `;
    
    document.querySelector('.content-wrapper').prepend(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

function showFieldError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const formGroup = field.closest('.form-group');
    
    if (formGroup) {
        const error = document.createElement('div');
        error.className = 'field-error';
        error.textContent = message;
        
        formGroup.appendChild(error);
        field.classList.add('error');
    }
}

function showFileError(errorId, message) {
    const errorElement = document.getElementById(errorId);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.add('visible');
    }
}

function clearFieldError(fieldId) {
    const field = document.getElementById(fieldId);
    const formGroup = field.closest('.form-group');
    
    if (formGroup) {
        const error = formGroup.querySelector('.field-error');
        if (error) error.remove();
        field.classList.remove('error');
    }
}

function clearFileError(errorId) {
    const errorElement = document.getElementById(errorId);
    if (errorElement) {
        errorElement.textContent = '';
        errorElement.classList.remove('visible');
    }
}

function clearAllErrors() {
    // Clear field errors
    document.querySelectorAll('.field-error').forEach(error => error.remove());
    document.querySelectorAll('.form-group input, .form-group select, .form-group textarea').forEach(field => {
        field.classList.remove('error');
    });
    
    // Clear file errors
    document.querySelectorAll('.file-error').forEach(error => {
        error.textContent = '';
        error.classList.remove('visible');
    });
}

// Helper functions
function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
}

function formatDateForInput(date) {
    return date.toISOString().split('T')[0];
}

function getStatusText(status) {
    const statusMap = {
        'pending': 'MENUNGGU',
        'accepted': 'DITERIMA',
        'rejected': 'DITOLAK',
        'review': 'DALAM REVIEW'
    };
    return statusMap[status] || 'MENUNGGU';
}

function getStatusClass(status) {
    const classMap = {
        'pending': 'status-pending',
        'accepted': 'status-approved',
        'rejected': 'status-rejected',
        'review': 'status-review'
    };
    return classMap[status] || 'status-pending';
}

// Add custom styles
const style = document.createElement('style');
style.textContent = `
    .hidden { display: none !important; }
    
    .info-alert {
        background: #d1ecf1;
        border: 1px solid #bee5eb;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 25px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    
    .info-alert i {
        color: #0c5460;
        font-size: 1.2rem;
        margin-top: 2px;
    }
    
    .warning-alert {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    
    .warning-alert i {
        color: #856404;
        font-size: 1.2rem;
        margin-top: 2px;
    }
    
    .applied-status-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 25px;
        border: 1px solid #e2e8f0;
    }
    
    .applied-status-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .applied-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }
    
    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    
    .detail-item label {
        font-weight: 600;
        color: #666;
        font-size: 0.9rem;
    }
    
    .detail-item span {
        color: #333;
    }
    
    .form-hint {
        color: #666;
        font-size: 0.85rem;
        margin-top: 5px;
        display: block;
    }
    
    .char-counter {
        text-align: right;
        font-size: 0.85rem;
        color: #666;
        margin-top: 5px;
    }
    
    .file-upload-area {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 25px;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }
    
    .file-upload-area:hover {
        border-color: var(--primary);
        background: #f8f9fa;
    }
    
    .file-upload-area.dragover {
        border-color: var(--primary);
        background: #e3f2fd;
    }
    
    .upload-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    
    .upload-content i {
        font-size: 2.5rem;
        color: var(--primary);
    }
    
    .upload-text {
        text-align: center;
    }
    
    .upload-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    
    .upload-subtitle {
        font-size: 0.85rem;
        color: #666;
    }
    
    .file-name {
        margin-top: 15px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 6px;
        font-size: 0.9rem;
        color: #666;
        text-align: center;
        word-break: break-all;
    }
    
    .file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    
    .file-error {
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 5px;
        display: none;
    }
    
    .file-error.visible {
        display: block;
    }
    
    .terms-box {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 25px;
        border: 1px solid #e2e8f0;
    }
    
    .terms-title {
        color: var(--primary);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .terms-content {
        color: #666;
        line-height: 1.6;
        margin-bottom: 20px;
        max-height: 300px;
        overflow-y: auto;
        padding-right: 10px;
    }
    
    .terms-content ul {
        margin: 10px 0;
        padding-left: 20px;
    }
    
    .terms-content li {
        margin-bottom: 5px;
    }
    
    .terms-agreement {
        margin-top: 20px;
    }
    
    .terms-checkbox {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        cursor: pointer;
    }
    
    .terms-checkbox input {
        margin-top: 3px;
    }
    
    .terms-text {
        color: #333;
        font-weight: 500;
    }
    
    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        padding-top: 25px;
        border-top: 1px solid #eee;
    }
    
    .field-error {
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 5px;
    }
    
    input.error,
    select.error,
    textarea.error {
        border-color: #e74c3c !important;
    }
    
    .error-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #e74c3c;
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 1000;
        animation: slideIn 0.3s ease;
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .modal-content {
        background: white;
        border-radius: 16px;
        max-width: 500px;
        width: 100%;
        animation: modalSlideIn 0.3s ease;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .modal-success-icon {
        text-align: center;
        padding: 40px 0 20px;
    }
    
    .modal-success-icon i {
        font-size: 5rem;
        color: #2ecc71;
    }
    
    .modal-body {
        padding: 0 30px 30px;
        text-align: center;
    }
    
    .modal-title {
        color: var(--primary);
        font-size: 1.5rem;
        margin-bottom: 15px;
    }
    
    .success-details {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
    }
    
    .modal-footer {
        padding: 20px 30px 30px;
        border-top: 1px solid #eee;
        text-align: center;
    }
    
    @media (max-width: 768px) {
        .form-actions {
            flex-direction: column;
        }
        
        .applied-status-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .modal-content {
            max-width: 100%;
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection