<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ChatController;
use App\Http\Controllers\Api\V1\MessageController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    //Регистрация авторизация
    Route::post('/register', [AuthController::class, 'store'])->name('auth.store');
    Route::get('/login', [AuthController::class, 'index'])->name('auth.index');

    Route::middleware('auth:sanctum')->group(function () {
        //Пользователи
        Route::get('/users', [UserController::class, 'index'])->name('users.index');

        //Чаты
        Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
        Route::post('/chats', [ChatController::class, 'store'])->name('chats.store');

        //Сообщения
        Route::get('/chats/{chatId}/messages',[MessageController::class, 'index'])->whereNumber('chatId')->name('messages.index');
        Route::post('/chats/{chatId}/messages', [MessageController::class, 'store'])->whereNumber('chatId')->name('messages.store');
    });

});