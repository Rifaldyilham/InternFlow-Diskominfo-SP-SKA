<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\ManajemenAkunController;
use App\Http\Controllers\Admin\VerifikasiBerikasController;
use App\Http\Controllers\AdminBidang\DashboardController;
use App\Http\Controllers\Mentor\VerifikasiLogbookController;
use App\Http\Controllers\Mentor\AbsensiController;
use App\Http\Controllers\Mentor\PenilaianController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PesertaMagang\AbsensiPesertaController;
use App\Http\Controllers\PesertaMagang\LogbookController;
use App\Http\Controllers\PesertaMagang\PenilaianPesertaController;
use App\Http\Controllers\PesertaMagang\SertifikatPesertaController;
use App\Http\Controllers\MentorBimbinganController;
use App\Http\Controllers\Admin\ManajemenBidangController;
use App\Http\Controllers\Admin\SertifikatController;

// Route untuk dashboard utama
Route::get('/', function () {
    return view('welcome');
});

// Route untuk halaman login placeholder
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store');

// Route untuk logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Route untuk halaman register placeholder
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->name('register.store');

// Dashboard routes dengan middleware auth 
Route::prefix('admin')->middleware(['auth', 'role:Admin Kepegawaian'])->group(function () {
    Route::get('/verifikasi-berkas', [VerifikasiBerikasController::class, 'index'])
        ->name('admin.verifikasiberkas');

    Route::get('/manajemen-akun', [ManajemenAkunController::class, 'index'])
        ->name('admin.manajemen-akun');

    Route::get('/manajemen-bidang', function () {
        return view('admin.manajemenbidang');
    })->name('admin.manajemen-bidang');

    Route::get('/sertifikat', function () {
        return view('admin.sertifikat');
    })->name('admin.sertifikat');
});



// Routes untuk Peserta Magang
Route::prefix('peserta')->middleware(['auth', 'role:Peserta Magang'])->group(function () {
    Route::get('/dashboard', function () {
        return view('peserta.dashboard');
    })->name('peserta.dashboard')->middleware('auth');

    Route::get('/pendaftaran', [PesertaController::class, 'create'])
        ->name('peserta.pendaftaran')
        ->middleware('auth');

    Route::post('/pendaftaran', [PesertaController::class, 'store'])
        ->name('peserta.store')
        ->middleware('auth');

    Route::get('/logbook', function () {
        return view('peserta.logbook');
    })->name('peserta.logbook');

    Route::get('/penilaian-sertifikat', function () {
        return view('peserta.penilaian-sertifikat');
    })->name('peserta.penilaian-sertifikat');

    Route::get('/absensi', [AbsensiPesertaController::class, 'index'])
        ->name('peserta.absensi');

    Route::post('/absensi', [AbsensiPesertaController::class, 'store']);
});

// Routes untuk Mentor
Route::prefix('mentor')->middleware(['auth', 'role:Mentor'])->group(function () {
    Route::get('/bimbingan', function () {
        return view('mentor.bimbingan');
    })->name('mentor.bimbingan');
    
    Route::get('/verifikasi', function () {
        return view('mentor.verifikasi', [
            'pageTitle' => 'Verifikasi Peserta',
            'pageSubtitle' => 'Verifikasi logbook dan absensi peserta',
            'initialTab' => 'logbook',
        ]);
    })->name('mentor.verifikasi');

    Route::get('/logbook', function () {
        return view('mentor.verifikasi', [
            'pageTitle' => 'Logbook Peserta',
            'pageSubtitle' => 'Detail logbook peserta bimbingan',
            'initialTab' => 'logbook',
        ]);
    })->name('mentor.logbook');

    Route::get('/absensi', function () {
        return view('mentor.verifikasi', [
            'pageTitle' => 'Absensi Peserta',
            'pageSubtitle' => 'Detail absensi peserta bimbingan',
            'initialTab' => 'absensi',
        ]);
    })->name('mentor.absensi');
    
    Route::get('/penilaian', function () {
        return view('mentor.penilaian');
    })->name('mentor.penilaian');
});

// API endpoints untuk Mentor - PAKAI CONTROLLER BARU
Route::prefix('api/mentor')->middleware(['auth'])->group(function () {
    Route::get('/stats', [MentorBimbinganController::class, 'stats']);
    Route::get('/peserta', [MentorBimbinganController::class, 'peserta']);
    Route::get('/peserta/{id}', [MentorBimbinganController::class, 'detailPeserta']);
    Route::get('/absensi', [AbsensiController::class, 'index']);
    Route::get('/absensi/{pesertaId}', [AbsensiController::class, 'byPeserta']);
    Route::get('/logbook/{pesertaId}', [VerifikasiLogbookController::class, 'index']);
    Route::get('/logbook', [VerifikasiLogbookController::class, 'index']);
    Route::get('/logbook/{pesertaId}/{logbookId}', [VerifikasiLogbookController::class, 'detail']);
    Route::post('/logbook/verify', [VerifikasiLogbookController::class, 'verify']);
    Route::get('/verifikasi/stats/{pesertaId}', [VerifikasiLogbookController::class, 'stats']);
    Route::get('/penilaian/peserta', [PenilaianController::class, 'peserta']);
    Route::post('/penilaian/upload', [PenilaianController::class, 'upload']);
    Route::get('/penilaian/stats', [PenilaianController::class, 'stats']);
    Route::get('/penilaian/{pesertaId}', [PenilaianController::class, 'show']);
    Route::delete('/penilaian/{pesertaId}', [PenilaianController::class, 'destroy']);
    Route::get('/penilaian/download/{id}', [PenilaianController::class, 'download']);
});

// Routes untuk Admin Bidang
Route::prefix('admin-bidang')->middleware(['auth'])->group(function () {
    Route::get('/mentor', [DashboardController::class, 'index'])
        ->name('admin-bidang.mentor');

    Route::get('/penempatan', [DashboardController::class, 'penempatan'])
        ->name('admin-bidang.penempatan');

    Route::post('/penempatan', [DashboardController::class, 'assignMentor'])
        ->name('admin-bidang.penempatan.store');
});

// API endpoints untuk admin-bidang
Route::prefix('api/admin-bidang')->middleware(['auth'])->group(function () {
    // Mentor
    Route::get('/mentor', [DashboardController::class, 'apiMentor']);
    Route::get('/mentor/{id}', [DashboardController::class, 'apiMentorDetail']);
    Route::get('/mentor/{id}/detail', [DashboardController::class, 'apiMentorDetail']);
    
    // Peserta untuk penempatan
    Route::get('/penempatan/peserta', [DashboardController::class, 'apiPeserta']);
    Route::get('/penempatan/peserta/{id}', [DashboardController::class, 'apiPesertaDetail']);
    Route::post('/penempatan/assign', [DashboardController::class, 'apiAssign']);
});

// API endpoints untuk admin verifikasi berkas
Route::prefix('api/admin')->middleware(['auth'])->group(function () {
    Route::get('/verifikasi-berkas/list', [VerifikasiBerikasController::class, 'apiList']);
    Route::get('/verifikasi-berkas/detail/{id}', [VerifikasiBerikasController::class, 'detail']);
    Route::post('/verifikasi-berkas/verify', [VerifikasiBerikasController::class, 'verify']);
    Route::get('/peserta/sertifikat', [SertifikatController::class, 'peserta']);
    Route::post('/sertifikat/upload', [SertifikatController::class, 'upload']);
    Route::get('/sertifikat/{pesertaId}', [SertifikatController::class, 'show']);
    Route::get('/sertifikat/download/{id}', [SertifikatController::class, 'download']);
    Route::delete('/sertifikat/{pesertaId}', [SertifikatController::class, 'destroy']);
});

// API endpoints untuk peserta dashboard
Route::prefix('api/peserta')->middleware(['auth'])->group(function () {
    Route::get('/pengajuan/status', [PesertaController::class, 'statusPengajuan']);
    // Route dashboard dengan data lengkap
    Route::get('/dashboard-detail', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        $peserta = \App\Models\PesertaMagang::with(['bidang', 'pegawai', 'bidangPilihan', 'sertifikat'])
            ->where('id_user', $user->id_user)
            ->orderByDesc('created_at')
            ->first();

        if (!$peserta) {
            return response()->json(['hasPengajuan' => false]);
        }

        // Format data untuk frontend
        $data = [
            'hasPengajuan' => true,
            'pengajuan' => [
                'id' => $peserta->id_pesertamagang,
                'nama' => $peserta->nama,
                'status' => $peserta->status,
                'status_verifikasi' => $peserta->status_verifikasi,
                'tanggal_mulai' => $peserta->tanggal_mulai,
                'tanggal_selesai' => $peserta->tanggal_selesai,
                'bidang' => $peserta->bidang ? [
                    'id' => $peserta->bidang->id_bidang,
                    'nama' => $peserta->bidang->nama_bidang
                ] : null,
                'mentor' => $peserta->pegawai ? [
                    'id' => $peserta->pegawai->id_pegawai,
                    'nama' => $peserta->pegawai->nama,
                    'nip' => $peserta->pegawai->nip
                ] : null,
                'bidang_pilihan' => $peserta->bidangPilihan ? [
                    'id' => $peserta->bidangPilihan->id_bidang,
                    'nama' => $peserta->bidangPilihan->nama_bidang
                ] : null,
                'sertifikat' => $peserta->sertifikat ? [
                    'id' => $peserta->sertifikat->id_sertifikat,
                    'tanggal_terbit' => $peserta->sertifikat->tanggal_terbit,
                ] : null
            ]
        ];

        return response()->json($data);
    });

    Route::get('/logbook/status', [LogbookController::class, 'status']);
    Route::get('/logbook', [LogbookController::class, 'index']);
    Route::post('/logbook', [LogbookController::class, 'store']);
    Route::get('/penilaian', [PenilaianPesertaController::class, 'detail']);
    Route::get('/penilaian/download', [PenilaianPesertaController::class, 'download']);
    Route::get('/sertifikat', [SertifikatPesertaController::class, 'detail']);
    Route::get('/sertifikat/download', [SertifikatPesertaController::class, 'download']);
});
