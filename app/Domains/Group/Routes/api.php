<?php

use App\Domains\Group\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

// Group API Routes
Route::apiResource('groups', GroupController::class)->names([
    'index' => 'api.groups.index',
    'store' => 'api.groups.store',
    'show' => 'api.groups.show',
    'update' => 'api.groups.update',
    'destroy' => 'api.groups.destroy',
]);
Route::get('groups-active', [GroupController::class, 'active'])->name('api.groups.active'); 