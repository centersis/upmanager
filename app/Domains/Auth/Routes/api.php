<?php

use App\Domains\Auth\Http\Controllers\AuthApiController;
use App\Domains\Auth\Http\Controllers\PasswordResetApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth API Routes
|--------------------------------------------------------------------------
|
| Here are the API routes for authentication functionality.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

// Public auth routes (no authentication required)
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthApiController::class, 'login'])
        ->name('api.auth.login');
        
    // Password reset routes
    Route::post('/forgot-password', [PasswordResetApiController::class, 'sendResetLink'])
        ->name('api.auth.forgot-password');
        
    Route::post('/validate-reset-token', [PasswordResetApiController::class, 'validateToken'])
        ->name('api.auth.validate-reset-token');
        
    Route::post('/reset-password', [PasswordResetApiController::class, 'resetPassword'])
        ->name('api.auth.reset-password');
});

// Protected auth routes (authentication required)
Route::prefix('auth')->middleware(['auth:sanctum', App\Domains\User\Http\Middleware\EnsureUserIsActiveApi::class])->group(function () {
    Route::get('/me', [AuthApiController::class, 'me'])
        ->name('api.auth.me');
        
    Route::post('/logout', [AuthApiController::class, 'logout'])
        ->name('api.auth.logout');
        
    Route::post('/logout-all', [AuthApiController::class, 'logoutAll'])
        ->name('api.auth.logout-all');
        
    Route::post('/refresh', [AuthApiController::class, 'refresh'])
        ->name('api.auth.refresh');
        
    // Change password route (requires authentication)
    Route::post('/change-password', [PasswordResetApiController::class, 'changePassword'])
        ->name('api.auth.change-password');
});
