<?php

use App\Domains\Dashboard\Http\Controllers\DashboardApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard API Routes
|--------------------------------------------------------------------------
|
| Here are the API routes for dashboard functionality.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

// Protected dashboard routes (authentication required)
Route::prefix('dashboard')->middleware(['auth:sanctum', App\Domains\User\Http\Middleware\EnsureUserIsActiveApi::class])->group(function () {
    
    Route::get('/overview', [DashboardApiController::class, 'overview'])
        ->name('api.dashboard.overview');
    
    Route::get('/stats', [DashboardApiController::class, 'stats'])
        ->name('api.dashboard.stats');
        
    Route::get('/recent-updates', [DashboardApiController::class, 'recentUpdates'])
        ->name('api.dashboard.recent-updates');
        
    Route::get('/recent-projects', [DashboardApiController::class, 'recentProjects'])
        ->name('api.dashboard.recent-projects');
        
    Route::get('/activity-timeline', [DashboardApiController::class, 'activityTimeline'])
        ->name('api.dashboard.activity-timeline');
        
});
