<?php

use Illuminate\Support\Facades\Route;
use App\Domains\Customer\Http\Controllers\CustomerController;
use App\Domains\Project\Http\Controllers\ProjectController;
use App\Domains\Update\Http\Controllers\UpdateController;

Route::apiResource('customers', CustomerController::class);
Route::apiResource('projects', ProjectController::class);
    Route::apiResource('updates', UpdateController::class);

    // Groups API routes
    Route::apiResource('groups', \App\Domains\Group\Http\Controllers\GroupController::class);
    Route::get('groups-active', [\App\Domains\Group\Http\Controllers\GroupController::class, 'active']); 