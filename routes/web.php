<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Domains\Customer\Http\Controllers\CustomerWebController;
use App\Domains\Project\Http\Controllers\ProjectWebController;
use App\Domains\Update\Http\Controllers\UpdateWebController;

// Public routes
Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::get('/login', function () {
    return view('auth::login');
})->name('login')->middleware('guest');


// Authentication routes
require __DIR__.'/auth.php';

// Public routes - no authentication required
Route::get('/project/{projectHash}', [App\Http\Controllers\PublicController::class, 'projectUpdates'])->name('public.project');
Route::get('/update/{updateHash}', [App\Http\Controllers\PublicController::class, 'updateDetail'])->name('public.update');
Route::get('/customer/{customerHash}/updates', [App\Http\Controllers\PublicController::class, 'customerUpdates'])->name('public.customer');
Route::get('/customer/{customerHash}/project/{projectHash}', [App\Http\Controllers\PublicController::class, 'customerProjectUpdates'])->name('public.customer.project');
Route::get('/iframe/{customerHash}/{projectHash}', [App\Http\Controllers\PublicController::class, 'iframe'])->name('public.iframe');

// Protected routes - require authentication and active user
Route::middleware(['auth', 'verified', 'active'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer CRUD Routes
    Route::get('/customers', [CustomerWebController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerWebController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerWebController::class, 'store'])->name('customers.store');
    Route::get('/customers/{id}', [CustomerWebController::class, 'show'])->name('customers.show');
    Route::get('/customers/{id}/edit', [CustomerWebController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{id}', [CustomerWebController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [CustomerWebController::class, 'destroy'])->name('customers.destroy');

    // Project CRUD Routes
    Route::get('/projects', [ProjectWebController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectWebController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectWebController::class, 'store'])->name('projects.store');
    Route::get('/projects/{id}', [ProjectWebController::class, 'show'])->name('projects.show');
    Route::get('/projects/{id}/edit', [ProjectWebController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{id}', [ProjectWebController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{id}', [ProjectWebController::class, 'destroy'])->name('projects.destroy');

    // Update CRUD Routes
    Route::get('/updates', [UpdateWebController::class, 'index'])->name('updates.index');
    Route::get('/updates/create', [UpdateWebController::class, 'create'])->name('updates.create');
    Route::post('/updates', [UpdateWebController::class, 'store'])->name('updates.store');
    Route::get('/updates/{id}', [UpdateWebController::class, 'show'])->name('updates.show');
    Route::get('/updates/{id}/edit', [UpdateWebController::class, 'edit'])->name('updates.edit');
    Route::put('/updates/{id}', [UpdateWebController::class, 'update'])->name('updates.update');
    Route::delete('/updates/{id}', [UpdateWebController::class, 'destroy'])->name('updates.destroy');

    // Group CRUD Routes
    Route::get('/groups', [\App\Domains\Group\Http\Controllers\GroupWebController::class, 'index'])->name('groups.index');
    Route::get('/groups/create', [\App\Domains\Group\Http\Controllers\GroupWebController::class, 'create'])->name('groups.create');
    Route::post('/groups', [\App\Domains\Group\Http\Controllers\GroupWebController::class, 'store'])->name('groups.store');
    Route::get('/groups/{id}', [\App\Domains\Group\Http\Controllers\GroupWebController::class, 'show'])->name('groups.show');
    Route::get('/groups/{id}/edit', [\App\Domains\Group\Http\Controllers\GroupWebController::class, 'edit'])->name('groups.edit');
    Route::put('/groups/{id}', [\App\Domains\Group\Http\Controllers\GroupWebController::class, 'update'])->name('groups.update');
    Route::delete('/groups/{id}', [\App\Domains\Group\Http\Controllers\GroupWebController::class, 'destroy'])->name('groups.destroy');
});

// Admin only routes - require admin role
Route::middleware(['auth', 'verified', 'active', 'admin'])->group(function () {
    // User Management Routes
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [App\Http\Controllers\UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
});
