<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\UserApiController;
use App\Http\Controllers\Api\Admin\RoleApiController;
use App\Models\Bidang;
use App\Models\PesertaMagang;

Route::prefix('admin')->group(function () {

    Route::get('/users', [UserApiController::class, 'index']);
    Route::post('/users', [UserApiController::class, 'store']);
    Route::get('/users/{id}', [UserApiController::class, 'show']);
    Route::put('/users/{id}', [UserApiController::class, 'update']);
    Route::delete('/users/{id}', [UserApiController::class, 'destroy']);
    Route::get('/roles', [RoleApiController::class, 'index']);

    Route::get('/bidang', [BidangApiController::class, 'index']);
    Route::post('/bidang', [BidangApiController::class, 'store']);
    Route::put('/bidang/{id}', [BidangApiController::class, 'update']);
    Route::delete('/bidang/{id}', [BidangApiController::class, 'destroy']);
    Route::get('/bidang/{id}', [BidangApiController::class, 'show']);
    Route::get('/bidang/{id}/admin', function ($id) {
        return Bidang::with('admin')->findOrFail($id)->admin;
    });
    Route::get('/bidang/{id}/peserta', function ($id) {
        return PesertaMagang::where('id_bidang', $id)->get();
    });
});
