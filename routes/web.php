<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth
Route::post('login', AuthController::class . '@login');
Route::post('register', AuthController::class . '@register');

// Google Auth
Route::controller(AuthController::class)->group(function () {
    Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});

// Use Cases Routes
Route::apiResource('tasks', TaskController::class)->except('index');
Route::apiResource('users', UserController::class);
Route::get('users/{user}/tasks', [TaskController::class, 'index'])->name('users.tasks.index');
