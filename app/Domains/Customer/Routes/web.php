<?php

use App\Domains\Customer\Http\Controllers\CustomerWebController;
use Illuminate\Support\Facades\Route;

// Customer CRUD Routes - Protected
Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::get('/customers', [CustomerWebController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerWebController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerWebController::class, 'store'])->name('customers.store');
    Route::get('/customers/{id}', [CustomerWebController::class, 'show'])->name('customers.show');
    Route::get('/customers/{id}/edit', [CustomerWebController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{id}', [CustomerWebController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [CustomerWebController::class, 'destroy'])->name('customers.destroy');
}); 