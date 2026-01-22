<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\UserApiController;

Route::prefix('admin')->group(function () {

    Route::get('/users', [UserApiController::class, 'index']);
    Route::post('/users', [UserApiController::class, 'store']);
    Route::get('/users/{id}', [UserApiController::class, 'show']);
    Route::put('/users/{id}', [UserApiController::class, 'update']);
    Route::delete('/users/{id}', [UserApiController::class, 'destroy']);

});
