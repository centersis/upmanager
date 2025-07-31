<?php

use App\Domains\Update\Http\Controllers\UpdateWebController;
use Illuminate\Support\Facades\Route;

// Update CRUD Routes - Protected
Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::get('/updates', [UpdateWebController::class, 'index'])->name('updates.index');
    Route::get('/updates/create', [UpdateWebController::class, 'create'])->name('updates.create');
    Route::post('/updates', [UpdateWebController::class, 'store'])->name('updates.store');
    Route::get('/updates/{id}', [UpdateWebController::class, 'show'])->name('updates.show');
    Route::get('/updates/{id}/edit', [UpdateWebController::class, 'edit'])->name('updates.edit');
    Route::put('/updates/{id}', [UpdateWebController::class, 'update'])->name('updates.update');
    Route::delete('/updates/{id}', [UpdateWebController::class, 'destroy'])->name('updates.destroy');
}); 