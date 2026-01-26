<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\ManajemenAkunController;

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
    Route::get('/verifikasi-berkas', function () {
        return view('admin.verifikasi-berkas');
    })->name('admin.verifikasiberkas');
    
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
    })->name('peserta.dashboard');
    
    Route::get('/pendaftaran', function () {
        return view('peserta.pendaftaran');
    })->name('peserta.pendaftaran');
    
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
Route::prefix('admin-bidang')->group(function () { 
    Route::get('/mentor', function () {
        return view('admin-bidang.mentor');
    })->name('admin-bidang.mentor');

    Route::get('/penempatan', function () {
        return view('admin-bidang.penempatan');
    })->name('admin-bidang.penempatan');
});