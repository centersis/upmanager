<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home redirect
Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

// Login page
Route::get('/login', function () {
    return view('auth::login');
})->name('login')->middleware('guest');

// Dashboard - Protected
Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Domain Routes
|--------------------------------------------------------------------------
|
| Load routes from each domain module
|
*/

// Auth Domain (Profile + Authentication)
require_once app_path('Domains/Auth/Routes/web.php');

// Public Domain (Public views)
require_once app_path('Domains/Public/Routes/web.php');

// Customer Domain
require_once app_path('Domains/Customer/Routes/web.php');

// Project Domain
require_once app_path('Domains/Project/Routes/web.php');

// Update Domain
require_once app_path('Domains/Update/Routes/web.php');

// Group Domain
require_once app_path('Domains/Group/Routes/web.php');

// User Domain (Admin only)
require_once app_path('Domains/User/Routes/web.php');
