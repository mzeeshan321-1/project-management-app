<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('super-admin')->middleware(['role:super-admin', 'permission:*'])->group(function () {

    });
});

require __DIR__.'/auth.php';
