<?php

use App\Domains\Public\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Public routes with optional language parameter
Route::get('/project/{projectHash}/{lang?}', [PublicController::class, 'projectUpdates'])->name('public.project');
Route::get('/update/{updateHash}/{lang?}', [PublicController::class, 'updateDetail'])->name('public.update');
Route::get('/customer/{customerHash}/updates/{lang?}', [PublicController::class, 'customerUpdates'])->name('public.customer');
Route::get('/customer/{customerHash}/project/{projectHash}/{lang?}', [PublicController::class, 'customerProjectUpdates'])->name('public.customer.project');
Route::get('/iframe/{customerHash}/{projectHash}/{lang?}', [PublicController::class, 'iframe'])->name('public.iframe'); 