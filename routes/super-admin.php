<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('super-admin.index');
// Route::group(['prefix' => 'SuperAdmin'],function () {
//     Route::get('/users', 'SuperAdminController@users')->name('super-admin.users');
//     Route::get('/settings', 'SuperAdminController@settings')->name('super-admin.settings');
// });

