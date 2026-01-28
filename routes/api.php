<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\UserApiController;
use App\Http\Controllers\Api\Admin\RoleApiController;
use App\Http\Controllers\Api\Admin\BidangApiController;
use App\Models\Bidang;
use App\Models\PesertaMagang;

Route::prefix('admin')->group(function () {

    Route::get('/users', [UserApiController::class, 'index']);
    Route::post('/users', [UserApiController::class, 'store']);
    Route::get('/users/{id}', [UserApiController::class, 'show']);
    Route::put('/users/{id}', [UserApiController::class, 'update']);
    Route::delete('/users/{id}', [UserApiController::class, 'destroy']);
    Route::get('/roles', [RoleApiController::class, 'index']);

    // Routes untuk Bidang
    Route::get('/bidang', [BidangApiController::class, 'index']);
    Route::post('/bidang', [BidangApiController::class, 'store']);
    Route::get('/bidang/{id}', [BidangApiController::class, 'show']);
    Route::put('/bidang/{id}', [BidangApiController::class, 'update']);
    Route::delete('/bidang/{id}', [BidangApiController::class, 'destroy']);
    Route::get('/bidang/{id}/admin', [BidangApiController::class, 'getAdmin']);
    Route::get('/bidang/{id}/peserta', [BidangApiController::class, 'getPeserta']);

});
