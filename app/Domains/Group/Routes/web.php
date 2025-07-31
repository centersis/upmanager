<?php

use App\Domains\Group\Http\Controllers\GroupWebController;
use Illuminate\Support\Facades\Route;

// Group CRUD Routes - Protected
Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::get('/groups', [GroupWebController::class, 'index'])->name('groups.index');
    Route::get('/groups/create', [GroupWebController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupWebController::class, 'store'])->name('groups.store');
    Route::get('/groups/{id}', [GroupWebController::class, 'show'])->name('groups.show');
    Route::get('/groups/{id}/edit', [GroupWebController::class, 'edit'])->name('groups.edit');
    Route::put('/groups/{id}', [GroupWebController::class, 'update'])->name('groups.update');
    Route::delete('/groups/{id}', [GroupWebController::class, 'destroy'])->name('groups.destroy');
}); 