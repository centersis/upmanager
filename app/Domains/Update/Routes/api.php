<?php

use App\Domains\Update\Http\Controllers\UpdateController;
use Illuminate\Support\Facades\Route;

// Update API Routes
Route::apiResource('updates', UpdateController::class)->names([
    'index' => 'api.updates.index',
    'store' => 'api.updates.store',
    'show' => 'api.updates.show',
    'update' => 'api.updates.update',
    'destroy' => 'api.updates.destroy',
]); 