<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Domains\Customer\Http\Controllers\CustomerWebController;
use App\Domains\Project\Http\Controllers\ProjectWebController;
use App\Domains\Update\Http\Controllers\UpdateWebController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/customers', [CustomerWebController::class, 'index'])->name('customers.index');
Route::get('/customers/{id}', [CustomerWebController::class, 'show'])->name('customers.show');

Route::get('/projects', [ProjectWebController::class, 'index'])->name('projects.index');
Route::get('/projects/{id}', [ProjectWebController::class, 'show'])->name('projects.show');

Route::get('/updates', [UpdateWebController::class, 'index'])->name('updates.index');
Route::get('/updates/{id}', [UpdateWebController::class, 'show'])->name('updates.show');
