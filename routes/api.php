<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    
    Route::post('/register', [AuthController::class, 'store'])->name('auth.store');
    Route::post('/login', [AuthController::class, 'index'])->name('auth.index');

});