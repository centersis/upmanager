<?php

use App\Domains\Public\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Public routes - no authentication required
Route::get('/project/{projectHash}', [PublicController::class, 'projectUpdates'])->name('public.project');
Route::get('/update/{updateHash}', [PublicController::class, 'updateDetail'])->name('public.update');
Route::get('/customer/{customerHash}/updates', [PublicController::class, 'customerUpdates'])->name('public.customer');
Route::get('/customer/{customerHash}/project/{projectHash}', [PublicController::class, 'customerProjectUpdates'])->name('public.customer.project');
Route::get('/iframe/{customerHash}/{projectHash}', [PublicController::class, 'iframe'])->name('public.iframe'); 