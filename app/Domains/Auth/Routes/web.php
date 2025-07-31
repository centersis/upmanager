<?php

use App\Domains\User\Http\Controllers\ProfileController;
use App\Domains\Auth\Http\Controllers\AuthenticatedSessionController;
use App\Domains\Auth\Http\Controllers\ConfirmablePasswordController;
use App\Domains\Auth\Http\Controllers\EmailVerificationNotificationController;
use App\Domains\Auth\Http\Controllers\EmailVerificationPromptController;
use App\Domains\Auth\Http\Controllers\NewPasswordController;
use App\Domains\Auth\Http\Controllers\PasswordController;
use App\Domains\Auth\Http\Controllers\PasswordResetLinkController;
use App\Domains\Auth\Http\Controllers\RegisteredUserController;
use App\Domains\Auth\Http\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// Login page
Route::get('/login', function () {
    return view('auth::login');
})->name('login')->middleware('guest');

// Guest routes (login, register, password reset)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

// Authenticated routes (email verification, password confirmation, logout)
Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// Profile routes - Protected
Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
}); 