<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    //Регистрация авторизация
    Route::post('/register', [AuthController::class, 'store'])->name('auth.store');
    Route::get('/login', [AuthController::class, 'index'])->name('auth.index');

    //Пользователи
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});