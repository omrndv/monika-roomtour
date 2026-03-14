<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoomtourController;
use App\Http\Controllers\AuthController;

Route::get('/', [RoomtourController::class, 'index'])->name('dashboard');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['checkadmin'])->group(function () {
    Route::get('/dashboard', [RoomtourController::class, 'index'])->name('dashboard');

    Route::get('/dashboard/grid', [RoomtourController::class, 'gridView'])->name('dashboard.grid');

    Route::post('/roomtour', [RoomtourController::class, 'store'])->name('roomtour.store');
    Route::put('/roomtour/{id}', [RoomtourController::class, 'update'])->name('roomtour.update');
    Route::delete('/roomtour/{id}', [RoomtourController::class, 'destroy'])->name('roomtour.destroy');
});
