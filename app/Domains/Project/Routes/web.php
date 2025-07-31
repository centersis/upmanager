<?php

use App\Domains\Project\Http\Controllers\ProjectWebController;
use Illuminate\Support\Facades\Route;

// Project CRUD Routes - Protected
Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::get('/projects', [ProjectWebController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectWebController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectWebController::class, 'store'])->name('projects.store');
    Route::get('/projects/{id}', [ProjectWebController::class, 'show'])->name('projects.show');
    Route::get('/projects/{id}/edit', [ProjectWebController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{id}', [ProjectWebController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{id}', [ProjectWebController::class, 'destroy'])->name('projects.destroy');
}); 