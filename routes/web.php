<?php

use Illuminate\Support\Facades\Route;

// Route untuk dashboard utama
Route::get('/', function () {
    return view('welcome');
});

// Route untuk halaman login placeholder
Route::get('/login', function () {
    return view('auth.login-placeholder');
})->name('login');

// Route untuk halaman register placeholder
Route::get('/register', function () {
    return view('auth.register-placeholder');
})->name('register');

// Dashboard routes dengan middleware auth (nanti)
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    Route::get('/peserta', function () {
        return view('admin.peserta.index');
    })->name('admin.peserta');
    
    Route::get('/mentor', function () {
        return view('admin.mentor.index');
    })->name('admin.mentor');
    
    Route::get('/sertifikat', function () {
        return view('admin.sertifikat.index');
    })->name('admin.sertifikat');
});

// Routes untuk Peserta Magang
Route::prefix('peserta')->group(function () {
    Route::get('/dashboard', function () {
        return view('peserta.dashboard');
    });
    
    Route::get('/pendaftaran', function () {
        return view('peserta.pendaftaran');
    });
    
    Route::get('/logbook', function () {
        return view('peserta.logbook');
    });
    
    Route::get('/absensi', function () {
        return view('peserta.absensi');
    });
    
    Route::get('/penilaian-sertifikat', function () {
        return view('peserta.penilaian-sertifikat');
    });
});

// Routes untuk Mentor Magang
Route::prefix('mentor')->group(function () {
    Route::get('/dashboard', function () {
        return view('mentor.dashboard');
    })->name('mentor.dashboard');
    
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
    Route::get('/dashboard', function () {
        return view('admin-bidang.dashboard');
    })->name('admin-bidang.dashboard');
    
    Route::get('/mentor', function () {
        return view('admin-bidang.mentor.index');
    })->name('admin-bidang.mentor');
});