<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::apiResource('tasks', TaskController::class)->except('index');
Route::apiResource('users', UserController::class);
Route::get('users/{user}/tasks', [TaskController::class, 'index'])->name('users.tasks.index');
