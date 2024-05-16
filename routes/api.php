<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ChatController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    //Регистрация авторизация
    Route::post('/register', [AuthController::class, 'store'])->name('auth.store');
    Route::get('/login', [AuthController::class, 'index'])->name('auth.index');

    Route::middleware('auth:sanctum')->group(function () {
        //Пользователи
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/chats/{userId}', [ChatController::class, 'store'])->whereNumber('userId')->name('chats.store');
    });

});