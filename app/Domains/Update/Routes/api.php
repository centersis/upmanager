<?php

use App\Domains\Update\Http\Controllers\UpdateApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Update API Routes
|--------------------------------------------------------------------------
|
| Here are the API routes for update functionality.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

// Protected update routes (authentication required)
Route::middleware(['auth:sanctum', App\Domains\User\Http\Middleware\EnsureUserIsActiveApi::class])->group(function () {
    
    // CRUD operations
    Route::get('/updates', [UpdateApiController::class, 'index'])
        ->name('api.updates.index');
        
    Route::post('/updates', [UpdateApiController::class, 'store'])
        ->name('api.updates.store');
        
    Route::get('/updates/{id}', [UpdateApiController::class, 'show'])
        ->name('api.updates.show');
        
    Route::put('/updates/{id}', [UpdateApiController::class, 'update'])
        ->name('api.updates.update');
        
    Route::delete('/updates/{id}', [UpdateApiController::class, 'destroy'])
        ->name('api.updates.destroy');
    
    // Additional endpoints
    Route::get('/updates/project/{projectId}', [UpdateApiController::class, 'byProject'])
        ->name('api.updates.by-project');
        
    Route::get('/updates/customer/{customerId}', [UpdateApiController::class, 'byCustomer'])
        ->name('api.updates.by-customer');
        
    Route::get('/updates-options', [UpdateApiController::class, 'options'])
        ->name('api.updates.options');
        
});

// Public routes (no authentication required)
Route::get('/updates/hash/{hash}', [UpdateApiController::class, 'byHash'])
    ->name('api.updates.by-hash'); 