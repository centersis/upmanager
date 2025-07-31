<?php

use App\Domains\Dashboard\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Home redirect
Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

// Dashboard - Protected
Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
}); 