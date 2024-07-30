<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Sanctum Auth
Route::post('login', AuthController::class . '@login')->name('login');
Route::post('register', AuthController::class . '@register')->name('register');

// Google Auth
Route::controller(AuthController::class)->group(function () {
    Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('me', [UserController::class, 'me']);
    Route::get('logout', [AuthController::class, 'logout']);

    Route::group(['middleware' => ['admin']], function () {
        Route::apiResource('users', UserController::class);
    });

    Route::apiResource('tasks', TaskController::class)->except('index');
    Route::get('users/{user}/tasks', [TaskController::class, 'index'])->name('users.tasks.index');
});
