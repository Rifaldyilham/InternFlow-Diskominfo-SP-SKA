<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\ManajemenAkunController;
use App\Http\Controllers\Admin\VerifikasiBerikasController;
use App\Http\Controllers\AdminBidang\DashboardController;
use App\Http\Controllers\PesertaController;

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
Route::prefix('admin')->group(function () {
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
Route::prefix('peserta')->group(function () {
    Route::get('/dashboard', function () {
        return view('peserta.dashboard');
    })->name('peserta.dashboard')->middleware('auth');

    Route::get('/pendaftaran', function () {
        return view('peserta.pendaftaran');
    })->name('peserta.pendaftaran')->middleware('auth');

    Route::post('/pendaftaran', [PesertaController::class, 'store'])
        ->name('peserta.pendaftaran.store')
        ->middleware('auth');

    Route::get('/logbook', function () {
        return view('peserta.logbook');
    })->name('peserta.logbook');

    Route::get('/absensi', function () {
        return view('peserta.absensi');
    })->name('peserta.absensi');

    Route::get('/penilaian-sertifikat', function () {
        return view('peserta.penilaian-sertifikat');
    })->name('peserta.penilaian-sertifikat');
});

// Routes untuk Mentor Magang
Route::prefix('mentor')->group(function () {
    Route::get('/bimbingan', function () {
        return view('mentor.bimbingan');
    })->name('mentor.bimbingan');

    Route::get('/verifikasi', function () {
        return view('mentor.verifikasi');
    })->name('mentor.verifikasi');

    Route::get('/penilaian', function () {
        return view('mentor.penilaian');
    })->name('mentor.penilaian');
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

// API endpoints untuk admin-bidang (digunakan oleh frontend penempatan)
Route::prefix('api/admin-bidang')->middleware(['auth'])->group(function () {
    Route::get('/mentor', [DashboardController::class, 'apiMentor']);
    Route::get('/mentor/{id}', [DashboardController::class, 'apiMentorDetail']);

    Route::get('/penempatan/peserta', [DashboardController::class, 'apiPeserta']);
    Route::get('/penempatan/peserta/{id}', [DashboardController::class, 'apiPesertaDetail']);
    Route::post('/penempatan/assign', [DashboardController::class, 'apiAssign']);
});

// API endpoints untuk admin verifikasi berkas
Route::prefix('api/admin')->middleware(['auth'])->group(function () {
    Route::get('/verifikasi-berkas/list', [VerifikasiBerikasController::class, 'apiList']);
    Route::get('/verifikasi-berkas/detail/{id}', [VerifikasiBerikasController::class, 'detail']);
    Route::post('/verifikasi-berkas/verify', [VerifikasiBerikasController::class, 'verify']);
});

// API endpoints untuk peserta dashboard
Route::prefix('api/peserta')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        $peserta = \App\Models\PesertaMagang::where('id_user', $user->id_user)->first();

        if (!$peserta) {
            return response()->json(['hasPengajuan' => false]);
        }

        return response()->json([
            'hasPengajuan' => true,
            'pengajuan' => [
                'id' => $peserta->id_pesertamagang,
                'status' => $peserta->status_verifikasi,
            ]
        ]);
    });
});
